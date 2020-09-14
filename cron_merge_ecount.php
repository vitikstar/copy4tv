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

//проходимось по всіх користувачах і на кожному користувачі будем шукати дубль. Якщо є то пишемо для першого номер замовлення 
$query = $db->query("SELECT email FROM oc_customer WHERE email!=''");

foreach ($query->rows as $row) {
    $result = $db->query("SELECT customer_id,email,telephone,address_id,avatar,avatar_fb,password,salt FROM oc_customer WHERE email='" . $db->escape($row['email']) . "'");
    if($result->num_rows>1){
        $customer_id = $result->row['customer_id']; //id першого в базі кастомера
        $count = 0;
        foreach ($result->rows as $customer_info) {
            
            /**
             * ПРИ МЕРЖІ ЗА ОСНОВУ БЕРЕМ СТАРИЙ ЕКАУНТ А З НОВИХ ДО СТАРОГО ДОДАЄМ ВСЮ ІСТОРІЮ І ТЕЛЕФОНИ КРІМ АДРЕСИ І ВСІХ ОСОБИСТИХ ДАНИХ
             * ВЕСЬ КАБІНЕТ КРІМ ОСОБИСТИХ ДАНИХ, ДОДАЄМ ТІЛЬКИ ТЕЛЕФОН БО ЇХ МОЖЕ БУТИ ДЕКІЛЬКА
             */
            //МЕРЖЕМО ТЕЛЕФОНИ

                $query = $db->query("SELECT * FROM oc_customer_telephone WHERE telephone='" . $customer_info['telephone'] . "'");
            $query2 = $db->query("SELECT * FROM oc_customer WHERE telephone='" . $customer_info['telephone'] . "'");
            if (!$query->rows and !$query2->rows) {
                                    if ($count) {
                        $sql ="INSERT INTO oc_customer_telephone SET telephone='" . $customer_info['telephone'] . "',customer_id='" . $customer_id . "'";
                        $db->query($sql);
                    }
                    $count++;
                }

                //ОБЄДНУЄМО АКАУНТИ
                $query = $db->query("SELECT * FROM oc_customer_relation WHERE customer_id_main='" . $customer_id . "' AND customer_id_child='" . $customer_info['customer_id'] . "'");
                if (!$query->rows and $customer_id!=$customer_info['customer_id']) {
                        $sql = "INSERT INTO oc_customer_relation SET customer_id_main='" . $customer_id. "',customer_id_child='" . $customer_info['customer_id'] . "'";
                        $db->query($sql);
                }
                if ($customer_id != $customer_info['customer_id']) {
                    //ми новиостворений екаунт відключаємо а зилишаємо старіший то якщо в новому буде звязок нам нада цей звязок перенести на старий
                    $result = $db->query("SELECT * FROM oc_customer WHERE relation_fb!='0' AND customer_id='" . $customer_info['customer_id'] . "'");

                    if($result->rows){
                            $sql = "UPDATE oc_customer SET relation_fb='0' WHERE customer_id='" . $customer_info['customer_id'] . "'";
                            $db->query($sql);
                            
                            $result2 = $db->query("SELECT * FROM oc_customer WHERE relation_fb='0' AND customer_id='" . $customer_id . "'");
                            if($result2->rows){
                                $sql = "UPDATE oc_customer SET relation_fb='". $result->row['relation_fb'] ."' WHERE customer_id='" . $customer_id . "'";
                                $db->query($sql);
                            }
                            $sql = "UPDATE oc_customer SET relation_fb='1' WHERE customer_id='" . $customer_id . "'";
                            $db->query($sql);
                    }
                    $sql = "UPDATE oc_customer SET status='0' WHERE customer_id='" . $customer_info['customer_id'] . "'";
                    $db->query($sql);

                    //переносимо адресу якщо вона пуста в екаунта який залишається включеним

                    $result2 = $db->query("SELECT * FROM oc_customer WHERE address_id='0' AND customer_id='" . $customer_id . "'");

                    if ($result2->rows) {
                        $sql = "UPDATE oc_customer SET address_id='" . $customer_info['address_id'] . "' WHERE customer_id='" . $customer_id . "'";
                        $db->query($sql);
                        


                        $sql = "UPDATE oc_address SET customer_id='" . $customer_id . "' WHERE address_id='" . $customer_info['address_id']  . "'";

                        $db->query($sql);

                    }

                    //переносимо картинку якщо вона пуста в екаунта який залишається включеним

                    if (empty($customer_info['avatar'])) {
                        $res = $db->query("SELECT * FROM oc_customer WHERE avatar!='' AND email='" . $customer_info['email'] . "'");
                        if($res->rows){
                                $sql = "UPDATE oc_customer SET avatar='" . $res->row['avatar'] . "' WHERE customer_id='" . $customer_id . "'";
                                $db->query($sql);
                        }
                    }

                    if (empty($customer_info['avatar_fb'])) {
                        $res = $db->query("SELECT * FROM oc_customer WHERE avatar_fb!='' AND email='" . $customer_info['email'] . "'");
                        if ($res->rows) {
                                    $sql = "UPDATE oc_customer SET avatar_fb='" . $res->row['avatar_fb'] . "' WHERE customer_id='" . $customer_id . "'";
                                    $db->query($sql);
                        }
                    }

                    //нам потрібно щоб залишилась привязка до соц мереж

                    $query = $db->query("SELECT * FROM oc_socnetauth2_customer2account WHERE customer_id='" . $customer_info['customer_id'] . "'");
                    if (!$query->rows) {
                        if ($count) {
                            $sql = "UPDATE oc_socnetauth2_customer2account SET customer_id='" . $customer_id . "',customer_id='" . $customer_info['customer_id'] . "'";
                            $db->query($sql);
                        }
                        $count++;
                    }


                    //переносимо пароль якщо він пустий в екаунта який залишається включеним
                    $result2 = $db->query("SELECT * FROM oc_customer WHERE customer_id='" . $customer_id . "' AND password='' AND salt=''");
                    if ($result2->num_rows) {
                        $res = $db->query("SELECT * FROM oc_customer WHERE email='" . $customer_info['email'] . "' AND password!='' AND salt!='' AND `register_email`=1");
                        if ($res->rows) {
                            $sql = "UPDATE oc_customer SET password='" . $res->row['password'] . "',salt='" . $res->row['salt'] . "' WHERE customer_id='" . $customer_id . "'";
                            $db->query($sql);
                        }
                    }
                }


                                            // $result = $db->query("SELECT oo.order_id FROM oc_order oo LEFT JOIN oc_customer oc ON oo.customer_id=oc.customer_id WHERE oc.email='" . $customer_info['email'] . "'");
                                            //     foreach ($result->rows as $order_by_customer_info) {
                                            //         $query = $db->query("SELECT * FROM oc_order_customer WHERE order_id='" . $order_by_customer_info['order_id'] . "' AND customer_id='" . $customer_info['customer_id'] . "'");
                                            //         if (!$query->rows) {
                                            //             $db->query("INSERT INTO oc_order_customer SET order_id='" . $order_by_customer_info['order_id'] . "',customer_id='" . $customer_info['customer_id'] . "'");
                                            //         }
                                            //     }
}
    }
}



$query = $db->query("SELECT telephone FROM oc_customer WHERE telephone!='' AND LENGTH(telephone) > 5");

foreach ($query->rows as $row) {

    $result = $db->query("SELECT * FROM oc_customer WHERE telephone='" . $db->escape($row['telephone']) . "'");
    if ($result->num_rows > 1) {
        $customer_id = $result->row['customer_id']; //id першого в базі кастомера
        $count = 0;
        foreach ($result->rows as $customer_info) {
            /**
             * ПРИ МЕРЖІ ЗА ОСНОВУ БЕРЕМ СТАРИЙ ЕКАУНТ А З НОВИХ ДО СТАРОГО ДОДАЄМ ВСЮ ІСТОРІЮ І ТЕЛЕФОНИ КРІМ АДРЕСИ І ВСІХ ОСОБИСТИХ ДАНИХ
             * ВЕСЬ КАБІНЕТ КРІМ ОСОБИСТИХ ДАНИХ, ДОДАЄМ ТІЛЬКИ ТЕЛЕФОН БО ЇХ МОЖЕ БУТИ ДЕКІЛЬКА
             */

            //МЕРЖЕМО ТЕЛЕФОНИ

            $query = $db->query("SELECT * FROM oc_customer_telephone WHERE telephone='" . $customer_info['telephone'] . "'");
            $query2 = $db->query("SELECT * FROM oc_customer WHERE telephone='" . $customer_info['telephone'] . "'");
            if (!$query->rows and !$query2->rows) {
                if ($count) {
                    $sql = "INSERT INTO oc_customer_telephone SET telephone='" . $customer_info['telephone'] . "',customer_id='" . $customer_id . "'";
                    $db->query($sql);
                }
                $count++;
            }

  

            

            //ОБЄДНУЄМО АКАУНТИ і виключаємо всі крім основного(самого першого зареєстрованого, він в нас основний)
            $query = $db->query("SELECT * FROM oc_customer_relation WHERE customer_id_main='" . $customer_id . "' AND customer_id_child='" . $customer_info['customer_id'] . "'");
            if (!$query->rows and $customer_id != $customer_info['customer_id']) {
                $sql = "INSERT INTO oc_customer_relation SET customer_id_main='" . $customer_id . "',customer_id_child='" . $customer_info['customer_id'] . "'";
                $db->query($sql);
            }
            if ($customer_id != $customer_info['customer_id']){
                //ми новиостворений екаунт відключаємо а зилишаємо старіший то якщо в новому буде звязок нам нада цей звязок перенести на старий
               
               // $customer_id це головний юзер(той з яким йде обєднання)
                // $customer_info['customer_id'] дочірній юзер(їх може бути багато)

                $result1 = $db->query("SELECT * FROM oc_customer WHERE relation_fb='1' AND customer_id='" . $customer_info['customer_id'] . "'");

                if ($result1->num_rows) {
                    $sql = "UPDATE oc_customer SET relation_fb='0' WHERE customer_id='" . $customer_info['customer_id'] . "'";
                    $db->query($sql);

                    $sql = "UPDATE oc_customer SET relation_fb='1' WHERE customer_id='" . $customer_id . "'";
                    $db->query($sql);
                }
                //нам потрібно щоб залишилась привязка до соц мереж

                $query = $db->query("SELECT * FROM oc_socnetauth2_customer2account WHERE customer_id='" . $customer_info['customer_id'] . "'");
                if (!$query->rows) {
                    if ($count) {
                        $sql = "UPDATE oc_socnetauth2_customer2account SET customer_id='" . $customer_id . "',customer_id='" . $customer_info['customer_id'] . "'";
                        $db->query($sql);
                    }
                    $count++;
                }



                            //переносимо пароль якщо він пустий в екаунта який залишається включеним
                            $result2 = $db->query("SELECT * FROM oc_customer WHERE customer_id='" . $customer_id . "' AND password='' AND salt=''");
            if ($result2->num_rows) {
                $res = $db->query("SELECT * FROM oc_customer WHERE telephone='" . $customer_info['telephone'] . "' AND password!='' AND salt!='' AND `register_email`=1");
                if ($res->rows) {
                        $sql = "UPDATE oc_customer SET password='" . $res->row['password'] . "',salt='" . $res->row['salt'] . "' WHERE customer_id='" . $customer_id . "'";
                        $db->query($sql);
                        }
            }
                //переносимо електронну пошту якщо вона пуста в екаунта який залишається включеним щоб не перезатерти
                $result2 = $db->query("SELECT * FROM oc_customer WHERE customer_id='" . $customer_id . "' AND email=''");


                if ($result2->num_rows) {
                    $result3 = $db->query("SELECT * FROM oc_customer WHERE customer_id='" . $customer_info['customer_id']. "' and register_email=1");
                    if ($result3->num_rows) {
                            $sql = "UPDATE oc_customer SET email='" . $result3->row['email'] . "' WHERE customer_id='" . $customer_id . "'";
                            $db->query($sql);
                            $sql = "UPDATE oc_customer SET password='" . $result3->row['password'] . "',salt='" . $result3->row['salt'] . "' WHERE customer_id='" . $customer_id . "'";
                            $db->query($sql);
                    }
                }

                //переносимо заявки на очікування товару лише унікальні
                $result4 = $db->query("SELECT * FROM oc_customer_relation WHERE customer_id_main='" . $customer_id . "'");

                if($result4->num_rows){
                            foreach ($result4->rows as $report) {
                                    $sql = "UPDATE oc_report_appearance SET customer_id='" . $customer_id . "' WHERE customer_id='" . $customer_info['customer_id'] . "'";
                                    $db->query($sql);
                            }
                }

                //переносимо картинку якщо вона пуста в екаунта який залишається включеним

                if (empty($customer_info['avatar'])) {
                    $res = $db->query("SELECT * FROM oc_customer WHERE avatar!='' AND telephone='" . $customer_info['telephone'] . "'");
                    if ($res->rows) {
                        $sql = "UPDATE oc_customer SET avatar='" . $res->row['avatar'] . "' WHERE customer_id='" . $customer_id . "'";
                        $db->query($sql);
                        }
                }

                if (empty($customer_info['avatar_fb'])) {
                    $res = $db->query("SELECT * FROM oc_customer WHERE avatar_fb!='' AND telephone='" . $customer_info['telephone'] . "'");
                    if ($res->rows) {
                        $sql = "UPDATE oc_customer SET avatar_fb='" . $res->row['avatar_fb'] . "' WHERE customer_id='" . $customer_id . "'";
                        $db->query($sql);
                        }
                }


                        //при перенесені заявок в одного юзера може виявитись дублі товару, якщо це так то видаляєм дублі щоб відібрати унікальні


                $sql = "UPDATE oc_customer SET status='0' WHERE customer_id='" . $customer_info['customer_id'] . "'";
                $db->query($sql);
            }
        }
    }
}

exit;
//шукаємо співпадіння по емайл
$email=array();
foreach ($query->rows as $row) {
    $result = $db->query("SELECT * FROM oc_order WHERE customer_id='" . $row['customer_id'] . "'");
    foreach ($result->rows as $order) {
        $query = $db->query("SELECT * FROM oc_order_customer WHERE order_id='" . $order['order_id'] . "' AND customer_id='" . $row['customer_id'] . "'");
        if(!$query->rows) $db->query("INSERT INTO oc_order_customer SET order_id='" . $order['order_id'] . "',customer_id='" . $row['customer_id'] . "'");
    }
}
$query = $db->query("SELECT * FROM oc_customer WHERE telephone!=''  GROUP BY `telephone` HAVING count(*)>1");

//шукаємо співпадіння по емайл
$telephone = array();
foreach ($query->rows as $row) {
    $result = $db->query("SELECT order_id FROM oc_order WHERE customer_id='" . $row['customer_id'] . "'");
        foreach($result->rows as $order){
            $query = $db->query("SELECT * FROM oc_order_customer WHERE order_id='" . $order['order_id'] . "' AND customer_id='" . $row['customer_id'] . "'");
                   if(!$query->rows)  $db->query("INSERT INTO oc_order_customer SET order_id='" . $order['order_id'] . "',customer_id='" . $row['customer_id'] . "'");
        }
}
