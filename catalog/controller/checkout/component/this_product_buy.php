<?php

class ControllerCheckoutComponentThisProductBuy extends Controller {
    public function index() {
        $this->load->language('checkout/component/this_product_buy');
        $setting['width']=300;
        $setting['height']=300;
        $setting['limit']=20;
        $setting['heading_title']=$this->language->get('heading_title_this_product_buy');
        $data['this_product_buy'] = $this->load->controller('product/buy_width_product',$setting);

        return $this->load->view('checkout/component/this_product_buy', $data);
    }
}