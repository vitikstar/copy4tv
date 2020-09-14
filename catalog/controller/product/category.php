<?php
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerProductCategory extends Controller {
    public function declOfNum($num, $titles,$product_total_all) {
        $cases = array(2, 0, 1, 1, 1, 2);

        return sprintf($titles[($num % 100 > 4 && $num % 100 < 20) ? 2 : $cases[min($num % 10, 5)]], $num, $product_total_all);
        return $num . " " . $titles[($num % 100 > 4 && $num % 100 < 20) ? 2 : $cases[min($num % 10, 5)]];
    }
    protected function getLevel(){
        $part=array();
        if(isset($this->request->get['path'])){
            $part = explode("_",(string)$this->request->get['path']);
        }
        return count($part);
    }
	public function index() {
        $data['link_all_show'] = $this->url->link("product/manufacturer");

        $data['main_class'] = 'active';
        $data['catalog_category_wrapper_class'] = '';
        $data['wide_class'] = '';
        $data['price_class'] = '';
        $data['button-view-type'] = '';
        if(isset($this->session->data['button-view-type'])){
            $data['button-view-type'] = $this->session->data['button-view-type'];
            if($this->session->data['button-view-type']=='button-price-catalog'){
                $data['main_class'] = '';
                $data['wide_class'] = '';
                $data['price_class'] = 'active';
                $data['catalog_category_wrapper_class'] = 'price-catalog wide-price-catalog';
            }elseif($this->session->data['button-view-type']=='button-wide-catalog'){
                $data['main_class'] = '';
                $data['wide_class'] = 'active';
                $data['price_class'] = '';
                $data['catalog_category_wrapper_class'] = 'wide-catalog wide-price-catalog';
            }elseif($this->session->data['button-view-type']=='button-main-catalog'){
                $data['main_class'] = 'active';
                $data['wide_class'] = '';
                $data['price_class'] = '';
                $data['catalog_category_wrapper_class'] = '';
            }
        }else{
            $data['main_class'] = 'active';
            $data['wide_class'] = '';
            $data['price_class'] = '';
            $data['catalog_category_wrapper_class'] = '';
        }

        $this->load->language('product/category');

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

        ////
        $config_language_id = (int)$this->config->get('config_language_id');
        $this->load->model('setting/module');
        $module_id = 52; // html banner opt buy
        $html_banner_opt_buy= $this->model_setting_module->getModule($module_id);
        $data['html_banner_opt_buy'] = '';
        if ($html_banner_opt_buy && $html_banner_opt_buy['status']) {
            if ($html_banner_opt_buy['module_description'][$config_language_id]['description']) {
                $data['html_banner_opt_buy'] = html_entity_decode($html_banner_opt_buy['module_description'][$config_language_id]['description'], ENT_QUOTES, 'UTF-8');
            }
        }
        ////
		
		$data['text_empty'] = $this->language->get('text_empty');
		$data['text_model'] = $this->language->get('text_model');


		if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
			$this->document->setRobots('noindex,follow');
		} else {
			$filter = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
			$this->document->setRobots('noindex,follow');
		} else {
			$sort = 'p.sort_order';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
			$this->document->setRobots('noindex,follow');
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
			$this->document->setRobots('noindex,follow');
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
			$this->document->setRobots('noindex,follow');
		} else {
			$limit = $this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit');
		}

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

//        $data['breadcrumbs'][] = array(
//			'text' => $this->language->get('text_home'),
//			'href' => $this->url->link('common/home')
//		);

        $this->load->model("account/wishlist");

		if (isset($this->request->get['path'])) {
			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$path = '';

			$parts = explode('_', (string)$this->request->get['path']);

			$category_id = (int)array_pop($parts);
			$parent_category_id = (isset($parts[0])) ? (int)$parts[0] : $category_id;

			foreach ($parts as $path_id) {
				if (!$path) {
					$path = (int)$path_id;
				} else {
					$path .= '_' . (int)$path_id;
				}

				$category_info = $this->model_catalog_category->getCategory($path_id);

				if ($category_info) {
					$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('product/category', 'path=' . $path . $url)
					);
				}
			}
		} else {
			$category_id = 0;
		}

		$category_info = $this->model_catalog_category->getCategory($category_id);


		
		if ($category_info) {

			if ($category_info['meta_title']) {
				$this->document->setTitle($category_info['meta_title']);
			} else {
				$this->document->setTitle($category_info['name']);
			}
			
			if ($category_info['noindex'] <= 0) {
				$this->document->setRobots('noindex,follow');
			}

			$data['category_description'] = html_entity_decode($category_info['description']);

			if (isMobile) {
                $data['heading_title'] = $category_info['name'];
            } else {
                if ($category_info['meta_h1']) {
                    $data['heading_title'] = $this->language->get('heading_title_all')." ".mb_strtolower($category_info['meta_h1']);
                } else {
                    $data['heading_title'] = $this->language->get('heading_title_all')." ".mb_strtolower($category_info['name']);
                }
            }

			
			$this->document->setDescription($category_info['meta_description']);
			$this->document->setKeywords($category_info['meta_keyword']);

			$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));

			// Set the last category breadcrumb
			$data['breadcrumbs'][] = array(
				'text' => $category_info['name'],
				'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'])
			);

			if ($category_info['image']) {
				$data['thumb'] = $this->model_tool_image->resize($category_info['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_category_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_category_height'));
			} else {
				$data['thumb'] = '';
			}

			//$data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');
			$data['compare'] = $this->url->link('product/compare');

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

            /**

			$data['categories'] = array();

			$results = $this->model_catalog_category->getCategories($category_id);

			foreach ($results as $result) {
				$filter_data = array(
					'filter_category_id'  => $result['category_id'],
					'filter_sub_category' => true
				);

				$data['categories'][] = array(
					'name' => $result['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
					'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '_' . $result['category_id'] . $url)
				);
			}
            */
            $category_info_4level = $this->model_catalog_category->getCategory($category_id);
            //$category_info_4level = $this->model_catalog_category->getCategory($parent_category_id);
            if ($category_info_4level['name']) {
                $this->registry->set('select_category', $category_info_4level['name']);
            } else {
                $this->registry->set('select_category', $category_info_4level['meta_h1']);
            }
			$data['category4level'] = ($this->getLevel()<3) ? $this->load->controller("extension/module/category4level") : '';
			$data['category4level'] = (isMobile) ? '' : $this->load->controller("extension/module/category4level");


			$filter_data = array(
				'filter_category_id' => $category_id,
				'filter_filter'      => $filter,
				'sort'               => $sort,
				'order'              => $order,
				'start'              => ($page - 1) * $limit,
				'limit'              => $limit
			);


			$product_total = $this->model_catalog_product->getTotalProducts($filter_data);
			$product_total_all = $this->model_catalog_product->getTotalProducts(
			    array(
                'filter_category_id' => $category_id,
            )
            );
			$this->declOfNum($product_total,[$this->language->get('total_products1'),$this->language->get('total_products2'),$this->language->get('total_products3')], $product_total_all);
            $data['total_text_ocfilter'] = $this->declOfNum($product_total,[$this->language->get('total_products1'),$this->language->get('total_products2'),$this->language->get('total_products3')], $product_total_all);
			$results = $this->model_catalog_product->getProducts($filter_data);
            $wishlist =  $this->model_account_wishlist->getWishlist();

            $this->load->model('extension/module/p_review');

                foreach ($results as $result) {
                    if ($result['image']) {
                        $image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
                    } else {
                        $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
                    }

                    if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                        $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                    } else {
                        $price = false;
                    }
                    if (($this->customer->isLogged() || !$this->config->get('config_customer_price')) and $result['metka_id']=='super_price') {
                        $super_price = $this->currency->format($this->tax->calculate($result['super_price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                    } else {
                        $super_price = false;
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

                    // up
                    $attribute_groups = $this->model_catalog_product->getProductAttributes($result['product_id']);
                    // end up


                    if(isset($this->session->data['compare'][$category_id])){
                        $compare_count=(in_array($result['product_id'],$this->session->data['compare'][$category_id])) ? count($this->session->data['compare'][$category_id]) : '';
                    }else{
                        $compare_count='';
                    }

                    $data['products'][] = array(
                        'product_id'  => $result['product_id'],
                        'model'      => $result['model'],
                        'thumb'       => $image,
                        'name'        => $result['name'],
                        'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                        'price'       => $price,
                        'super_price'       => $super_price,
                        'special'     => $special,
                        'tax'         => $tax,
                        'class_name'         => (in_array($result['product_id'],$wishlist)) ? 'products-wish-btn-active' : 'products-wish-btn',
                        'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
                        'rating'      => $result['rating'],
                        'compare_count'      => $compare_count,
                        // up
                        'reviews'      => $this->model_extension_module_p_review->getTotalReviews($result['product_id']),
                        'metka_id'      => $result['metka_id'],
                        'attribute_groups'    => $attribute_groups,
                        //'options'    => $this->model_catalog_product->getProductOption($result['product_id']),
                        // end up
                        'href'        => $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'] . $url)
					);
				}

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['sorts'] = array();

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_default'),
				'value' => 'p.sort_order-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.sort_order&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=DESC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_asc'),
				'value' => 'p.price-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_desc'),
				'value' => 'p.price-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=DESC' . $url)
			);

			if ($this->config->get('config_review_status')) {
				$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=DESC' . $url)
				);

				$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_asc'),
					'value' => 'rating-ASC',
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=ASC' . $url)
				);
			}

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_asc'),
				'value' => 'p.model-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_desc'),
				'value' => 'p.model-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=DESC' . $url)
			);

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$data['limits'] = array();

			$limits = array_unique(array($this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit'), 25, 50, 75, 100));

			sort($limits);

			foreach($limits as $value) {
				$data['limits'][] = array(
					'text'  => $value,
					'value' => $value,
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=' . $value)
				);
			}

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&page={page}');

			$data['pagination'] = $pagination->render();

			$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

			// http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
			if ($page == 1) {
			    $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id']), 'canonical');
			} else {
				$this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'] . '&page='. $page), 'canonical');
			}
			
			if ($page > 1) {
			    $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'] . (($page - 2) ? '&page='. ($page - 1) : '')), 'prev');
			}

			if ($limit && ceil($product_total / $limit) > $page) {
			    $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'] . '&page='. ($page + 1)), 'next');
			}

			$data['sort'] = $sort;
			$data['order'] = $order;
			$data['limit'] = $limit;

			$data['continue'] = $this->url->link('common/home');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');


            $data['content_top'] = ($this->getLevel()<3) ? $this->load->controller('common/content_top') : '';
            $data['content_top'] = (isMobile) ? '' : $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');

$data['header'] = $this->load->controller('common/header');
$data['column_megamenu'] = $this->load->controller('common/column_megamenu');
            /**
             * сортування на мобільному
             */
            $this->load->language('product/category');
            if($data['order']=='ASC'){
                if($data['sort']=='p.sort_order'){
                    $data['sort_text'] = $this->language->get('text_default');
                }else if($data['sort']=='pd.name'){
                    $data['sort_text'] = $this->language->get('text_name_asc');
                }else if($data['sort']=='p.price'){
                    $data['sort_text'] = $this->language->get('text_price_asc');
                }else if($data['sort']=='rating'){
                    $data['sort_text'] = $this->language->get('text_rating_asc');
                }else if($data['sort']=='p.model'){
                    $data['sort_text'] = $this->language->get('text_model_asc');
                }
            }else if($data['order']=='DESC'){
                if($data['sort']=='pd.name'){
                    $data['sort_text'] = $this->language->get('text_name_desc');
                }else if($data['sort']=='p.price'){
                    $data['sort_text'] = $this->language->get('text_price_desc');
                }else if($data['sort']=='rating'){
                    $data['sort_text'] = $this->language->get('text_rating_desc');
                }else if($data['sort']=='p.model'){
                    $data['sort_text'] = $this->language->get('text_model_desc');
                }
            }
            $data['sort_text_title'] = $this->language->get('text_sort');
            $data['text_total_filter_title'] = (isset($this->session->data['text_total_filter'])) ? $this->session->data['text_total_filter'] : '';



            /**
             * сортування на мобільному end
             */
			$this->response->setOutput($this->load->view('product/category', $data));
		} else {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
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
				'href' => $this->url->link('product/category', $url)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = ($this->getLevel()<3) ? $this->load->controller('common/content_top') : '';
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');

$data['header'] = $this->load->controller('common/header');
$data['column_megamenu'] = $this->load->controller('common/column_megamenu');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}
    public function showMore() {
        $this->load->language('product/category');
        $json = array();
        $url = '';
        if (isset($this->request->get['path'])) {

            $parts = explode('_', (string)$this->request->get['path']);

            $category_id = (int)array_pop($parts);

        } else {
            $category_id = 0;
        }
        if (isset($this->request->get['filter'])) {
            $filter = $this->request->get['filter'];
            $url .= '&filter=' . $this->request->get['filter'];
        } else {
            $filter = '';
        }
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
            $url .= '&sort=' . $this->request->get['sort'];
        } else {
            $sort = 'p.sort_order';
        }
        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
            $url .= '&order=' . $this->request->get['order'];
        } else {
            $order = 'ASC';
        }
        $page_arr = explode("page=",$this->request->get['url']);

        $page = $page_arr[1];
        $limit = $this->request->get['limit'];
        $url .= '&limit=' . $limit;
        $filter_data = array(
            'filter_category_id' => $category_id,
            'filter_filter'      => $filter,
            'sort'               => $sort,
            'order'              => $order,
            'start'              => ($page - 1) * $limit,
            'limit'              => $limit
        );

        $product_total2 = $this->model_catalog_product->getTotalProducts($filter_data);


       // $results = $this->model_catalog_product->getProducts($filter_data);
        $pagination = new Pagination();
        $pagination->total = $product_total2;
        $pagination->page = $page;
        $pagination->limit = $limit;
        $pagination->url = $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&page={page}');

        $json['pagination'] = $pagination->render();

       // $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    public function setSession(){
        $this->session->data['button-view-type'] = $_POST['key'];
    }
}
