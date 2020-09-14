<?php
// Version
define('VERSION', '2.1.0.2');

// Configuration
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config.php';

// Startup
require_once DIR_SYSTEM . 'startup.php';

// Registry
$registry = new Registry();

// Config
$config = new Config();
$config->load('default');
$registry->set('config', $config);

define('DB_DRIVER_FROM', 'mysqli');
define('DB_HOSTNAME_FROM', 'tvfree.mysql.tools');
define('DB_USERNAME_FROM', 'tvfree_replicate');
define('DB_PASSWORD_FROM', '+Ux77K_nn2');
define('DB_DATABASE_FROM', 'tvfree_replicate');
define('DB_PORT_FROM', '3306');
define('DB_PREFIX_FROM', '');

define('DB_DRIVER_TO', 'mysqli');
define('DB_HOSTNAME_TO', 'tvfree.mysql.tools');
define('DB_USERNAME_TO', 'tvfree_test');
define('DB_PASSWORD_TO', 'K;u52E3th(');
define('DB_DATABASE_TO', 'tvfree_test');
define('DB_PORT_TO', '3306');
define('DB_PREFIX_TO', 'oc_');

// Database
$db_from = new DB(DB_DRIVER_FROM, DB_HOSTNAME_FROM, DB_USERNAME_FROM, DB_PASSWORD_FROM, DB_DATABASE_FROM, DB_PORT_FROM);
$registry->set('db_from', $db_from);

// Database
$db_to = new DB(DB_DRIVER_TO, DB_HOSTNAME_TO, DB_USERNAME_TO, DB_PASSWORD_TO, DB_DATABASE_TO, DB_PORT_TO);
$registry->set('db_to', $db_to);

// Settings
$query = $db_to->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0'");

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


// Url
if ($config->get('url_autostart')) {
    $registry->set('url', new Url($config->get('site_url'), $config->get('site_ssl')));
}

// Turbosms
$turbosms = new Turbosms($registry);
$registry->set('turbosms', $turbosms);


// Document
$registry->set('document', new Document());

// Config Autoload
if ($config->has('config_autoload')) {
    foreach ($config->get('config_autoload') as $value) {
        $loader->config($value);
    }
}

/**
 * oc_product
 */
$all = 0;
$add=0;
$edit = 0;
$delete = 0;

 $query = $db_from->query('SELECT * FROM product');

 foreach ($query->rows as $item) {
     $all++;
     $product_id_from = $item['product_id'];

     $query2 = $db_to->query('SELECT * FROM oc_product WHERE product_id="'. $product_id_from .'"');
     if($query2->row){ //якщо знайшло товар то чатково оновлюємо його
         $edit++;
        $product_id_to = $query2->row['product_id'];
        $db_to->query('UPDATE  oc_product SET quantity="'. $item['quantity'] .'", stock_status_id="'. $item['stock_status_id'] .'", price="'. $item['price'] .'" WHERE product_id="' . $product_id_from . '"');
     }else{
         $sql = 'INSERT INTO  oc_product SET product_id="' . $item['product_id'] .'",model="' . $item['model'] . '",sku="' . $item['sku'] . '",upc="' . $item['upc'] . '",ean="' . $item['ean'] . '",jan="' . $item['jan'] . '",isbn="' . $item['isbn'] . '",mpn="' . $item['mpn'] . '",location="' . $item['location'] . '",quantity="' . $item['quantity'] . '",stock_status_id="' . $item['stock_status_id'] . '",image="' . $db_from->escape($item['image']) . '",manufacturer_id="' . $item['manufacturer_id'] . '",shipping="' . $item['shipping'] . '",price="' . $item['price'] . '",points="' . $item['points'] . '",tax_class_id="' . $item['tax_class_id'] . '",date_available="' . $item['date_available'] . '",weight="' . $item['weight'] . '",weight_class_id="' . $item['weight_class_id'] . '",length="' . $item['length'] . '",width="' . $item['width'] . '",height="' . $item['height'] . '",length_class_id="' . $item['length_class_id'] . '",subtract="' . $item['subtract'] . '",minimum="' . $item['minimum'] . '",sort_order="' . $item['sort_order'] . '",status="' . $item['status'] . '",viewed="' . $item['viewed'] . '",date_added="' . $item['date_added'] . '", date_modified="' . $item['date_modified'] . '", noindex="1"';
        $db_to->query($sql);
     }
 }
echo '('. $all .' Товарів всього), ('. $add. ' Товарів додано), ('. $edit .' Товарів відредаговано), ('. $delete .' Товарів видалено)';

/**
 * product_to_category
 */

 $query = $db_from->query('SELECT * FROM product_to_category');

foreach ($query->rows as $item) {
    $all++;
    $category_id_from = $item['category_id'];
    $product_id_from = $item['product_id'];


    $query2 = $db_to->query('SELECT * FROM oc_product_to_category WHERE category_id="' . $category_id_from . '" AND product_id="' . $product_id_from . '"');
    if (!$query2->row) { //якщо не знайшло категорію то додаємо
        $sql = 'INSERT INTO  oc_product_to_category SET product_id="' . $item['product_id'] . '",category_id="' . $item['category_id'] . '",main_category="' . $item['main_category'] . '"';
        $db_to->query($sql);
    }
}
/**
 * oc_product_description
 */

$query = $db_from->query('SELECT * FROM product_description');

foreach ($query->rows as $item) {
    $all++;
    $product_id_from = $item['product_id'];

    $query2 = $db_to->query('SELECT * FROM oc_product_description WHERE  product_id="' . $product_id_from . '" AND language_id="1"');
    if (!$query2->row) { //якщо не знайшло категорію то додаємо
        $sql = 'INSERT INTO  oc_product_description SET product_id="' . $item['product_id'] . '",language_id="1",name="' . $item['name'] . '",description="' . $item['description'] . '"';
        $db_to->query($sql);
    }
    
$query2 = $db_to->query('SELECT * FROM oc_product_description WHERE  product_id="' . $product_id_from . '" AND language_id="3"');
if (!$query2->row) { //якщо не знайшло категорію то додаємо
    $sql = 'INSERT INTO  oc_product_description SET product_id="' . $item['product_id'] . '",language_id="3",name="' . $item['name'] . '",description="' . $item['description'] . '"';
    $db_to->query($sql);
}

}

/**
 * product_image
 */

$query = $db_from->query('SELECT * FROM product_image');
$db_to->query("TRUNCATE `oc_product_image`");

foreach ($query->rows as $item) {
        $sql = 'INSERT INTO  oc_product_image SET product_id="' . $item['product_id'] . '",product_image_id="' . $item['product_image_id'] . '",image="' . $item['image'] . '",sort_order="' . $item['sort_order'] . '"';
        $db_to->query($sql);
}
/**
 * product_to_store
 */

$query = $db_from->query('SELECT * FROM product_to_store');

foreach ($query->rows as $item) {
    $product_id_from = $item['product_id'];

    $query2 = $db_to->query('SELECT * FROM oc_product_to_store WHERE  product_id="' . $product_id_from . '"');
    if (!$query2->row) {
        $sql = 'INSERT INTO  oc_product_to_store SET product_id="' . $item['product_id'] . '",store_id="0"';
        $db_to->query($sql);
    }
}

/**
 * product_to_layout
 */

$query = $db_from->query('SELECT * FROM product_to_layout');

foreach ($query->rows as $item) {
    $product_id_from = $item['product_id'];

    $query2 = $db_to->query('SELECT * FROM oc_product_to_layout WHERE  product_id="' . $product_id_from . '"');
    if (!$query2->row) {
        $sql = 'INSERT INTO  oc_product_to_layout SET product_id="' . $item['product_id'] . '",store_id="0",layout_id="0"';
        $db_to->query($sql);
    }
}

/**
 * 	product_attribute
 */

$query = $db_from->query('SELECT * FROM product_attribute');

foreach ($query->rows as $item) {
    $product_id_from = $item['product_id'];

    $query2 = $db_to->query('SELECT * FROM oc_product_attribute WHERE  product_id="' . $product_id_from . '" AND language_id="1"');
    if (!$query2->row) {
        $sql = 'INSERT INTO  oc_product_attribute SET product_id="' . $item['product_id'] . '",attribute_id="' . $item['attribute_id'] . '",language_id="1",text="' . $item['text'] . '"';
        $db_to->query($sql);
    }
    $query2 = $db_to->query('SELECT * FROM oc_product_attribute WHERE  product_id="' . $product_id_from . '" AND language_id="3"');
    if (!$query2->row) {
        $sql = 'INSERT INTO  oc_product_attribute SET product_id="' . $item['product_id'] . '",attribute_id="' . $item['attribute_id'] . '",language_id="3",text="' . $item['text'] . '"';
        $db_to->query($sql);
    }
}



exit;
// $query = $db_from->query('SELECT * FROM address');
// $db_to->query("TRUNCATE `oc_address`");
// foreach ($query->rows as $item) {
//     $sql = "INSERT INTO `oc_address` SET
//      `address_id`='" . $db_to->escape($item['address_id']) . "',
//      `customer_id`='" . $db_to->escape($item['customer_id']) . "',
//       `firstname`='" . $db_to->escape($item['firstname']) . "',
//        `lastname`='" . $db_to->escape($item['lastname']) . "',
//         `company`='" . $db_to->escape($item['company']) . "',
//          `address_1`='" . $db_to->escape($item['address_1']) . "',
//           `address_2`='" . $db_to->escape($item['address_2']) . "',
//            `city`='" . $db_to->escape($item['city']) . "',
//            `postcode`='" . $db_to->escape($item['postcode']) . "',
//             `country_id`='" . $db_to->escape($item['country_id']) . "',
//              `zone_id`='" . $db_to->escape($item['zone_id']) . "',
//               `custom_field`='" . $db_to->escape($item['custom_field']) . "',
//                `metka`='0'";
//     $db_to->query($sql);
// }

// $query = $db_from->query('SELECT * FROM agoo_signer');
// $db_to->query("TRUNCATE `oc_agoo_signer`");
// foreach ($query->rows as $item) {
//     $sql = "INSERT INTO `oc_agoo_signer` SET `id`='" . $db_to->escape($item['id']) . "', `pointer`='" . $db_to->escape($item['pointer']) . "', `customer_id`='" . $db_to->escape($item['customer_id']) . "', `email`='" . $db_to->escape($item['email']) . "', `date`='" . $db_to->escape($item['date']) . "'";
//     $db_to->query($sql);
// }

// $query = $db_from->query('SELECT * FROM attribute');
// $db_to->query("TRUNCATE `oc_attribute`");
// foreach ($query->rows as $item) {
//     $sql = "INSERT INTO `oc_attribute` SET `attribute_id`='" . $db_to->escape($item['attribute_id']) . "', `attribute_group_id`='" . $db_to->escape($item['attribute_group_id']) . "', `sort_order`='" . $db_to->escape($item['sort_order']) . "'";
//     $db_to->query($sql);
// }

// $query = $db_from->query('SELECT * FROM attribute_description');
// $db_to->query("TRUNCATE `oc_attribute_description`");
// foreach ($query->rows as $item) {
//     $sql = "INSERT INTO `oc_attribute_description` SET `attribute_id`='" . $db_to->escape($item['attribute_id']) . "', `language_id`='" . $db_to->escape($item['language_id']) . "', `name`='" . $db_to->escape($item['name']) . "'";
//     $db_to->query($sql);
// }

// $query = $db_from->query('SELECT * FROM attribute_group');
// $db_to->query("TRUNCATE `oc_attribute_group`");
// foreach ($query->rows as $item) {
//     $sql = "INSERT INTO `oc_attribute_group` SET `attribute_group_id`='" . $db_to->escape($item['attribute_group_id']) . "', `sort_order`='" . $db_to->escape($item['sort_order']) . "'";
//     $db_to->query($sql);
// }

// $query = $db_from->query('SELECT * FROM attribute_group_description');
// $db_to->query("TRUNCATE `oc_attribute_group_description`");
// foreach ($query->rows as $item) {
//     $sql = "INSERT INTO `oc_attribute_group_description` SET `attribute_group_id`='" . $db_to->escape($item['attribute_group_id']) . "', `language_id`='" . $db_to->escape($item['language_id']) . "', `name`='" . $db_to->escape($item['name']) . "'";
//     $db_to->query($sql);
// }

// $query = $db_from->query('SELECT * FROM cart');
// $db_to->query("TRUNCATE `oc_cart`");
// foreach ($query->rows as $item) {
//     $sql = "INSERT INTO `oc_cart` SET `cart_id`='" . $db_to->escape($item['cart_id']) . "', `api_id`='" . $db_to->escape($item['api_id']) . "', `customer_id`='" . $db_to->escape($item['customer_id']) . "',`session_id`='" . $db_to->escape($item['session_id']) . "', `product_id`='" . $db_to->escape($item['product_id']) . "', `recurring_id`='" . $db_to->escape($item['recurring_id']) . "', `option`='" . $db_to->escape($item['option']) . "', `quantity`='" . $db_to->escape($item['quantity']) . "', `date_added`='" . $db_to->escape($item['date_added']) . "', `price`='" . $db_to->escape($item['price']) . "'";
//     $db_to->query($sql);
// }

// $query = $db_from->query('SELECT * FROM attribute_group_description');
// $db_to->query("TRUNCATE `oc_attribute_group_description`");
// foreach ($query->rows as $item) {
//     $sql = "INSERT INTO `oc_attribute_group_description` SET `attribute_group_id`='" . $db_to->escape($item['attribute_group_id']) . "', `language_id`='" . $db_to->escape($item['language_id']) . "', `name`='" . $db_to->escape($item['name']) . "'";
//     $db_to->query($sql);
// }
