<?php
class ControllerExtensionPaymentCod extends Controller {
	public function index() {
        $this->load->language('extension/payment/cod');
        $data['button_confirm'] = $this->language->get('button_confirm');


        return $this->load->view('extension/payment/cod',$data);
	}
}
