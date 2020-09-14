<?php
class ControllerProductInfoProduct extends Controller {
	public function index() {
	    $data=array();
		return $this->load->view('product/info_product', $data);
	}
}