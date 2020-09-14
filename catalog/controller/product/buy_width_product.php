<?php
class ControllerProductBuyWidthProduct extends Controller {
	public function index($setting) {

        $data['heading_title']=$setting['heading_title'];

        $data['text_view_all_link'] =  (isset($this->request->get['product_id'])) ?  $this->url->link('product/buy_width_product_page_all_product','buy_width_product_id='.$this->request->get['product_id']) : $this->url->link('product/buy_width_product_page_all_product');


        $data['text_view_all'] =  $this->language->get('text_view_all');


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


        if (empty($setting['limit'])) {
            $setting['limit'] = 20;
        }
        if($setting['limit']=20) $limit = LIMIT_BUY_PRODUCT;

        $filter_data = array(
            'limit' => $limit
        );


        $products = $this->model_catalog_product->getBuyWidthProducts($filter_data);

            if($products){
                //   rsort($products);
                $new_product_id_arr[0]=array();
                $i=0;
                foreach ($products as $k=>$v){
                    if($i==0){
                        array_push($new_product_id_arr[$i],$v);
                    }else{
                        if(in_array($v,$new_product_id_arr[$i-1])){
                            array_push($new_product_id_arr[$i-1],$v);
                            $i--;
                        }else{
                            $new_product_id_arr[$i]=array($v);
                        }
                    }
                    $i++;
                }

                array_multisort(array_map('count', $new_product_id_arr), SORT_DESC, $new_product_id_arr);
                $products = array();
                foreach ($new_product_id_arr as $array){
                    $products[]=$array[0];
                }
            }

        $this->load->model("account/wishlist");
        $wishlist =  $this->model_account_wishlist->getWishlist();


        if($products){

    foreach ($products as $product) {
        $product_id = $product['product_id'];
        $product_info = $this->model_catalog_product->getProduct($product_id);

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
            $data['products'][] = array(
                'product_id'  => $product_info['product_id'],
                'thumb'       => $image,
                'name'        => $product_info['name'],
                'description' => utf8_substr(trim(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                'price'       => $price,
                'special'     => $special,
                'tax'         => $tax,
                'rating'      => $rating,
                'class_name'         => (in_array($product_info['product_id'],$wishlist)) ? 'products-wish-btn-active' : 'products-wish-btn',
                'compare_count'      => $compare_count,
                // up
                'reviews'      => $product_info['reviews'],
                'attribute_groups'    => $attribute_groups,
                // end up
                'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
            );
        }
    }


    return $this->load->view('product/buy_width_product', $data);
}

	}

}
