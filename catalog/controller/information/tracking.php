<?php
class ControllerInformationTracking extends Controller {
	public function index() {
		$this->load->language('information/tracking');

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
		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');

$data['header'] = $this->load->controller('common/header');
$data['column_megamenu'] = $this->load->controller('common/column_megamenu');

		$this->response->setOutput($this->load->view('information/tracking', $data));
	}
	
	public function track() {
		$json = array();
		
		$this->load->model('account/shipping');
		
		$this->model_account_shipping->getShippingByCode($this->request->get['code']);
		
	}
}