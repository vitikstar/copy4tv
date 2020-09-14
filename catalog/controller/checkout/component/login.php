<?php

class ControllerCheckoutComponentLogin extends Controller {
    private $error=array();
        public function saveLoggedUser($email,$password,$post=true){
                if($post){
                        $email = $this->request->post['email'];
                        $password = $this->request->post['password'];
                }
        if ($this->customer->login($email, $password, true)) {
            // Unset guest
            unset($this->session->data['guest']);

            unset($this->session->data['logged_checkout']);

            // Default Shipping Address
            $this->load->model('account/address');

            if ($this->config->get('config_tax_customer') == 'payment') {
                $this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
            }

            if ($this->config->get('config_tax_customer') == 'shipping') {
                $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
            }

            // Wishlist
            if (isset($this->session->data['wishlist']) && is_array($this->session->data['wishlist'])) {
                $this->load->model('account/wishlist');

                foreach ($this->session->data['wishlist'] as $key => $product_id) {
                    $this->model_account_wishlist->addWishlist($product_id);

                    unset($this->session->data['wishlist'][$key]);
                }
            }
        }

        $this->load->language('checkout/checkout');

        $json = array();

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    public function index() {

        $this->load->model('localisation/country');
                $array_region_data = $this->model_localisation_country->getCountries();
                foreach ($array_region_data as $region){
                    $regions[$region['country_id']] = $region['name'];
                }

        $data['regions'] = $regions;

        $data['region_login_val'] = (isset($this->session->data['guest']['region_login'])) ? $this->session->data['guest']['region_login'] : '';
        $data['city_login_val'] = (isset($this->session->data['guest']['city_login'])) ? $this->session->data['guest']['city_login'] : '';
        $data['lastname_login_val'] = (isset($this->session->data['guest']['lastname_login'])) ? $this->session->data['guest']['lastname_login'] : '';
        $data['firstname_login_val'] = (isset($this->session->data['guest']['firstname_login'])) ? $this->session->data['guest']['firstname_login'] : '';
        $data['email_login_val'] = (isset($this->session->data['guest']['email_login'])) ? $this->session->data['guest']['email_login'] : '';
        $data['telephone_login_val'] = (isset($this->session->data['guest']['telephone_login'])) ? $this->session->data['guest']['telephone_login'] : '';



        $data['logged'] = $this->customer->isLogged();
        $this->load->language('checkout/checkout');
        $data['text_new_customer'] = $this->language->get('text_new_customer');
        $data['text_returning_customer'] = $this->language->get('text_returning_customer');
        $data['entry_firstname_lastname'] = $this->language->get('entry_firstname_lastname');
        $data['entry_city'] = $this->language->get('entry_city');
        $data['entry_zone'] = $this->language->get('entry_zone');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_verefication_code'] = $this->language->get('entry_verefication_code');
        $data['text_next'] = $this->language->get('text_next');
        $data['entry_email_or_telephone'] = $this->language->get('entry_email_or_telephone');
        $data['entry_password'] = $this->language->get('entry_password');
        $data['entry_remember_me'] = $this->language->get('entry_remember_me');
        $data['text_forgotten'] = $this->language->get('text_forgotten');
        $data['text_loading'] = $this->language->get('text_loading');
        $data['text_soc_auth'] = $this->language->get('text_soc_auth');
        $data['text_firstname'] = $this->language->get('text_firstname');
        $data['text_lastname'] = $this->language->get('text_lastname');
        $data['text_contact_data_confirmed'] = $this->language->get('text_contact_data_confirmed');
        $view = $this->load->view('checkout/component/login', $data);
        $this->response->setOutput($view);
    }
    public function save2(){
        $this->load->model('account/customer');
        $this->load->language('checkout/checkout');
        $json = array();

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && !$this->validate2()) {
            $telephone = preg_replace("/[^0-9]/", '', $this->request->post['telephone']);
                if (isset($this->request->post['email'])) {
                    if (!empty($this->request->post['email'])) {
                        $query = $this->db->query("SELECT customer_id FROM `oc_customer` WHERE `email` LIKE '%" . $this->db->escape($this->request->post['email']) . "%' AND `email`!=''");
                        if ($query->rows) {
                            $customer_id = $query->row['customer_id'];
                        }
                    }
                }

                if(!isset($customer_id)){
                        $sql = "SELECT c.telephone,c.password,c.customer_id FROM `oc_customer` AS c LEFT JOIN `oc_customer_telephone` AS  ct ON(c.customer_id=ct.customer_id) WHERE (c.telephone LIKE '%" . $telephone . "%' OR ct.telephone LIKE '%" . $telephone . "%') AND c.telephone!=''";
                        $query = $this->db->query($sql);
                        $customer_id = $query->row['customer_id'];
                }


                if (!isset($customer_id)) {
                    $json['customer_id'] = $customer_id = $this->model_account_customer->addCustomer($this->request->post, $this->request->post['verefication_code']);
                }
            $this->session->data['logged_checkout']['customer_id'] = $customer_id;

        }else{
            $json['error'] = $this->validate2();
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
        private function validate2() {
        return false;

        if(isset($this->request->post['verefication_code'])){
            if($this->request->post['verefication_code']!=$this->session->data['verefication_code']) $this->error['verefication_code'] = $this->language->get('error_verefication_code');
        }

        return $this->error;
    }

        public function save5(){
        $this->load->model('account/customer');
        $this->load->language('checkout/checkout');
        $json = array();

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $customer_id = 0;
            $telephone = preg_replace("/[^0-9]/", '', $this->request->post['telephone']);
            if(isset($this->request->post['email'])){
                if (!empty($this->request->post['email'])) {
                    $query = $this->db->query("SELECT customer_id FROM `oc_customer` WHERE `email` LIKE '%" . $this->db->escape($this->request->post['email']) . "%' AND `email`!=''");
                    if ($query->rows) {
                        $customer_id = $query->row['customer_id'];
                    }
                }
            }
            //якщо по милу не знайшло користувача то шукаємо по телефону
                if(!$customer_id){
                    $sql = "SELECT c.telephone,c.password FROM `oc_customer` AS c LEFT JOIN `oc_customer_telephone` AS  ct ON(c.customer_id=ct.customer_id) WHERE (c.telephone LIKE '%" . $telephone . "%' OR ct.telephone LIKE '%" . $telephone . "%') AND c.telephone!=''";
                }else{
                    $sql = "SELECT * FROM `oc_customer` WHERE `customer_id`='" . $customer_id . "'";
                }
            $query = $this->db->query($sql);
            if($query->rows){ //якщо в базі знайшло користувача то логінемо
                    $this->saveLoggedUser($query->row['telephone'],$query->row['password'],false);
            }else{
                if(!$this->validate()){ //якщо не знайшло то реєструємо лише тоді коли пройдена валідація
                    $json['customer_id'] = $customer_id = $this->model_account_customer->addCustomer($this->request->post, $this->request->post['verefication_code']);
                    $this->session->data['logged_checkout']['customer_id'] = $customer_id;
                    // Clear any previous login attempts for unregistered accounts.

                    $login =   $this->request->post['telephone'];
                    $this->model_account_customer->deleteLoginAttempts($login);
                    
                    
                    $this->customer->login($login, '',true);

                    unset($this->session->data['guest']);
                }else{
                        $json['error'] = $this->validate();
                    }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function save(){
        $this->load->model('account/customer');
        $this->load->language('checkout/checkout');
        $json = array();

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            if(!$this->validateLogin()){
                $customer_id = 0;
                $telephone_or_email = preg_replace("/[^0-9]/", '', $this->request->post['email']);
                        $query = $this->db->query("SELECT customer_id FROM `oc_customer` WHERE `email` LIKE '%" . $this->db->escape($this->request->post['email']) . "%'");
                        if ($query->rows) {
                            $customer_id = $query->row['customer_id'];
                        }

                //якщо по милу не знайшло користувача то шукаємо по телефону
                    if(!$customer_id){
                        $sql = "SELECT c.telephone,c.password,c.customer_id FROM `oc_customer` AS c LEFT JOIN `oc_customer_telephone` AS  ct ON(c.customer_id=ct.customer_id) WHERE c.telephone LIKE '%" . $telephone_or_email . "%' OR ct.telephone LIKE '%" . $telephone_or_email . "%'";
                    }else{
                        $sql = "SELECT * FROM `oc_customer` WHERE `customer_id`='" . $customer_id . "'";
                        //$this->model_account_customer->addCustomer($this->request->post);
                    }

                $query = $this->db->query($sql);

                if(!$query->rows){ //якщо в базі знайшло користувача то логінемо
                        $json['error']['email'] ='користувача не знайдено';
                }else{
                               $this->session->data['verefication_code'] = $code_register = randomNumber(4);
                               //$this->session->data['verefication_code'] = $code_register = 1111;

            $message = "Код для оформлення замовлення: " . $code_register;

            $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';

            if (preg_match($regex, $this->request->post['email'])) {
                    $subject = "Подверждение заказа на 4tv.in.ua";

                    $mail = new Mail($this->config->get('config_mail_engine'));
                    $mail->parameter = $this->config->get('config_mail_parameter');
                    $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
                    $mail->smtp_username = $this->config->get('config_mail_smtp_username');
                    $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
                    $mail->smtp_port = $this->config->get('config_mail_smtp_port');
                    $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

                    $mail->setTo($this->request->post['email']);
                    $mail->setFrom($this->config->get('config_email'));
                    $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
                    $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
                    $mail->setHtml(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
                    $mail->send();
                    $json['alert_message'] = "На пошту було відіслано код(для продовження введіть його в поле)";
                } else {

                    if (!$this->turbosms->sms($this->request->post['email'], $message)) { //якщо невдалось відіслати на основний то перебираєм додаткові. І коли вдасться відіслати то пририваємо спроби
                        $sql = "SELECT * FROM  " . DB_PREFIX . "customer_telephone  WHERE customer_id='" . $query->row['customer_id'] . "'";
                        $query = $this->db->query($sql);
                        foreach ($query->rows as $row) {
                            if ($this->turbosms->sms($row['telephone'], $message)) {
                                // $json['error']['user_telephone_exists'] = 1;
                                // $json['user_telephone_exists'] = "Проблема при відправці смс. Зверніться до адміністратора!!!";
                                break;
                            }
                        }
                    }
                    $json['alert_message'] = "На телефон було відіслано код(для продовження введіть його в поле)";
                    }
                }

            }else{
                $json['error'] = $this->validateLogin();
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }


    public function login(){
        $this->load->model('account/customer');
        $this->load->language('checkout/checkout');
        $json = array();
// $login = str_replace("+","",$this->request->post['email']);
// $login = str_replace("(", "", $login);
// $login = str_replace(")", "", $login);
// $login = str_replace("-", "", $login);
// $login = str_replace(" ", "", $login);

$login = $this->request->post['email'];

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            if(!$this->validate()){

                $this->customer->login($login, '',true);
            }else{
                $json['error'] = $this->validate();
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    private function validateLogin() {
       if ((utf8_strlen(trim($this->request->post['email'])) < 1)) {
            $this->error['email'] = 'Пусте поле';
       }
        return $this->error;
    }

    private function validate() {
        return false;

      $telephone = preg_replace("/[^0-9]/", '', $this->request->post['telephone']);


        if(isset($this->request->post['verefication_code'])){
         if ($this->request->post['verefication_code'] != $this->session->data['verefication_code']) {
           $this->error['verefication_code'] = $this->language->get('error_verefication_code');
          }
        }
        
        if (!empty($this->request->post['email'])) {
            if (!preg_match("/[0-9a-z]+@[a-z]/", $this->request->post['email'])) {
                $this->error['email'] = "Не валідна електронна пошта";
            }
        }

        if (strlen($telephone) != 12) {
            $this->error['telephone'] = "Не валідний номер мобільного";
        }

        return $this->error;
    }
    public function validateGuestUser() {
        $this->load->language('checkout/checkout');
        foreach ($this->request->post as $k=>$item){
            $this->session->data['logged_checkout'][$k] = $item;
        }
        $json = array();

    $telephone_login = preg_replace("/[^0-9]/", '', $this->request->post['telephone_login']);
    $email_login = (!empty($this->request->post['email_login'])) ? $this->request->post['email_login'] : '111111111111111111111111111';

    $query = $this->db->query("SELECT * FROM `oc_customer` WHERE `email`='" . $email_login . "'");

if (!$query->rows) { //якщо не зареєстрований
    $json['check_logged'] = false;
    $json['telephone_logged'] = false;
    $json['check_logged_text'] = '';

    //        if ((int)$this->request->post['region_login']===0){
//            $json['error']['region_login'] = $this->language->get('error_region_login');
//        }
//        if(isset($this->request->post['city_login'])){
//            if ((int)$this->request->post['city_login']===0){
//                $json['error']['city_login'] = $this->language->get('error_city_login');
//            }
//        }else{
//            $json['error']['city_login'] = $this->language->get('error_city_login');
//        }
//
//

if ((utf8_strlen(trim($this->request->post['firstname_login'])) < 1) || (utf8_strlen(trim($this->request->post['firstname_login'])) > 32)) {
    $json['error']['firstname_login'] = $this->language->get('error_firstname');
}
//
//        if ((utf8_strlen(trim($this->request->post['lastname_login'])) < 1) || (utf8_strlen(trim($this->request->post['lastname_login'])) > 32)) {
//            $json['error']['lastname_login'] = $this->language->get('error_lastname');
//        }
if (strpos($this->request->post['telephone_login'], '_') or utf8_strlen(trim($this->request->post['telephone_login'])) < 1) {
    $json['error']['telephone_login'] = $this->language->get('error_telephone_login');
}
//
//        if ((utf8_strlen($this->request->post['email_login']) > 96) || !filter_var($this->request->post['email_login'], FILTER_VALIDATE_EMAIL)) {
//            $json['error']['email_login'] = $this->language->get('error_email');
//        }else{
//            $this->load->model("account/customer");
//            if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['email_login'])) {
//                $json['error']['email_login'] = $this->language->get('error_exists');
//            }
//        }

if (!isset($json['error'])) {
    if (!TURBO_SMS_CHECKOUT_TEST) {
        $code = $this->randomNumber(4);
        $this->session->data['verefication_code'] = $code;
        $this->turbosms->sms($this->request->post['telephone_login'], "Пароль: " . $code . " Оформлення замовлення");
    } else {
        $this->session->data['verefication_code'] = 1111;
    }
}
}else{

        $store_name = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');
    

    $subject = 'Код підтвердження оформлення замовлення: '. $store_name;

    $json['check_logged'] = true;
    $json['check_logged_text'] = $this->language->get('check_logged_text');
    $code = $this->randomNumber(4);
    $this->session->data['verefication_code'] = $code;
    $mail = new Mail($this->config->get('config_mail_engine'));
    $mail->parameter = $this->config->get('config_mail_parameter');
    $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
    $mail->smtp_username = $this->config->get('config_mail_smtp_username');
    $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
    $mail->smtp_port = $this->config->get('config_mail_smtp_port');
    $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

    $mail->setTo($query->row['email']);
    $mail->setFrom($this->config->get('config_email'));
    $mail->setSender($store_name);
    $mail->setSubject($subject);
    $mail->setText('Ваш код: '. $code);
    $mail->send();

}

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function randomNumber($length) {
        $result = '';

        for($i = 0; $i < $length; $i++) {
            $result .= mt_rand(0, 9);
        }

        return $result;
    }
}
