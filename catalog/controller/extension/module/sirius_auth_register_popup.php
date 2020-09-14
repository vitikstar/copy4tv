<?php

class ControllerExtensionModuleSiriusAuthRegisterPopup extends Controller
{
    private $error = array();

    public function index($setting)
    {


        $data['SOCNETAUTH2_DATA'] = $this->load->controller('account/socnetauth2/showcode');
        //$this->load->language('extension/module/sirius_auth_register_popup');
        $this->load->language('common/footer');

        if ($this->customer->isLogged()) {
            return false;
        }

        if (!$this->config->get('socnetauth2_status')) return false;

        if (empty($_COOKIE['show_socauth2_popup'])) {
            $data['show_socauth2_popup'] = 1;
        } else {
            $data['show_socauth2_popup'] = 0;
        }

        $data['socnetauth2_mobile_control'] = $this->config->get('socnetauth2_mobile_control');


        $data['socnetauth2_vkontakte_status'] = $this->config->get('socnetauth2_vkontakte_status');
        $data['socnetauth2_odnoklassniki_status'] = $this->config->get('socnetauth2_odnoklassniki_status');
        $data['socnetauth2_facebook_status'] = $this->config->get('socnetauth2_facebook_status');
        $data['socnetauth2_twitter_status'] = $this->config->get('socnetauth2_twitter_status');
        $data['socnetauth2_gmail_status'] = $this->config->get('socnetauth2_gmail_status');
        $data['socnetauth2_mailru_status'] = $this->config->get('socnetauth2_mailru_status');

        $data['heading_title1'] = $this->language->get('heading_title1');
        $data['heading_title2'] = $this->language->get('heading_title2');
        $data['text_skip'] = $this->language->get('text_skip');

        return $this->load->view('extension/module/sirius_auth_register_popup', $data);
    }

    public function loadTemplateLoginTelephone(){
        $data = array();
        $this->response->setOutput($this->load->view('extension/module/login/load_template_telephone', $data));
    }
    public function loadTemplateRegister(){
        $data = $this->load->language('extension/module/sirius_auth_register_popup');
        $this->response->setOutput($this->load->view('extension/module/login/load_template_register', $data));
    }

    public function register(){
        $json=array();
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function loadTemplateLoginEmail(){
        $data = array();
        $data['email'] = (isset($this->session->data['email'])) ? $this->session->data['email'] : '';
        $this->response->setOutput($this->load->view('extension/module/login/load_template_email', $data));
    }

    public function loginRegister()
    {
        $this->load->language('extension/module/sirius_auth_register_popup');
        $json = array();
        $this->load->model('account/customer');



        $json['post']=json_encode($this->request->post);
        $json['error_register'] = $this->validate($this->request->post);
        if(!$json['error_register']){ // no error
            $json['error_register'] = 0;
            $this->session->data['data_user']['lastname'] = $this->request->post['lastname'];
            $this->session->data['data_user']['firstname'] = $this->request->post['firstname'];
            $this->session->data['data_user']['email'] = $this->request->post['email'];
            $this->session->data['data_user']['password'] = $this->request->post['password'];
            $this->session->data['data_user']['telephone'] = preg_replace("/[^0-9]/", '', $this->request->post['telephone']);
            $this->session->data['data_user']['type_register'] = $this->request->post['type_register'];



            $type_register = $json['register-ver-block'] = $this->request->post['type_register'];
            $code_register = $this->session->data['code-ver'] = randomNumber(4);
            if($type_register=="email"){
                $subject = "Подтверждение регистрации на 4tv.in.ua";
                $message = sprintf($this->language->get('text_password_reminder'),$code_register);

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

            }elseif ($type_register=="telephone"){
                $this->turbosms->sms($this->request->post['telephone'],sprintf($this->language->get('text_password_reminder'),$code_register));
            }
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    public function loginRegisterGo()
    {
        $this->load->model('account/customer');
        $this->load->language('common/footer');

        $json=array();
        $this->load->language('extension/module/sirius_auth_register_popup');
        $this->load->model('account/customer');
        $json['error']['code-activation-email'] = 0;
        $json['error']['code-activation-telephone'] = 0;

        $register = false; // флаг означаэ чи можна реэструвати( чи правильно введено код )

        if($this->session->data['data_user']['type_register']=='telephone'){
            if($this->request->post['code_telephone_register']==$this->session->data['code-ver']){
                $register =  $json['register'] = true;
            }else{
                $json['error']['code-activation-telephone'] = $this->language->get('code-activation-text-er');
            }
            if ($register) {
                            $data_user = $this->session->data['data_user'];
                            $this->model_account_customer->addCustomer($data_user);
                            $this->customer->login($this->session->data['data_user']['telephone'], $this->session->data['data_user']['password'], true);
                        }

        }elseif($this->session->data['data_user']['type_register']=='email'){
            if($this->request->post['code_email_register']==$this->session->data['code-ver']){
                $register =  $json['register'] = true;
            }else{
                $json['error']['code-activation-email'] = $this->language->get('code-activation-text-er');
            }
            if ($register) {
                                $data_user = $this->session->data['data_user'];
                                $this->model_account_customer->addCustomer($data_user,$this->request->post['code_email_register'],1);
                                $this->customer->login($this->session->data['data_user']['email'], $this->session->data['data_user']['password'], true);
                            }

        }


        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));

    }

    public function loginRegisterEmail()
    {
        $json = array();
        $this->load->language('common/footer');

        $this->load->language('account/login');
        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $json['post']=json_encode($this->request->post);
            if(isset($this->request->post['password_empty']) && isset($this->request->post['password']) && isset($this->request->post['email'])){ //якщо користувач в базі є але з пустим паролем то йому буде запропоновано його ввести
                if($this->request->post['password_empty']!==$this->request->post['password']){
                    $json['empty_password'] = false;
                    $json['error_passwords_not_match'] =true;
                    $json['warning']['error_passwords_not_match'] = $this->language->get('error_passwords_not_match');
                }else{
                    $json['empty_password'] = false;
                    $json['error_passwords_not_match'] =false;
                    $sql = "UPDATE " . DB_PREFIX . "customer SET salt = '" . $this->db->escape($salt = token(9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($this->request->post['password'])))) . "' WHERE email = '" . $this->db->escape($this->request->post['email']) . "'";
                    $this->db->query($sql);
                    if ($this->customer->login($this->request->post['email'], '', true)) {
                        // Unset guest
                        unset($this->session->data['guest']);

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
                        $json['redirect'] = $this->url->link('account/success');
                    }
                }
            }else if(isset($this->request->post['email']) and !isset($this->request->post['confirm'])){
                $json['empty_password'] = false;
                $this->load->model('account/customer');
                $customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);
                if (!$customer_info) { //при першому вводі нема користувача в базі
                    $code = randomNumber(6);
                    $json['register'] = true;
                    $json['email'] = $this->request->post['email'];
                    $json['login'] = false;
                    $json['success'] = "Пользователь с почтой \"".$this->request->post['email']."\" не найдено в базе.\nЗаполните форму или введите другой email";
                    $json['continue'] = 'Продолжить';
                }else{
                    $json['register'] = false;
                    $json['login'] = true;
                    $json['warning'] = $this->validateLoginEmail();
                    $json['empty_password'] = $this->customer->loginEmptyPassword($this->request->post['email']);

                    if (!$json['warning'] && $customer_info) {
                        if ($customer_info && !$customer_info['status']) {
                            $this->error['warning'] = $this->language->get('error_approved');
                        }
                        if (!$customer_info) {
                            $this->error['warning'] = $this->language->get('error_approved');
                        }
                        // Unset guest
                        unset($this->session->data['guest']);

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

                        // Added strpos check to pass McAfee PCI compliance test (http://forum.opencart.com/viewtopic.php?f=10&t=12043&p=151494#p151295)
                        if (isset($this->request->post['redirect']) && $this->request->post['redirect'] != $this->url->link('account/logout', '', true) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
                            $json['redirect'] = str_replace('&amp;', '&', $this->request->post['redirect']);
                        } else {
                            $json['redirect'] = $this->url->link('account/account', '', true);
                        }
                    }
                }
            }elseif(isset($this->request->post['email']) and isset($this->request->post['confirm'])){
                /**
                 * реєструємо
                 */
                $json['error_register'] = $this->validate($this->request->post);
                if (count($json['error_register']) == 0) {
                    $code_register = randomNumber(6);
                    $subject = "Подтверждение регистрации на 4tv.in.ua";
                    $message = $code_register;

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
                    $json['success'] = sprintf($this->language->get('text_success_email_code'));
                    $json['customer_register_id'] = $this->model_account_customer->addCustomer($this->request->post, $code_register,1);
                }
            }elseif (!empty($this->request->post['customer_register_id'])){
                $customer_id = $this->request->post['customer_register_id'];
                $sql = "SELECT access_code,email FROM " . DB_PREFIX . "customer  WHERE customer_id='" . $customer_id . "'";
                $result = $this->db->query($sql);
                $access_code = $result->row['access_code'];
                if ($access_code == $this->request->post['access-code']) {
                    $this->db->query("UPDATE " . DB_PREFIX . "customer  SET status=1 WHERE customer_id='" . $customer_id . "'");
                    if ($this->customer->login($result->row['email'], '', true)) {
                        // Unset guest
                        unset($this->session->data['guest']);

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
                        $json['redirect'] = $this->url->link('account/success');
                    }
                } else {
                    $json['error'] = $this->language->get('error_access_code');
                }
            }
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    private function validate()
    {
        $this->load->model('account/customer');
        $error = array();

        $type_register = $this->request->post['type_register'];

        if($type_register=="email"){
            if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
                $error['email'] = $this->language->get('error_email');
            }else{
                if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
                    $error['email'] = $this->language->get('error_exists');
                }
            }

             if ((utf8_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8')) < 4) || (utf8_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8')) > 40)) {
                    $error['password'] = $this->language->get('error_password');
                }

                if ($this->request->post['confirm'] != $this->request->post['password']) {
                    $error['confirm'] = $this->language->get('error_confirm');
                }
                
                $telephone = preg_replace("/[^0-9]/", '', $this->request->post['telephone']);



        if ((utf8_strlen($telephone) <12)) {
                         $error['telephone'] = $this->language->get('error_telephone');
                } else {
                        if($telephone==380){
                                $error['telephone'] = $this->language->get('error_telephone');
                        }else{
                            if ($this->model_account_customer->getCheckCustomerByPhone($this->request->post['telephone'])) {
                                $error['telephone'] = $this->language->get('error_exists_telephone');
                            }
                        }
                }
        }elseif ($type_register=="telephone"){ 
            if ((utf8_strlen(preg_replace("/[^0-9]/", '', $this->request->post['telephone'])) !=19)) {
                $error['telephone'] = $this->language->get('error_telephone');
            }else{
                if ($this->model_account_customer->getCheckCustomerByPhone($this->request->post['telephone'])) {
                    $error['telephone'] = $this->language->get('error_exists_telephone');
                }
            }
        }

        if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
            $error['firstname'] = $this->language->get('error_firstname');
        }

        // if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
        //     $error['lastname'] = $this->language->get('error_lastname');
        // }



        // Customer Group
        if (isset($this->request->post['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->post['customer_group_id'], $this->config->get('config_customer_group_display'))) {
            $customer_group_id = $this->request->post['customer_group_id'];
        } else {
            $customer_group_id = $this->config->get('config_customer_group_id');
        }

        // Custom field validation
        $this->load->model('account/custom_field');

        $custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

        foreach ($custom_fields as $custom_field) {
            if ($custom_field['location'] == 'account') {
                if ($custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']])) {
                    $error['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
                } elseif (($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !filter_var($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']], FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $custom_field['validation'])))) {
                    $error['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
                }
            }
        }



//        // Captcha
//        if ($this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('register', (array)$this->config->get('config_captcha_page'))) {
//            $captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');
//
//            if ($captcha) {
//                $error['captcha'] = $captcha;
//            }
//        }
//
//        // Agree to terms
//        if ($this->config->get('config_account_id')) {
//            $this->load->model('catalog/information');
//
//            $information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));
//
//            if ($information_info && !isset($this->request->post['agree'])) {
//                $error['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
//            }
//        }

        return $error;
    }


    public function validateTelephone(){
        $json=array();
        $this->load->language('extension/module/sirius_auth_register_popup');
        $this->load->model('account/customer');
        $json['lr-code-activation-text'] = $this->language->get('no_messages');
        $json['error']['user_telephone_exists'] = 0;
        $telephone = $this->request->post['telephone'];
        $data_user = $this->model_account_customer->getCustomerByPhone($telephone);
        $this->session->data['code-v-tel'] = $code_register = randomNumber(4);

        if (!$data_user or empty($this->request->post['telephone'])) {
            $json['error']['user_telephone_exists'] = 1;
            $json['user_telephone_exists'] = $this->language->get('error_user_telephone_exists');
        }else{
            $telephone = $this->session->data['user_telephone_login'] = $data_user['telephone'];
            

            $message = "Ваш код  авторизації: " . $code_register;
            if (!$this->turbosms->sms($this->request->post['telephone'], $message)) { //якщо невдалось відіслати на основний то перебираєм додаткові. І коли вдасться відіслати то пририваємо спроби
                $sql = "SELECT * FROM  " . DB_PREFIX . "customer_telephone  WHERE customer_id='" . $data_user['customer_id'] . "'";
                $query = $this->db->query($sql);
                foreach ($query->rows as $row) {
                    if ($this->turbosms->sms($row['telephone'], $message)) {
                        // $json['error']['user_telephone_exists'] = 1;
                        // $json['user_telephone_exists'] = "Проблема при відправці смс. Зверніться до адміністратора!!!";
                        break;
                    }
                }
            }
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    public function reSendCode(){
        $json=array();
        $this->load->language('extension/module/sirius_auth_register_popup');
        $json['lr-code-activation-text'] = $this->language->get('no_messages');

        $this->turbosms->sms($this->session->data['user_telephone_login'],"Ваш код  авторизації: " . $this->session->data['code-v-tel']);
        
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    public function validateTelephoneCode(){
        $json=array();
        $this->load->language('extension/module/sirius_auth_register_popup');
        $this->load->model('account/customer');
        $json['error']['code-activation'] = 0;
        $json['lr-code-activation-text'] = html_entity_decode($this->request->post['lr-code-activation-text']);
        if($this->request->post['code-v-tel']==$this->session->data['code-v-tel']){
            $telephone = $this->session->data['user_telephone_login'];
            if(!empty($telephone)) $this->customer->login($telephone, '',true);
            $json['code-activation-text'] = $this->language->get('code-activation-text-s');
        }else{
            $json['error']['code-activation'] = 1;
            $json['code-activation-text'] = $this->language->get('code-activation-text-er');
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    public function validateEmailCode(){ //нагадування паролю, перевірка коду
        $json=array();
        $this->load->language('extension/module/sirius_auth_register_popup');
        $this->load->model('account/customer');
        $json['error']['code-activation'] = 0;
        if($this->request->post['code-v-mail']==$this->session->data['code-v-mail']){
            $json['code-activation-text'] = $this->language->get('code-activation-text-s');
        }else{
            $json['error']['code-activation'] = 1;
            $json['code-activation-text'] = $this->language->get('code-activation-text-er');
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    public function validateEmail(){ //нагадування паролю
        $json=array();
        $this->load->language('extension/module/sirius_auth_register_popup');
        $this->load->model('account/customer');
        $json['lr-code-activation-text'] = $this->language->get('no_messages_email');
        $code = randomNumber(4);
        $this->session->data['code-v-mail']=$code;
        $json['error']['user_email_exists'] = 0;
        if (!$this->model_account_customer->getCustomerByEmail($this->request->post['email-forgot'])) {
            $json['error']['user_email_exists'] = $this->language->get('error_user_email_exists');
        }else{
            send_code_mail($this->request->post['email-forgot'],sprintf($this->language->get('text_password_reminder'),$code));
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    function telephoneAuthorization(){
        $json = array();
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
function emailPasswordReminder()
{
    $json = array();
    $this->load->language('extension/module/sirius_auth_register_popup');
    $this->load->model('account/customer');
    $json['redirect'] = 0;
    $email = $this->request->post['email'];
    $new_password = $this->request->post['new_password'];
    $new_password_repeat = $this->request->post['new_password_repeat'];
     if ($new_password != $new_password_repeat){
         $json['error'] = "Пароли не совпадают";
    }
    else{ 
                             if ((utf8_strlen(html_entity_decode($new_password, ENT_QUOTES, 'UTF-8')) >= 4) && (utf8_strlen(html_entity_decode($new_password, ENT_QUOTES, 'UTF-8')) < 40)){
                                $this->load->model('account/customer');
                                $this->model_account_customer->editPassword($email, $new_password);
                                        if ($this->customer->login($email, $new_password)) {
                                            // Default Addresses
                                            $this->load->model('account/address');
                                            if ($this->config->get('config_tax_customer') == 'payment') {
                                                $this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
                                            }

                                            if ($this->config->get('config_tax_customer') == 'shipping') {
                                                $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
                                            }
                                             if(isset($json['error'])) unset($json['error']);
                                            $json['redirect'] = $this->url->link('account/account');
                                        }
                            }else{
                                $json['error'] = $this->language->get('error_password');
                                $json['redirect'] = 0;
                            }
    }
    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
}

    function emailAuthorization(){
        $json = array();
        $this->load->language('extension/module/sirius_auth_register_popup');
        $this->load->model('account/customer');
        $json['error']['email_exists'] = 0;
        $json['error']['password'] = 0;
        $json['redirect'] = 0;
        $email = $this->session->data['email'] = $this->request->post['email'];
        $password = $this->request->post['password'];


        if(!empty($email)){
            if (!$this->model_account_customer->getCustomerByEmail($email)) {
                $json['error']['email_exists'] = $this->language->get('error_user_email_exists');
            }else{
                if ((utf8_strlen(html_entity_decode($password, ENT_QUOTES, 'UTF-8')) >= 4) || (utf8_strlen(html_entity_decode($password, ENT_QUOTES, 'UTF-8')) < 40)){
                    if ($this->customer->login($email, $password)) {
                        $json['error']['email_exists'] = 0;
                        $json['error']['password'] = 0;
                        // Default Addresses
                        $this->load->model('account/address');
                        if ($this->config->get('config_tax_customer') == 'payment') {
                            $this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
                        }

                        if ($this->config->get('config_tax_customer') == 'shipping') {
                            $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
                        }
                        $json['redirect'] =  $this->url->link('account/account');
                    } else {
                        $json['error']['password'] = 'Неверний пароль. Воспользуйтесь востановлением.';
                        $json['redirect'] = 0;
                    }
                }else{
                    $json['error']['password'] = $this->language->get('error_password');
                    $json['redirect'] = 0;
                }
            }
        }else{
            $json['error']['email_exists'] = $this->language->get('error_user_email_exists');
            $json['redirect'] = 0;
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validateLoginEmail()
    {
        $this->load->model('account/customer');
        $this->load->language('account/login');
        // Check how many login attempts have been made.
        // $login_info = $this->model_account_customer->getLoginAttempts($this->request->post['email']);

//        if ($login_info && ($login_info['total'] >= $this->config->get('config_login_attempts')) && strtotime('-1 hour') < strtotime($login_info['date_modified'])) {
//            $this->error['warning'] = $this->language->get('error_attempts');
//        }
        // 'Ваша учетная запись превысила допустимое количеств… систему. Пожалуйста, попробуйте еще через 1 час.'

        // Check if customer has been approved.
        // $customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);

//        if ($customer_info && !$customer_info['status']) {
//            $this->error['warning'] = $this->language->get('error_approved');
//        }


        if (!$this->error) {
            if ($this->customer->loginEmptyPassword($this->request->post['email'])) { // перевірка чи користувач не входив за допомогою соц мереж, бо коли ходив то пароль пустий
                $this->error['warning']['empty_password'] = $this->language->get('error_empty_password');

                $this->model_account_customer->addLoginAttempt($this->request->post['email']);
            }else if (!$this->customer->login($this->request->post['email'], $this->request->post['password'])) {
                $this->error['warning']['password'] = $this->language->get('error_login');

                $this->model_account_customer->addLoginAttempt($this->request->post['email']);
            } else {
                $this->model_account_customer->deleteLoginAttempts($this->request->post['email']);
            }
        }

        return $this->error;
    }
}

?>