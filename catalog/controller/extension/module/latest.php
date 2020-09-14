<?php
class ControllerExtensionModuleLatest extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/latest');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

        $data['text_model'] = $this->language->get('text_model');

		// up
        $this->document->addStyle('catalog/view/javascript/jquery/swiper/css/swiper.min.css');
        $this->document->addStyle('catalog/view/javascript/jquery/swiper/css/opencart.css');
        $this->document->addScript('catalog/view/javascript/jquery/swiper/js/swiper.jquery.js');
        // end up

		$data['products'] = array();

		$filter_data = array(
			'sort'  => 'p.date_added',
			'order' => 'DESC',
			'start' => 0,
			'available' => 1,
			'limit' => $setting['limit']
		);

        $data['text_view_all'] =  sprintf($this->language->get('text_view_all'),$this->url->link('product/latest'));


        $results = $this->model_catalog_product->getProducts($filter_data);
        $this->load->model("account/wishlist");
        $wishlist =  $this->model_account_wishlist->getWishlist();
		if ($results) {
			foreach ($results as $result) {


				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
				}

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = $result['rating'];
				} else {
					$rating = false;
				}

                // up
                $attribute_groups = $this->model_catalog_product->getProductAttributes($result['product_id']);
                // end up
                if(isset($this->session->data['compare'])){
                    $compare_count=(in_array($result['product_id'],$this->session->data['compare'])) ? count($this->session->data['compare']) : '';
                }else{
                    $compare_count='';
                }
				$data['products'][] = array(
					'product_id'         => $result['product_id'],
					'thumb'              => $image,
					'name'               => $result['name'],
					'description'        => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
					'price'              => $price,
					'special'            => $special,
					'rating'             => $rating,
                    'tax'                => $tax,
                    'compare_count'      => $compare_count,
                    'class_name'         => (in_array($result['product_id'],$wishlist)) ? 'products-wish-btn-active' : 'products-wish-btn',
                    // up
                    'reviews'             => $result['reviews'],
                    'attribute_groups'    => $attribute_groups,
					// end up
					'href'                => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}

			return $this->load->view('extension/module/latest', $data);
		}
	}
}
