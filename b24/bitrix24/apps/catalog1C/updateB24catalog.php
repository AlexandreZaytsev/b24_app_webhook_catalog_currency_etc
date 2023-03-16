<?
require_once (dirname(__DIR__, 2) . '/settings.php');						//токен вебхука
require_once (dirname(__DIR__, 2) . '/wrapperB24/php/vendor/autoload.php');	//обертка

use App\Bitrix24\Bitrix24API;
use App\Bitrix24\Bitrix24APIException;
use App\DebugLogger\DebugLogger;

//------------------------------------------------------------------------------------------

    function show($res,$title=''){
		$result=print_r($res, true);
/*    	
       	echo '<pre>';
		echo $title;
       	echo '<br/>';
    		$result;
		echo '</pre>';
*/
//		file_put_contents('_log.txt', $title."\n".$result."\n", FILE_APPEND | LOCK_EX);
    }

/***********************************************************************************
получить цену из массива цен по id продукта удалив найденные цены из исходного массива
***********************************************************************************/
//получить цену по id продукта
function getPriceByProductId(&$array, $findValue){
	$findArr=array();

/*	
    foreach($array as $price) {
    	foreach ($price as $key => $value){
         	if ($key==='productId' && $value === $findValue){
    			$findArr[]=$price;
			}
		}
    }
	
	if ($findArr){											//удалить цены из исходного массива по ключу значению
		array_remove($array, 'productId', $findValue);
	}
*/

    foreach($array as $keyVariable=> $valueVariable) {
    	foreach ($valueVariable as $key => $value){
        	if ($key==='productId' && $value === $findValue){
    			$findArr[]=$array[$keyVariable];			//выведем найденные цены вбок
    			unset($array[$keyVariable]);				//удалим цену из общего массива
			}
		}
    }

	
	if (count($findArr)>1){									//сортировка и возврат первого значения если их больше двух
		usort($findArr, function ($item1, $item2) {			//сортировка по дате создания цены на убывание
				//так
			$timeStamp1 = strtotime($item1['timestampX']);
       		$timeStamp2 = strtotime($item2['timestampX']);		
 				return $timeStamp2 - $timeStamp1;			//Desc
 				//или так
 				//return $item2['timestampX'] <=> $item1['timestampX'];
			});
			$findArr = $findArr[0]; 
		}else{
			foreach ($findArr as $res){						//спуститься на второй уровень вниз
				$findArr=$res;
				break;	
			}
		}

	return $findArr;
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
	$inArrProduct - массив с данными продукта  
	$outArr - массив с данными для обновления 
вернуть чистое наименование товара/торгового предложения c признаком (itemProp/ACTION) обновления если нужно
***********************************************************************************/
Function updateProduct($inArrProduct, $outArr)
{
	$elementName=$inArrProduct['name'];
	
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

	if ($outArr['itemProp']['ACTION']!='update'){						//могди в ценах установить
		$outArr['itemProp']['ACTION'] = ($elementName == $inArrProduct['name']) ? 'skip' : 'update';
	}

//	if ($outArr['itemProp']['ACTION']=='update'){
		$outArr['itemProp']['id'] = $inArrProduct['id'];
		$outArr['itemProp']['iblockId'] = $inArrProduct['iblockId'];
		$outArr['itemProp']['iblockSectionId'] = $inArrProduct['iblockSectionId'];
		$outArr['itemProp']['type'] = $inArrProduct['type'];
		$outArr['itemProp']['name'] = $elementName;
//	}	

    return $outArr; 
}
/***********************************************************************************
получить секцию НДС по имени типа цены
***********************************************************************************/
function getVatFromTypePrice($id){
	$vat = array('vatId' => '', 'vatIncluded' => '');
	//перепроверка расширенной цены по ее типу
	switch ($id) {
		case 158:											//соглашение RUB (НДС 20)
	    case 150:											//соглашение USD (НДС 20)
	    case 148:											//соглашение EUR (НДС 20)
	    	$vat['vatIncluded'] = 'Y';						//НДС входит в цену
	       	$vat['vatId'] = 3;								//НДС 20%
	       	break;
	    case 154:											//соглашение RUB (без НДС)
		case 156:											//соглашение USD (без НДС)
	    case 152:											//соглашение EUR (без НДС)
	    	$vat['vatIncluded'] = 'N';						//НДС не входит в цену
	    	$vat['vatId'] = 4;								//4 - НДС 0%, 1 - Без НДС
	    	break;
		default:
	    	$vat['vatIncluded'] = 'N';						//НДС не входит в цену
	    	$vat['vatId'] = 4;								//4 - НДС 0%, 1 - Без НДС
	}
	return $vat;		
}

/***********************************************************************************
получить свежие значения для базовой цены товара исходя их всех дополнительных цен
	$inArrProduct - массив с данными продукта  
	$inArrPrice - массив с ценами продукта  
	$outArr - массив с данными для обновления 
вернуть цену товара/торгового предложения c признаком (itemPrice/ACTION itemProp/ACTION) обновления если нужно
***********************************************************************************/
function updateProductPrice($inArrProduct, $basePrice, $extPrice, $outArr)
{
//show($outArr,'before');	
	$elementID = $inArrProduct['id'];								//id элемента
//show($elementID);

	if (!empty($basePrice) || !empty($extPrice)){											//если цены есть работаем, нет - уйдем отсюда с тем с чем пришли
		
		//подготовим данные по цене
		if (!empty($basePrice) && empty($extPrice)){					//если есть базовая цена и нет расширенной
			//ничего делать не нужно
			$outArr['itemPrice']['ACTION'] = 'skip';
		}elseif (empty($basePrice) && !empty($extPrice)){				//если нет базовой и есть расширенная
			$vat = getVatFromTypePrice($extPrice['catalogGroupId']);
			
			//просто создать базовую цену из расширенной
			//свойства товара можно не трогать просто копируем данные из расширенной цены
			$price = $extPrice['currency']=='RUB' ? $extPrice['price'] : $extPrice['priceScale'];
			
            $outArr['itemPrice']['catalogGroupId'] = 1;					//Тип цены (integer) Неизменяемое, обязательное поле. 
			$outArr['itemPrice']['currency'] = 'RUB';					//Валюта (string) Обязательное поле.
			$outArr['itemPrice']['price'] = $price;						//Цена (double) Обязательное поле.
			$outArr['itemPrice']['productId'] = $elementID;				//Идентификатор товара (integer) Неизменяемое, обязательное поле. 
//			'quantityFrom' =>, 											//Количество от (integer)
//			'quantityTo' =>, 											//Количество до (integer)
//			'timestampX' =>, 											//Дата изменения (datetime) Только для чтения.
			$outArr['itemPrice']['ACTION'] = 'create';					//Создать базовую цену на основе расширенной - товар не затриагивается		

			$outArr['itemProp']['vatId'] = $vat['vatId'];				//Идентификатор НДС (integer)
			$outArr['itemProp']['vatIncluded'] = $vat['vatIncluded'];	//НДС включен в цену (char)
			$outArr['itemProp']['ACTION'] = 'update';

		}elseif (!empty($basePrice) && !empty($extPrice)){				//если есть и базовая и расширенная
//show($outArr,'after');	
//show($basePrice,'базовая цена');	
//show($extPrice,'расширенная цена');	

			//обновить базовую цену из расширенной (заодно проверив ее) и понять затрагивает это обновление товар
			$vat = getVatFromTypePrice($extPrice['catalogGroupId']);			
			
			//сравним цены базовой и расширенной цены в секции цена
			$price = $extPrice['currency']=='RUB' ? $extPrice['price'] : $extPrice['priceScale'];
			If ($basePrice['price']!= $price){ 								//возможно обновление валюты по разному количеству десятичных знаков
				$outArr['itemPrice']['id'] = $basePrice['id'];				//id базовой цены
	            $outArr['itemPrice']['catalogGroupId'] = 1;					//Тип цены (integer) Неизменяемое, обязательное поле. 
				$outArr['itemPrice']['currency'] = 'RUB';					//Валюта (string) Обязательное поле.
				$outArr['itemPrice']['price'] = $price;						//Цена (double) Обязательное поле.
				$outArr['itemPrice']['productId'] = $elementID;				//Идентификатор товара (integer) Неизменяемое, обязательное поле. 
				$outArr['itemPrice']['ACTION'] = 'update';///'.$basePrice['price'].'/'.$price.'/extprice'.$extPrice['price'].'/extpriceScale'.$extPrice['priceScale'];			
			}
			//сравним НДС базовой и расширенной цены (значения берем из типа цены)
			if (($inArrProduct['vatId'] != $vat['vatId']) || ($inArrProduct['vatIncluded'] != $vat['vatIncluded']) ){
				$outArr['itemProp']['id'] = $elementID;						//Идентификатор товара (integer) Только для чтения.
//				$outArr['itemProp']['name'] = $vatId;						//Название (string)	Обязательное поле.
				$outArr['itemProp']['vatId'] = $vat['vatId'];				//Идентификатор НДС (integer)
				$outArr['itemProp']['vatIncluded'] = $vat['vatIncluded'];	//НДС включен в цену (char)
				$outArr['itemProp']['ACTION'] = 'update';	
			}
		}
		
	}
//show($outArr,'after');	
	
	return $outArr;                              	// вернуть массив параметров для установки базовой цены  
}		

//чистый массив для обновления информационного блока товара и цен
/*
TYPE_PRODUCT = 1; // Простой товар
TYPE_SET = 2; // Комплект 
TYPE_SKU = 3; // Товар с торговыми предложениями
TYPE_OFFER = 4; // Торговое предложение
TYPE_FREE_OFFER = 5; // Торговое предложение, у которого нет товара (не указан или удален)
TYPE_EMPTY_SKU = 6; // Специфический тип, означает невалидный товар с торговыми предложениями
*/
function getInfoBlockArray(){
	return array(
		'itemPrice'=>[						//массив для обновления цены
			'catalogGroupId' => 0,			//Тип цены (integer) Неизменяемое, обязательное поле. //по умолчанию базовой цены в результатах нет (нужно будет создать ее)
			'currency'=> '',				//Валюта (string) Обязательное поле.
//			'extraId =>,					//Идентификатор наценки	(integer) 
			'id' => 0,						//Идентификатор цены (integer) Только для чтения.
			'price' => 0,					//Цена (double) Обязательное поле.
//			'priceScale' =>0,				//Базовая цена (double) Только для чтения.
			'productId' => 0,				//Идентификатор товара (integer) Неизменяемое, обязательное поле. 
//			'quantityFrom' =>, 				//Количество от (integer)
//			'quantityTo' =>, 				//Количество до (integer)
//			'timestampX' =>, 				//Дата изменения (datetime) Только для чтения.
			'ACTION' => 'skip',				//Что делать с данными - create/update/skip
		],
		'itemProp' =>[						//массив для обновления продукта
//			'active'=>,						//Активность (char)	
			'iblockId' => 0,				//Идентификатор информационного блока (integer) Обязательное поле.
			'iblockSectionId' => 0,			//Идентификатора раздела информационного блока (integer) Обязательное поле.			
			'id' => 0,						//Идентификатор товара (integer) Только для чтения.			
			'name' => '',					//Название (string)	Обязательное поле.
//			'priceType'=>,					//Тип цены (char)
//			'purchasingCurrency'=>,			//Валюта (string) возможно это закупочная цена
//			'purchasingPrice'=>,			//Цена (string) возможно это закупочная цена
			'type' => 0,					//Тип (integer)	Только для чтения.
			'vatId' => 0,					//Идентификатор НДС (integer)
			'vatIncluded'=>'',				//НДС включен в цену (char)
			'ACTION'=>'skip',				//Что делать с данными - update/skip
		]
	);	
}

//------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------
//разберемся с тем что пришло    
//------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------
//КОНСТАНТЫ
define('IDSITE_CATALOG',21);			//Общий каталог CRM & Catalog
define('IDSITE_CATALOG_SKU',46);		//Товарный каталог CRM (предложения) Catalog only (infoblok)

	$typeImport='updateCurrency';						//Пересчитать валюты каталога по курсу и установить базовую цену
	$typeImport='updateCatalog';						//Обновить позиции каталога Наименование, Активность
	$typeImport='updateAll';							//Обновить и каталог и цены

//	$productArr = array();								//массив продуктов
//	$priceArr = array();								//массив цен
	
//	$tblProducts = array();								//массив продуктов
//	$tblPrice = array();								//массив цен
	$updateProducts = array();							//массив для записи в Б24
	$return = array();									//массив для ответа
	
	$input = file_get_contents('php://input');			//прочтем сырые данные JSON из потока
	if ($input) {										//запуск через b24
		$arr = json_decode($input,true);
		
	    $typeImport = $arr['typeImport'];				//$arr["typeImport"]
		$productArr = $arr['product'];					//внешний массив продуктов
		$basePriceArr = $arr['basePrice'];				//внешний массив базовых цен
		$extPriceArr = $arr['extPrice'];				//внешний массив расширенных цен
	} else {											//запуск через крон
		$typeImport='updateCron';						//свое значение (для крона выбираем только нерублевые типы цен)
		$productArr = array();							//массив продуктов - наберем внутри
		$basePriceArr = array();						//массив базовых цен - наберем внутри
		$extPriceArr = array();							//массив расширенных цен - наберем внутри
	}
//	show($typeImport, 'тип импорта');

	$modeArr = array();									//установим режим работы	
	switch ($typeImport) {
		case 'updateCron':
			$modeArr['product'] = '';					//товары не обновляем 
			$modeArr['price'] = 'currencyOnly';			//обновляем только валютные цены 
			break;
	    case 'updateCurrency':
			$modeArr['product'] = '';					//товары не обновляем 
			$modeArr['price'] = 'currencyOnly';			//обновляем только валютные цены
			break;
	    case 'updateCatalog':
			$modeArr['product'] = 'name';				//товары обновляем 
			$modeArr['price'] = '';						//цены не обновляем вообще
			break;
  		case 'updateAll':
			$modeArr['product'] = 'name';				//товары обновляем 
			$modeArr['price'] = 'currencyAll';			//обновляем ВСЕ цены
			break;
	}
//	show($modeArr,'режим работы');

	
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

	//ЦЕНЫ

/*
	//прочтем списки НДС
//	$tblVat = $bx24->request('crm.vat.list',[]);
//	show($tblVat,'crm список НДС');
	$tblVat = $bx24->request('catalog.vat.list',[]);
	show($tblVat['vats'],'catalog список НДС');

    //прочтем список валют (метод без параметров)    
	$tblCurrency = $bx24->request('crm.currency.list',[]);
//	$tblCurrency = $bx24->getList('crm.currency.list',[]);
	show($tblCurrency,'список валют');

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
	show($tblPriceType,'типы цен');
*/

	if ($modeArr['price']!=''){											//если нужны цены
		if (count($basePriceArr) === 0){								//если массив пустой - наберем его
			//запрос на базовые цены (значение >=0)
			$params = [ 
//	            'order'  => ['timestampX'=>'desc'],
	            'filter' => ['catalogGroupId'=> 1, '>=price'=>0],// '>price'=>0],//'id'=>94364],//'>=price'=>0], 'productId'=>43666,
	            'select' => ['catalogGroupId', 'currency', 'id', 'price', 'priceScale', 'productId', 'timestampX'],
//	            'start' => 1,
	        ];		

			$generator = $bx24->getList('catalog.price.list',$params);  //список цен товаров по фильтру
			foreach ($generator as $products) {
				foreach($products as $product) {
					foreach($product as $prod) {						//опустимся на третий уровень
						$basePriceArr[] = $prod;
					}
				}
			}
		}

		//запрос на расширенные цены
		if (count($extPriceArr) === 0){								//если массив пустой - наберем его
			switch ($modeArr['price']) {
				case 'currencyAll':										//все валюты в расширенных ценах
					$params = [
		//	            'order'  => ['timestampX'=>'desc'],
			            'filter' => ['>catalogGroupId'=> 1,'>price'=>0],//,'productId'=>43728,'!=price'=>0 ],
			            'select' => ['catalogGroupId', 'currency', 'id', 'price', 'priceScale', 'productId', 'timestampX'],//'*'],
			   //         'start' => 1,
			            ];		
				case 'currencyOnly':									//только валюта в расширенных ценах
					$params = [
		//	            'order'  => ['timestampX'=>'desc'],
			            'filter' => ['>catalogGroupId'=> 1,'>price'=>0, '!=currency'=>'RUB'],//,'productId'=>43728,'!=price'=>0 ],
			            'select' => ['catalogGroupId', 'currency', 'id', 'price', 'priceScale', 'productId', 'timestampX'],//'*'],
			   //         'start' => 1,
			            ];		
			}
			//запрос на расширенные цены (значение >0)
			$generator = $bx24->getList('catalog.price.list',$params);  //список цен товаров по фильтру
			foreach ($generator as $products) {
				foreach($products as $product) {
					foreach($product as $prod) {						//опустимся на третий уровень
						$extPriceArr[] = $prod;
					}
				}
			}
		} 
	}
	
//	show($basePriceArr,'список базовых цен чистые');
//	show($extPriceArr,'список расширенных цен чистые');

	//ТОВАРЫ
	if (count($productArr) === 0){									//если массив пустой - наберем его

		//запрос на товары (простые type=1 и головные type=3) 		//type не учитываем поскольку в этом каталоге
		$params = [
            'order'  => ['id'=>'asc'],
            'filter' => ['iblockId'=>IDSITE_CATALOG],// 'type'=>1],// 'id'=>43666],	//простой товар из общего каталога товаров
            'select' => ['id', 'iblockId', 'iblockSectionId', 'name', 'type', 'vatId', 'vatIncluded'],
        ];	

		$generator = $bx24->getList('catalog.product.list',$params);//список товаров (выводит все свойства товара = ресурс товара)
		foreach ($generator as $products) {
			foreach($products as $product) {
				foreach($product as $prod) {						//опустимся на третий уровень
					$productArr[] = [
						'id'=>$prod['id'], 
						'iblockId'=>$prod['iblockId'], 
						'iblockSectionId'=>$prod['iblockSectionId'], 
						'name'=>$prod['name'], 
						'type'=>$prod['type'], 
						'vatId'=>$prod['vatId'], 
						'vatIncluded'=>$prod['vatIncluded'] 
					];
				}	
			}
		}

		//запрос на торговые предложения
		$params = [
            'order'  => ['id'=>'asc'],
            'filter' => ['iblockId'=>IDSITE_CATALOG_SKU],// 'id'=>43666 ],	//товарное предложение из каталога торговых предложений
            'select' => ['id', 'iblockId', 'iblockSectionId', 'name', 'type', 'vatId', 'vatIncluded'],
        ];	

		$generator = $bx24->getList('catalog.product.offer.list',$params);//список товаров (выводит все свойства товара = ресурс товара)
		foreach ($generator as $products) {
			foreach($products as $product) {
				foreach($product as $prod) {						//опустимся на третий уровень
					array_push($productArr,[
						'id'=>$prod['id'], 
						'iblockId'=>$prod['iblockId'], 
						'iblockSectionId'=>$prod['iblockSectionId'], 							
						'name'=>$prod['name'], 
						'type'=>$prod['type'], 
						'vatId'=>$prod['vatId'] ? : 1, 
						'vatIncluded'=>$prod['vatIncluded']
					]);
				}	
			}
		}
	} 

//	show($productArr,'список продуктов и торговых предложений');

	//пройдем по товарам и подготовим массив для обновления
	$updateProducts=array();
	foreach ($productArr as $product) {
		$updateProduct = getInfoBlockArray();							//получим заготовку
		
		if ($modeArr['product'] !=''){									//если нужно обновить продукты;	
			$updateProduct = updateProduct($product, $updateProduct);	//очистить наименование
		}
		
		if ($modeArr['price'] !=''){									//если нужны обновить цены;	
			$idProduct = $product['id'];
			//получим базовую цену элемента
			$basePrice = getPriceByProductId($basePriceArr, $idProduct);
			//получим расширенные цены элемента
			$extPrice = getPriceByProductId($extPriceArr, $idProduct);
			//обновим цены
			$updateProduct = updateProductPrice($product, $basePrice, $extPrice, $updateProduct);

//show($basePrice,'basePrice '.count($basePrice));			
//show($extPrice,'extPrice '.count($extPrice));			
		}else{															//если цены не обновляем - выключим их в продукие
			unset($updateProduct['itemProp']['vatId']);
			unset($updateProduct['itemProp']['vatIncluded']);
		}
		
		$updateProducts[]=$updateProduct;
	}

//	show($basePriceArr,'список базовых цен после удаления');
//	show($extPriceArr,'список расширенных цен после удаления');
//show($updateProducts,'обновление список продуктов и торговых предложений');
	

	//пройдем по массиву обновленя и сформируем запросы
	$updateRequest=array();
	$commandParams=array();
	$commands = array();
	foreach ($updateProducts as $itemProduct) {

		//для продуктов
		if($itemProduct['itemProp']['ACTION'] != 'skip'){
			$commandParams = array(
								'id' => $itemProduct['itemProp']['id'],
								'fields' => [
										'name' => $itemProduct['itemProp']['name'],
										'iblockId' => $itemProduct['itemProp']['iblockId'],
										'iblockSectionId' => $itemProduct['itemProp']['iblockSectionId'],
										'vatId' => $itemProduct['itemProp']['vatId'],
										'vatIncluded' => $itemProduct['itemProp']['vatIncluded'],	
									]
							);
							
			switch ($itemProduct['itemProp']['type']){
				case 1:										// Простой товар
				case 3:										// Товар с торговыми предложениями
					$commands = $bx24->buildCommand('catalog.product.update', $commandParams);
					break;
				case 4:										// Торговое предложение
				case 5:										// Торговое предложение, у которого нет товара (не указан или удален)				
					$commands = $bx24->buildCommand('catalog.product.offer.update', $commandParams);
					break;
			}
			$updateRequest[] = $commands; 	
		}

		//для цен
		if($itemProduct['itemPrice']['ACTION'] != 'skip'){
			switch ($itemProduct['itemPrice']['ACTION']){
				case 'update':	
					$commandParams = array(
								'id' => $itemProduct['itemPrice']['id'],
								'fields' => [
										'currency' => $itemProduct['itemPrice']['currency'],
										'price' => $itemProduct['itemPrice']['price'],										
									]
							);
					$commands = $bx24->buildCommand('catalog.price.update', $commandParams);
					break;
				case 'create':	
					$commandParams = array(
								'fields' => [
										'catalogGroupId' => $itemProduct['itemPrice']['catalogGroupId'],
										'currency' => $itemProduct['itemPrice']['currency'],
										'price' => $itemProduct['itemPrice']['price'],		
										'productId' => $itemProduct['itemPrice']['productId'],
									]
							);
					$commands = $bx24->buildCommand('catalog.price.add', $commandParams);
					break;	
							
			}
			$updateRequest[] = $commands; 	
		}
		
	}
//	$updateProducts = array();							//сбросим массив для записи в Б24		
//	show($updateRequest,'запросы на обновление цен, продуктов и торговых предложений');

	//отправим массив в Б24
	foreach(array_chunk($updateRequest, 50 ) as $part){ //,true) as $part){
		$result = $bx24->batchRequest($part);			// Пакетно обновляем товары

		$sent = count($part);
		$received = count($result);

		if ($received != $sent) {
			$jsonResponse = $this->toJSON($this->lastResponse);
				throw new Bitrix24APIException(
							"Невозможно пакетно добавить товары ({$sent}/{$received}): {$jsonResponse}"
							);
		}
	} 
 

	ob_end_clean(); // очистить буфер
	$return = $updateRequest;
	echo json_encode($return,JSON_UNESCAPED_UNICODE);
//	echo $bx24->toJSON($return);
	
} catch (Bitrix24APIException $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
} catch (Exception $e) {
    printf('Ошибка (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
}
	
