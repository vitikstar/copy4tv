<?php
class ControllerExtensionModuleBlogMenu extends Controller {
	public function index() {
		$data = $this->load->language('extension/module/blog_menu');
		$data['logged'] = $this->customer->isLogged();
		$data['about'] = $this->url->link('catalog/information','information_id=4');
		$data['news_shop'] = $this->url->link('blog/category','blog_category_id=1');
		$data['contact'] =  $this->url->link('information/contact', '', true);
		$data['delivery_and_payment'] =  $this->url->link('catalog/information','information_id=10');
		$data['garantie'] =  $this->url->link('catalog/information','information_id=11');
		$data['opt_pay'] =  $this->url->link('catalog/information','information_id=7');
		$data['dropshiping'] =  $this->url->link('catalog/information','information_id=8');
		$data['answer'] =  $this->url->link('catalog/information','information_id=15');


        //up
        if(isset($this->request->get['information_id'])){
            $catLinkDone = $this->url->link($this->request->get['route'], 'information_id='.$this->request->get['information_id'], true);
        }elseif(isset($this->request->get['blog_category_id'])){
            $catLinkDone = $this->url->link($this->request->get['route'], 'blog_category_id=1', true);
        }else{
            $catLinkDone = $this->url->link($this->request->get['route'], true);
        }

        //end up

        $data['catLinkDone'] = $catLinkDone;

		return $this->load->view('extension/module/blog_menu', $data);
	}
}