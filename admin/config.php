<?php
// HTTP
define('HTTP_SERVER', 'https://test.uniup.com.ua/admin/');
define('HTTP_CATALOG', 'https://test.uniup.com.ua/');

// HTTPS
define('HTTPS_SERVER', 'https://test.uniup.com.ua/admin/');
define('HTTPS_CATALOG', 'https://test.uniup.com.ua/');

// DIR
define('DIR_APPLICATION', '/home/tvfree/uniup.com.ua/test/admin/');
define('DIR_SYSTEM', '/home/tvfree/uniup.com.ua/test/system/');
define('DIR_IMAGE', '/home/tvfree/uniup.com.ua/test/image/');
define('DIR_STORAGE', DIR_SYSTEM . 'storage/');
define('DIR_CATALOG', '/home/tvfree/uniup.com.ua/test/catalog/');
define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');
define('DIR_TEMPLATE', DIR_APPLICATION . 'view/template/');
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

// OpenCart API
define('OPENCART_SERVER', 'https://www.opencart.com/');
define('OPENCARTFORUM_SERVER', 'https://opencartforum.com/');

//укрпочта тестові ключі
define('SANDBOX_BEARER', 'f9027fbb-cf33-3e11-84bb-5484491e2c94'); // воно же апі
define('SAND_COUNTERPARTY_TOKEN', 'ba5378df-985e-49c5-9cf3-d222fa60aa68');
define('SAND_COUNTERPARTY_UUID', '2304bbe5-015c-44f6-a5bf-3e750d753a17');
define('SAND_TEST_KEY', '628dc133-a69f-30fb-ab19-35458e82b15c'); //Тестовый ключ трекинга
define('APPI_STORE_LOGIN', 'test_user'); //логин  в appi-store
define('APPI_STORE_PASSWORD', 'test_123$'); //пароль в appi-store

define('DEBUG', true); //отладка всього сайта
