<?php
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

use LisDev\Delivery\NovaPoshtaApi2;

class ControllerAccountAccount extends Controller
{
    public $regions;
    public $zones;
    public $service_deliverys;
    public $error;
    public $errorChangePassword = false;

    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->load->model('checkout/service_delivery');
        $this->service_deliverys = $this->model_checkout_service_delivery->getServices();




        $this->load->model('localisation/country');
        $array_region_data = $this->model_localisation_country->getCountries();
        foreach ($array_region_data as $region) {
            if (!empty($region['name'])) $regions[$region['country_id']] = $region['name'];
        }
        $data['regions'] = $regions;
        $this->regions = $regions;

        $this->load->model('localisation/zone');
        $array_zone_data = $this->model_localisation_zone->getZones();
        $zones[] = "Выберите...";
        foreach ($array_zone_data as $zone) {
            $zones[$zone['zone_id']] = $zone['name'];
        }
        $this->zones = $zones;
    }

    public function index()
    {

        $this->load->language('account/account');

        $data['success_upload_avatar'] = $this->language->get('success_upload_avatar');
        $data['error_upload_avatar'] = $this->language->get('error_upload_avatar');
        $data['mime_bad_upload_avatar'] = $this->language->get('mime_bad_upload_avatar');

        // https://test.uniup.com.ua/index.php?route=&relation=1

        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/account', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }


        /**
         * relation socnet
         */
        $this->load->model('account/customer');

        if (isset($this->request->get['relation_cancel_fb']) or isset($this->request->get['relation_cancel_gmail'])) {
            if(isset($this->request->get['relation_cancel_fb'])){
                    if ($this->request->get['relation_cancel_fb']) {
                        $this->model_account_customer->cancelRelationFb();
                     }
            }  
                        if (isset($this->request->get['relation_cancel_gmail'])) {
                            if ($this->request->get['relation_cancel_gmail']) {
                                $this->model_account_customer->cancelRelationGmail();
                            }
                        }
             
            $this->response->redirect("/index.php?route=common/socauthcontinue&uri=" . urldecode($this->url->link('account/account', '', true)));

            $this->response->redirect($this->url->link('account/account', '', true));
        }

        $sql = "SELECT avatar_fb FROM `" . DB_PREFIX . "customer` WHERE email='" . $this->db->escape($this->customer->getEmail()) . "' AND avatar_fb!='' AND relation_fb!=0 ORDER BY customer_id DESC LIMIT 1";
        $result = $this->db->query($sql);

        $img_avatar = $this->model_account_customer->getAvatar(); //аватарка рідна(руцями завантажена)

        if($result->row){ 
            if(file_exists(DIR_IMAGE . '/'.$result->row['avatar_fb']) and $this->model_account_customer->checkRelationFb()){
                if (!empty($result->row['avatar_fb'])) {
                    $img_fb_avatar = $result->row['avatar_fb'];
                }else{
                         $img_fb_avatar = 'catalog/4tv/ac-soc-1.png';
                       }
            }
            if (!file_exists(DIR_IMAGE . '/' . $result->row['avatar_fb'])) {
                $img_fb_avatar = 'catalog/4tv/ac-soc-1.png';
            }
        }

        if (empty($img_avatar)) {
                if ($result->row) {
                    $img_avatar = $result->row['avatar_fb'];
                } else {
                    $img_avatar = 'placeholder.png';
                }
        }else{

            if (!file_exists(DIR_IMAGE . $img_avatar)) {
                if ($result->row) {
                    $img_avatar = $result->row['avatar_fb'];
                } else {
                    $img_avatar = 'placeholder.png';
                }
            }
        }
        if (empty($img_fb_avatar)) {
            $img_fb_avatar = 'catalog/4tv/ac-soc-1.png';
        }

        $data['img_fb_avatar'] = '/image/' .$img_fb_avatar;
        $data['img_avatar'] = '/image/' . $img_avatar;

        $href_relation_fb = $this->url->link('extension/module/socnetauth2/facebook', ['relation' => 1]);
        $href_relation_cancel_fb = $this->url->link('account/account/index', ['relation_cancel_fb' => 1]);
        $href_relation_gmail = $this->url->link('extension/module/socnetauth2/gmail', ['relation' => 1]);
        $href_relation_cancel_gmail = $this->url->link('account/account/index', ['relation_cancel_gmail' => 1]);
        $data['relation_fb']['text'] = ($this->model_account_customer->checkRelationFb()) ?  $this->language->get('text_cancel_relation') : $this->language->get('text_set_relation');
        $data['relation_fb']['link'] = ($this->model_account_customer->checkRelationFb()) ? $href_relation_cancel_fb : $href_relation_fb;
        $data['relation_fb']['name'] = ($this->model_account_customer->checkRelationFb()) ? $this->customer->getFirstNameFb() : '';
        $data['relation_fb']['check'] = $this->model_account_customer->checkRelationFb();
        $data['relation_gmail']['text'] = ($this->model_account_customer->checkRelationGmail()) ? $this->language->get('text_cancel_relation') : $this->language->get('text_set_relation');
        $data['relation_gmail']['link'] = ($this->model_account_customer->checkRelationGmail()) ? $href_relation_cancel_gmail : $href_relation_gmail;
        $data['relation_gmail']['name'] = ($this->model_account_customer->checkRelationGmail()) ? $this->customer->getFirstNameGmail() : '';
        $data['relation_gmail']['check'] = $this->model_account_customer->checkRelationGmail();
        /**
         * relation socnet end
         */





        $data['text_catalog'] = $this->language->get('text_catalog');
        $data['text_delivery_pickup_store'] = $this->language->get('text_delivery_pickup_store');
        $data['text_persodal_data'] = $this->language->get('text_persodal_data');
        $data['text_download_foto'] = $this->language->get('text_download_foto');
        $data['text_firstname'] = $this->language->get('text_firstname');
        $data['text_lastname'] = $this->language->get('text_lastname');
        $data['text_firstname_lastname'] = $this->language->get('text_firstname_lastname');
        $data['text_address'] = $this->language->get('text_address');
        $data['text_email'] = $this->language->get('text_email');
        $data['text_email_edit'] = $this->language->get('text_email_edit');
        $data['text_telephone'] = $this->language->get('text_telephone');
        $data['text_telephone_edit'] = $this->language->get('text_telephone_edit');
        $data['text_edit_data'] = $this->language->get('text_edit_data');
        $data['text_edit_label'] = $this->language->get('text_edit_label');
        $data['text_edit_data_title'] = $this->language->get('text_edit_data_title');
        $data['text_change_password'] = $this->language->get('text_change_password');
        $data['text_password_label'] = $this->language->get('text_password_label');
        $data['text_new_password_label'] = $this->language->get('text_new_password_label');
        $data['text_new_password_repeat_label'] = $this->language->get('text_new_password_repeat_label');
        $data['text_forgot_password'] = $this->language->get('text_forgot_password');
        $data['text_loqout'] = $this->language->get('text_loqout');
        $data['text_edit_photo'] = $this->language->get('text_edit_photo');
        $data['text_delete'] = $this->language->get('text_delete');
        $data['text_region'] = $this->language->get('text_region');
        $data['text_zone'] = $this->language->get('text_zone');
        $data['text_city'] = $this->language->get('text_city');
        $data['text_street'] = $this->language->get('text_street');
        $data['text_house'] = $this->language->get('text_house');
        $data['text_flat'] = $this->language->get('text_flat');
        $data['text_address'] = $this->language->get('text_address');
        $data['text_send_code'] = $this->language->get('text_send_code');
        $data['text_verification_code'] = $this->language->get('text_verification_code');
        $data['text_save_data'] = $this->language->get('text_save_data');
        $data['text_save'] = $this->language->get('text_save');
        $data['text_cancel'] = $this->language->get('text_cancel');
        $data['text_delivery_address'] = $this->language->get('text_delivery_address');
        $data['text_address_shop'] = $this->language->get('text_address_shop');
        $data['text_delivery_type'] = $this->language->get('text_delivery_type');
        $data['text_delivery_from_department'] = $this->language->get('text_delivery_from_department');
        $data['text_delivery_from_department_label'] = $this->language->get('text_delivery_from_department_label');
        $data['text_delivery_from_department_text'] = $this->language->get('text_delivery_from_department_text');
        $data['text_delivery_courier'] = $this->language->get('text_delivery_courier');
        $data['text_delivery_service'] = $this->language->get('text_delivery_service');
        $data['text_delivery_service_label'] = $this->language->get('text_delivery_service_label');
        $data['text_add_delivery_address'] = $this->language->get('text_add_delivery_address');
        $data['text_delivery_services'] = $this->language->get('text_delivery_services');
        $data['text_delivery_features'] = $this->language->get('text_delivery_features');
        $data['text_delivery_service_text'] = $this->language->get('text_delivery_service_text');
        $data['text_delivery_branch_label'] = $this->language->get('text_delivery_branch_label');
        $data['text_personal_address_default'] = $this->language->get('text_personal_address_default');
        $data['text_personal_address_set'] = $this->language->get('text_personal_address_set');
        $data['text_account_soclist'] = $this->language->get('text_account_soclist');
        $data['text_unlink'] = $this->language->get('text_unlink');
        $data['text_verification_code_no_send'] = $this->language->get('text_verification_code_no_send');
        $data['text_fill_in_please'] = $this->language->get('text_fill_in_please');
        $data['text_password_empty'] = $this->language->get('text_password_empty');




        $this->document->setTitle($this->language->get('heading_title'));
        $this->document->setRobots('noindex,follow');

        $data['breadcrumbs'] = array();



        $lang = $this->language->get('code');
        $data['lang'] = $lang;
        if ($lang == 'ru') {
            $data['breadcrumbs'][] = array(
                'text' => 'Главная',
                'href' => $this->url->link('common/home')
            );
        } else {
            $data['breadcrumbs'][] = array(
                'text' => 'Головна',
                'href' => $this->url->link('common/home')
            );
        }
        //		$data['breadcrumbs'][] = array(
        //			'text' => $this->language->get('text_home'),
        //			'href' => $this->url->link('common/home')
        //		);


        $data['service_deliverys'] = $this->service_deliverys;

        $this->load->model('account/delivery');

        $data['form_option_select'] = 'Выберите...';
        $data['regions'] = $this->regions;
        $data['zones'] = $this->zones;


        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/account', '', true)
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_persodal_data'),
        );

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $data['edit'] = $this->url->link('account/edit', '', true);
        $data['password'] = $this->url->link('account/password', '', true);
        $data['address'] = $this->url->link('account/address', '', true);

        $data['credit_cards'] = array();

        $files = glob(DIR_APPLICATION . 'controller/extension/credit_card/*.php');

        foreach ($files as $file) {
            $code = basename($file, '.php');

            if ($this->config->get('payment_' . $code . '_status') && $this->config->get('payment_' . $code . '_card')) {
                $this->load->language('extension/credit_card/' . $code, 'extension');

                $data['credit_cards'][] = array(
                    'name' => $this->language->get('extension')->get('heading_title'),
                    'href' => $this->url->link('extension/credit_card/' . $code, '', true)
                );
            }
        }

        $data['wishlist'] = $this->url->link('account/wishlist');
        $data['order'] = $this->url->link('account/order', '', true);
        $data['download'] = $this->url->link('account/download', '', true);

        if ($this->config->get('total_reward_status')) {
            $data['reward'] = $this->url->link('account/reward', '', true);
        } else {
            $data['reward'] = '';
        }

        $data['return'] = $this->url->link('account/return', '', true);
        $data['transaction'] = $this->url->link('account/transaction', '', true);
        $data['newsletter'] = $this->url->link('account/newsletter', '', true);
        $data['recurring'] = $this->url->link('account/recurring', '', true);

        $this->load->model('account/customer');


        
        $data['curent_password_sheck'] = $this->model_account_customer->emptyCheckEmptyPassword($this->customer->getId());


        $data['text_password_change'] = ($data['curent_password_sheck']) ? $this->language->get('text_password_set') : $this->language->get('text_password_change');


        $affiliate_info = $this->model_account_customer->getAffiliate($this->customer->getId());

        if (!$affiliate_info) {
            $data['affiliate'] = $this->url->link('account/affiliate/add', '', true);
        } else {
            $data['affiliate'] = $this->url->link('account/affiliate/edit', '', true);
        }

        if ($affiliate_info) {
            $data['tracking'] = $this->url->link('account/tracking', '', true);
        } else {
            $data['tracking'] = '';
        }

        /**
         * всі адреса по замовчуванню
         */
        $customer_country_name = $this->model_account_customer->getCountriesByCustomerId($this->customer->getId()); //область array
        $customer_zone_name = $this->model_account_customer->getZoneByCustomerId($this->customer->getId()); //район
        $customer_city_name = $this->model_account_customer->getCityByCustomerId($this->customer->getId()); //місто
        $customer_address_name = $this->model_account_customer->getAddressByCustomerId($this->customer->getId()); //адресса

        $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
        $data['customer_country_name'] = ($customer_country_name) ? $customer_country_name : array('country_id' => 0, 'name' => '');
        $data['customer_zone_name'] = ($customer_zone_name) ? $customer_zone_name : array('zone_id' => 0, 'name' => '', 'address_id' => 0);

        $data['customer_city_name'] = ($customer_city_name) ? $customer_city_name : '';
        $data['customer_address_name'] = ($customer_address_name) ? $customer_address_name : '';

        $data['customer_address_full'] = "";
        $k = 0;
        foreach ($this->model_account_delivery->getAddress($this->customer->getId()) as $address_delivery) {
            $data['address_delivery'][$k]['customer_address_delivery_id'] = $address_delivery['customer_address_delivery_id'];
            $data['address_delivery'][$k]['default_address'] = $address_delivery['default_address'];
            $data['address_delivery'][$k]['shape_delivery_id'] = $address_delivery['shape_delivery_id'];
            $data['address_delivery'][$k]['address_shop_name'] = $address_delivery['address_shop_name'];
            $data['address_delivery'][$k]['service_delivery_name'] = $address_delivery['service_delivery_name'];

            if (!empty($address_delivery['branch_new_post'])) {
                $data['address_delivery'][$k]['address'] = $address_delivery['branch_new_post'];
                $data['address_delivery'][$k]['text_delivery_branch'] = $this->language->get('text_delivery_branch_new_post');
            } elseif (!empty($address_delivery['branch_ukr_post'])) {
                $data['address_delivery'][$k]['address'] = $address_delivery['branch_ukr_post'];
                $data['address_delivery'][$k]['text_delivery_branch'] = $this->language->get('text_delivery_branch_ukr_post');
            }
            $k++;
        }


        if ($customer_city_name) $data['customer_address_full'] .= $customer_city_name;
        if ($customer_city_name and $customer_country_name['name']) $data['customer_address_full'] .= ", ";
        if (isset($customer_country_name['name']) and !empty($customer_country_name['name'])) $data['customer_address_full'] .= $customer_country_name['name'];
        if (isset($customer_zone_name['name']) and !empty($customer_zone_name['name']) and isset($customer_country_name['name']) and !empty($customer_country_name['name'])) $data['customer_address_full'] .= ", ";
        if (isset($customer_zone_name['name']) and !empty($customer_zone_name['name'])) $data['customer_address_full'] .= $customer_zone_name['name'] . " р-н";
        if (isset($customer_zone_name['name']) and !empty($customer_zone_name['name']) and $customer_address_name) $data['customer_address_full'] .= ", ";
        if ($customer_address_name) $data['customer_address_full'] .= $customer_address_name;


        if (isset($this->request->post['firstname_login'])) {
            $data['firstname_login'] = $this->request->post['firstname_login'];
        } elseif (!empty($customer_info)) {
            $data['firstname_login'] = $customer_info['firstname'];
        } else {
            $data['firstname_login'] = '';
        }
        if (isset($this->request->post['lastname_login'])) {
            $data['lastname_login'] = $this->request->post['lastname_login'];
        } elseif (!empty($customer_info)) {
            $data['lastname_login'] = $customer_info['lastname'];
        } else {
            $data['lastname_login'] = '';
        }

        if (isset($this->request->post['email_login'])) {
            $data['email_login'] = $this->request->post['email'];
        } elseif (!empty($customer_info)) {
            $data['email_login'] = $customer_info['email'];
        } else {
            $data['email_login'] = '';
        }

        if (isset($this->request->post['telephone_login'])) {
            $data['telephone_login'] = $this->request->post['telephone'];
        } elseif (!empty($customer_info)) {
            $data['telephone_login'] = $customer_info['telephones'];
        } else {
            $data['telephone_login'] = '';
        }
        $data['telephone_login_value'] = $customer_info['telephone'];

        if ($customer_info['avatar']) {
            $avatar = $customer_info['avatar'];
        } else {
            $avatar = '';
        }


        $data['avatar'] = $this->model_tool_image->resize($avatar, 148, 148);


        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');

        $data['header'] = $this->load->controller('common/header');
        $data['column_megamenu'] = $this->load->controller('common/column_megamenu');



        $this->response->setOutput($this->load->view('account/account', $data));
    }

    public function saveAvatar()
    {
        // Проверяем установлен ли массив файлов и массив с переданными данными

        if (isset($_FILES) && isset($_FILES['avatar'])) {

            //Переданный массив сохраняем в переменной
            $image = $_FILES['avatar'];

            // Проверяем размер файла и если он превышает заданный размер
            // завершаем выполнение скрипта и выводим ошибку
            //            if ($image['size'] > 200000) {
            //                die('error');
            //            }

            // Достаем формат изображения
            $imageFormat = explode('.', $image['name']);
            $imageFormat = $imageFormat[1];

            // Генерируем новое имя для изображения. Можно сохранить и со старым
            // но это не рекомендуется делать

            $avatar = 'catalog/avatar/' . hash('crc32', time()) . '.' . $imageFormat;

            $imageFullName = DIR_IMAGE . $avatar;


            // Сохраняем тип изображения в переменную
            $imageType = $image['type'];

            // Сверяем доступные форматы изображений, если изображение соответствует,
            // копируем изображение в папку images
            if ($imageType == 'image/jpeg' || $imageType == 'image/png' || $imageType == 'image/gif' || $imageType == 'image/jpg') {
                if (move_uploaded_file($image['tmp_name'], $imageFullName)) {
                    $this->load->model('account/customer');
                    $this->model_account_customer->editCustomerAvatar($this->customer->getId(), $avatar);
                    echo 'success';
                } else {
                    // echo 'error';
                }
            } else {
                echo 'error_mime';
            }
        }
    }

    public function changePasword()
    {
        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $json = array();
            $json['error'] = $this->validatePassword();
            if (!$json['error']) {
                $this->load->model('account/customer');

                $this->model_account_customer->editPassword($this->customer->getEmail(), $this->request->post['new_password']);

                $this->session->data['success'] = "Пароль изменен";
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    public function editAddressDelivery()
    {
        $json = array();

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            if(isset($this->request->post['ch_del_input_5']) and isset($this->request->post['ch_del_input_2'])){
                if ($this->request->post['ch_del_input_5'] == 'true' and $this->request->post['ch_del_input_2'] == 'true') { // Добавить новий Службой доставки
                    if (strlen($this->request->post['region_id']) <= 1) {
                        $json['error']["select[name='region-samov']"] = true;
                    }
                    if (strlen($this->request->post['zone_id']) <= 1) {
                        $json['error']["select[name='city-samov']"] = true;
                    }

                    if ($this->request->post['service_delivery_id'] == 'novaposhta' or $this->request->post['service_delivery_id'] == 1) {
                        if ($this->request->post['type_delivery_id'] == 0) {
                            $json['error']["select[name='checkout_type_method']"] = true;
                        }
                        if ($this->request->post['SiteKey'] == 0) {
                            $json['error']["#inputNovaPoshta"] = true;
                        }
                    } else {
                        if (isset($this->request->post['name_delivery_checked'])) {
                            if (empty($this->request->post['poshtomat_address']) and $this->request->post['name_delivery_checked'] == 'ukrposhta.0') {
                                $json['error'][".ukrposhta_0"] = true;
                            }
                            if (empty($this->request->post['shipping_address']) and $this->request->post['name_delivery_checked'] == 'ukrposhta.1') {
                                $json['error'][".ukrposhta_1"] = true;
                            }
                            if (empty($this->request->post['poshtomat_address']) and $this->request->post['name_delivery_checked'] == 'ukrposhta.2') {
                                $json['error'][".ukrposhta_2"] = true;
                            }
                            if (empty($this->request->post['shipping_address']) and $this->request->post['name_delivery_checked'] == 'ukrposhta.3') {
                                $json['error'][".ukrposhta_3"] = true;
                            }
                        }
                    }
                }
            }
            if (!isset($json['error'])) {


                $customer_address_delivery_id = (isset($this->request->post['customer_address_delivery_id'])) ? $this->request->post['customer_address_delivery_id'] : 0;
                $default_address = (isset($this->request->post['default_address']) and !$this->customer->isLogged()) ? $this->request->post['default_address'] : 0;


                if ($this->customer->isLogged()) {
                    $rows = $this->db->query("SELECT default_address FROM `oc_customer_address_delivery` WHERE customer_id='" . $this->customer->getId() . "'");
                    if (!$rows->rows) $default_address = 1;
                }
                $shape_delivery_id = (isset($this->request->post['shape_delivery_id'])) ? $this->request->post['shape_delivery_id'] : '';
                $new_address_or_default = $this->session->data['new_address_or_default'] = (isset($this->request->post['new_address_or_default'])) ? $this->request->post['new_address_or_default'] : 3;


                if (!$this->customer->isLogged()) $new_address_or_default = 3;

                $branch_ukr_post = '';
                $branch_new_post = '';
                $type_delivery_id = '';
                $address_shop_id = '';
                $SiteKey = '';
                $street = '';
                $house = '';
                $flat = '';
                $text_delivery_feature = '';


                if ($new_address_or_default == 3) {
                    if ($shape_delivery_id == 1) { // Службой доставки
                        $type_delivery_id = $this->request->post['type_delivery_id'];
                        if ($type_delivery_id == 2) { //Адресная доставка (курьер)
                            $text_delivery_feature = $this->request->post['text_delivery_feature'];
                            $street = $this->request->post['street'];
                            $house = $this->request->post['house'];
                            $flat = $this->request->post['flat'];
                        } elseif ($type_delivery_id == 1) { //Самовивоз з відділення
                            $SiteKey = $this->request->post['SiteKey'];
                        }


                        $service_delivery_id = $this->request->post['service_delivery_id'];
                        $region_id = $this->request->post['region_id'];
                        $zone_id = $this->request->post['zone_id'];

                        if (isset($this->request->post['service_delivery_id']) and ($this->request->post['service_delivery_id'] == 'novaposhta' or $this->request->post['service_delivery_id'] == 1)) {
                            if ($this->request->post['region_name']) {
                                $branch_new_post .= $this->request->post['region_name'] . ', ';
                            }
                            if ($this->request->post['zone_name']) {
                                $branch_new_post .= $this->request->post['zone_name'] . ', ';
                            }
                            $branch_new_post .= $this->request->post['branch_new_post'];

                        }
                    } elseif ($shape_delivery_id == 2) { // Самовивоз з нашого магазину
                        $text_delivery_feature = '';
                        $street = '';
                        $house = '';
                        $flat = '';
                        $service_delivery_id = '';
                        $region_id = '';
                        $zone_id = '';
                        $SiteKey = '';
                        $type_delivery_id = '';
                        $address_shop_id = $this->request->post['address_shop_id'];
                    }
                    if ($this->customer->isLogged()) {
                        $customer_id = $this->customer->getId();
                    } else {
                        if ($this->request->post['customer_id']) {
                            $customer_id = $this->request->post['customer_id'];
                        }
                    }
                    if (isset($this->request->post['service_delivery_id']) and ($this->request->post['service_delivery_id'] == 'ukrposhta' or $this->request->post['service_delivery_id'] == 2)) {
                        if ($this->request->post['region_name']) {
                            $branch_ukr_post .= $this->request->post['region_name'] . ' ,';
                        }
                        if ($this->request->post['zone_name']) {
                            $branch_ukr_post .= $this->request->post['zone_name'] . ' ,';
                        }
                    }
                    $service_delivery_name = '';
                    if (isset($this->request->post['service_delivery_name'])) {
                        $service_delivery_name = $this->request->post['service_delivery_name'];
                    }
                    $branch_ukr_post = rtrim($branch_ukr_post, ', ');

                    $sql = 'SELECT customer_address_delivery_id FROM oc_customer_address_delivery WHERE ';

                    if (!empty($branch_ukr_post)) {
                        $sql .= 'branch_ukr_post LIKE "%' . $branch_ukr_post . '%" AND ';
                    } elseif (!empty($branch_new_post)) {
                        $sql .= 'branch_new_post LIKE "%' . $branch_new_post . '%" AND ';
                    } else {
                        $sql .= 'address_shop_id="' . $address_shop_id . '" AND ';
                    }

                    $sql .= 'customer_id="' . $this->customer->getId() . '" LIMIT 1';


                    $query = $this->db->query($sql);


                    if (!$query->row) {
                        $sql = 'INSERT INTO `oc_customer_address_delivery` SET 
                type_delivery_id="' . $type_delivery_id . '",
                default_address="' . $default_address . '",
                service_delivery_id="' . $service_delivery_id . '",
                shape_delivery_id="' . $shape_delivery_id . '",
                branch_new_post="' . $this->db->escape($branch_new_post) . '",
                branch_ukr_post="' . $this->db->escape($branch_ukr_post) . '",
                service_delivery_name="' . $this->db->escape($service_delivery_name) . '",
                SiteKey="' . $SiteKey . '",
                region_id="' . $region_id . '",
                zone_id="' . $zone_id . '",
                text_delivery_feature="' . $this->db->escape($text_delivery_feature) . '",
                shipping_address_1="' . $this->db->escape($this->session->data['checkout_data']['shipping_address_1']) . '",
                street="' . $this->db->escape($street) . '",
                house="' . $this->db->escape($house) . '",
                flat="' . $this->db->escape($flat) . '",
                address_shop_id="' . $address_shop_id . '",
                customer_id="' . $this->customer->getId() . '"';
                        $this->db->query($sql);
                        $customer_address_delivery_id = $this->db->getLastId();
                    } else {
                        $this->db->getLastId();
                        $customer_address_delivery_id = $query->row['customer_address_delivery_id'];
                    }
                    $this->session->data['customer_address_delivery_id'] = $customer_address_delivery_id;
                } elseif ($new_address_or_default == 4 and $this->customer->isLogged()) {
                    $this->session->data['customer_address_delivery_id'] = $customer_address_delivery_id;
                }
            }
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    public function country()
    {
        $json = array();

        $this->load->model('localisation/country');

        $country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

        if ($country_info) {
            $this->load->model('localisation/zone');

            $json = array(
                'country_id'        => $country_info['country_id'],
                'name'              => $country_info['name'],
                'iso_code_2'        => $country_info['iso_code_2'],
                'iso_code_3'        => $country_info['iso_code_3'],
                'address_format'    => $country_info['address_format'],
                'postcode_required' => $country_info['postcode_required'],
                'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
                'status'            => $country_info['status']
            );
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    public function getListSities($region_id = false)
    {
        $html = "";


        if (isset($_POST['region_id']) or $region_id) {
            $this->load->language('checkout/checkout');
            $region_id = (isset($_POST['region_id'])) ? $_POST['region_id'] : $region_id;
            $this->load->model('localisation/zone');
            $array_region_data = $this->model_localisation_zone->getZonesByCountryId($region_id);
            $html = '<option value="0">' . $this->language->get('form_option_select') . '</option>';


            if (isset($this->request->post['city_selected'])) {
                $city_selected = $this->request->post['city_selected'];
            } else {
                $city_selected = '';
            }

            foreach ($array_region_data as $k => $sities) {
                $html .= '<option ';
                if ($city_selected == $sities['zone_id']) $html .= 'selected';
                $html .= ' value="' . $sities['zone_id'] . '">' . $sities['name'] . '</option>';
            }
        }
        if (!empty($html)) echo $html;
    }
    public function saveDataUser()
    {
        $this->load->model('account/customer');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $confirmation_code = 1111;

            $this->model_account_customer->editCustomer($this->customer->getId(), $this->request->post);
        }

        $json = array();
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function addressDeliveryEditAddForm()
    {
        $data = array();
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/account', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }



        $this->load->language('account/account');


        $data['text_delete'] = $this->language->get('text_delete');
        $data['text_region'] = $this->language->get('text_region');
        $data['text_zone'] = $this->language->get('text_zone');
        $data['text_city'] = $this->language->get('text_city');
        $data['text_street'] = $this->language->get('text_street');
        $data['text_house'] = $this->language->get('text_house');
        $data['text_flat'] = $this->language->get('text_flat');
        $data['text_address'] = $this->language->get('text_address');
        $data['text_send_code'] = $this->language->get('text_send_code');
        $data['text_verification_code'] = $this->language->get('text_verification_code');
        $data['text_save_data'] = $this->language->get('text_save_data');
        $data['text_save'] = $this->language->get('text_save');
        $data['text_cancel'] = $this->language->get('text_cancel');
        $data['text_delivery_address'] = $this->language->get('text_delivery_address');
        $data['text_address_shop'] = $this->language->get('text_address_shop');
        $data['text_delivery_type'] = $this->language->get('text_delivery_type');
        $data['text_delivery_from_department'] = $this->language->get('text_delivery_from_department');
        $data['text_delivery_from_department_label'] = $this->language->get('text_delivery_from_department_label');
        $data['text_delivery_from_department_text']  = $this->language->get('text_delivery_from_department_text');
        $data['text_delivery_courier'] = $this->language->get('text_delivery_courier');
        $data['text_delivery_service'] = $this->language->get('text_delivery_service');
        $data['text_delivery_service_label'] = $this->language->get('text_delivery_service_label');
        $data['text_add_delivery_address'] = $this->language->get('text_add_delivery_address');
        $data['text_delivery_services'] = $this->language->get('text_delivery_services');
        $data['text_delivery_features'] = $this->language->get('text_delivery_features');
        $data['text_delivery_service_text'] = $this->language->get('text_delivery_service_text');
        $data['text_delivery_branch_label'] = $this->language->get('text_delivery_branch_label');
        $data['text_personal_address_default'] = $this->language->get('text_personal_address_default');
        $data['text_personal_address_set'] = $this->language->get('text_personal_address_set');
        $data['text_account_soclist'] = $this->language->get('text_account_soclist');
        $data['text_unlink'] = $this->language->get('text_unlink');
        $data['text_verification_code_no_send'] = $this->language->get('text_verification_code_no_send');
        $data['form_option_select'] = 'Выберите...';
        $data['regions'] = $this->regions;
        $data['zones'] = $this->zones;
        $data['service_deliverys'] = $this->service_deliverys;
        $data['row'] = array();
        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $this->load->model('account/delivery');
            $customer_address_delivery_id = (isset($this->request->post['customer_address_delivery_id'])) ? $this->request->post['customer_address_delivery_id'] : 0;
            $row = $this->model_account_delivery->getOneAddress($this->customer->getId(), $customer_address_delivery_id);
            $this->load->model('localisation/location');
            $data['type_delivery_all'] = $this->model_account_delivery->getTypeDeliveryAll();
            $data['row'] = $row;
            $data['locations'] = $this->model_localisation_location->getLocations();
        }

        echo $this->load->view('account/delivery_address_template', $data);
    }
    public function getListServiceAddress()
    {
        // Shipping Methods
        $method_data = array();

        $this->load->model('setting/extension');

        $results = $this->model_setting_extension->getExtensions('shipping');
        foreach ($results as $result) {
            if ($this->config->get('shipping_' . $result['code'] . '_status')) {
                $this->load->model('extension/shipping/' . $result['code']);

                $quote = $this->{'model_extension_shipping_' . $result['code']}->getQuote(array(
                    'country_id' => $_POST['region_id'],
                    'zone_id' => $_POST['city'],
                ));

                if ($quote) {
                    $method_data[$result['code']] = array(
                        'title'      => $quote['title'],
                        'quote'      => $quote['quote'],
                        'sort_order' => $quote['sort_order'],
                        'error'      => $quote['error']
                    );
                }
            }
        }
        $method_data['novaposhta'] = array(
            'title'      => 'Новая почта',
            'quote'      => array(
                '1' => array(
                    'code' => 'novaposhta.standard_w',
                    'title' => 'Самовывоз'
                ),
                '2' => array(
                    'code' => 'novaposhta.standard_d',
                    'title' => 'Адрессная доставка'
                )
            ),
            'sort_order' => 0,
            'error'      => ''
        );
        $sort_order = array();

        foreach ($method_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $method_data);


        $this->session->data['shipping_methods'] = $data['shipping_methods'] = $method_data;
        if (isset($_POST['region_id']) and isset($_POST['city'])) {
            $this->load->language('checkout/checkout');
            $this->load->model('localisation/zone');
            $city_data = $this->model_localisation_zone->getZone($_POST['city']);
            if ($city_data and isset($city_data['name'])) {
                $city = $city_data['name'];
            } else {
                return;
            }

            $np = new NovaPoshtaApi2(
                API_NOVA_POSHTA
            );

            // $region = $array_region_data['regions'][$region_id]->name;
            $city_data = $np->getCity($city);


            if (isset($city_data['data'][0][0]['Ref']) or isset($city_data['data'][0]['Ref'])) {
                $Ref = (isset($city_data['data'][0][0])) ? $city_data['data'][0][0]['Ref'] : $city_data['data'][0]['Ref'];
            } else {
                echo '<option value="0">' . $this->language->get('no_offices_found') . '</option>';
                return;
            }
            $result = $np->getWarehouses($Ref);
            $html = '<option value="0">' . $this->language->get('form_option_select') . '</option>';
            if (isset($this->request->post['site_key'])) {
                $site_key = $this->request->post['site_key'];
            } else {
                $site_key = '';
            }
            if ($result) {
                $i = 0;
                foreach ($result['data'] as $k => $item) {
                    $delivery = ($this->session->data['language'] == 'uk-ua') ? $item['Description'] : $item['DescriptionRu'];
                    $html .= '<option data-latitude="' . $item['Latitude'] . '" data-longitude="' . $item['Longitude'] . '" data-item="' . $k . '" value="' . $item['SiteKey'] . '"';
                    if ($site_key == $item['SiteKey']) $html .= 'selected';
                    $html .= '>' . $delivery . '</option>';
                    $i++;
                }
            } else {
                $html = '<option>' . $this->language->get('no_offices_found') . '</option>';
            }
        }
        echo $html;
    }

    public function getListShippingMethod()
    {
        $this->load->language('checkout/checkout');
        // Shipping Methods
        $method_data = array();

        $this->load->model('setting/extension');

        $results = $this->model_setting_extension->getExtensions('shipping');
        foreach ($results as $result) {
            if ($this->config->get('shipping_' . $result['code'] . '_status')) {
                $this->load->model('extension/shipping/' . $result['code']);

                $quote = $this->{'model_extension_shipping_' . $result['code']}->getQuote(array(
                    'country_id' => (isset($_POST['region_id'])) ? $_POST['region_id'] : '',
                    'zone_id' => (isset($_POST['city'])) ? $_POST['city'] : '',
                ));

                if ($quote) {
                    $method_data[$result['code']] = array(
                        'title'      => $quote['title'],
                        'quote'      => $quote['quote'],
                        'sort_order' => $quote['sort_order'],
                        'error'      => $quote['error']
                    );
                }
            }
        }
        $method_data['novaposhta'] = array(
            'title'      => 'Новая почта',
            'quote'      => array(
                '1' => array(
                    'code' => 'novaposhta.standard_w',
                    'title' => 'Самовывоз'
                ),
                '2' => array(
                    'code' => 'novaposhta.standard_d',
                    'title' => 'Адрессная доставка'
                )
            ),
            'sort_order' => 0,
            'error'      => ''
        );
        $sort_order = array();

        foreach ($method_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $method_data);


        $this->session->data['shipping_methods'] = $data['shipping_methods'] = $method_data;
        $html = '<option value="0">' . $this->language->get('form_option_select') . '</option>';
        if ($method_data) {
            foreach ($method_data as $k => $value) {
                $html .= '<option value="' . $k . '" >' . $value['title'] . '</option>';
            }
        }
        echo $html;
    }
    public function getDeliveryUkrposhta()
    {
        $this->load->language('checkout/checkout');
        // Shipping Methods
        $method_data = array();

        $this->load->model('setting/extension');

        $results = $this->model_setting_extension->getExtensions('shipping');
        foreach ($results as $result) {
            if ($this->config->get('shipping_ukrposhta_status')) {
                $this->load->model('extension/shipping/ukrposhta');

                $quote = $this->model_extension_shipping_ukrposhta->getQuote(array(
                    'country_id' => $_POST['region_id'],
                    'zone_id' => $_POST['city'],
                ));

                if ($quote) {
                    $method_data[$result['code']] = array(
                        'title'      => $quote['title'],
                        'quote'      => $quote['quote'],
                        'sort_order' => $quote['sort_order'],
                        'error'      => $quote['error']
                    );
                }
            }
        }
        $sort_order = array();

        foreach ($method_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $method_data);

        //  print_my($method_data);

        //        $this->session->data['shipping_methods'] = $data['shipping_methods'] = $method_data;
        $html = '';
        if ($method_data) {
            foreach ($method_data['ukrposhta']['quote'] as $k => $value) {
                $class = '';
                if ($value['code'] == 'ukrposhta.0' or $value['code'] == 'ukrposhta.2') {
                    $class = 'poshtomat_address_checked';
                } elseif ($value['code'] == 'ukrposhta.1' or $value['code'] == 'ukrposhta.3') {
                    $class = 'shipping_address_checked';
                }
                $html .= '<label for="' . $value['code'] . '" class="ch-del-pay-label">
<input type="radio" id="' . $value['code'] . '" name="delivery" value="' . $value['code'] . '" class="checkbox-input-mod checkbox-input-mod-shape ' . $class . '">
<span class="ch-del-pay-item-title">' . $value['title'] . '</span>
</label><br>';
            }
            $html .= '<div class="ukrposhta-wrap">';
            $html .= '<div class="ukrposhta-wrap"><div class="form-group poshtomat-address"><label>' . $this->language->get('entry_poshtomat_address') . '</label><input name="poshtomat_address" class="form-control" type="text" maxlength="10" minlength="2"></div>
                           <br><div class="form-group textarea-shipping-address"><label>' . $this->language->get('text_your_address') . '</label><textarea cols=""name="shipping_address" class="form-control"></textarea></div>';
            $html .= '</div>';
        }

        echo $html;
    }
    public function addressDeliverySetDefault()
    {
        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $this->db->query("UPDATE `oc_customer_address_delivery` SET `default_address`=0 WHERE `customer_id`='" . $this->customer->getId() . "'");
            $this->db->query("UPDATE `oc_customer_address_delivery` SET `default_address`=1 WHERE `customer_id`='" . $this->customer->getId() . "' AND `customer_address_delivery_id`='" . $this->request->post['customer_address_delivery_id'] . "'");
        }
        $json = array();
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    public function addressDeliveryDelete()
    {
        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $this->db->query("DELETE FROM `oc_customer_address_delivery`  WHERE `customer_id`='" . $this->customer->getId() . "' AND `customer_address_delivery_id`='" . $this->request->post['customer_address_delivery_id'] . "'");
        }
        $json = array();
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    private function validate()
    {
        return true;
        if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
            $this->error['firstname'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
            $this->error['lastname'] = $this->language->get('error_lastname');
        }

        if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
            $this->error['warning'] = $this->language->get('error_exists');
        }



        return !$this->error;
    }
    protected function validatePassword()
    {
        if (isset($this->request->post['confirmation_code'])) {
            if ($this->request->post['confirmation_code'] != 1111) $this->errorChangePassword['confirmation_code'] = $this->language->get('error_confirmation_code');
        } else {
            $this->load->model('account/customer');
            if (!$this->model_account_customer->emptyCheckEmptyPassword($this->customer->getId())) { //якщо є пароль в базі то перевіряти чи правильно ввели поточний
                $customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($this->customer->getEmail())) . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($this->request->post['curent_password']) . "'))))) OR password = '" . $this->db->escape(md5($this->request->post['curent_password'])) . "') AND status = '1'");
                if (!$customer_query->row) $this->errorChangePassword['curent_password'] = $this->language->get('error_curent_password');
            }
        }
        if ((utf8_strlen(html_entity_decode($this->request->post['new_password'], ENT_QUOTES, 'UTF-8')) < 4) || (utf8_strlen(html_entity_decode($this->request->post['new_password'], ENT_QUOTES, 'UTF-8')) > 40)) {
            $this->errorChangePassword['new_password'] = $this->language->get('error_new_password');
        }


        if ($this->request->post['repeat_new_password'] != $this->request->post['new_password']) {
            $this->errorChangePassword['repeat_new_password'] = $this->language->get('error_repeat_new_password');
        }



        return $this->errorChangePassword;
    }
}
