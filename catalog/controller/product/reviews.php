<?php
class ControllerProductReviews extends Controller {
    public function index($state) {
        $data=array();
        $this->load->language('product/product');
        $data['write_review'] = $this->language->get('write_review');
        $data['consumer_review'] = sprintf($this->language->get('consumer_review'), $state['reviews']);


        $data['bad_review']     = $this->language->get('bad_review');
        $data['so_so_review']     = $this->language->get('so_so_review');
        $data['good_review']     = $this->language->get('good_review');
        $data['nice_review']     = $this->language->get('nice_review');
        $data['great_review']     = $this->language->get('great_review');

        $data['comment_review']     = $this->language->get('comment_review');
        $data['advantages_review']     = $this->language->get('advantages_review');
        $data['disadvantages_review']     = $this->language->get('disadvantages_review');
        $data['name_surname_review']     = $this->language->get('name_surname_review');
        $data['email_review']     = $this->language->get('email_review');
        $data['register_review']     = sprintf($this->language->get('register_review'), ['<a href="javascript:void(0)">','</a>','<a href="javascript:void(0)">','</a>']);
        $data['cancel_review']     = $this->language->get('cancel_review');
        $data['add_review']     = $this->language->get('add_review');

        return $this->load->view('product/reviews', $data);
    }
}