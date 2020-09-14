<?php
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerProductProduct extends Controller {
	private $error = array();

    public function test(){
        $result=$this->db->query("SELECT * FROM oc_review");

        foreach ($result->rows as $row){
            $sql = "INSERT INTO " . DB_PREFIX . "p_review SET name = '" . $this->db->escape($row['author']) . "',text = '" . $this->db->escape($row['text']) . "',comment = '" . $this->db->escape($row['answer']) . "',good = '" . $this->db->escape($row['good']) . "',rating = '" . $row['rating'] . "',bad = '" . $this->db->escape($row['bads']). "',store_name = 'Ваш магазин', store_url = 'https://test.uniup.com.ua/', product_id = '" . $row['product_id'] . "', status = '" . $row['status'] . "', language_id = '" . $row['language_id'] . "', date_added = '" . $row['date_added'] . "'";

            $this->db->query($sql);

            $p_review_id = $this->db->getLastId();

            $this->db->query("INSERT INTO " . DB_PREFIX . "p_review_to_store SET p_review_id = '" . (int)$p_review_id . "', store_id = '" . (int)$this->config->get('config_store_id') . "'");
            $this->db->query("INSERT INTO " . DB_PREFIX . "p_review_to_layout SET store_id = '0', p_review_id = '" . (int)$p_review_id . "', layout_id = '0'");
            $seo_url = $this->config->get('module_p_review_seo_url');

            $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$this->config->get('config_store_id') . "', language_id = '" . (int)$this->config->get('config_language_id') . "', query = 'p_review_id=" . (int)$p_review_id . "', keyword = '" . $this->db->escape($seo_url[$this->config->get('config_store_id')][$this->config->get('config_language_id')]) . "-" . (int)$p_review_id . "'");

        }

        exit;
                $result=$this->db->query("SELECT * FROM ostatki_dlya_perekladu AS opdp LEFT JOIN oc_product AS p ON(p.sku=opdp.model)  LEFT JOIN oc_product_description AS pd ON(p.product_id=pd.product_id) WHERE `language_id`=1  ORDER BY date_available DESC");
                echo"<table>";
                foreach ($result->rows as $row){
                    $this->db->query("DELETE FROM `ostatki_dlya_perekladu` WHERE model='".$row['sku']."'");
                    echo"<tr><td>".$row['product_id']."</td><td>".$row['sku']."</td><td>".$row['name']."</td><td>".$row['description']."</td><td>".$row['tag']."</td><td>".$row['meta_title']."</td><td>".$row['meta_description']."</td><td>".$row['meta_keyword']."</td><td>".$row['meta_h1']."</td><td>".$row['date_available']."</td><td>".$row['quantity']."</td></tr>";
                }
                echo"</table>";
    }




    public function index() {
        $this->load->model('setting/setting');
        $setting = $this->model_setting_setting->getSetting('module_matrosite_looked');

        if (isset($this->session->data['matrosite']['looked'])) {
            $products = $this->session->data['matrosite']['looked'];
        } else {
            $products = array();
        }

        if (isset($this->request->get['product_id'])) {
            $isset = false; // Флаг присутствия текущего товара в списке

            foreach($products as $key => $product_id){
                if ($product_id == $this->request->get['product_id']) {
                    $isset = true;
                    unset($products[$key]);
                }
            }

            if (!$isset) {
                $this->session->data['matrosite']['looked'][] = $this->request->get['product_id'];
            }

            // Удаляем излишки
            if (count($this->session->data['matrosite']['looked']) > (int)$setting['module_matrosite_looked_limit']) {
                $iteration = count($this->session->data['matrosite']['looked']) - (int)$setting['module_matrosite_looked_limit'];
                for ($i=0; $i<$iteration; $i++){
                    array_shift($this->session->data['matrosite']['looked']);
                }
			}


			if ($this->customer->isLogged()) {
				$this->load->model('extension/module/looked');
				$customer_id = $this->customer->getId();
					$this->model_extension_module_looked->setLookedProducts($customer_id,$this->request->get['product_id']);
			}


        }
        $products = array();

        if (isset($this->request->cookie['viewed_popular_instrument'])) {
            $products = explode(',', $this->request->cookie['viewed_popular_instrument']);
        }
        if(isset($this->request->get['path'])){
            $arr=explode("_",$this->request->get['path']);
            if($arr[0]==157){
                if(isset($this->request->get['product_id'])){
                    $product_id = $this->request->get['product_id'];
                    $products = array_diff($products, array($product_id));
                    array_unshift($products, $product_id);
                    setcookie('viewed_popular_instrument', implode(',',$products), time() + 60 * 60 * 24 * 30, '/', $this->request->server['HTTP_HOST']);
                }
            }
		}

		$this->load->language('product/product');

		$data['breadcrumbs'] = array();


        $data['isMobile'] = false;
		if (isMobile || isTablet) {
            $data['isMobile'] = true;
        }




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

		$this->load->model('catalog/category');

		if (isset($this->request->get['path'])) {
			$path = '';

			$parts = explode('_', (string)$this->request->get['path']);

			$category_id = (int)array_pop($parts);

			foreach ($parts as $path_id) {
				if (!$path) {
					$path = $path_id;
				} else {
					$path .= '_' . $path_id;
				}

				$category_info = $this->model_catalog_category->getCategory($path_id);

				if ($category_info) {
					$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('product/category', 'path=' . $path)
					);
				}
			}

			// Set the last category breadcrumb
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info) {
				$url = '';

				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}

				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}

				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}

				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}

				$data['breadcrumbs'][] = array(
					'text' => $category_info['name'],
					'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url)
				);
			}
		}

		$this->load->model('catalog/manufacturer');

		if (isset($this->request->get['manufacturer_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_brand'),
				'href' => $this->url->link('product/manufacturer')
			);

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id']);

			if ($manufacturer_info) {
				$data['breadcrumbs'][] = array(
					'text' => $manufacturer_info['name'],
					'href' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . $url)
				);
			}
		}

		if (isset($this->request->get['search']) || isset($this->request->get['tag'])) {
			$url = '';

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_search'),
				'href' => $this->url->link('product/search', $url)
			);
		}

		if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
		} else {
			$product_id = 0;
		}

		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);


		if ($product_info) {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $product_info['name'],
				'href' => $this->url->link('product/product', $url . '&product_id=' . $this->request->get['product_id']),
                //up
                'last' => true
                //
			);

			if ($product_info['meta_title']) {
				$this->document->setTitle($product_info['meta_title']);
			} else {
				$this->document->setTitle($product_info['name']);
			}
			
			if ($product_info['noindex'] <= 0) {
				$this->document->setRobots('noindex,follow');
			}
			
			if ($product_info['meta_h1']) {
				$data['heading_title'] = $product_info['meta_h1'];
			} else {
				$data['heading_title'] = $product_info['name'];
			}

            $setting['width']=300;
            $setting['height']=300;
            $setting['limit']=20;
            $setting['heading_title']=sprintf($this->language->get('heading_title_buy_width_product'),$product_info['name']);


			$this->document->setDescription($product_info['meta_description']);
			$this->document->setKeywords($product_info['meta_keyword']);
			$this->document->addLink($this->url->link('product/product', 'product_id=' . $this->request->get['product_id']), 'canonical');
			$this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
			$this->document->addScript('catalog/view/javascript/jquery.popupWindow.js');
			$this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
			$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment.min.js');
			$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment-with-locales.min.js');
			$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
			$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

			$data['text_minimum'] = sprintf($this->language->get('text_minimum'), $product_info['minimum']);
			$data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', true), $this->url->link('account/register', '', true));

			$this->load->model('catalog/review');
			$this->load->model('extension/module/p_review');
            $total_reviews =  $this->model_extension_module_p_review->getTotalReviews($product_id);
			$data['tab_review'] = sprintf($this->language->get('tab_review'), $total_reviews);
			$data['reviews'] = sprintf($this->language->get('reviews'), $total_reviews);


            $this->load->model("account/wishlist");
            $wishlist =  $this->model_account_wishlist->getWishlist();

            $add_compare_text = sprintf($this->language->get('add_compare_text2'), $product_id);
            $add_compare_active = false;
            $add_wishlist_text = sprintf($this->language->get('add_wishlist_text2'), $product_id);
            $add_wishlist_active = false;

            $path_array = (isset($this->request->get['path'])) ? explode("_",$this->request->get['path']) : array();
            $category_id_compare = end($path_array);
            if(isset($this->session->data['compare'][$category_id_compare])){
                if (in_array($product_id, $this->session->data['compare'][$category_id_compare])) {
                    $add_compare_text = sprintf($this->language->get('add_compare_text'), $this->url->link('product/compare', '', true));
                    $add_compare_active = true;
                }
            }

            if(in_array($product_id,$wishlist)){
                $add_wishlist_text = sprintf($this->language->get('add_wishlist_text'), $this->url->link('account/wishlist', '', true));
                $add_wishlist_active = true;
            }

			$data['add_compare_text'] = $add_compare_text;
			$data['add_compare_active'] = $add_compare_active;
			$data['add_wishlist_text'] = $add_wishlist_text;
			$data['add_wishlist_active'] = $add_wishlist_active;
			$data['all_about_product'] = $this->language->get('all_about_product');
			$data['video_review'] = $this->language->get('all_about_product');
			$data['text_model'] = $this->language->get('text_model');
			$data['text_pre_order'] = $this->language->get('text_pre_order');
			$data['text_delivery_expected'] = $this->language->get('text_delivery_expected');
			$data['text_buy_one_click'] = $this->language->get('text_buy_one_click');
			$data['text_short_quantity'] = $this->language->get('text_short_quantity');
			$data['text_product_in_stock'] = $this->language->get('text_product_in_stock');
			$data['text_not_available'] = $this->language->get('text_not_available');
			$data['text_video_review'] = $this->language->get('text_video_review');
			$data['text_share'] = $this->language->get('text_share');
			$data['text_product_in_stock_messages'] = $this->language->get('text_product_in_stock_messages');
			$data['text_zakazat'] = $this->language->get('text_zakazat');
			$data['comment_review'] = $this->language->get('comment_review');
			$data['name_surname_review'] = $this->language->get('name_surname_review');
			$data['email_review'] = $this->language->get('email_review');
			$data['register_review'] = $this->language->get('register_review');
			$data['cancel_review'] = $this->language->get('cancel_review');
			$data['add_review'] = $this->language->get('add_review');


			$data['product_id'] = (int)$this->request->get['product_id'];
			$data['manufacturer'] = $product_info['manufacturer'];
			$data['manufacturers'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_info['manufacturer_id']);
			$data['model'] = $product_info['model'];
			$data['sku'] = $product_info['sku'];
			$data['product_name'] = $product_info['name'];
			$data['reward'] = $product_info['reward'];
			$data['points'] = $product_info['points'];
			$data['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');

            $data['product_in_stock'] = false;
            $data['delivery_expected'] = false;
            $data['out_of_stock'] = false;
            if ($product_info['quantity'] <= 0) {
                $data['product_in_stock'] = false;
                $data['delivery_expected'] = false;
                $data['out_of_stock'] = true;
                if($product_info['stock_status_id']==8){ //8 це предзаказ
                    $data['product_in_stock'] = false;
                    $data['delivery_expected'] = true;
                    $data['out_of_stock'] = false;
                }
            } else {
                $data['product_in_stock'] = true;
                $data['delivery_expected'] = false;
                $data['out_of_stock'] = false;
            }

			if ($product_info['quantity'] <= 0) {
				$data['stock'] = $product_info['stock_status'];
			} elseif ($this->config->get('config_stock_display')) {
				$data['stock'] = $product_info['quantity'];
			} else {
				$data['stock'] = $this->language->get('text_instock');
			}
            $data['text_quantity'] = $this->language->get('quantity');
            $data['buying_from'] = sprintf($this->language->get('buying_from'),8);

			$this->load->model('tool/image');

			if ($product_info['image']) {
				$data['popup'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_height'));
			} else {
				$data['popup'] = '';
			}

			if ($product_info['image']) {
				$data['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_height'));
			} else {
				$data['thumb'] = '';
			}

			$data['images'] = array();

			$results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);


            //up
            $data['images'][] = array(
                'popup' => $this->model_tool_image->resize($product_info['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_height')),
                'thumb' => $this->model_tool_image->resize($product_info['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_height')),


                'index' => 0,
                'popup_main' => $data['popup'],
                'thumb_main' => $data['thumb']
            );
            //end up

			$count = 1;
			foreach ($results as $result) {
				$data['images'][] = array(
					'popup' => $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_height')),
					'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_height')),

                    //up
                    'index' => $count,
                    'popup_main' => $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_height')),
                    'thumb_main' => $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_height'))
                    //end up
				);
				$count++;
			}

			// product video

            $video_hash_youtube = (!empty($product_info['video_hash_youtube'])) ? $product_info['video_hash_youtube'] : 'RG9TMn1FJzc';


            $product_video = '';

            if ($product_video) {
                $data['product_video'] = $product_video;
                $count += 1;
                $data['product_video_index'] = $count;
            }

            $data['metka_id'] = $product_info['metka_id'];

            // end  product video




            // Если больше 5 блоков вертикально, то подгружаем слайдер для вертикального перелистывания изображений thumbs

            $thumbnails_images_slider = false;
            if (!$data['isMobile']) {
                if ($count > 5) {
                    $thumbnails_images_slider = true;
                    $this->document->addStyle('catalog/view/javascript/slick/slick.css');
                    $this->document->addStyle('catalog/view/javascript/slick/slick-theme.css');
                    $this->document->addScript('catalog/view/javascript/slick/slick.min.js');
                }
                $data['thumbnails_images_slider'] = $thumbnails_images_slider;
            }
            //

            if ($data['isMobile']) {
                $this->document->addStyle('catalog/view/javascript/slick/slick.css');
                $this->document->addStyle('catalog/view/javascript/slick/slick-theme.css');
                $this->document->addScript('catalog/view/javascript/slick/slick.min.js');
            }



			if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
				$data['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
			} else {
				$data['price'] = false;
			}
            $data['site_price'] = $this->currency->format($this->tax->calculate($product_info['site_price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);

			if ((float)$product_info['special']) {
				$data['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
			} else {
				$data['special'] = false;
			}

			if ($this->config->get('config_tax')) {
				$data['tax'] = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
			} else {
				$data['tax'] = false;
			}

			$discounts = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);

			$data['discounts'] = array();

			foreach ($discounts as $discount) {
				$data['discounts'][] = array(
					'quantity' => $discount['quantity'],
					'price'    => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'])
				);
			}

			$data['options'] = array();
			$data['options_color'] = array();

			foreach ($this->model_catalog_product->getProductOptions($this->request->get['product_id']) as $option) {
				$product_option_value_data = array();

				foreach ($option['product_option_value'] as $option_value) {
					if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
						if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
							$price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false), $this->session->data['currency']);
						} else {
							$price = false;
						}

						$product_option_value_data[] = array(
							'product_option_value_id' => $option_value['product_option_value_id'],
							'option_value_id'         => $option_value['option_value_id'],
							'name'                    => $option_value['name'],
							'for_color'                    => $option_value['for_color'],
							'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
							'price'                   => $price,
							'price_prefix'            => $option_value['price_prefix']
						);
					}
				}

				$data['options'][] = array(
					'product_option_id'    => $option['product_option_id'],
					'product_option_value' => $product_option_value_data,
					'option_id'            => $option['option_id'],
					'name'                 => $option['name'],
					'type'                 => $option['type'],
					'value'                => $option['value'],
					'required'             => $option['required']
				);
				if($option['option_id']==30){
                    $data['options_color'] = array(
                        'product_option_id'    => $option['product_option_id'],
                        'product_option_value' => $product_option_value_data,
                        'option_id'            => $option['option_id'],
                        'name'                 => $option['name'],
                        'type'                 => $option['type'],
                        'value'                => $option['value'],
                        'required'             => $option['required']
                    );
                }
			}


			if ($product_info['minimum']) {
				$data['minimum'] = $product_info['minimum'];
			} else {
				$data['minimum'] = 1;
			}

			$data['review_status'] = $this->config->get('config_review_status');

			if ($this->config->get('config_review_guest') || $this->customer->isLogged()) {
				$data['review_guest'] = true;
			} else {
				$data['review_guest'] = false;
			}

			if ($this->customer->isLogged()) {
				$data['customer_name'] = $this->customer->getFirstName() . '&nbsp;' . $this->customer->getLastName();
			} else {
				$data['customer_name'] = '';
			}

            $this->load->model('extension/module/p_review');
            $data['text_one_click_mask']           = ($this->customer->isLogged()) ? $this->customer->getTelephone() : '+38 (ХХХ) ХХХ-ХХ-ХХ';
			$data['text_one_click_placeholder']    = ($this->customer->isLogged()) ? $this->customer->getFirstName().' '.$this->customer->getLastName() : 'Ваше имя';

			$data['report_appearance_telephone'] = ($this->customer->isLogged()) ? $this->customer->getTelephone() : '';
			$data['report_appearance_name'] = ($this->customer->isLogged()) ? $this->customer->getFirstName() . ' ' . $this->customer->getLastName() : '';

            $data['one_click_modal_text']    = $this->language->get('one_click_modal_text');
            $data['one_click_fast_order_text']    = $this->language->get('one_click_fast_order_text');
            $data['text_one_click_name_customer']    = $this->language->get('text_one_click_name_customer');

			$data['rating'] = $this->model_extension_module_p_review->getRating((int)$this->request->get['product_id']);
            $data['total_reviews'] =  $this->model_extension_module_p_review->getTotalReviews((int)$this->request->get['product_id']);
            $data['consumer_review'] = sprintf($this->language->get('consumer_review'), $data['total_reviews']);
			// Captcha
			if ($this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
				$data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'));
			} else {
				$data['captcha'] = '';
			}

			$data['share'] = $this->url->link('product/product', 'product_id=' . (int)$this->request->get['product_id']);
			$data['info_product'] = $this->load->controller('product/info_product');
			$data['reviews_html'] = $this->load->controller('product/reviews',['reviews'=>(int)$product_info['reviews']]);

			$data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);

			$data['products'] = array();

			$results = $this->model_catalog_product->getProductRelated($this->request->get['product_id']);

			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_height'));
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
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}

				$data['products'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $rating,
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}


			$data['tags'] = array();

			if ($product_info['tag']) {
				$tags = explode(',', $product_info['tag']);

				foreach ($tags as $tag) {
					$data['tags'][] = array(
						'tag'  => trim($tag),
						'href' => $this->url->link('product/search', 'tag=' . trim($tag))
					);
				}
			}

			$data['recurrings'] = $this->model_catalog_product->getProfiles($this->request->get['product_id']);

			$this->model_catalog_product->updateViewed($this->request->get['product_id']);
			
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');

$data['header'] = $this->load->controller('common/header');
$data['column_megamenu'] = $this->load->controller('common/column_megamenu');

            $data['buy_width_product'] = $this->load->controller('product/buy_width_product',$setting);

			$this->response->setOutput($this->load->view('product/product', $data));
		} else {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('product/product', $url . '&product_id=' . $product_id),
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');

$data['header'] = $this->load->controller('common/header');
$data['column_megamenu'] = $this->load->controller('common/column_megamenu');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}

	public function review() {
		$this->load->language('product/product');
        $_['sort_order_rating_asc']     = 'Рейтинг (Починаючи з нижчого)';
        $_['sort_order_rating_desc']     = 'Рейтинг (Починаючи з високого)';
        $_['sort_order_date_asc']     = 'Починаючи з нових';
        $_['sort_order_date_desc']     = 'Починаючи з старих';
        $_['sort_order_popular_asc']     = 'За популярністю';
        $sort_array = array(
            $this->url->link('product/product', 'product_id=' . (int)$this->request->get['product_id']).'?sort=p.sort_order&amp;order=ASC'=>$this->language->get('sort_order_popular_asc'),
            $this->url->link('product/product', 'product_id=' . (int)$this->request->get['product_id']).'?sort=pd.name&amp;order=ASC'=>$this->language->get('sort_order_date_asc'),
            $this->url->link('product/product', 'product_id=' . (int)$this->request->get['product_id']).'?sort=pd.name&amp;order=DESC'=>$this->language->get('sort_order_date_desc'),
            $this->url->link('product/product', 'product_id=' . (int)$this->request->get['product_id']).'?sort=p.price&amp;order=ASC'=>$this->language->get('sort_order_rating_asc'),
            $this->url->link('product/product', 'product_id=' . (int)$this->request->get['product_id']).'?sort=p.price&amp;order=DESC'=>$this->language->get('sort_order_rating_desc'),
        );
//
//		$this->load->model('catalog/review');
//
//		if (isset($this->request->get['page'])) {
//			$page = $this->request->get['page'];
//		} else {
//			$page = 1;
//		}
//
//		$data['reviews'] = array();
//
//		$review_total = $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id']);
//
//		$results = $this->model_catalog_review->getReviewsByProductId($this->request->get['product_id'], ($page - 1) * 5, 5);
//
//		foreach ($results as $result) {
//			$data['reviews'][] = array(
//				'author'     => $result['author'],
//				'text'       => nl2br($result['text']),
//				'rating'     => (int)$result['rating'],
//				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
//			);
//		}
//
//		$pagination = new Pagination();
//		$pagination->total = $review_total;
//		$pagination->page = $page;
//		$pagination->limit = 5;
//		$pagination->url = $this->url->link('product/product/review', 'product_id=' . $this->request->get['product_id'] . '&page={page}');
//
//		$data['pagination'] = $pagination->render();
//
//		$data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * 5) + 1 : 0, ((($page - 1) * 5) > ($review_total - 5)) ? $review_total : ((($page - 1) * 5) + 5), $review_total, ceil($review_total / 5));

		$this->response->setOutput($this->load->view('product/review', $data));
	}

	public function write() {
		$this->load->language('product/product');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
				$json['error'] = $this->language->get('error_name');
			}

			if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
				$json['error'] = $this->language->get('error_text');
			}

			if (empty($this->request->post['rating']) || $this->request->post['rating'] < 0 || $this->request->post['rating'] > 5) {
				$json['error'] = $this->language->get('error_rating');
			}

			// Captcha
			if ($this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
				$captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');

				if ($captcha) {
					$json['error'] = $captcha;
				}
			}

			if (!isset($json['error'])) {
				$this->load->model('catalog/review');

				$this->model_catalog_review->addReview($this->request->get['product_id'], $this->request->post);

				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function getRecurringDescription() {
		$this->load->language('product/product');
		$this->load->model('catalog/product');

		if (isset($this->request->post['product_id'])) {
			$product_id = $this->request->post['product_id'];
		} else {
			$product_id = 0;
		}

		if (isset($this->request->post['recurring_id'])) {
			$recurring_id = $this->request->post['recurring_id'];
		} else {
			$recurring_id = 0;
		}

		if (isset($this->request->post['quantity'])) {
			$quantity = $this->request->post['quantity'];
		} else {
			$quantity = 1;
		}

		$product_info = $this->model_catalog_product->getProduct($product_id);
		
		$recurring_info = $this->model_catalog_product->getProfile($product_id, $recurring_id);

		$json = array();

		if ($product_info && $recurring_info) {
			if (!$json) {
				$frequencies = array(
					'day'        => $this->language->get('text_day'),
					'week'       => $this->language->get('text_week'),
					'semi_month' => $this->language->get('text_semi_month'),
					'month'      => $this->language->get('text_month'),
					'year'       => $this->language->get('text_year'),
				);

				if ($recurring_info['trial_status'] == 1) {
					$price = $this->currency->format($this->tax->calculate($recurring_info['trial_price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					$trial_text = sprintf($this->language->get('text_trial_description'), $price, $recurring_info['trial_cycle'], $frequencies[$recurring_info['trial_frequency']], $recurring_info['trial_duration']) . ' ';
				} else {
					$trial_text = '';
				}

				$price = $this->currency->format($this->tax->calculate($recurring_info['price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);

				if ($recurring_info['duration']) {
					$text = $trial_text . sprintf($this->language->get('text_payment_description'), $price, $recurring_info['cycle'], $frequencies[$recurring_info['frequency']], $recurring_info['duration']);
				} else {
					$text = $trial_text . sprintf($this->language->get('text_payment_cancel'), $price, $recurring_info['cycle'], $frequencies[$recurring_info['frequency']], $recurring_info['duration']);
				}

				$json['success'] = $text;
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
