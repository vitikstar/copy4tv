<?php
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerAccountNewsletter extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/newsletter', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/account');

		$data['text_catalog'] = $this->language->get('text_catalog');

		$this->load->language('account/newsletter');
        $data['text_newsletter'] = $this->language->get('text_newsletter');
        $data['text_form_send'] = $this->language->get('text_form_send');
        $data['entry_newsletter'] = $this->language->get('entry_newsletter');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->setRobots('noindex,follow');
$this->load->model("account/newsletter");

$form_send_all = array();

foreach ($this->model_account_newsletter->getFormSendAll(array('email','sms','viber')) as $k=>$item){
    $row = $this->db->query("SELECT form_alias  FROM `" . DB_PREFIX . "newsletter_module`  WHERE form_alias='". $item['alias'] ."' AND `customer_id` = '" . (int)$this->customer->getId() . "'");

    if($row->rows) $item['checked'] = 1;

    $form_send_all[$k] = $item;
}
$type_all = array();

foreach ($this->model_account_newsletter->getTypeAll() as $k=>$item){
    $row = $this->db->query("SELECT type_alias  FROM `" . DB_PREFIX . "newsletter_module`  WHERE type_alias='". $item['alias'] ."' AND `customer_id` = '" . (int)$this->customer->getId() . "'");

    if($row->rows) $item['checked'] = 1;

    $type_all[$k] = $item;
}

$data["form_send_all"] = $form_send_all;
$data["type_all"] = $type_all;

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

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_newsletter'),
			'href' => $this->url->link('account/newsletter', '', true)
		);

		$data['action'] = $this->url->link('account/newsletter', '', true);

		$data['newsletter'] = $this->customer->getNewsletter();

		$data['back'] = $this->url->link('account/account', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');

$data['header'] = $this->load->controller('common/header');
$data['column_megamenu'] = $this->load->controller('common/column_megamenu');

		$this->response->setOutput($this->load->view('account/newsletter', $data));
	}

	public function newsletter(){
        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }
        $json = array();

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

            $newsletter_channel = $this->request->post['newsletter_channel'];
            $newsletter = $this->request->post['newsletter'];
            $data = array();
            foreach ($newsletter_channel as $form_alias){
                foreach ($newsletter as $type_alias){
                    $data[] = array('form_alias'=>$form_alias,'type_alias'=>$type_alias);
                }
            }
                $this->load->model('account/newsletter');
                $this->load->language('account/newsletter');
            $this->model_account_newsletter->addNewsletter($data);

            $json['redirect'] = $this->url->link('account/review');
            $this->session->data['success'] = $this->language->get('text_success');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}