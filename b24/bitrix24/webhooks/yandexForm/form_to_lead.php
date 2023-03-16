<?php

//настройки
require_once (dirname(__DIR__,2).'/settings.php');		//токен вебхука	
define('C_REST_WEB_HOOK_URL', YANDEXFORM_WEB_HOOK_URL);	//url on creat Webhook YandexForm для обертки crest от Битрикс24
require_once (dirname(__DIR__,2).'/crest.php');			//обертка crest от Битрикс24

	$main = NULL;			//основной блок с обязательными вопросами	
	$questions = NULL;		//блок с дополнительными вопросами	
	$from = NULL;			//блок с описанием доп параметров	
	//итоговое поля
	$note = NULL;			//комментарий	
	$add_lead = FALSE;		//создать Лид в Битрикс24 или нет (для удобства)
	//
	$executor_id = 3;		//id исполнителя на Лид из структуры компании РПК в Битрикс24 (78-sa, 3-Руденко Марина)	

	if($json = json_decode(file_get_contents("php://input"), true)) {
//		print_r($json);
		$data = $json;
		if(isset($data)){
			$add_lead = TRUE;
			//-------------------------
			//отдельные поля
			//обязательно желательные поля для Лида
			$title = $data['params']['title'] ? : NULL;							//Заголовок[string] 	
			$last_name = $data['params']['last_name'] ? : NULL;					//Фамилия[string]
			$name = $data['params']['name'] ? : NULL;							//Имя[string]
			$second_name = $data['params']['second_name'] ? : NULL;				//Отчество[string]
			$email = $data['params']['email'] ? : NULL;							//Адрес электронной почты[crm_multifield]
			$phone = $data['params']['phone'] ? : NULL;							//Телефон контакта[crm_multifield]
			$company_title = $data['params']['company_title'] ? : NULL;			//Название компании, привязанной к лиду[crm_company]
			$web = $data['params']['web'] ? : NULL;								//URL ресурсов лида[crm_multifield]
			$address = $data['params']['address'] ? : NULL;						//Адрес контакта[string]

			//UTF метки
			$host_name = $data['params']['host_name'] ? : 'yandex_form';		//Адрес хоста с формами (forms.yandex.ru)
			$utm_source = $data['params']['utm_source'] ? : $host_name;			//Рекламная система[string]	Yandex-Direct, Google-Adwords и другие.
			
			$utm_campaign = $data['params']['utm_campaign'] ? : 'get_lead';		//Обозначение рекламной кампании[string]{campaign_id}
			$utm_medium = $data['params']['utm_medium'] ? : 'cpc';				//Тип трафика[string] CPC (объявления), CPM (баннеры) {source_type}
			$utm_content = $data['params']['utm_content'] ? : 'soft';			//Содержание кампании[string] Например, для контекстных объявлений {ad_id}
			$utm_term = $data['params']['utm_term'] ? : 'demo';					//Условие поиска кампании[string] Например, ключевые слова контекстной рекламы {keyword}

			//дополнительные поля (!не существуют в Лиде)
			$type_counterparty = $data['params']['type_counterparty'] ? : NULL;	//Тип контрагента (юрик/физик)
			$company_inn = $data['params']['company_inn'] ? : NULL;				//ИНН компании
			$company_kpp = $data['params']['company_kpp'] ? : NULL;				//КПП компании
			
			if ($type_counterparty == 'Юридическое лицо'){
				//добавить к имени компании ИНН КПП
				$company_title .= $company_inn ? ', ИНН: '.$company_inn : ''; 
				$company_title .= $company_kpp ? ', КПП: '.$company_kpp : ''; 
			} else {
				$company_title = $type_counterparty;	//.trim($last_name." ".$name." ".$second_name);
			}

			//набор полей(вопросов ответов формы) в формате JSON
			$note = '';															//сводное поле - комментарий
			$questions = json_decode($data['params']['questions'], true);		//массив данных о вопросах для поля комментарий
			//собрать всю инфу в одно поле с прасингом перевода строки для радиокнопок (разделитель "\n") и чекбоксов (разделитель ",")
			if ($questions){
			    $note .= '';//$company_title.'<br>';
				$note .= '<b>Анкета ('.$title.')</b><hr>';
				foreach ($questions as $key => $value) {
					$value = str_replace(array("\r\n", "\n", "\r"),'<br> -',$value);
//					$value = str_replace(", ",'<br> -',$value);
					$note .= '<br><b>'.$key.':</b><br> -'.$value.'<br>';
				}
				$note .= '<hr>';	
				$from = $data['params']['from'] ? : NULL;						//данные о источнике для поля комментарий
				$note .= $from;
			} else {
				$note .= 'no comments';	
			}
			
		}
	} else {
//		print_r($_POST);
		$data = $_POST;
		if(isset($data)){
			$add_lead = TRUE;
		}
	}

//	file_put_contents('frm_log.txt', date('Y-m-d H:i:s', time()).PHP_EOL.json_encode($json, JSON_UNESCAPED_UNICODE)."\n", FILE_APPEND);
//	file_put_contents('frm_log.txt', date('Y-m-d H:i:s', time()).PHP_EOL.$note."\n", FILE_APPEND);

	//Добавить Lead в Битрикс24
	$add_lead = $type_counterparty ? TRUE : FALSE;							//блокировка при открытии страницы хука по адресу  
	if ($add_lead){
//		echo "b24\n";		
		$result = CRest::call(
		   'crm.lead.add',
	   	[
			'fields' =>[
				'TITLE' => $title,											//Заголовок[string] 
				'LAST_NAME' => $last_name,									//Фамилия[string]
				'NAME' => $name,											//Имя[string]
				'SECOND_NAME' => $second_name,								//Отчество[string]		
				'EMAIL' => [												//Адрес электронной почты[crm_multifield]														
						['VALUE' => $email, 'VALUE_TYPE' => 'WORK']
					],	
				'PHONE' => [												//Телефон контакта[crm_multifield]
						['VALUE' => $phone, 'VALUE_TYPE' => 'WORK']
					],	
			      
				'COMPANY_TITLE' => $company_title,							//Название компании, привязанной к лиду[crm_company]	
				'ADDRESS' => $address,										//Адрес контакта[string]			

//				'STATUS_ID' => 'NEW',										//string Идентификатор статуса лида
//				'STATUS_DESCRIPTION' => NULL,								//string Дополнительная информация о статусе		      
				'SOURCE_ID' => 'WEB',										//Идентификатор источника[crm_status]
				'SOURCE_DESCRIPTION' => 'Yandex Form',						//Описание источника[string]
				'COMMENTS' => $note,										//Комментарии[string]
				'OPENED' => 'Y',											//Доступен для всех[char]
				'WEB' => [													//URL ресурсов лида[crm_multifield]
						['VALUE' => $web, 'VALUE_TYPE' => 'WORK']
					],

				'ASSIGNED_BY_ID' => $executor_id,							//Связано с пользователем по ID[user]

				'UTM_SOURCE' => $utm_source,								//Рекламная система[string]	Yandex-Direct, Google-Adwords и другие.
				'UTM_CAMPAIGN' => $utm_campaign,							//Обозначение рекламной кампании[string]
				'UTM_MEDIUM' => $utm_medium,								//Тип трафика[string] CPC (объявления), CPM (баннеры)
				'UTM_CONTENT' => $utm_content,								//Содержание кампании[string] Например, для контекстных объявлений.
				'UTM_TERM' => $utm_term,									//Условие поиска кампании[string] Например, ключевые слова контекстной рекламы.
	   	   ]
	   	]);

//		посмотрим на результат или ошибки запроса
//		$out = print_r($result, true);
//		file_put_contents('frm_log.txt', date('Y-m-d H:i:s', time()).PHP_EOL.$out."\n", FILE_APPEND);
	}	    
