<?php
class ControllerCommonFooter extends Controller {
	public function index() {
		$this->load->language('common/footer');

		$this->load->model('catalog/information');

        // up
        $this->document->addStyle('catalog/view/javascript/jquery/swiper/css/swiper.min.css');
        $this->document->addStyle('catalog/view/javascript/jquery/swiper/css/opencart.css');
        $this->document->addScript('catalog/view/javascript/jquery/swiper/js/swiper.jquery.js');
        // end up

		$data['informations'] = array();
        /* SIRIUS CustomFooter - begin */
        $this->load->model("extension/module/sirius_custom_footer");
        $data = array_merge($data, $this->model_extension_module_sirius_custom_footer->getData());

        /* SIRIUS CustomFooter - end */
		foreach ($this->model_catalog_information->getInformations() as $result) {
			if ($result['bottom']) {
				$data['informations'][] = array(
					'title' => $result['title'],
					'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
				);
			}
		}
        $data['sirius_auth_register_popup'] = $this->load->controller('extension/module/sirius_auth_register_popup');
		$data['contact'] = $this->url->link('information/contact');
		$data['return'] = $this->url->link('account/return/add', '', true);
		$data['sitemap'] = $this->url->link('information/sitemap');
		$data['tracking'] = $this->url->link('information/tracking');
		$data['manufacturer'] = $this->url->link('product/manufacturer');
		$data['voucher'] = $this->url->link('account/voucher', '', true);
		$data['affiliate'] = $this->url->link('affiliate/login', '', true);
		$data['special'] = $this->url->link('product/special');
		$data['account'] = $this->url->link('account/account', '', true);
		$data['order'] = $this->url->link('account/order', '', true);
		$data['wishlist'] = $this->url->link('account/wishlist', '', true);
		$data['newsletter'] = $this->url->link('account/newsletter', '', true);

		$data['powered'] = sprintf($this->language->get('text_powered'), $this->config->get('config_name'), date('Y', time()));
		$data['text_login_register'] = $this->language->get('text_login_register');
		$data['entry_confirm'] = $this->language->get('entry_confirm');
		$data['entry_password'] = $this->language->get('entry_password');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_lastname'] = $this->language->get('entry_lastname');
		$data['entry_firstname'] = $this->language->get('entry_firstname');
		$data['entry_confirm'] = $this->language->get('entry_confirm');
//        $this->load->model('extension/module/socnetauth2');
//        $verification = $this->model_extension_module_socnetauth2->sheckLoginSocialNetworks($this->session->data['identity_customer_id'],'google');
//		if($verification!=3){
//            if($verification==1) $data['text_phone_number_verification'] = $this->language->get('text_phone_number_verification');
//            elseif($verification==2) $data['text_phone_number_verification'] = $this->language->get('text_phone_number_verification_register_end');
//            $phone_number_verification_form_display=true;
//        }else{
//		    $phone_number_verification_form_display=false;
//        }
//
//        $data['phone_number_verification_form_display'] = $phone_number_verification_form_display;

		// Whos Online
		if ($this->config->get('config_customer_online')) {
			$this->load->model('tool/online');
			if (isset($this->request->server['REMOTE_ADDR'])) {
				$ip = $this->request->server['REMOTE_ADDR'];
			} else {
				$ip = '';
			}
			if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {
				$url = ($this->request->server['HTTPS'] ? 'https://' : 'http://') . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];
			} else {
				$url = '';
			}
			if (isset($this->request->server['HTTP_REFERER'])) {
				$referer = $this->request->server['HTTP_REFERER'];
			} else {
				$referer = '';
			}
			$this->model_tool_online->addOnline($ip, $this->customer->getId(), $url, $referer);
		}

		$data['scripts'] = $this->document->getScripts('footer');
		return $this->load->view('common/footer', $data);
	}
}
