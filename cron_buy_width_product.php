<?php
// Version
define('VERSION', '2.1.0.2');

// Configuration
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config.php');


// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Registry
$registry = new Registry();

// Config
$config = new Config();
$config->load('default');
$registry->set('config', $config);


// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
$registry->set('db', $db);





// Settings
$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0'");


foreach ($query->rows as $setting) {
	if (!$setting['serialized']) {
		$config->set($setting['key'], $setting['value']);
	} else {
		$config->set($setting['key'], json_decode($setting['value'], true));
	}
}


// Log
$log = new Log($config->get('error_filename'));
$registry->set('log', $log);

date_default_timezone_set($config->get('date_timezone'));

set_error_handler(function ($code, $message, $file, $line) use ($log, $config) {
    // error suppressed with @
    if (error_reporting() === 0) {
        return false;
    }

    switch ($code) {
        case E_NOTICE:
        case E_USER_NOTICE:
            $error = 'Notice';
            break;
        case E_WARNING:
        case E_USER_WARNING:
            $error = 'Warning';
            break;
        case E_ERROR:
        case E_USER_ERROR:
            $error = 'Fatal Error';
            break;
        default:
            $error = 'Unknown';
            break;
    }

    if ($config->get('error_display')) {
        echo '<b>' . $error . '</b>: ' . $message . ' in <b>' . $file . '</b> on line <b>' . $line . '</b>';
    }

    if ($config->get('error_log')) {
        $log->write('PHP ' . $error . ':  ' . $message . ' in ' . $file . ' on line ' . $line);
    }

    return true;
});


// Event
$event = new Event($registry);
$registry->set('event', $event);

// Event Register
if ($config->has('action_event')) {
    foreach ($config->get('action_event') as $key => $value) {
        foreach ($value as $priority => $action) {
            $event->register($key, new Action($action), $priority);
        }
    }
}

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Request
$request = new Request();
$registry->set('request', $request);

// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$response->setCompression($config->get('config_compression'));
$registry->set('response', $response);

// Database
if ($config->get('db_autostart')) {
    $db = new DB($config->get('db_engine'), $config->get('db_hostname'), $config->get('db_username'), $config->get('db_password'), $config->get('db_database'), $config->get('db_port'));
    $registry->set('db', $db);
}

// Session
$session = new Session($config->get('session_engine'), $registry);
$registry->set('session', $session);

if ($config->get('session_autostart')) {
    /*
    We are adding the session cookie outside of the session class as I believe
    PHP messed up in a big way handling sessions. Why in the hell is it so hard to
    have more than one concurrent session using cookies!

    Is it not better to have multiple cookies when accessing parts of the system
    that requires different cookie sessions for security reasons.

    Also cookies can be accessed via the URL parameters. So why force only one cookie
    for all sessions!
     */

    if (isset($_COOKIE[$config->get('session_name')])) {
        $session_id = $_COOKIE[$config->get('session_name')];
    } else {
        $session_id = '';
    }

    $session->start($session_id);

    setcookie($config->get('session_name'), $session->getId(), ini_get('session.cookie_lifetime'), ini_get('session.cookie_path'), ini_get('session.cookie_domain'));
}

// Cache
$registry->set('cache', new Cache($config->get('cache_engine'), $config->get('cache_expire')));


// Url
if ($config->get('url_autostart')) {
    $registry->set('url', new Url($config->get('site_url'), $config->get('site_ssl')));
}

// Turbosms
$turbosms = new Turbosms($registry);
$registry->set('turbosms', $turbosms);

// Ukrposhta
//require_once(DIR_SYSTEM . 'UkrposhtaAPI-master/src/Pochta.php');

$ukrpochta = new \Ukrpochta\Pochta(SANDBOX_BEARER);
$registry->set('ukrpochta', $ukrpochta);

// Language
$language = new Language($config->get('language_directory'));
$registry->set('language', $language);

// OpenBay Pro
$registry->set('openbay', new Openbay($registry));

// Document
$registry->set('document', new Document());

// Config Autoload
if ($config->has('config_autoload')) {
    foreach ($config->get('config_autoload') as $value) {
        $loader->config($value);
    }
}


// Language Autoload
if ($config->has('language_autoload')) {
    foreach ($config->get('language_autoload') as $value) {
        $loader->language($value);
    }
}

// Library Autoload
if ($config->has('library_autoload')) {
    foreach ($config->get('library_autoload') as $value) {
        $loader->library($value);
    }
}

// Model Autoload
if ($config->has('model_autoload')) {
    foreach ($config->get('model_autoload') as $value) {
        $loader->model($value);
    }
}


$query = $db->query('SELECT product_id FROM oc_product');

foreach($query->rows as $row){
    $query = $db->query("SELECT GROUP_CONCAT(order_id SEPARATOR ',') FROM oc_order_product   WHERE product_id='" . $row['product_id'] . "' ORDER BY product_id DESC");
if($query->row["GROUP_CONCAT(order_id SEPARATOR ',')"]){
    $order_id_list = $query->row["GROUP_CONCAT(order_id SEPARATOR ',')"];
    $query2 = $db->query("SELECT GROUP_CONCAT(product_id SEPARATOR ',') FROM oc_order_product WHERE order_id IN('" . $order_id_list . "') AND product_id!='" . $row['product_id'] . "'");
    if($query2->row){
$buy_width_product_id_list = trim($query2->row["GROUP_CONCAT(product_id SEPARATOR ',')"],',');
if(!empty($buy_width_product_id_list)){
$db->query("UPDATE oc_product SET product_buy_width='" . $buy_width_product_id_list . "' WHERE  product_id='" . $row['product_id'] . "'");

}


    }

}

}






