<?php
define('C_OWNER_ID_DEFAULT', xx);				//id владельца интеграций по умолчанию (пользователдь sa)	
define('C_SUBDOMAIN_NAME','xx.bitrix24.ru');	//поддомен
//define('C_REST_CLIENT_ID','local.63xxxxxxcc9.593xxxx13');//Application ID
//define('C_REST_CLIENT_SECRET','Zu20xxxxHwEPyzdV416xxxxx2JXeI9uhxxxxxxMwBwLt7zAE');//Application key
// or
//define('C_REST_WEB_HOOK_URL','https://'.C_SUBDOMAIN_NAME.'/rest/'.C_DEV_ID.'/egwxxxxp3h3xxxxxttj/');//url on creat Webhook

//define('C_REST_CURRENT_ENCODING','windows-1251');
//define('C_REST_IGNORE_SSL',true);//turn off validate ssl by curl
//define('C_REST_LOG_TYPE_DUMP',true); //logs save var_export for viewing convenience
define('C_REST_BLOCK_LOG',true);//);false);//,true);//turn off default logs
//define('C_REST_LOGS_DIR', __DIR__ .'/logs/'); //directory path to save the log
//--------------------------------------------------------------------------------------------------------------------

define('CURRENCY_WEB_HOOK_URL', 'https://'.C_SUBDOMAIN_NAME.'/rest/xx/xxxx/'); 	//url on creat in Webhook Currency
define('YANDEXFORM_WEB_HOOK_URL', 'https://'.C_SUBDOMAIN_NAME.'/rest/xx/xxxx/');//url on creat Webhook YandexForm
define('FULL_WEB_HOOK_URL', 'https://'.C_SUBDOMAIN_NAME.'/rest/xx/xxxx/');		//in Внешний доступ для CRM-автоматизации
    