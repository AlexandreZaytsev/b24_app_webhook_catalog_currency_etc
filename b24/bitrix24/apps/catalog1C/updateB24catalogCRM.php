<?
require_once (dirname(__DIR__, 2) . '/settings.php');						//токен вебхука
require_once (dirname(__DIR__, 2) . '/wrapperB24/php/vendor/autoload.php');	//обертка

//КОНСТАНТЫ
define('IDSITE_CATALOG',21);			//Общий каталог CRM & Catalog
define('IDSITE_CATALOG_SKU',46);		//Товарный каталог CRM (предложения) Catalog only (infoblok)

use App\Bitrix24\Bitrix24API;
use App\Bitrix24\Bitrix24APIException;
use App\DebugLogger\DebugLogger;

//------------------------------------------------------------------------------------------

    function show($res,$title=''){
       	echo '<pre>';
		echo $title;
       	echo '<br/>';
    		print_r($res);
		echo '</pre>';
    }

/***********************************************************************************
удалить массив по ключу
array_remove($array, "address", "helen@gmail.com");
***********************************************************************************/
function array_remove(array &$haystack, $needleKey, $needleValue, array &$parent = array(), $previousKey = null) {
    foreach($haystack as $key => $value) {
        if(is_array($value)) {
            array_remove($value, $needleKey, $needleValue, $haystack, $key);
        }elseif($key === $needleKey && $value === $needleValue) {
            unset($parent[$previousKey]);
        }
    }   
}
/***********************************************************************************
очистить наименование товарного предложения от наименования товара
	$elementID - id элемента ТОРГОВОГО ПРЕДЛОЖЕНИЯ  
	$elementName - наименование ТОРГОВОГО ПРЕДЛОЖЕНИЯ 
вернуть чистое наименование торгового предложения
***********************************************************************************/
Function fn_ClearOfferName($elementID, $elementName)
{
	$elementName=str_replace("(T)", "{T}", $elementName);				//спрячем непонятные скобки
	$elementName=str_replace("/", " ", $elementName); 

	$elementName=trim($elementName);
	$pos1 = strpos($elementName, " (");									//если есть признак грязной строки ' ('
	if ($pos1>0){
		$left = substr($elementName, 0, $pos1);							//наименование товара
		$right = substr($elementName, $pos1+2);							//грязное наименование торгового предложения
		if (strpos($right, $left) !== false && substr($right, -1)==')'){//если название товара входит в название торгового предложения и в конце его есть ')'
			$elementName = substr($right, 0, strlen($right)-1);    		//чистое ноименование торгового предложения
		}
	}

	$elementName=str_replace(array("(", ")"), "", $elementName);		//удалим все скобки

    //исключения
      $elementName=str_replace("{T}", "(T)", $elementName);				//вернем непонятные скобки на место
    return $elementName; 
}

/***********************************************************************************
получить свежие значения для базовой цены товара исходя их всех дополнительных цен
	$elementID - id элемента ТОРГОВОГО ПРЕДЛОЖЕНИЯ  
	$arrPrice - массив всех цен на торговые предложения 
	$tblPriceType - массив типов цен	
вернуть массив с ценой валютой ставкой ндс 
***********************************************************************************/

Function fn_GetPriceForBase($elementID, $arrPrice, $tblPriceType)
{
	$retPriceArr=array(
					'PRICE'=> 0,								//Цена	double
//					'CURRENCY_ID'=> 'RUB',//0,					//Идентификатор валюты	string
//					'VAT_ID'=> 4,//0,							//Идентификатор ставки НДС	integer
//					'VAT_INCLUDED'=> 'N',//0,					//НДС включён в цену	char
					);	

	//получим все цены элемента
	$result = array_filter($arrPrice,function($v) use ($elementID) {
		return $v['productId'] == $elementID;
	});
	
//	show($result);
	
	//если нет цен вообще - уйдем отсюда
	if (empty($result)){
		return array();
	}
		
	//поищем в массиве цен базовую цену
	$basePrice = array_filter($result,function($v) use ($elementID) {
		return $v['catalogGroupId'] == 1;
	});
	
	//если есть базовая цена удалим ее
	if (!empty($basePrice)){
		array_remove($result, 'catalogGroupId', 1);
	}
	
	//если несколько цен - взять свежую	
	if (!empty($result)){
		if (count($result)>1){
			
			usort($result, function ($item1, $item2) {			//сортировка по дате создания цены на убывание
				//так
 				$timeStamp1 = strtotime($item1['timestampX']);
        		$timeStamp2 = strtotime($item2['timestampX']);		
 				return $timeStamp2 - $timeStamp1;			//Desc
 				//или так
 				//return $item2['timestampX'] <=> $item1['timestampX'];
			});
			
			$result = $result[0]; 
		}else{
			foreach ($result as $res){							//спуститься на второй уровень вниз
				$result=$res;
				break;	
			}
		}
/*		
		//разборки с ценами - не будем - в яявном виде укажем
		$typeName='';
		foreach ($tblPriceType as $item){
			if ($item['id']==$result['catalogGroupId']){
				$typeName = $item['name']);
				break;
			}
		}
*/
		//НДС входит в цену
//		switch ($typeName) {
		switch ($result['catalogGroupId']) {
		    case 158:									//соглашение RUB (НДС 20)
    		case 150:									//соглашение USD (НДС 20)
    		case 148:									//соглашение EUR (НДС 20)
       			$retPriceArr['VAT_INCLUDED'] = 'Y';
       			$retPriceArr['VAT_ID'] = 3;				//НДС 20%
       			break;
    		case 154:									//соглашение RUB (без НДС)
		    case 156:									//соглашение USD (без НДС)
    		case 152:									//соглашение EUR (без НДС)
       			$retPriceArr['VAT_INCLUDED'] = 'N';
       			$retPriceArr['VAT_ID'] = 4;				//4 - НДС 0%, 1 - Без НДС
       			break;
		}
		
		$retPriceArr['MEASURE'] = 9;
		$retPriceArr['CURRENCY_ID'] = "RUB";
		//цена в рублях или в конвертации по курсу
		$retPriceArr['PRICE'] = $result['currency']=='RUB' ? $result['price'] : $result['priceScale'];
	}
	
	//если есть базовая цена сравним ее с тем что хотим записать
	if (!empty($basePrice) && !empty($retPriceArr) ){
		foreach ($basePrice as $res){					//спуститься на второй уровень вниз
			$basePrice=$res;
			break;	
		}
		if ($basePrice['price'] == $retPriceArr['PRICE']){		//возможно обновление валюты по количеству десятичных знаков
			$retPriceArr = array(); //сброим результат
		}
	}

	return $retPriceArr;                              	// вернуть массив параметров для установки базовой цены  
}		

//------------------------------------------------------------------------------------------------------------
//разберемся с тем что пришло    
//------------------------------------------------------------------------------------------------------------
	$typeImport='updateCurrency';						//Пересчитать валюты каталога по курсу и установить базовую цену
	$typeImport='updateCatalog';						//Обновить позиции каталога Наименование, Активность
	$typeImport='updateAll';							//Обновить и каталог и цены

	$tblProducts = array();								//массив продуктов
	$tblPrice = array();								//массив цен
	$updateProducts = array();							//массив для записи в Б24
	$return = array();									//массив для ответа
	
	$input = file_get_contents('php://input');			//прочтем сырые данные JSON из потока
	if ($input) {										//запуск через b24
		$arr = json_decode($input,true);
	    $typeImport = $arr['typeImport'];//$arr->typeImport;					//$arr["typeImport"]
//		$tblProducts = $arr['items'];//$arr->items;
	} else {											//запуск через крон
		$typeImport='updateCron';						//свое значение (для крона выбираем только нерублевые типы цен)
		$tblProducts = Array('ret'=>'нет данных');
	}

//echo ($typeImport);
	
//------------------------------------------------------------------------------------------------------------
//на обертке библиотеки Б24    
//------------------------------------------------------------------------------------------------------------
try {
    $webhookURL = FULL_WEB_HOOK_URL;		//in Внешний доступ для CRM-автоматизации
    
    $bx24 = new Bitrix24API($webhookURL);

    // Устанавливаем каталог для сохранения лог файлов
   // DebugLogger::$mkdirMode = 0775;
   //каталог создаем САМИ (и указываем здесь) или в качестве имени пустую строку (тогда лог ляжет в корень) 
   //если своего каталога нет - скрипт создаст свой по имени - но закроет его от удаления
    DebugLogger::$logFileDir = '';//myselfDir/';///'name dir with slash/';//'bad_dir_name_without_slash';//;log_temp/';//'logs_test';//_test_logs_without_chmod_to_file/';
    // Создаем объект класса логгера
    $logFileName = 'debug_bitrix24api.log';
    $logger = DebugLogger::instance($logFileName);
    // Включаем логирование
    $logger->isActive = false;//true;//false;//true;//false;//true;//false;//true;//false;// true;
    // Устанавливаем логгер
    $bx24->setLogger($logger);
//--------------------------------------------------------------------------------------------------------------

    //прочтем список валют (метод без параметров)    
	$tblCurrency = $bx24->request('crm.currency.list',[]);
//	$tblCurrency = $bx24->getList('crm.currency.list',[]);
//   		   show($tblCurrency,'список валют');

	//прочтем списки НДС
//	$tblVat = $bx24->request('crm.vat.list',[]);
//   		   show($tblVat,'crm список НДС');
//	$tblVat = $bx24->request('catalog.vat.list',[]);
//   		   show($tblVat['vats'],'catalog список НДС');

   		   
   	//прочтем типы цен	 
   	$tblPriceType=array();
	$params = [ 
            'order'  => ['sort'=>'desc'],
        //    'filter' => [],// 'productId'=>45126],
            'select' => ['id', 'name']
        ];   	  
	$generator = $bx24->getList('catalog.priceType.list',$params);
    foreach ($generator as $priceTypes) {
        foreach($priceTypes as $priceType) {
	        foreach($priceType as $priceTyp) {
				$tblPriceType[] = $priceTyp;
			}
		}
	}			
//		   show($tblPriceType,'типы цен');

	//прочтем список цен и наберем их в массив
	$params = array();
	switch ($typeImport) {
		case 'updateCron':
	    case 'updateCurrency':
	    case 'updateCatalog':
			//прочтем все не рублевые цены
			$params = [
		            'order'  => ['id'=>'asc', 'timestampX'=>'desc'],
		            'filter' => ['!=price'=>0 ,'!=currency'=>'RUB'],// 'productId'=>48608],	//все кроме нулевых цен и БАЗОВОЙ ЦЕНЫ
//            'filter' => ['!=price'=>0, '!=catalogGroupId'=>1],// 'productId'=>45126],	//все кроме нулевых цен и БАЗОВОЙ ЦЕНЫ
		            'select' => ['id', 'iblockId','catalogGroupId','productId','currency','price','priceScale', 'extraId', 'timestampX']
		        ];
			break;
//		case 'updateCron':
		case 'updateAll':
			//прочтем все цены
			$params = [
		            'order'  => ['id'=>'asc', 'timestampX'=>'desc'],
	//            'filter' => ['!=price'=>0, '!=catalogGroupId'=>1],// 'productId'=>45126],	//все кроме нулевых цен и БАЗОВОЙ ЦЕНЫ
		            'filter' => ['!=price'=>0],// 'productId'=>48608],	//все кроме нулевых цен 
		            'select' => ['*','id', 'iblockId','catalogGroupId','productId','currency','price','priceScale', 'extraId', 'timestampX']
		        ];
			break;
	}
	
	$generator = $bx24->getList('catalog.price.list',$params);  					//список цен товаров по фильтру
    foreach ($generator as $products) {
        foreach($products as $product) {
	        foreach($product as $prod) {											//опустимся на третий уровень
				$tblPrice[] = $prod;
			}
		}
	}			
//	show($tblPrice,'list список цен');
	

		/*
		   	$tblPrice=array();
			$params = [
		           // 'order'  => ['id'=>'asc', 'timestampX'=>'desc'],
		            'filter' => ['iblockId'=>21],//["CATALOG_ID"=>21],//['!=price'=>0, '!=catalogGroupId'=>1],// 'productId'=>45126],	//все кроме нулевых цен и БАЗОВОЙ ЦЕНЫ
		            'select' => ['id', 'iblockId']//['id', 'iblockId','catalogGroupId','productId','currency','price','priceScale', 'extraId', 'timestampX']
		        ];
		        
		// Загружаем все товары c фильтрацией по полю SECTION_ID
		    $generator = $bx24->getList('catalog.product.list',$params);
		    foreach ($generator as $users) {
		        foreach($users as $user) {
		            $tblPrice[] = $user;
		        }
		    }
		  show($tblPrice,'fetch list список цен');
		*/
//		echo ('ok');
		
	//прочтем все товары и наберем массив
/*
TYPE_PRODUCT = 1; // Простой товар
TYPE_SET = 2; // Комплект 
TYPE_SKU = 3; // Товар с торговыми предложениями
TYPE_OFFER = 4; // Торговое предложение
TYPE_FREE_OFFER = 5; // Торговое предложение, у которого нет товара (не указан или удален)
TYPE_EMPTY_SKU = 6; // Специфический тип, означает невалидный товар с торговыми предложениями
*/
	switch ($typeImport) {
	    case 'updateCurrency':
	    case 'updateCatalog':
	    case 'updateAll':
    		$tblProducts = $arr['items'];//$arr->items;			//товары поступают из Б24 пачками
			break;												
		case 'updateCron':
//		echo ('ok');
//		$generator = $bx24->fetchProductList(['ID'=>48608], ['*','ID', 'NAME' ], ['ID'=>'asc']);//48610  4512448608
			$generator = $bx24->fetchProductList([], ['ID', 'NAME' ], ['ID'=>'asc']);//48610  45124
		    foreach ($generator as $products) {
		        foreach($products as $product) {
						$tblProducts[] = $product;
				}
			}	
//				  show($tblProducts,'массив продуктов');
			break;
	}	

	//пройдем по товарам и подготовим массив для обновления
	$updateProduct=array();
	foreach ($tblProducts as $product) {
		
		switch ($typeImport) {
		    case 'updateCurrency':
		    case 'updateAll':
			case 'updateCron':
				$updateProduct = fn_GetPriceForBase($product['ID'], $tblPrice, $tblPriceType);	//блок с ценами	
				break;
		    case 'updateCatalog':
				break;
		}
   		$updateProduct['ID'] = (int)$product['ID'];					//id продукта

		switch ($typeImport) {
		    case 'updateCurrency':
				break;
		    case 'updateAll':
			case 'updateCron':
		    case 'updateCatalog':
				$nName = fn_ClearOfferName($product['ID'],$product['NAME']);
		       	if (strcmp($nName, $product['NAME'])<>0){
		        		$updateProduct['NAME'] = $nName;				//наименование продукта
					}
				break;
		}

//echo $product['ID'];
//		$bx24->updateProduct($product['ID'], $updateProduct);		// Обновляем один товар
		
//			$params = [
  //              'id'     => (int)$product['ID'],
    //            'fields' => $updateProduct
      //      ];
//show($params);            
	//	$tblCurrency = $bx24->request('crm.product.update',$params);
		
		if (array_key_exists('NAME', $updateProduct) || array_key_exists('PRICE', $updateProduct)){
			$updateProducts[]=$updateProduct;					//добавим к массиву для обновления
		}
	}	
	


	//отправим массив в Б24
	foreach(array_chunk($updateProducts, 50 ) as $part){ //,true) as $part){
	   	$bx24->updateProducts($updateProducts);		// Пакетно обновляем товары
	}
//	show($updateProducts,'массив продуктов на обновление');

//	ob_end_clean(); // очистить буфер
	$return = $updateProducts;
	echo json_encode($return);

} catch (Bitrix24APIException $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
} catch (Exception $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
}
	
