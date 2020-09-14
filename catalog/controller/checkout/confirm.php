<?php
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerCheckoutConfirm extends Controller {
	public function index() {
		$json = array();
				if (!$this->customer->isLogged()) { 
					//якщо не вибраний спосіб доставки "самовивіз з відділення"  або вибраний спосіб доствки службою(нова почта або укрпочта)
					if($this->request->post['ch_del_input_1']=='false' && $this->request->post['ch_del_input_2']=='false'){
						$json['error'][] = 'Виберіть спосіб доставки';
					}
						$json['error'][] = 'Для оформлення замовлення завершіть перший крок';
				}
				

				if ($this->customer->isLogged() and $this->request->post['ch_del_input_5']=='true') { //якщо юзер залогінений і додає новий спосіб доставки
					//якщо не вибраний спосіб доставки "самовивіз з відділення"  або вибраний спосіб доствки службою(нова почта або укрпочта)
					if($this->request->post['ch_del_input_1']=='false' && $this->request->post['ch_del_input_2']=='false'){
						$json['error'][] = 'Виберіть спосіб доставки';
					}
				}
				if($this->request->post['shipping_address_checked']=='false' && $this->request->post['poshtomat_address_checked']=='false' && $this->request->post['delivery_service_samov_val']=='ukrposhta'){
					$json['error'][] = 'Виберіть тип доставки';
				}

				
				// if(!isset($this->request->post['name_delivery_checked']) && $this->request->post['delivery_service_samov_val']=='ukrposhta'){
				// 	$json['error'][] = 'Виберіть тип доставки укрпочтою';
				// }
				

				if($this->request->post['payment_method']=='false'){
					$json['error'][] = 'Виберіть спосіб оплати';
				}
				if($this->request->post['checkbox_input_mod']=='false'){
					$json['error'][] = 'Ви повині погодитись з умовами';
				}

		$redirect = '';


		// Validate minimum quantity requirements.
		$products = $this->cart->getProducts();



		foreach ($products as $product) {
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$redirect = $this->url->link('checkout/cart');

				break;
			}
		}


		if (!$redirect and !isset($json['error'])) {

			$order_data = array();

			$totals = array();
			$taxes = $this->cart->getTaxes();
			$total = 0;

			// Because __call can not keep var references so we put them into an array.
			$total_data = array(
				'totals' => &$totals,
				'taxes'  => &$taxes,
				'total'  => &$total
			);

			$this->load->model('setting/extension');

			$sort_order = array();

			$results = $this->model_setting_extension->getExtensions('total');

			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
			}

			array_multisort($sort_order, SORT_ASC, $results);

			foreach ($results as $result) {
				if ($this->config->get('total_' . $result['code'] . '_status')) {
					$this->load->model('extension/total/' . $result['code']);

					// We have to put the totals in an array so that they pass by reference.
					$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
				}
			}

			$sort_order = array();

			foreach ($totals as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $totals);

			$order_data['totals'] = $totals;

			$this->load->language('checkout/checkout');

			$order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
			$order_data['store_id'] = $this->config->get('config_store_id');
			$order_data['store_name'] = $this->config->get('config_name');

			if ($order_data['store_id']) {
				$order_data['store_url'] = $this->config->get('config_url');
			} else {
				if ($this->request->server['HTTPS']) {
					$order_data['store_url'] = HTTPS_SERVER;
				} else {
					$order_data['store_url'] = HTTP_SERVER;
				}
			}

			$this->load->model('account/customer');

			if ($this->customer->isLogged()) {
				$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

				$order_data['customer_id'] = $this->customer->getId();
				$order_data['customer_group_id'] = $customer_info['customer_group_id'];
				$order_data['firstname']  = $order_data['payment_firstname'] = $customer_info['firstname'];
				$order_data['lastname'] = $order_data['payment_lastname'] = $customer_info['lastname'];
				$order_data['email'] = $customer_info['email'];
				$order_data['telephone'] = $this->session->data['telephone'] =  $customer_info['telephone'];
				$order_data['custom_field'] = json_decode($customer_info['custom_field'], true);

                $order_data['payment_address_1'] = (isset($this->session->data['payment_address']['address_1'])) ? $this->session->data['payment_address']['address_1'] : '';
                $order_data['payment_address_2'] = (isset($this->session->data['payment_address']['address_2'])) ? $this->session->data['payment_address']['address_2'] : '';
                $order_data['payment_city'] = (isset($this->session->data['payment_address']['city'])) ? $this->session->data['payment_address']['city'] : '';
                $order_data['payment_postcode'] = (isset($this->session->data['payment_address']['postcode'])) ? $this->session->data['payment_address']['postcode'] : '';
                $order_data['payment_zone'] = (isset($this->session->data['payment_address']['zone'])) ? $this->session->data['payment_address']['zone'] : '';
                $order_data['payment_zone_id'] = (isset($this->session->data['payment_address']['zone_id'])) ? $this->session->data['payment_address']['zone_id'] : '';
                $order_data['payment_country'] = (isset($this->session->data['payment_address']['country'])) ? $this->session->data['payment_address']['country'] : '';
                $order_data['payment_country_id'] = (isset($this->session->data['payment_address']['country_id'])) ? $this->session->data['payment_address']['country_id'] : '';
                $order_data['payment_address_format'] = (isset($this->session->data['payment_address']['address_format'])) ? $this->session->data['payment_address']['address_format'] : '';
                $order_data['payment_custom_field'] = (isset($this->session->data['payment_address']['custom_field']) ? $this->session->data['payment_address']['custom_field'] : array());

            } elseif (isset($this->session->data['guest'])) {
				$order_data['customer_id'] = 0;
				$order_data['customer_group_id'] = $this->config->get('config_customer_group_id');

                $order_data['lastname'] = $this->session->data['guest']['lastname_login'];
                $order_data['firstname'] = $this->session->data['guest']['firstname_login'];

				$order_data['email'] = $this->session->data['guest']['email_login'];
				$order_data['telephone'] = $this->session->data['telephone'] = $this->session->data['guest']['telephone_login'];
				$order_data['custom_field'] = array();
                $order_data['payment_firstname'] = $order_data['firstname'];
                $order_data['payment_lastname'] = $order_data['lastname'];
            }

            $order_data['payment_company'] = '';
            $order_data['payment_address_1'] = '';
            $order_data['payment_address_2'] = '';
            $order_data['payment_city'] = '';
            $order_data['payment_postcode'] = '';
            $order_data['payment_zone'] = '';
            $order_data['payment_zone_id'] = '';
            $order_data['payment_country'] = '';
            $order_data['payment_country_id'] = '';
            $order_data['payment_address_format'] = '';
            $order_data['payment_custom_field'] = array();
            if (isset($this->session->data['payment_method']['title'])) {
                $order_data['payment_method'] = $this->session->data['payment_method']['title'];
            } else {
                $order_data['payment_method'] = '';
            }

            if (isset($this->session->data['payment_method']['code'])) {
                $order_data['payment_code'] = $this->session->data['payment_method']['code'];
            } else {
                $order_data['payment_code'] = '';
            }
            /**
             * shipping
             */

            if ($this->customer->isLogged()) {
                $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

                $order_data['shipping_firstname'] = $customer_info['firstname'];
                $order_data['shipping_lastname'] = $customer_info['lastname'];

            }else{
                $order_data['shipping_lastname'] = $this->session->data['guest']['lastname_login'];
                $order_data['shipping_firstname'] = $this->session->data['guest']['firstname_login'];
            }

            $order_data['shipping_company'] = '';
            $order_data['shipping_address_1'] = $this->session->data['checkout_data']['shipping_address_1'];
            $order_data['shipping_address_2'] = '';
            $order_data['shipping_city'] = '';
            $order_data['shipping_postcode'] = '';
            $order_data['shipping_zone'] = '';
            $order_data['shipping_zone_id'] = '';
            $order_data['shipping_country'] = '';
            $order_data['shipping_country_id'] = '';
            $order_data['shipping_address_format'] = '';
            $order_data['shipping_custom_field'] = array();
            $order_data['shipping_method'] = '';
            $order_data['shipping_code'] = '';

            if (isset($this->session->data['shipping_method']['title'])) {
				if(isset($this->session->data['shipping_methods'][$this->session->data['checkout_data']['delivery_service_samov_val']])){
					$order_data['shipping_method'] = $this->session->data['shipping_method']['title'].' '.$this->session->data['shipping_methods'][$this->session->data['checkout_data']['delivery_service_samov_val']]['title'];
				}else{
					$order_data['shipping_method'] = '';
				}
            } else {
                $order_data['shipping_method'] = '';
			}

            if (isset($this->session->data['shipping_code']['title'])) {
                $order_data['shipping_code'] = $this->session->data['shipping_code']['title'];
            } else {
                $order_data['shipping_code'] = '';
            }

			$order_data['products'] = array();

			foreach ($this->cart->getProducts() as $product) {
				$option_data = array();

				foreach ($product['option'] as $option) {
					$option_data[] = array(
						'product_option_id'       => $option['product_option_id'],
						'product_option_value_id' => $option['product_option_value_id'],
						'option_id'               => $option['option_id'],
						'option_value_id'         => $option['option_value_id'],
						'name'                    => $option['name'],
						'value'                   => $option['value'],
						'type'                    => $option['type']
					);
				}

				$order_data['products'][] = array(
					'product_id' => $product['product_id'],
					'name'       => $product['name'],
					'model'      => $product['model'],
					'option'     => $option_data,
					'download'   => $product['download'],
					'quantity'   => $product['quantity'],
					'subtract'   => $product['subtract'],
					'price'      => $product['price'],
					'total'      => $product['total'],
					'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
					'reward'     => $product['reward']
				);
			}

			// Gift Voucher
			$order_data['vouchers'] = array();

			if (!empty($this->session->data['vouchers'])) {
				foreach ($this->session->data['vouchers'] as $voucher) {
					$order_data['vouchers'][] = array(
						'description'      => $voucher['description'],
						'code'             => token(10),
						'to_name'          => $voucher['to_name'],
						'to_email'         => $voucher['to_email'],
						'from_name'        => $voucher['from_name'],
						'from_email'       => $voucher['from_email'],
						'voucher_theme_id' => $voucher['voucher_theme_id'],
						'message'          => $voucher['message'],
						'amount'           => $voucher['amount']
					);
				}
			}

            if(isset($this->session->data['new_address_or_default'])){
                $order_data['comment'] = '';
            }else{
                $order_data['comment'] = $this->session->data['comment'];
            }

			$order_data['total'] = $total_data['total'];

			if (isset($this->request->cookie['tracking'])) {
				$order_data['tracking'] = $this->request->cookie['tracking'];

				$subtotal = $this->cart->getSubTotal();

				// Affiliate
				$affiliate_info = $this->model_account_customer->getAffiliateByTracking($this->request->cookie['tracking']);

				if ($affiliate_info) {
					$order_data['affiliate_id'] = $affiliate_info['customer_id'];
					$order_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
				} else {
					$order_data['affiliate_id'] = 0;
					$order_data['commission'] = 0;
				}

				// Marketing
				$this->load->model('checkout/marketing');

				$marketing_info = $this->model_checkout_marketing->getMarketingByCode($this->request->cookie['tracking']);

				if ($marketing_info) {
					$order_data['marketing_id'] = $marketing_info['marketing_id'];
				} else {
					$order_data['marketing_id'] = 0;
				}
			} else {
				$order_data['affiliate_id'] = 0;
				$order_data['commission'] = 0;
				$order_data['marketing_id'] = 0;
				$order_data['tracking'] = '';
			}

			$order_data['language_id'] = $this->config->get('config_language_id');
			$order_data['currency_id'] = $this->currency->getId($this->session->data['currency']);
			$order_data['currency_code'] = $this->session->data['currency'];
			$order_data['currency_value'] = $this->currency->getValue($this->session->data['currency']);
			$order_data['ip'] = $this->request->server['REMOTE_ADDR'];

			if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
				$order_data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
			} elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
				$order_data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
			} else {
				$order_data['forwarded_ip'] = '';
			}

			if (isset($this->request->server['HTTP_USER_AGENT'])) {
				$order_data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
			} else {
				$order_data['user_agent'] = '';
			}

			if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
				$order_data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
			} else {
				$order_data['accept_language'] = '';
			}

            $order_data['customer_address_delivery_id'] = (isset($this->session->data['customer_address_delivery_id'])) ? $this->session->data['customer_address_delivery_id'] : 0;

            $this->load->model('checkout/order');
            $json['order_id'] = 0;
            $this->session->data['order_id'] = $order_id =  $this->model_checkout_order->addOrder($order_data);

            if (empty($order_id)) {
                $json['error']['order'] = $this->language->get('error_order');
            } else {
                $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('config_order_status_id'));
                $json['order_id'] = $order_id;
				$json['success'] = '<div class="alert alert-success" role="alert">'.sprintf($this->language->get('heading_title_customer'), $order_id).'</div>';
				$this->session->data['checkout_success'] = sprintf($this->language->get('heading_title_customer'), $order_id);
				$json['redirect_page_success'] = $this->url->link('checkout/confirm/success');
            }
		} else {
            $json['redirect'] = $redirect;
		}

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
	}

	public function method(){
        $this->session->data['shipping_method']['step'][3]=true;
        $data['payment'] = $this->load->controller('extension/payment/' . $this->session->data['payment_method_val']);
        $this->response->setOutput($this->load->view('checkout/payment_form', $data));
	}
	
	public function success(){
		$data['breadcrumbs'] = array();
		$this->load->language('checkout/checkout');
		$lang = $this->language->get('code');
		$data['lang'] = $lang;

		if ($lang == 'ru') {
			$data['breadcrumbs'][] = array(
				'text' => 'Главная',
				'href' => $this->url->link('common/home'),
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => 'Головна',
				'href' => $this->url->link('common/home'),
			);
		}

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('checkout/checkout', '', true),
		);
		$data['breadcrumbs'][] = array(
    'text' => $this->language->get('text_confirm_success'),
    'href' => $this->url->link('checkout/confirm/success', '', true),
);

        $data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$data['heading_title_customer'] = $this->session->data['checkout_success'];

        $this->response->setOutput($this->load->view('checkout/success', $data));
    }
}
