<?php
class ControllerProductCompare extends Controller {
	public function index() {
		$data = $this->load->language('product/compare');

		$data['text_empty'] = $this->language->get('text_empty');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		if (!isset($this->session->data['compare'])) {
			$this->session->data['compare'] = array();
		}

        $data['compare_add_new_item_icon'] =  $this->url->link('common/home');


        if (isset($this->request->get['category_compare_id'])) {
		    $data['compare_add_new_item_icon'] = $this->url->link('product/category','path=' . $this->request->get['category_compare_id']);

        }


		$this->document->setTitle($this->language->get('text_heading_title'));

		$data['breadcrumbs'] = array();


        $lang = $this->language->get('code');
        $data['lang'] = $lang;
        if ($lang == 'ru') {
            $data['breadcrumbs'][] = array(
                'text' => 'Главная',
                'href' => $this->url->link('common/home')
            );
        } else {
            $data['breadcrumbs'][] = array(
                'text' => 'Головна',
                'href' => $this->url->link('common/home')
            );
        }
//		$data['breadcrumbs'][] = array(
//			'text' => $this->language->get('text_home'),
//			'href' => $this->url->link('common/home')
//		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_heading_title'),
			'href' => $this->url->link('product/compare')
		);

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['review_status'] = $this->config->get('config_review_status');

		$data['products'] = array();

		$data['attribute_groups'] = array();

		if(isset($this->request->get['category_compare_id'])) {
            if (isset($this->session->data['compare'][$this->request->get['category_compare_id']])) {
                foreach ($this->session->data['compare'][$this->request->get['category_compare_id']] as $key => $product_id) {
                    $product_info = $this->model_catalog_product->getProduct($product_id);

                    if ($product_info) {
                        if ($product_info['image']) {
                            $image = $this->model_tool_image->resize($product_info['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_compare_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_compare_height'));
                        } else {
                            $image = false;
                        }

                        if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                            $price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                        } else {
                            $price = false;
                        }

                        if ((float)$product_info['special']) {
                            $special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                        } else {
                            $special = false;
                        }

                        if ($product_info['quantity'] <= 0) {
                            $availability = $product_info['stock_status'];
                        } elseif ($this->config->get('config_stock_display')) {
                            $availability = $product_info['quantity'];
                        } else {
                            $availability = $this->language->get('text_instock');
                        }

                        $attribute_data = array();

                        $attribute_groups = $this->model_catalog_product->getProductAttributes($product_id);

                        foreach ($attribute_groups as $attribute_group) {
                            foreach ($attribute_group['attribute'] as $attribute) {
                                $attribute_data[$attribute['attribute_id']] = $attribute['text'];
                            }
                        }

                        $data['products'][$product_id] = array(
                            'product_id' => $product_info['product_id'],
                            'name' => $product_info['name'],
                            'thumb' => $image,
                            'price' => $price,
                            'special' => $special,
                            'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, 200) . '..',
                            'model' => $product_info['model'],
                            'manufacturer' => $product_info['manufacturer'],
                            'availability' => $availability,
                            'minimum' => $product_info['minimum'] > 0 ? $product_info['minimum'] : 1,
                            'rating' => (int)$product_info['rating'],
                            'reviews' => sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']),
                            'weight' => $this->weight->format($product_info['weight'], $product_info['weight_class_id']),
                            'length' => $this->length->format($product_info['length'], $product_info['length_class_id']),
                            'width' => $this->length->format($product_info['width'], $product_info['length_class_id']),
                            'height' => $this->length->format($product_info['height'], $product_info['length_class_id']),
                            'attribute' => $attribute_data,
                            'category_id' => $this->request->get['category_compare_id'],
                            'href' => $this->url->link('product/product', 'product_id=' . $product_id)
                        );

                        foreach ($attribute_groups as $attribute_group) {
                            $data['attribute_groups'][$attribute_group['attribute_group_id']]['name'] = $attribute_group['name'];

                            foreach ($attribute_group['attribute'] as $attribute) {
                                $data['attribute_groups'][$attribute_group['attribute_group_id']]['attribute'][$attribute['attribute_id']]['name'] = $attribute['name'];
                            }
                        }
                    } else {
                        unset($this->session->data['compare'][$key]);
                    }
                }
            }
        }

		$data['count_products'] = count($data['products']);


		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');

$data['header'] = $this->load->controller('common/header');
$data['column_megamenu'] = $this->load->controller('common/column_megamenu');


        //ocdev_smart_cart_form_data replace modification

		$this->response->setOutput($this->load->view('product/compare', $data));
	}
	public function popup() {
        $data['category_group_compare'] = array();
        $this->load->model('catalog/category');

        if(isset($this->session->data['compare'])){
            foreach ($this->session->data['compare'] as $category_id => $product_array) {
                $category_info = $this->model_catalog_category->getCategory($category_id);

                if ($category_info) {
                    $data['category_group_compare'][$category_id] = array(
                        'href' => $this->url->link('product/compare', 'category_compare_id=' . $category_id),
                        'name_category' => $category_info['name'] . ' (' . count($product_array) . ')',
                    );
                }

            }
        }
        echo $this->load->view('product/compare_popup', $data);
	}

	public function add() {

        $initial_page = $this->request->post['initial_page'];
		$this->load->language('product/compare');

		$json = array();

		if (!isset($this->session->data['compare'])) {
			$this->session->data['compare'] = array();
		}

		if (isset($this->request->post['product_id'])) {
			$product_id = $this->request->post['product_id'];
		} else {
			$product_id = 0;
		}
        $json['add_compare_text']='';
        if($initial_page=='product'){
            $this->load->language('product/product');

            $json['add_compare_text'] = $this->language->get('add_compare_text');

        }
		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id,true);


		if ($product_info) {
		    if(isset($this->session->data['compare'][$product_info['category_id']])){
                if (!in_array($this->request->post['product_id'], $this->session->data['compare'][$product_info['category_id']])) {
                    if (count($this->session->data['compare'][$product_info['category_id']]) >= 4) {
                        array_shift($this->session->data['compare'][$product_info['category_id']]);
                        $json['access'] = sprintf($this->language->get('text_access'), $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']), $product_info['name'], $this->url->link('product/compare','category_id='.$product_info['category_id']), 4);
                    }
                    $this->session->data['compare'][$product_info['category_id']][] = $this->request->post['product_id'];
                }
            }else{
                $this->session->data['compare'][$product_info['category_id']][] = $this->request->post['product_id'];
            }


			$json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']), $product_info['name'], $this->url->link('product/compare','category_compare_id='.$product_info['category_id']));

            $total = 0;
            if(isset($this->session->data['compare'])){
                foreach($this->session->data['compare'] as $item) $total += count($item);
            }

			$json['total'] = sprintf($this->language->get('text_compare'), $total);
			$json['total_number'] = $total;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function remove(){
        $json = array();
        if (isset($this->request->post['remove']) and isset($this->request->post['category_id'])) {
//            print_my($this->request->post);
//            print_my($this->session->data['compare']);
//            exit;
            $key = array_search($this->request->post['remove'], $this->session->data['compare'][$this->request->post['category_id']]);

            if ($key !== false) {
                unset($this->session->data['compare'][$this->request->post['category_id']][$key]);
                if(count($this->session->data['compare'][$this->request->post['category_id']])==0) {
                    unset($this->session->data['compare'][$this->request->post['category_id']]);
                    //$json['redirect'] = $this->url->link('product/category','path=' . $this->request->post['category_id']);
                }

                $total = 0;
                if(isset($this->session->data['compare'])){
                    foreach($this->session->data['compare'] as $item) $total += count($item);
                }

                $json['total'] = sprintf($this->language->get('text_compare'), $total);
                $json['total_number'] = $total;
            }
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
