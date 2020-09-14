<?php
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt
use LisDev\Delivery\NovaPoshtaApi2;
class ControllerCheckoutCheckout extends Controller {
    private $error = array();
    public $array_region_data;


	public function index() {
        $this->load->model('localisation/location');
        $locations=array();

        $data['auth'] = ($this->customer->isLogged()) ? 1 : 0;

        $results = $this->model_localisation_location->getLocations();

        foreach ($results as $result) {
            $locations[$result['location_id']] = $result['address'];
        }

        $data['locations'] = $locations;

        $this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
        $this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
		// Validate cart has products and has stock.
//		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
//			$this->response->redirect($this->url->link('checkout/cart'));
//		}



		$this->load->language('checkout/checkout');

        $data['text_your_details'] = $this->language->get('text_your_details');
        $data['entry_city'] = $this->language->get('entry_city');
        $data['entry_follow_the_order'] = $this->language->get('entry_follow_the_order');
        $data['text_next'] = $this->language->get('text_next');
        $data['text_checkout_shipping_method'] = $this->language->get('text_checkout_shipping_method');
        $data['text_delivery_service'] = $this->language->get('text_delivery_service');
        $data['text_delivery_samov'] = $this->language->get('text_delivery_samov');
        $data['text_delivery_adres'] = $this->language->get('text_delivery_adres');
        $data['ch_del_pay_item_text'] = $this->language->get('ch_del_pay_item_text');
        $data['text_checkout_type_method'] = $this->language->get('text_checkout_type_method');
        $data['text_street'] = $this->language->get('text_street');
        $data['text_house'] = $this->language->get('text_house');
        $data['text_flat'] = $this->language->get('text_flat');
        $data['text_delivery_features'] = $this->language->get('text_delivery_features');
        $data['text_pickup_from_our_store'] = $this->language->get('text_pickup_from_our_store');
        $data['text_ch_del_pay_item'] = $this->language->get('text_ch_del_pay_item');
        $data['text_address_shop'] = $this->language->get('text_address_shop');
        $data['text_checkout_payment_method'] = $this->language->get('text_checkout_payment_method');
        $data['text_comment_order'] = $this->language->get('text_comment_order');
        $data['text_c_order'] = $this->language->get('text_c_order');
        $data['text_delivery_s'] = $this->language->get('text_delivery_s');
        $data['text_branch'] = $this->language->get('text_branch');
        $data['text_checkout_order'] = $this->language->get('text_checkout_order');
        $data['column_model'] = $this->language->get('column_model');
        $data['column_quantity'] = $this->language->get('column_quantity');
        $data['column_quantity'] = $this->language->get('column_quantity');
        $data['text_agree'] = sprintf($this->language->get('text_agree'),$this->url->link('information/information', 'information_id=14', true));
        $data['entry_zone'] = $this->language->get('entry_zone');
        $data['form_option_select'] = $this->language->get('form_option_select');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->setRobots('noindex,follow');

		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment.min.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment-with-locales.min.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

		// Required by klarna
		if ($this->config->get('payment_klarna_account') || $this->config->get('payment_klarna_invoice')) {
			$this->document->addScript('http://cdn.klarna.com/public/kitt/toc/v1.0/js/klarna.terms.min.js');
		}

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
//			'text' => $this->language->get('text_cart'),
//			'href' => $this->url->link('checkout/cart')
//		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('checkout/checkout', '', true)
		);

		$data['text_checkout_option'] = sprintf($this->language->get('text_checkout_option'), 1);
		$data['text_checkout_account'] = sprintf($this->language->get('text_checkout_account'), 2);
		$data['text_checkout_payment_address'] = sprintf($this->language->get('text_checkout_payment_address'), 2);
		$data['text_checkout_shipping_address'] = sprintf($this->language->get('text_checkout_shipping_address'), 3);
		$data['text_checkout_shipping_method'] = sprintf($this->language->get('text_checkout_shipping_method'), 4);
		
		if ($this->cart->hasShipping()) {
			$data['text_checkout_payment_method'] = sprintf($this->language->get('text_checkout_payment_method'), 5);
			$data['text_checkout_confirm'] = sprintf($this->language->get('text_checkout_confirm'), 6);
		} else {
			$data['text_checkout_payment_method'] = sprintf($this->language->get('text_checkout_payment_method'), 3);
			$data['text_checkout_confirm'] = sprintf($this->language->get('text_checkout_confirm'), 4);	
		}

		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];
			unset($this->session->data['error']);
		} else {
			$data['error_warning'] = '';
        }
        
        if(!$this->cart->getProducts()){
            $data['text_empty_continue'] = $this->language->get('text_empty_continue');
            $data['text_empty'] = $this->language->get('text_empty');
        } 
        else $data['text_empty'] = false;

		if (isset($this->session->data['account'])) {
			$data['account'] = $this->session->data['account'];
		} else {
			$data['account'] = '';
		}

		$data['shipping_required'] = $this->cart->hasShipping();

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['delivery_method_form'] = $this->load->controller('checkout/component/delivery_method_form');
		$data['payment_method_form'] = $this->load->controller('checkout/component/payment_method_form');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');

$data['header'] = $this->load->controller('common/header');
$data['column_megamenu'] = $this->load->controller('common/column_megamenu');

		$this->response->setOutput($this->load->view('checkout/checkout', $data));
	}
    protected function validateForm() {

        /**
         * region
         */
        if(isset($this->request->post['region'])){
            $this->load->model('localisation/zone');
            $arr = $this->model_localisation_zone->getZone($this->request->post['region']);

            if (empty($arr['name'])) {
                $this->error['region'] = $this->language->get('error_region');
            }
        }
        /**
         * city
         */
        if(isset($this->request->post['city'])){
            if (!$this->request->post['city']) {
                $this->error['city'] = $this->language->get('error_city');
            }
        }


        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }
	public function country() {
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
    public function getListServiceAddress() {
        if(isset($_POST['region_id']) and isset($_POST['city'])){
            $this->load->language('checkout/checkout');
            $region_id = $_POST['region_id'];
            $city = $_POST['city'];

            $data_region = ($this->session->data['language']=='uk-ua') ? 'region-ua.txt' : 'region-ru.txt';

            if(file_exists(DIR_DATA.$data_region)){
                if($json_region_data = file_get_contents(DIR_DATA.$data_region)){
                    $this->load->language('checkout/checkout');
                    $array_region_data = (array)json_decode($json_region_data);
                    $np = new NovaPoshtaApi2(
                        API_NOVA_POSHTA
                    );

                    $region = $array_region_data['regions'][$region_id]->name;
                    $city_data = $np->getCity($city, $region);
                    $Ref = (isset($city_data['data'][0][0])) ? $city_data['data'][0][0]['Ref'] : $city_data['data'][0]['Ref'];
                    $result = $np->getWarehouses($Ref);
                    $html = '<option value="0">'. $this->language->get('form_option_select') .'</option>';
                    if (isset($this->request->post['delivery_selected'])) {
                        $delivery_selected = $this->request->post['delivery_selected'];
                    }else {
                        $delivery_selected = '';
                    }
                    if($result){
                        $i=0;
                        foreach ($result['data'] as $k=>$item){
                            $delivery = ($this->session->data['language']=='uk-ua') ? $item['Description'] : $item['DescriptionRu'];
                            $html .= '<option data-latitude="'. $item['Latitude'] .'" data-longitude="'. $item['Longitude'] .'" data-item="'. $k .'"';
                            if($delivery_selected == $delivery) $html .= 'selected';
                            $html .= '>'. $delivery .'</option>';
                            $i++;
                        }
                    }else{
                        $html = '<option>'. $this->language->get('no_offices_found') .'</option>';
                    }
                }
            }
        }
        echo $html;
    }
    public function getListServiceInfo() {
        if(isset($_POST['region_id']) and isset($_POST['city']) and isset($_POST['key'])){

            $this->load->language('checkout/checkout');
            $region_id = $_POST['region_id'];
            $city = $_POST['city'];

            $data_region = ($this->session->data['language']=='uk-ua') ? 'region-ua.txt' : 'region-ru.txt';

            if(file_exists(DIR_DATA.$data_region)){
                if($json_region_data = file_get_contents(DIR_DATA.$data_region)){
                    $array_region_data = (array)json_decode($json_region_data);
                    $np = new NovaPoshtaApi2(
                        API_NOVA_POSHTA
                    );

                    $region = $array_region_data['regions'][$region_id]->name;
                    $city_data = $np->getCity($city, $region);
                    $Ref = (isset($city_data['data'][0][0])) ? $city_data['data'][0][0]['Ref'] : $city_data['data'][0]['Ref'];
                    $result = $np->getWarehouses($Ref);
                    if($result){
                        $data = $result['data'][$_POST['key']];
                        $html = '<h5>Телефон</h5>';
                        $html .= '<p>'.$data['Phone'].'</p>';
                        $html .= '<h5>Дні та час роботи</h5>';
                        $html .= '<table width="1000" border="0" align="center">
  <tr>
    <th scope="col" width="200px" bgcolor="#CCCCCC"><font size="4" color="#CC0033"><i>Понеділок</i></font></th>
    <th scope="col" width="200px" bgcolor="#CCCCCC"><font size="4" color="#CC0033"><i>Вівторок </i></font></th>
    <th scope="col" width="200px" bgcolor="#CCCCCC"><font size="4" color="#CC0033"><i>Середа   </i></font></th>
    <th scope="col" width="200px" bgcolor="#CCCCCC"><font size="4" color="#CC0033"><i>Четвер   </i></font></th>
    <th scope="col" width="200px" bgcolor="#CCCCCC"><font size="4" color="#CC0033"><i>П\'ятниця   </i></font></th>
    <th scope="col" width="200px" bgcolor="#CCCCCC"><font size="4" color="#CC0033"><i>Субота   </i></font></th>
    <th scope="col" width="200px" bgcolor="#CCCCCC"><font size="4" color="#CC0033"><i>Неділя   </i></font></th>
  </tr>
  <tr>
    <td>'.$data['Schedule']['Monday'].'</td>
    <td>'.$data['Schedule']['Tuesday'].'</td>
    <td>'.$data['Schedule']['Wednesday'].'</td>
    <td>'.$data['Schedule']['Thursday'].'</td>
    <td>'.$data['Schedule']['Friday'].'</td>
    <td>'.$data['Schedule']['Saturday'].'</td>
    <td>'.$data['Schedule']['Sunday'].'</td>
  </tr>
</table>';
                    }
                }
            }
        }
        echo $html;
    }
    public function getListSities($region_id=false) {
        $html = "";


        if(isset($_POST['region_id']) or $region_id){
            $this->load->language('checkout/checkout');
            $region_id = (isset($_POST['region_id'])) ? $_POST['region_id'] : $region_id;

            $this->load->model('localisation/zone');
            $array_region_data = $this->model_localisation_zone->getZonesByCountryId($region_id);
            $html = '<option value="0">'. $this->language->get('form_option_select') .'</option>';


            if (isset($this->request->post['city_selected'])) {
                $city_selected = $this->request->post['city_selected'];
            }else {
                $city_selected = '';
            }

            foreach ($array_region_data as $k=>$sities){
                $html .= '<option ';
                if($city_selected == $sities['name']) $html .= 'selected';
                $html .=' value="'. $sities['zone_id'] .'">'. $sities['name'] .'</option>';
            }
        }
        if(!empty($html)) echo $html;
    }
	public function customfield() {
		$json = array();

		$this->load->model('account/custom_field');

		// Customer Group
		if (isset($this->request->get['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->get['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = $this->request->get['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		$custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

		foreach ($custom_fields as $custom_field) {
			$json[] = array(
				'custom_field_id' => $custom_field['custom_field_id'],
				'required'        => $custom_field['required']
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}