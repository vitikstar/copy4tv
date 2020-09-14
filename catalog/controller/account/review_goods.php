<?php
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerAccountReviewGoods extends Controller {
    public function index() {
               if (!$this->customer->isLogged()) {
                   
           $this->session->data['redirect'] = $this->url->link('account/review_goods', '', true);

           $this->response->redirect($this->url->link('account/login', '', true));
       }

        $this->load->language('account/review_goods');
		$data['heading_title'] = $this->language->get('heading_title');
        $data['text_catalog'] = $this->language->get('text_catalog');

        $this->document->setTitle($data['heading_title']);
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


        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/account', '', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $data['heading_title'],
            'href' => $this->url->link('account/review_goods')
        );

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }
       // $customer_id = 0;
        //$customer_id = $this->customer->getId();

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');

$data['header'] = $this->load->controller('common/header');
$data['column_megamenu'] = $this->load->controller('common/column_megamenu');
        $this->response->setOutput($this->load->view('account/review_goods', $data));
    }
}
