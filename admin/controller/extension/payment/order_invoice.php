<?php
class ControllerExtensionPaymentOrderInvoice extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/order_invoice');

		$this->document->setTitle($this->language->get('page_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_order_invoice', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'].'&type=payment', true));
		}

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			if (isset($this->error['supplier_info_' . $language['language_id']])) {
				$data['error_supplier_info_' . $language['language_id']] = $this->error['supplier_info_' . $language['language_id']];
			} else {
				$data['error_supplier_info_' . $language['language_id']] = '';
			}
		}

  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'].'&type=payment', true),
      		'separator' => ' :: '
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('page_title'),
			'href'      => $this->url->link('extension/payment/order_invoice', 'user_token=' . $this->session->data['user_token'], true),
      		'separator' => ' :: '
   		);

		$data['action'] = $this->url->link('extension/payment/order_invoice', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'].'&type=payment', true);

		foreach ($languages as $language) {
			if (isset($this->request->post['payment_order_invoice_supplier_info_' . $language['language_id']])) {
				$data['payment_order_invoice_supplier_info_' . $language['language_id']] = $this->request->post['payment_order_invoice_supplier_info_' . $language['language_id']];
			} else {
				$data['payment_order_invoice_supplier_info_' . $language['language_id']] = $this->config->get('payment_order_invoice_supplier_info_' . $language['language_id']);
			}
		}

		$data['languages'] = $languages;

		if (isset($this->request->post['payment_order_invoice_total'])) {
			$data['payment_order_invoice_total'] = $this->request->post['payment_order_invoice_total'];
		} else {
			$data['payment_order_invoice_total'] = $this->config->get('payment_order_invoice_total');
		}

		if (isset($this->request->post['payment_order_invoice_sheff'])) {
			$data['payment_order_invoice_sheff'] = $this->request->post['payment_order_invoice_sheff'];
		} else {
			$data['payment_order_invoice_sheff'] = $this->config->get('payment_order_invoice_sheff');
		}

		if (isset($this->request->post['payment_order_invoice_order_status_id'])) {
			$data['payment_order_invoice_order_status_id'] = $this->request->post['payment_order_invoice_order_status_id'];
		} else {
			$data['payment_order_invoice_order_status_id'] = $this->config->get('payment_order_invoice_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_order_invoice_geo_zone_id'])) {
			$data['payment_order_invoice_geo_zone_id'] = $this->request->post['payment_order_invoice_geo_zone_id'];
		} else {
			$data['payment_order_invoice_geo_zone_id'] = $this->config->get('payment_order_invoice_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_order_invoice_status'])) {
			$data['payment_order_invoice_status'] = $this->request->post['payment_order_invoice_status'];
		} else {
			$data['payment_order_invoice_status'] = $this->config->get('payment_order_invoice_status');
		}

        if (isset($this->request->post['payment_order_invoice_tax_status'])) {
            $data['payment_order_invoice_tax_status'] = $this->request->post['payment_order_invoice_tax_status'];
        } else {
            $data['payment_order_invoice_tax_status'] = $this->config->get('payment_order_invoice_tax_status');
        }

		if (isset($this->request->post['payment_order_invoice_sort_order'])) {
			$data['payment_order_invoice_sort_order'] = $this->request->post['payment_order_invoice_sort_order'];
		} else {
			$data['payment_order_invoice_sort_order'] = $this->config->get('payment_order_invoice_sort_order');
		}

		if (isset($this->request->post['payment_order_invoice_image'])) {
			$data['payment_order_invoice_image'] = $this->request->post['payment_order_invoice_image'];
		} elseif ($this->config->get('payment_order_invoice_image')) {
			$data['payment_order_invoice_image'] = $this->config->get('payment_order_invoice_image');
		} else {
			$data['payment_order_invoice_image'] = '';
		}

		if (isset($this->request->post['payment_order_invoice_sign'])) {
			$data['payment_order_invoice_sign'] = $this->request->post['payment_order_invoice_sign'];
		} elseif ($this->config->get('payment_order_invoice_sign')) {
			$data['payment_order_invoice_sign'] = $this->config->get('payment_order_invoice_sign');
		} else {
			$data['payment_order_invoice_sign'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['payment_order_invoice_image']) && is_file(DIR_IMAGE . $this->request->post['payment_order_invoice_image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['payment_order_invoice_image'], 100, 100);
		} elseif ($this->config->get('payment_order_invoice_image') && is_file(DIR_IMAGE . $this->config->get('payment_order_invoice_image'))) {
			$data['thumb'] = $this->model_tool_image->resize($this->config->get('payment_order_invoice_image'), 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		if (isset($this->request->post['payment_order_invoice_sign']) && is_file(DIR_IMAGE . $this->request->post['payment_order_invoice_sign'])) {
			$data['thumb2'] = $this->model_tool_image->resize($this->request->post['payment_order_invoice_sign'], 100, 100);
		} elseif ($this->config->get('payment_order_invoice_sign') && is_file(DIR_IMAGE . $this->config->get('payment_order_invoice_sign'))) {
			$data['thumb2'] = $this->model_tool_image->resize($this->config->get('payment_order_invoice_sign'), 100, 100);
		} else {
			$data['thumb2'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/order_invoice', $data) );
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/order_invoice')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			if (!$this->request->post['payment_order_invoice_supplier_info_' . $language['language_id']]) {
				$this->error['supplier_info_' .  $language['language_id']] = $this->language->get('error_supplier_info');
			}
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>