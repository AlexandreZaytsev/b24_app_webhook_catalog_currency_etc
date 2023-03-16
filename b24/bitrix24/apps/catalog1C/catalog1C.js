"use strict";										// код здесь обрабатывается в строгом режиме

let util =UIkit.util;

let inGet = util.$('#selGET');						//выпадающий список запросов 
let outMsg = util.$('#comment'); 					//комментарий
let outShow = util.$('#outShow'); 					//результаты запроса
let preloader = util.$('#preloader');				//спиннер
let bar = util.$('#js-progressbar');				//прогресс бар
let btnGet = util.$('#get');						//прочитать данные js
let btnUpdate = util.$('#update');					//обновить данные php
let tSelect = util.$('#typeSelect');				//тип запроса
let catalog = util.$('#catalog');					//номер каталога

//---------------------------------------------------------------------------------------------------------

/**
 * Установить прогресс бар на web морде
 * @param  {int} v Значение прогрессбара
 */
async function showProgressBar(v){
//	console.log(`progress: ${v}%`);
	if (!isNaN(v))
		bar.value=v;
		bar.value++;	
}

/**
 * Показать сообщение с ошибкой
 * @param {string} msg текст сообщения с ошибкой
 */
function errGet(msg){
	util.attr(preloader, 'hidden','');										//спрятать спиннер 
	showProgressBar(100);													//подкрутить progress bar на максимум
	UIkit.notification(msg,{pos: 'bottom-right',status: 'danger'})			//показать текст ошибки  
}

/**
 * Показать сообщение
 * @param {string} msg текст сообщения
 * @param {string} stat визуальный статус сообщения
 */
function showMsg(msg, stat){
	outMsg.innerHTML ='';
	outShow.innerHTML = '';
	util.attr(preloader, 'hidden','');										//спрятать спиннер 
	showProgressBar(100);													//подкрутить progress bar на максимум
	UIkit.notification(msg,{pos: 'bottom-right',status: stat})				//показать текст ошибки  
}

/**
 * Получить данные из Б24 для одного запроса
 * @param {string} method имя метода/запроса
 * @param {object} params параметры запроса
 * @param {string} type выбор метода обработки запроса промис или генератор
 * @return {object} Promise or Generator
 */
async function getInfo(method, params, type='list'){
	let result={};
	let bx24 = new BX24Wrapper();
//	bx24.throttle = 0.5;	//Устанавливаем троттлинг запросов к API Битрикс24 на уровне 0,5 запросов в секунду

	bx24.progress = percent => showProgressBar(percent);//console.log(`progress: ${percent}%`);//showProgressBar(percent);				//progress bar
	try {
		
			let dataExtractor=null;
            switch (method){
				case "catalog.price.list":
					dataExtractor = data => data.prices;
					break;   					
   				case "catalog.product.list":
					dataExtractor = data => data.products;   					
					break;   					
				case "catalog.product.sku.list":
					dataExtractor = data => data.units;   					
					break;   					
				case "catalog.product.offer.list":
					dataExtractor = data => data.offers;   					
					break;   					
				case "catalog.product.service.list":
					dataExtractor = data => data.services;   					
					break;   					
//				...
	
			}	
				
		if (type == "list"){									//запросить данные на промисах в массив за один раз
			result = await bx24.callListMethod(method, params, dataExtractor)
				.catch(error => {										//ошибка конкретного промиса
					errGet(error, 'danger');
					});
/*
			for (let product of result) {
				console.log('Product:', product);
			}			
*/
		} else {														//запросить данные на генераторе
			let generator = bx24.fetchList(method, params, dataExtractor, 'id');
			for await (let products of generator) {
				Array.prototype.push.apply(result,products); 				//упакуем в массив
	    	}			
		}
	}catch(err) {  // перехватит любую ошибку в блоке try: и в fetch, и в response.json
    	errGet(err, 'danger');
  	}
  	
  	bx24 = null;
// OR
//   delete bx24;	
  	
	return result;		
  }

/*  
async function cleanUserData(userData) {
      console.log('Cleaning data');      
  }
  
async function saveToDataBase(userData) {
      console.log('Saving to DB');     
  }
*/

/**
 * Выполнить один запрос к Б24 (получить данные для внутреннего обработчика)
 * @param {string} method имя метода/запроса
 * @param {object} params параметры запроса
 * @return {object} Promise or Generator
 */
async function getInsideData(method, params) {
	bar.value =0;							
	bar.max =100;								//подкрутить progress bar
	outMsg.innerHTML = 'идет загрузка данных из Б24 пакетами по 50 штук...'; 
	outShow.innerHTML = '';
	util.removeAttr(preloader, 'hidden');									//показать спиннер
	UIkit.notification(method,{pos: 'bottom-right', status: 'primary'});	//показать юзеру сообщение
//    console.log('start');	  	

    const userData = await getInfo(method, params, tSelect.value);			//дождемся данных
//    const cleanedData = await cleanUserData(userData);
//    await saveToDataBase(cleanedData);
    
	bar.value =100;															//подкрутить progress bar на максимум
	outMsg.innerHTML ='выберите действие...';
	outShow.innerHTML = printResult(userData, 1);							//напечатать результат 												//напечатать результат
    util.attr(preloader, 'hidden','');										//спрятать спиннер	
	UIkit.notification('success',{pos: 'bottom-right', status: 'success'});	//показать юзеру сообщение	    
 
//    console.log('done');
}

/**
 * Выполнить несколько запросов к Б24 и отправить их по fetch к внешнему обработчику(получить данные для внешнего обработчика)
 * @param {string} method имя метода/запроса
 * @param {object} params параметры запроса
 * @return {object} 
 */
async function getOutsideData(method, params) {
	bar.value =0;															//подкрутить progress bar
	bar.max =100;
	outMsg.innerHTML = 'идет загрузка данных из Б24 пакетами по 50 штук...'; 
	outShow.innerHTML = '';
	util.removeAttr(preloader, 'hidden');									//показать спиннер
	UIkit.notification(method,{pos: 'bottom-right', status: 'primary'});	//показать юзеру сообщение
//    console.log('start');	  	

	//Простые товары (читаем весь каталог всегда)
	params = {
	    	select: ['id', 'iblockId', 'iblockSectionId', 'name', 'type', 'vatId', 'vatIncluded'],
			filter: { iblockId: 0 }, 
	    	order: { id: 'asc'}			    	
	};	
	params['filter']['iblockId']=21;
	outMsg.innerHTML = 'идет загрузка простых продуктов из Б24 (каталог 21) пакетами по 50 штук...';
    const baseProduct = await getInfo('catalog.product.list', params, 'list');			//дождемся данных
    //Торговые предложения
	params['filter']['iblockId']=46;
	outMsg.innerHTML = 'идет загрузка торговых предложений из Б24 (каталог 46) пакетами по 50 штук...';
    const extProduct = await getInfo('catalog.product.offer.list', params, 'list');		//дождемся данных

	let product = baseProduct.concat(extProduct);		//сложим массивы продуктов
	let products=[];									//сокращенный массив продуктов
	
	product.forEach(function(element, key){
		products.push({
				'id': element.id, 
				'iblockId': element.iblockId, 
				'iblockSectionId': element.iblockSectionId, 
				'name': element.name, 
				'type': element.type, 
				'vatId': element.vatId, 
				'vatIncluded': element.vatIncluded, 
			});
	});
	
//   console.log('сокращенный массив', products);

	let basePrices = [];								//массив базовых цен
	let extPrices = [];									//массив расширенных цен
	//ЦЕНЫ (читаем в зависимости от режима)
	if (method !='updateCatalog'){
		//базовые цены читаем всегда
		params = { 
//		            'order'  => ['timestampX'=>'desc'],
		            filter: {},//catalogGroupId: 1, >=price:0},// '>price'=>0],//'id'=>94364],//'>=price'=>0], 'productId'=>43666,
		            select: ['catalogGroupId', 'currency', 'id', 'price', 'priceScale', 'productId', 'timestampX'],
//		            start: 1, 
		};
		params.filter['catalogGroupId'] = 1;        		
		params.filter['>=price'] = 0; 
		outMsg.innerHTML = 'идет загрузка базовых цен из Б24 пакетами по 50 штук...';       		
		/*const basePrice*/ basePrices = await getInfo('catalog.price.list',params, 'list');		//дождемся данных
//	    console.log('базовые цены',basePrice);

		//расширенные в зависимости от режима
		params = { 
//	        'order'  => ['timestampX'=>'desc'],
			filter: {},//catalogGroupId: 1, '>=price':0},// '>price'=>0],//'id'=>94364],//'>=price'=>0], 'productId'=>43666,
			select: ['catalogGroupId', 'currency', 'id', 'price', 'priceScale', 'productId', 'timestampX'],
//			start: 1,
		};
	    switch (method){
			case "updateCurrency":
				params.filter['>catalogGroupId'] = 1;        		
				params.filter['>price'] = 0;
				params.filter['!=currency'] = 'RUB';
				break;			
			case "updateAll":
				params.filter['>catalogGroupId'] = 1;        		
				params.filter['>price'] = 0;        		
				break;
		}
		outMsg.innerHTML = 'идет загрузка расширенных цен из Б24 пакетами по 50 штук...';       		
		/*const extPrice*/ extPrices = await getInfo('catalog.price.list',params, 'list');		//дождемся данных
//	    console.log('расширенные цены',extPrice);
	}

	bar.value =0;
	bar.max =100;															//подкрутить progress bar
	outMsg.innerHTML = 'идет ОБНОВЛЕНИЕ данных в Б24 пакетами по 1000 штук...'; 	
	importJsonDataToB24(products, basePrices, extPrices);						//вызваттчик на обновление данных
  	
	  	
//    const userData = await getInfo(method, params);							//дождемся данных
//    const cleanedData = await cleanUserData(userData);
//    await saveToDataBase(cleanedData);
    
	bar.value =100;															//подкрутить progress bar на максимум
	outMsg.innerHTML ='выберите действие...';
//    outShow.innerHTML = tree(userData); 
    util.attr(preloader, 'hidden','');										//спрятать спиннер	
	UIkit.notification('success',{pos: 'bottom-right', status: 'success'});	//показать юзеру сообщение	    
 
//    console.log('done');
}

/**
 * Отправить пакетами информацию внешнему обработчику для последующей записи в Б24
 * @param {array} aProducts - массив продуктов и торговых предложений
 * @param {array} aBasePice - массив базовых цен
 * @param {array} aExtPtice - массив расширенных цен
 * @return {object} 
 */
async function importJsonDataToB24(aProducts, basePrice=[], extPrice=[]) {

	let chunkSize = 0;															//длина пакета данных
	let typeImport = inGet.value;												//наименование операции импортируемых данных (имя операции из формы)
	let packArray = [];															//массив пакетов
	let packNumber = 0;															//номер пакета 

	let caption = [];															//строка заголовковв из Excel
	let product =[];

	chunkSize = 1000;															//количество товаров в пакете
	caption = 'run from b24';													//строка заголовков
	
 	//отправка пакетов если они есть

 	if (chunkSize>0){

 		//собрать (нарезать) массив пакетов (json) для передачи
		for (let i = 0; i < aProducts.length; i += chunkSize) {
			product = aProducts.slice(i, i + chunkSize);
			packArray.push(JSON.stringify({
							typeImport,											//наименование операции 
							caption, 											//заголовок
							product, 											//пакет товаров
							basePrice, 											//базовые цены
							extPrice											//расширенные цены
						}));		//несколько пакетов структуры - имя(typeImport)+заголовок(caption)+данные(product)
		}

		bar.max = packArray.length;												//max progress bar = количество пакетов												
		bar.value=0;															//start progress bar = 0 

		//передаем пакеты из массива packArray по одному в обработчик updateDataB24.php
		const uploadStack = (row) => {
			return fetch("updateB24catalog.php", {
				method: 'POST',	        	
	        	body: row,
				headers: {'Content-Type': 'application/json;charset=utf-8'},
//	        	headers: new Headers()
				})
/*				
				.then(response => response.json())
  				.then(json => {
    				console.log('parsed json', json) // access json.body here
  				})
				.then((resp) => resp.json())
*/				
			   	.then(response => {return response.json();})
				.then(function(data) {
	            	util.attr(preloader, 'hidden','');
	         		if (!data){     
						UIkit.notification('обработчик не вернул данных... не хочет наверно...',{pos: 'bottom-right',status: 'warning'});
						outShow.innerHTML='';
					}else{
						if (data.length>0){
							UIkit.notification('обработчик чтото ответил (может в консоли посмотреть?)...',{pos: 'bottom-right',status: 'success'});
							outShow.innerHTML += printResult(data, 1);	//напечатать результат 
							console.log(data);
						}
					} 
				})
				.catch(function(error) {
					UIkit.notification(error,{pos: 'bottom-right'});
				})				
				;
		}			

		//цикл по массиву пакетов packArray
		for (const row of packArray) { 
			await uploadStack(row);
			 bar.value++;													//сдвинем прогресс бар
		}//конец цикла
//		outMsg.innerHTML ='выберите действие...';
		console.log('Done!');
  	} else {
		console.log('no Data!');		
	}	
	
}	

/**
 * Обработка событий web морды после загрузки страницы
 */
util.ready(function () {
    //BX24.setTitle('getInfo');
//    bar.value=100;
    UIkit.notification(BX24.getDomain()+' is Ready',{pos: 'bottom-right', status: 'success'});
    //---------------------------------------------------------------------------------------------------------------
	    
	util.on(inGet, 'change', function(event){								//юзер изменил запрос к базе
		if (inGet.value ==='') {   
			util.attr(btnGet, 'disabled', '');
			util.attr(btnUpdate, 'disabled', '');
			util.attr(tSelect, 'disabled', '');	
			util.attr(catalog, 'disabled', '');				
		}
		else{
			util.removeAttr(catalog, 'disabled');				
			switch (inGet.value){
				case "updateCurrency":
				case "updateCatalog":
				case "updateAll":
					util.attr(btnGet, 'disabled', '');
					util.removeAttr(btnUpdate, 'disabled')
					util.attr(tSelect, 'disabled', '');					
					break;
				default:
					util.removeAttr(btnGet, 'disabled')
					util.attr(btnUpdate, 'disabled', '');
					util.removeAttr(tSelect, 'disabled');					
			}
		}

		util.html(outShow, '');
	});	

	util.on(btnGet, 'click', function(){    								//нажали на кнопку
		let params={};
		let getOn=true;
		switch (inGet.value){
			case "crm.product.list":
				params = {
					filter: { CATALOD_ID: catalog.value },
//			    	select: [ 'ID', 'ACTIVE', 'NAME','ID', 'PRICE', 'CURRENCY_ID', 'VAT_ID', 'VAT_INCLUDED', 'UF_*' ]
 				    select: [ '*', 'PROPERTY_*']
			    };				
				break;
			case "crm.catalog.list":
			case "crm.productsection.list":
			case "crm.currency.list":
			case "crm.product.property.enumeration.fields":
			case "crm.product.property.list":
			case "crm.product.property.settings.fields":
			case "crm.product.property.types":
				params = {
			    };				
				break;
			case "catalog.price.list":
				params = {
			    	select: ['*', 'id' ]
				};				
				break;
			case "catalog.product.list":
			case "catalog.product.sku.list":
			case "catalog.product.offer.list":
			case "catalog.product.service.list":
    			params = {
					filter: { iblockId: catalog.value }, //,id: 10093 },
			    	select: ['*', 'id', 'iblockId' ]
//			    	select: [ 'id', 'iblockId', 'name', 'quantity', 'xmlId' ]			    	
				};				
				break;
   		case "catalog.section.list":
    			params = {
					filter: { iblockId: 21 }, //,id: 10093 },
			    	select: ['*', 'id', 'iblockId' ]
//			    	select: [ 'id', 'iblockId', 'name', 'quantity', 'xmlId' ]			    	
				};				
				break;

			case "catalog.catalog.list":
			case "catalog.productProperty.list": 
			case "catalog.vat.list":
    		case "catalog.productPropertySection.list":
				params = {
//					filter: { iblockId: catalog.value }, //,id: 10093 },
//			    	select: [ 'id', 'iblockId' ]
				};				
				break;

			default:
				getOn=false;
				showMsg('запрос не выбран', 'warning');
		}
		
		if (getOn)
			getInsideData(inGet.value, params);
	});
	
	util.on(btnUpdate, 'click', function(){    								//нажали на кнопку
		getOutsideData(inGet.value, {});
	});
});