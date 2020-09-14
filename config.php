<?php
// HTTP
define('HTTP_SERVER', 'https://test.uniup.com.ua/');

// HTTPS
define('HTTPS_SERVER', 'https://test.uniup.com.ua/');
define('COUNT_SHOW_MINI_IMG_ORDER',4);

// DIR
define('DIR_APPLICATION', '/home/tvfree/uniup.com.ua/test/catalog/');
define('DIR_SYSTEM', '/home/tvfree/uniup.com.ua/test/system/');
define('DIR_IMAGE', '/home/tvfree/uniup.com.ua/test/image/');
define('DIR_IMAGE_AVATAR_SOCAUTH', 'avatar/socauth/');
define('DIR_DATA', '/home/tvfree/uniup.com.ua/test/data/');
define('DIR_STORAGE', DIR_SYSTEM . 'storage/');
define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');
define('DIR_TEMPLATE', DIR_APPLICATION . 'view/theme/');
define('DIR_CONFIG', DIR_SYSTEM . 'config/');
define('DIR_CACHE', DIR_STORAGE . 'cache/');
define('DIR_DOWNLOAD', DIR_STORAGE . 'download/');
define('DIR_LOGS', DIR_STORAGE . 'logs/');
define('DIR_MODIFICATION', DIR_STORAGE . 'modification/');
define('DIR_SESSION', DIR_STORAGE . 'session/');
define('DIR_UPLOAD', DIR_STORAGE . 'upload/');

// DB
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'tvfree.mysql.tools');
define('DB_USERNAME', 'tvfree_test');
define('DB_PASSWORD', 'K;u52E3th(');
define('DB_DATABASE', 'tvfree_test');
define('DB_PORT', '3306');
define('DB_PREFIX', 'oc_');

//turbosms setting
define('GATEWAY_SENDER_SMS','4tv.in.ua'); //вона же пыдпис ы сендер
define('GATEWAY_PASSWORD','Cfif1980');
define('GATEWAY_LOGIN','zovyt123');
define('TURBO_SMS_DEBUG',false);
define('TURBO_SMS_CHECKOUT_TEST',false); //якщо стоїть true то можна оформити замовлення пропустивши на першому етапі відправлення смс.

//define('API_NOVA_POSHTA', '15bdabc931702f1fc00ce98ceab3be29');  //Термін дії ключа 18.01.2021 12:19:39 мый ключ
define('API_NOVA_POSHTA', 'bab7f270772fcdaca3aad1b0366b5eca');

//укрпочта тестові ключі
define('SANDBOX_BEARER', 'f9027fbb-cf33-3e11-84bb-5484491e2c94'); // воно же апі
define('SAND_COUNTERPARTY_TOKEN', 'ba5378df-985e-49c5-9cf3-d222fa60aa68');
define('SAND_COUNTERPARTY_UUID', '2304bbe5-015c-44f6-a5bf-3e750d753a17');
define('SAND_TEST_KEY', '628dc133-a69f-30fb-ab19-35458e82b15c'); //Тестовый ключ трекинга
define('APPI_STORE_LOGIN', 'test_user'); //логин  в appi-store
define('APPI_STORE_PASSWORD', 'test_123$'); //пароль в appi-store


//продакшн ключі


define('PRODUCTION_BEARER_eCom', '3057712f-107c-3ed0-a3bb-286699972c47');
define('SANDBOX_BEARER_eCom', '014aec33-49d8-3644-b92b-f7937314faaa');
define('SANDBOX_BEARER_StatusTracking', '5e879a60-f696-33c8-95ff-e925d2b89fce');
define('PRODUCTION_BEARER_StatusTracking', '06c-b9a4-3931-8176-77942cce3c9e');
define('PRODUCTION_COUNTER_PARTY_TOKEN', '279125ab-cdcb-4bd1-9b63-10720ded1c10');
define('SAND_COUNTER_PARTY_TOKEN', '610500ad-0b7c-49f1-a58c-d963e0a4b79d');
define('COUNTER_PARTY_UUID', '3d42151f-b3aa-4afa-b450-b6bb986d6c8a');

define('DEBUG', true); //отладка всього сайта


//лимити
define('LIMIT_BUY_PRODUCT', 15);
define('LIMIT_REVIEW_LIST', 20);

