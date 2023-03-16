"use strict";										// код здесь обрабатывается в строгом режиме

let util =UIkit.util;
let inGet = util.$('#selGET');						//выпадающий список запросов 	

let first50 = util.$('#first50');					//первые 50 результатов 
let tSelect = util.$('#typeSelect');				//тип запроса 

//https://asuikit.com/v3/is/utility-javascript.filter
let chID = util.$('#chID');							//чекбокс Идентификатор
let valID = util.$('#valID');						//значение Идентификатор

let chFilter = util.$('#chFilter');					//чекбокс Фильтр
let maskFilter = util.$('#maskFilter');				//маска Фильтра
let nameFilter = util.$('#nameFilter');				//имя поля фильтра
let valFilter = util.$('#valFilter');				//значение поля фильтра

let bttnGet = util.$('.uk-button');					//кнопка

let outGet=null;				
let outMsg = util.$('#comment'); 					//комментарий
let outShow = util.$('#outShow'); 					//результаты запроса

let preloader = util.$('#preloader');				//спиннер
let bar = util.$('#js-progressbar');				//прогресс бар
//let bar = document.getElementById('js-progressbar');

let p1 ="&bull;";
let p2 ="&nldr;";
let p3 ="&hellip";

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
 * Обработка событий web морды после загрузки страницы
 */
util.ready(function () {
    //BX24.setTitle('getInfo');
//    bar.value=100;
    UIkit.notification(BX24.getDomain()+' is Ready',{pos: 'bottom-right', status: 'success'});
    //---------------------------------------------------------------------------------------------------------------

	util.on(inGet, 'change', function(event){									//юзер изменил запрос к базе
		chID.checked = (inGet.value.indexOf(".get") >= 0);
		chFilter.checked = (inGet.value.indexOf(".list") >= 0);	

		if (inGet.value ==='') {   
			util.attr(bttnGet, 'disabled', '')
		}
		else{
			util.removeAttr(bttnGet, 'disabled')
		}

		util.html(outShow, '');
	}) 

	util.on(first50, 'change', function(event){									//юзер изменил запрос к базе
		typeSelect.value='list';
		if (first50.value ==='one') {   
			util.attr(typeSelect, 'disabled', '')
		}
		else{
			util.removeAttr(typeSelect, 'disabled')
		}
	}) 

	util.on(bttnGet, 'click', function(){    									//нажали на кнопку

		let payload = inGet.value;													//имя метода Rest API			
		if (payload==='')
		{
    		util.html(outShow, '');												//вывести результаты запроса
			UIkit.notification('запрос не выбран',{pos: 'bottom-right',status: 'warning'})				//показать текст ошибки 		
		}
		else 
		{
			bar.value =0;							
			bar.max =100;															//подкрутить progress bar
			util.html(outMsg, 'идет загрузка данных из Б24 пакетами по 50 штук...'); 
	//		outMsg.innerHTML = 'идет загрузка данных из Б24 пакетами по 50 штук...'; 
			outShow.innerHTML = '';
			util.removeAttr(preloader, 'hidden');									//показать спиннер
			UIkit.notification(payload,{pos: 'bottom-right', status: 'primary'});	//показать юзеру сообщение

			(async () => {

			    let bx24 = new BX24Wrapper();
			    bx24.throttle = 0.5;											//один запрос в 2 сек 
				bx24.progress = percent => bar.value = percent;					//progress bar

				let params={};  												//объект параметров
				if (chID.checked)							
					Object.assign(params, {id: valID.value});					//если есть добавим id

				if (chFilter.checked){											//если есть добавим фильтр
					let filter = {
							filter: {
								[maskFilter.value + nameFilter.value] : valFilter.value,
									}
					};
					Object.assign(params, filter);
        		}

            
//				let params = {
//				  id: valID.value	
			//    filter: { CATALOD_ID: 21 },
			//    select: [ '*', 'PROPERTY_*' ]
//				};

//				util.html(outMsg, '<p>идет загрузка данных из Б24 пакетами по 50 штук...</p>');
//				util.removeAttr(preloader, 'hidden');								//показать спиннер
//				UIkit.notification(payload,{pos: 'bottom-right', status: 'primary'});	//показать юзеру сообщение

                if (first50.value==='one'){
					outGet = await bx24.callMethod(payload, params)					//запросить данные на промисах
						.catch(error => {											//ошибка конкретного промиса
							errGet(error);
						});
				}
				else{
					
					if (tSelect.value == "list"){
						outGet = await bx24.callListMethod(payload, params)				//запросить данные на промисах в массив за один раз
							.catch(error => {											//ошибка конкретного промиса
								errGet(error, 'danger');
								});

//						for (let product of outGet) {
//						console.log('Product:', product);
//						}			
					} else {
					// Загружем список всех товаров в заданном товарном каталоге используя асинхронный генератор
						let generator = bx24.fetchList(payload, params);
						for await (let products of generator) {
							for (let product of products) {
//								console.log('Product:', product);
							}
							Array.prototype.push.apply(outGet,products); 				//упакуем в массив
	    				}			
					}
					
/*					
					outGet = await bx24.callListMethod(payload, params)				//запросить данные на промисах
						.catch(error => {											//ошибка конкретного промиса
							errGet(error);
						});
*/
				}

				let outRes=printResult(outGet, 1);							//напечатать результат 					
				if (outRes.length==0){
					util.html(outShow, '');					
					UIkit.notification('запрос не вернул результатов',{pos: 'bottom-right', status: 'warning'});
				}
				else {
					UIkit.notification('success',{pos: 'bottom-right', status: 'success'});
					outShow.innerHTML = outRes;									//вывести результаты запроса
//					util.html('<p>'+outMsg+'</p>', outRes);
				}

				outMsg.innerHTML ='выберите действие...';
				util.attr(preloader, 'hidden','');								//спрятать спиннер
	    
			})().catch(															//общая ошибка цепочки промисов
				error => {errGet(error);}
				);
		}
	});

});