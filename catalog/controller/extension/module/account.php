<?php
class ControllerExtensionModuleAccount extends Controller {
	public function index() {
		$data = $this->load->language('extension/module/account');

		$data['logged'] = $this->customer->isLogged();
		$data['register'] = $this->url->link('account/register', '', true);
		$data['login'] = $this->url->link('account/login', '', true);
		$data['logout'] = $this->url->link('account/logout', '', true);
		$data['forgotten'] = $this->url->link('account/forgotten', '', true);
		$data['account'] = $this->url->link('account/account', '', true);
		$data['edit'] = $this->url->link('account/edit', '', true);
		$data['password'] = $this->url->link('account/password', '', true);
		$data['address'] = $this->url->link('account/address', '', true);
		$data['wishlist'] = $this->url->link('account/wishlist');
		$data['order'] = $this->url->link('account/order', '', true);
		$data['download'] = $this->url->link('account/download', '', true);
		$data['reward'] = $this->url->link('account/reward', '', true);
		$data['return'] = $this->url->link('account/return', '', true);
		$data['transaction'] = $this->url->link('account/transaction', '', true);
		$data['newsletter'] = $this->url->link('account/newsletter', '', true);
		$data['recurring'] = $this->url->link('account/recurring', '', true);
		$data['review_goods'] = $this->url->link('account/review_goods', '', true);

        $data['waiting_list'] = $this->url->link('account/waiting_list', '', true);
        $data['review'] = $this->url->link('account/review', '', true);



        //up
        if (isset($_SERVER['HTTPS'])) {$http = "https://";} else {$http = "http://";}
        $serverName =  $_SERVER['SERVER_NAME'];
        $linkMain = $_SERVER['REQUEST_URI'];
        $catLinkDone = $http.$serverName.$linkMain;
        $data['catLinkDone'] = $catLinkDone;

        $data['catLinkDone'] = $this->url->link($this->request->get['route'], '', true);
        //end up


		return $this->load->view('extension/module/account', $data);
	}
}