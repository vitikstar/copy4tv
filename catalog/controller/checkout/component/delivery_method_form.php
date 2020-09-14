<?php

use LisDev\Delivery\NovaPoshtaApi2;

class ControllerCheckoutComponentDeliveryMethodForm extends Controller
{
    private $error = array();
    public $array_region_data;


    public function index()
    {
        $data = $this->load->language('checkout/component/delivery_method_form');


        $this->load->model('localisation/location');
        $locations = array();

        $results = $this->model_localisation_location->getLocations();

        foreach ($results as $result) {
            $locations[$result['location_id']] = $result['address'];
        }

        $data['locations'] = $locations;

        $this->load->model('localisation/country');
        $array_region_data = $this->model_localisation_country->getCountries();
        foreach ($array_region_data as $region){
            $regions[$region['country_id']] = $region['name'];
        }

        $data['regions'] = $regions;

        $this->load->model('checkout/service_delivery');
        $data['service_deliverys'] = $this->model_checkout_service_delivery->getServices();
        $data['form_option_select'] = $this->language->get('form_option_select');

        $this->load->model('account/delivery');
        $data['type_delivery_all'] = $this->model_account_delivery->getTypeDeliveryAll();

        $data['type_delivery_val'] = (isset($this->session->data['checkout_data']['type_delivery_val'])) ? $this->session->data['checkout_data']['type_delivery_val'] : '';
        $data['type_delivery_id_val'] = (isset($this->session->data['type_delivery_id_val'])) ? $this->session->data['type_delivery_id_val'] : '';
        $data['region_delivery_adres_val'] = (isset($this->session->data['checkout_data']['region_delivery_adres_val'])) ? (int)$this->session->data['checkout_data']['region_delivery_adres_val'] : 0;
        $data['region_delivery_samov_val'] = (isset($this->session->data['checkout_data']['region_delivery_samov_val'])) ? (int)$this->session->data['checkout_data']['region_delivery_samov_val'] : 0;
        $data['region_id_delivery_adres_val'] = (isset($this->session->data['checkout_data']['region_id_delivery_adres_val'])) ? (int)$this->session->data['checkout_data']['region_id_delivery_adres_val'] : 0;
        $data['region_id_delivery_samov_val'] = (isset($this->session->data['checkout_data']['region_id_delivery_samov_val'])) ? (int)$this->session->data['checkout_data']['region_id_delivery_samov_val'] : 0;
        $data['city_delivery_samov_val'] = (isset($this->session->data['checkout_data']['city_delivery_samov_val'])) ? $this->session->data['checkout_data']['city_delivery_samov_val'] : '';
        $data['city_delivery_adres_val'] = (isset($this->session->data['city_delivery_adres_val'])) ? $this->session->data['city_delivery_adres_val'] : '';
        $data['delivery_val'] = (isset($this->session->data['checkout_data']['delivery_val'])) ? $this->session->data['checkout_data']['delivery_val'] : '';
        $data['delivery_service_samov_val'] = (isset($this->session->data['checkout_data']['delivery_service_samov_val'])) ? $this->session->data['checkout_data']['delivery_service_samov_val'] : '';
        $data['delivery_service_adres_val'] = (isset($this->session->data['checkout_data']['delivery_service_adres_val'])) ? $this->session->data['checkout_data']['delivery_service_adres_val'] : '';
        $data['text_house_val'] = (isset($this->session->data['checkout_data']['text_house_val'])) ? $this->session->data['checkout_data']['text_house_val'] : '';
        $data['text_street_val'] = (isset($this->session->data['checkout_data']['text_street_val'])) ? $this->session->data['checkout_data']['text_street_val'] : '';
        $data['text_flat_val'] = (isset($this->session->data['checkout_data']['text_flat_val'])) ? $this->session->data['checkout_data']['text_flat_val'] : '';
        $data['text_delivery_features_val'] = (isset($this->session->data['text_delivery_features_val'])) ? $this->session->data['text_delivery_features_val'] : '';

        $data['logged'] = $this->customer->isLogged();

       // $data['address_delivery'] = $this->model_account_delivery->getAddressForCheckoutPage($this->customer->getId());
$k=0;
        foreach ($this->model_account_delivery->getAddress($this->customer->getId()) as $address_delivery){
            $data['address_delivery'][$k]['customer_address_delivery_id'] = $address_delivery['customer_address_delivery_id'];
            $data['address_delivery'][$k]['default_address'] = $address_delivery['default_address'];
            
            if(!empty($address_delivery['address_shop_name'])){
                $address = $address_delivery['address_shop_name'];
            }else{
                $address = ($address_delivery['service_delivery_name']) ? $address_delivery['service_delivery_name'] : $this->language->get('entry_service_delivery_name');

            }
            $data['address_delivery'][$k]['address'] =  trim($address.', '.$address_delivery['shipping_address_1']);

            $k++;
        }

        /**
         * region
         */

        if (isset($this->request->post['city'])) {
            $data['city'] = $this->request->post['city'];
        } else {
            $data['city'] = '';
        }
        if (isset($this->request->post['delivery'])) {
            $data['delivery'] = $this->request->post['delivery'];
        } else {
            $data['delivery'] = '';
        }


        $data['disable_checkbox'] = !TURBO_SMS_CHECKOUT_TEST;
        if($this->customer->isLogged()) $data['disable_checkbox'] = false;

        $this->response->setOutput($this->load->view('checkout/component/delivery_method_form', $data));
    }

    public function save()
    {
        $json = array();

        $this->load->language('checkout/checkout');


        $json['customer_id'] = isset($this->session->data['logged_checkout']['customer_id']) ? $this->session->data['logged_checkout']['customer_id'] : $this->customer->getId();
        if(isset($this->request->post)){
            unset($this->session->data['checkout_data']);
        foreach ($this->request->post as $k => $item) {
            $this->session->data['checkout_data'][$k] = $item;
        }
    }

    if($this->request->post['ch_del_input_1']=='true'){
        $this->session->data['checkout_data']['shipping_address_1'] = $this->language->get('entry_text_from_our_store').', '.$this->request->post['text_address_shop'];
    }elseif($this->request->post['ch_del_input_4']=='true'){
        $this->session->data['checkout_data']['shipping_address_1'] = $this->request->post['customer_address_delivery_text'];
    }elseif($this->request->post['ch_del_input_2']=='true'){ // Добавить новий Службой доставки
            if(isset($this->request->post['delivery_service_samov_val'])){
                if(isset($this->session->data['shipping_methods'][$this->request->post['delivery_service_samov_val']]['quote'][$this->request->post['type_delivery_val']])){
                    $this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$this->request->post['delivery_service_samov_val']]['quote'][$this->request->post['type_delivery_val']];
                }
            }
            $this->session->data['checkout_data']['shipping_address_1'] = $this->request->post['region_delivery_samov_text'].', '.$this->request->post['city_delivery_samov_text'];
            $address = '';
            if (isset($this->request->post['delivery_service_samov_val']) and isset($this->request->post['name_delivery_checked'])) {
                            if ($this->request->post['name_delivery_checked'] == 'ukrposhta.0' or $this->request->post['name_delivery_checked'] == 'ukrposhta.2') {
                                $address = $this->request->post['poshtomat_address'];
                            }elseif ($this->request->post['name_delivery_checked'] == 'ukrposhta.1' or $this->request->post['name_delivery_checked'] == 'ukrposhta.3') {
                                $address = $this->request->post['shipping_address'];
                            }
            }
            if (isset($this->request->post['delivery_service_samov_val'])) {
                    if($this->request->post['delivery_service_samov_val'] == 'novaposhta') $address = $this->request->post['delivery_text'];
            }

            $this->session->data['checkout_data']['shipping_address_1'] .= ', ' . $address;


            if(strlen($this->request->post['delivery_service_samov_val'])<=1) $json['error']["select[name='delivery_service_samov']"] = true;
                    if(strlen($this->request->post['region_id_delivery_samov_val'])<=1) $json['error']["select[name='region-samov']"] = true;
                    if(strlen($this->request->post['city_delivery_samov_val'])<=1) $json['error']["select[name='city-samov']"] = true;
                    $code_full = '';
                    if(isset($this->request->post['delivery_val_ukrposhta'])){
                        $code_full = $this->request->post['delivery_val_ukrposhta'];
                    }else{
                        if(isset($this->request->post['delivery_service_samov_val']) and isset($this->request->post['type_delivery_val'])){
                            $code_full = $this->request->post['delivery_service_samov_val'].'.'.$this->request->post['type_delivery_val'];
                        }
                    }
                    $key = 0;
                    $code = '';
                    if(!empty($code_full)){
                        list($key, $code) = explode('.',$code_full);
            
                        $this->session->data['shipping_method']['title'] = (isset($this->session->data['shipping_methods'][$key]['quote'][$code]['title'])) ? $this->session->data['shipping_methods'][$key]['quote'][$code]['title'] . ' - '. $key : '';
                        $this->session->data['shipping_method']['code'] = (isset($this->session->data['shipping_methods'][$key]['quote'][$code]['code'])) ? $this->session->data['shipping_methods'][$key]['quote'][$code]['code'] : '';
                    }
        }



        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }


}