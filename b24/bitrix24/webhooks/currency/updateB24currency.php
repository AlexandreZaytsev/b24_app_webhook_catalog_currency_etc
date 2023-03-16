<?php

	//настройки
	require_once (dirname(__DIR__, 2) . '/settings.php');	//токен вебхука
	define('C_REST_WEB_HOOK_URL', CURRENCY_WEB_HOOK_URL); 	//url on creat in Webhook Currency для обертки crest от Битрикс24
	require_once (dirname(__DIR__, 2) . '/crest.php');		//обертка crest от Битрикс24

	//запросить курсы валют на дату из базы центробанка и вернуть массив код=>значение
	//создать или обновить локальный файл с курсами валют
	//возврат из локального файла массива ключ Имя валюты => значение куртс
	function get_currency($currency_code, $format) {

		$date = date('d/m/Y'); // Текущая дата
		$cache_time_out = 14400; // Время жизни кэша в секундах
		$file_currency_cache = 'daily.xml'; //./currency.xml'; // Файл кэша

		if(!is_file($file_currency_cache) || filemtime($file_currency_cache) < (time() - $cache_time_out)) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://www.cbr.ru/scripts/XML_daily.asp?date_req='.$date);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
			$out = curl_exec($ch);
			curl_close($ch);

			file_put_contents($file_currency_cache, $out);				//сохранить xml курсы валют локально
		}
		$content_currency = simplexml_load_file($file_currency_cache);	//получить объект SimpleXMLElement

//		return number_format(str_replace(',', '.', $content_currency->xpath('Valute[CharCode="'.$currency_code.'"]')[0]->Value), $format);

		$result = array();
		foreach ($content_currency as $el) {
	    	$result["$el->CharCode"] = number_format(strtr($el->Value, ',', '.'), $format);
		}	
		return $result; 
	}

	$cur = get_currency('',4);											//прочтем курсы валют из локального файла
	$arData = [
		'setUSD_currency' => [
			'method' => 'crm.currency.update',
			'params' => [ "id" => "USD", "fields" => array('AMOUNT_CNT' => 1, 'AMOUNT' => $cur['USD'], 'SORT' => 200)]
			],
		'setEUR_currency' => [
			'method' => 'crm.currency.update',
			'params' => [ "id" => "EUR", "fields" => array('AMOUNT_CNT' => 1, 'AMOUNT' => $cur['EUR'], 'SORT' => 300)]
			],
		];

	//Отправить пакет запросов	
	//@var $arData array 												//массив запросов
	//@var $halt integer 0 or 1 stop batch on error
	//@return array
	$result = CRest::callBatch($arData, $halt = 0);
	
	//Отправить один запрос	
	//@return array
/*	
	$result = CRest::call(
		'crm.currency.update', 
		[
                'id' => 'USD',
                'fields' => [
                    'AMOUNT_CNT' => 1,
                    'AMOUNT' => $cur['USD'],
                    'SORT' => 9000					
					]
        ]
        );
*/


	//посмотрим на результат или ошибки запроса
	//$out = print_r($result, true);
	//file_put_contents('currency_log.txt', date('Y-m-d H:i:s', time()).PHP_EOL.$out."\n", FILE_APPEND);            
