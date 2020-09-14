<?php
class ControllerExtensionModuleBestSeller extends Controller {
	public function index($setting) {
	    $data['heading_title']=$setting['name'];
//        $path = '';
//        $category_arr=array();
//if(isset($this->request->get['path'])){
//    $parts = explode('_', (string)$this->request->get['path']);
//
    $category_arr = (!empty($setting['product_category'])) ? $setting['product_category'] : array();
//    $category_id = (int)array_pop($parts);
//
//
//    if(!in_array($category_id,$category_arr)) return;
//}



		$this->load->language('extension/module/bestseller');

		$data['text_name_module'] = $this->language->get('text_name_module');
		$data['text_view_all'] =  sprintf($this->language->get('text_view_all'),$this->url->link('product/bestseller'));

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

        // up
        $this->document->addStyle('catalog/view/javascript/jquery/swiper/css/swiper.min.css');
        $this->document->addStyle('catalog/view/javascript/jquery/swiper/css/opencart.css');
        $this->document->addScript('catalog/view/javascript/jquery/swiper/js/swiper.jquery.js');
        // end up

		$data['products'] = array();

        $this->load->model('catalog/product');

        $this->load->model('tool/image');

        $data['products'] = array();

        $products = array();

        if (isset($this->request->cookie['viewed_popular_instrument'])) {
            $products = explode(',', $this->request->cookie['viewed_popular_instrument']);
        }

        $total = count($products);

        if($total==0){
            $products=[3458,1809,2723,2933,2935,3518,190,2219,2459,3501,2455,3144];
        }elseif($total==1){
            $products = array_merge($products,[3458,1809,2723,2933,2935,3518,190,2219,2459,3501,2455]);
        }elseif($total==2){
            $products = array_merge($products,[3458,1809,2723,2933,2935,3518,190,2219,2459,3501]);
        }elseif($total==3){
            $products = array_merge($products,[3458,1809,2723,2933,2935,3518,190,2219,2459]);
        }elseif($total==4){
            $products = array_merge($products,[3458,1809,2723,2933,2935,3518,190,2219]);
        }elseif($total==5){
            $products = array_merge($products,[3458,1809,2723,2933,2935,3518,190]);
        }elseif($total==6){
            $products = array_merge($products,[3458,1809,2723,2933,2935,3518]);
        }elseif($total==7){
            $products = array_merge($products,[3458,1809,2723,2933,2935]);
        }elseif($total==8){
            $products = array_merge($products,[3458,1809,2723,2933]);
        }elseif($total==9){
            $products = array_merge($products,[3458,1809,2723]);
        }elseif($total==10){
            $products = array_merge($products,[3458,1809]);
        }elseif($total==11){
            $products = array_merge($products,[3458]);
        }




        if (empty($setting['limit'])) {
            $setting['limit'] = 12;
        }
        if($setting['limit']<12) $setting['limit'] = 12;

        $products = array_slice($products, 0, (int)$setting['limit']);
        $this->load->model("account/wishlist");
        $wishlist =  $this->model_account_wishlist->getWishlist();
        $products = $this->model_catalog_product->getProducts(array('list_bestseller'=>implode(',',$products),'available' => 1));

        if($products){
    foreach ($products as $product_info) {

        if ($product_info) {
            if ($product_info['image']) {
                $image = $this->model_tool_image->resize($product_info['image'], $setting['width'], $setting['height']);
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
            }

            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
            } else {
                $price = false;
            }

            if ($this->customer->isLogged() || !$this->config->get('config_customer_price') and $product_info['metka_id']=='super_price') {
                $super_price = $this->currency->format($this->tax->calculate($product_info['super_price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
            } else {
                $super_price = false;
            }

            if ((float)$product_info['special']) {
                $special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
            } else {
                $special = false;
            }

            if ($this->config->get('config_tax')) {
                $tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
            } else {
                $tax = false;
            }

            if ($this->config->get('config_review_status')) {
                $this->load->model('extension/module/p_review');
                $rating = $this->model_extension_module_p_review->getRating($product_info['product_id']);
            } else {
                $rating = false;
            }

            // up
            $attribute_groups = $this->model_catalog_product->getProductAttributes($product_info['product_id']);
            // end up

            if(isset($this->session->data['compare'])){
                $compare_count=(in_array($product_info['product_id'],$this->session->data['compare'])) ? count($this->session->data['compare']) : '';
            }else{
                $compare_count='';
            }
            $this->load->model('extension/module/p_review');
            $data['products'][] = array(
                'product_id'  => $product_info['product_id'],
                'thumb'       => $image,
                'name'        => $product_info['name'],
                'description' => utf8_substr(trim(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                'price'       => $price,
                'special'     => $special,
                'tax'         => $tax,
                'rating'      => $rating,
                'super_price'       => $super_price,
                'class_name'         => (in_array($product_info['product_id'],$wishlist)) ? 'products-wish-btn-active' : 'products-wish-btn',
                'compare_count'      => $compare_count,

                // up
                'reviews'      => $product_info['reviews'],
                'metka_id'      => $product_info['metka_id'],
                'attribute_groups'    => $attribute_groups,
                // end up
                'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
            );
        }
    }
    return $this->load->view('extension/module/bestseller', $data);
}
	}
}
