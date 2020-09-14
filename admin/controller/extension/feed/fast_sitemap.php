<?php

/*
 * Fast Sitemap [xml]
 * by dub(nix)
 */

class ControllerExtensionFeedFastSitemap extends Controller {
	private $error = array(); 

	public function index() {
		$this->language->load('feed/fast_sitemap');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('fast_sitemap', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=feed', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_cache_status'] = $this->language->get('entry_cache_status');
		$data['entry_data_feed'] = $this->language->get('entry_data_feed');
		$data['bt_clear_cache'] = $this->language->get('bt_clear_cache');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

  		$data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=feed', true)
        );

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/feed/fast_sitemap', 'user_token=' . $this->session->data['user_token'], true),
   		);

		$data['action'] = $this->url->link('extension/feed/fast_sitemap', 'user_token=' . $this->session->data['user_token'] . '&type=feed', true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], true);
		$data['clear_cache'] = $this->url->link('extension/feed/fast_sitemap/clear_cache', 'user_token=' . $this->session->data['user_token'], true);

		if (isset($this->request->post['fast_sitemap_status'])) {
			$data['fast_sitemap_status'] = $this->request->post['fast_sitemap_status'];
		} else {
			$data['fast_sitemap_status'] = $this->config->get('fast_sitemap_status');
		}
		if (isset($this->request->post['f_s_cache_status'])) {
			$data['f_s_cache_status'] = $this->request->post['f_s_cache_status'];
		} else {
			$data['f_s_cache_status'] = $this->config->get('f_s_cache_status');
		}

		$data['data_feed'] = HTTP_CATALOG . 'index.php?route=feed/fast_sitemap';


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/feed/fast_sitemap', $data));
	}

	public function clear_cache() {
		$this->language->load('feed/fast_sitemap');

		$json = array();

		if ($this->cache->get('fast_sitemap')) {
			$this->cache->delete('fast_sitemap');
			$text_success = $this->language->get('text_cache_success');
		} else {
			$text_success = $this->language->get('text_cache_empty');
		}

		$json['success'] = '<b>' . $text_success . '</b>';

		$this->response->setOutput(json_encode($json));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'feed/fast_sitemap')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>