<?php
class ControllerExtensionModuleSendpulse extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/sendpulse');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
		$this->load->model('extension/module/sendpulse');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->request->post['module_sendpulse_count'] = 0;
			$this->model_setting_setting->editSetting('module_sendpulse', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('extension/module/sendpulse', 'user_token=' . $this->session->data['user_token'], true));
		}
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['user_token'] = $this->session->data['user_token'];
		$data['heading_title'] = $this->language->get('heading_title');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/sendpulse', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/sendpulse', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->error['sendpulse_id'])) {
			$data['error_sendpulse_id'] = $this->error['sendpulse_id'];
		} else {
			$data['error_sendpulse_id'] = '';
		}
		if (isset($this->error['sendpulse_secret'])) {
			$data['error_sendpulse_secret'] = $this->error['sendpulse_secret'];
		} else {
			$data['error_sendpulse_secret'] = '';
		}
		$id = $this->config->get('module_sendpulse_id');
		$secret = $this->config->get('module_sendpulse_secret');

		if (isset($this->request->post['module_sendpulse_id'])) {
			$data['module_sendpulse_id'] = $this->request->post['module_sendpulse_id'];
		} else {
			$data['module_sendpulse_id'] = $id;
		}
		if (isset($this->request->post['module_sendpulse_secret'])) {
			$data['module_sendpulse_secret'] = $this->request->post['module_sendpulse_secret'];
		} else {
			$data['module_sendpulse_secret'] = $secret;
		}
		if (isset($this->request->post['module_sendpulse_auto_add'])) {
			$data['module_sendpulse_auto_add'] = $this->request->post['module_sendpulse_auto_add'];
		} else {
			$data['module_sendpulse_auto_add'] = $this->config->get('module_sendpulse_auto_add');
		}
		if (isset($this->request->post['module_sendpulse_book_default'])) {
			$data['module_sendpulse_book_default'] = $this->request->post['module_sendpulse_book_default'];
		} else {
			$data['module_sendpulse_book_default'] = $this->config->get('module_sendpulse_book_default');
		}
		if($id != '' && $secret != '') {
			$data['books'] = $this->model_extension_module_sendpulse->getBooks($id, $secret);
			if(!$data['books']) $this->error['warning'] = $this->language->get('error_connect');
		}
		else $data['books'] = false;

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		if (isset($this->request->post['module_sendpulse_status'])) {
			$data['module_sendpulse_status'] = $this->request->post['module_sendpulse_status'];
		} else {
			$data['module_sendpulse_status'] = $this->config->get('module_sendpulse_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/sendpulse', $data));
	}

	public function export() {
		$this->load->language('extension/module/sendpulse');

		$this->load->model('extension/module/sendpulse');

		$id = $this->config->get('module_sendpulse_id');
		$secret = $this->config->get('module_sendpulse_secret');

		if ($this->user->hasPermission('modify', 'extension/module/sendpulse')) {
			if($id != '' && $secret != '' && isset($this->request->post['book'])) {
				$json = $this->model_extension_module_sendpulse->export($id, $secret, $this->request->post['book']);
			} else $json['error'] = $this->language->get('error_export');
		} else $json['error'] = $this->language->get('error_permission');

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/sendpulse')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (!isset($this->request->post['module_sendpulse_id']) || (utf8_strlen($this->request->post['module_sendpulse_id']) < 20) || (utf8_strlen($this->request->post['module_sendpulse_id']) > 64)) {
			$this->error['sendpulse_id'] = $this->language->get('error_api');
			unset($this->session->data['success']);
		}

		if (!isset($this->request->post['module_sendpulse_id']) || (utf8_strlen($this->request->post['module_sendpulse_secret']) < 20) || (utf8_strlen($this->request->post['module_sendpulse_secret']) > 64)) {
			$this->error['sendpulse_secret'] = $this->language->get('error_api');
			unset($this->session->data['success']);
		}

		return !$this->error;
	}

	public function install(){
	    $data['redirect'] = 'extension/extension/module';
	    $this->load->controller('marketplace/modification/refresh', $data);
	}

}
