<?php
class ControllerExtensionModuleSiriusWishlist extends Controller {
	public function index($setting) {
        if(!$setting) return;
		$this->load->language('extension/module/sirius_wishlist');

		$this->load->model('catalog/product');
        $this->load->model('account/wishlist');

		$this->load->model('tool/image');

        $data['text_view_all'] =  sprintf($this->language->get('text_view_all'),$this->url->link('account/wishlist'));



        // up
        $this->document->addStyle('catalog/view/javascript/jquery/swiper/css/swiper.min.css');
        $this->document->addStyle('catalog/view/javascript/jquery/swiper/css/opencart.css');
        $this->document->addScript('catalog/view/javascript/jquery/swiper/js/swiper.jquery.js');
        // end up

		$data['products'] = array();



        $results = $wishlist = $this->model_account_wishlist->getWishlist();
        $this->load->model("account/wishlist");
        $data['link_all_show'] = $this->url->link("account/wishlist");
        if(count($results)<7) return;
		if ($results) {
			foreach ($results as $product_id) {
if(!$product_id) continue;
                $product_info = $this->model_catalog_product->getProduct($product_id);
                if ($product_info) {
                    if ($product_info['image']) {
                        $image = $this->model_tool_image->resize($product_info['image'], $setting['width'], $setting['height']);
                    } else {
                        $image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
                    }

                    if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
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
                        $rating = $product_info['rating'];
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
                        'super_price'       => $super_price,
                        'class_name'         => (in_array($product_info['product_id'],$wishlist)) ? 'products-wish-btn-active' : 'products-wish-btn',
                        'compare_count'      => $compare_count,
                        'special'     => $special,
                        'tax'         => $tax,
                        'rating'      => $rating,
                        // up
                        'reviews'      => $product_info['reviews'],
                        'metka_id'      => $product_info['metka_id'],
                        'attribute_groups'    => $attribute_groups,
                        // end up
                        'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
                    );
                }
			}
			return $this->load->view('extension/module/sirius_wishlist', $data);
		}
	}
}
