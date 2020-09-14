<?php

class ControllerExtensionModuleMatrositeLooked extends Controller {
	public function index() {
	    if(isset($this->request->get['route']) && $this->request->get['route'] == 'product/category'){
            $this->load->model('setting/setting');
            $this->load->language('extension/module/matrosite/looked');
            $this->load->model('catalog/product');
            $this->load->model('tool/image');

            $data['text_view_all']      =  $this->language->get('text_view_all');
            $data['text_view_all_link'] =  $this->url->link('product/looked');


            $setting = $this->model_setting_setting->getSetting('module_matrosite_looked');

            if ( !isset($setting['module_matrosite_looked_limit']) ) {
                $setting['module_matrosite_looked_limit'] = 4;
            }

            if ( (int)$setting['module_matrosite_looked_status'] == 1 ) {

                $data['heading_title'] = $this->language->get('matrosite_looked_title');

                if ($this->customer->isLogged()) {
                    $this->load->model('extension/module/looked');
                    $customer_id = $this->customer->getId();
                    $filter_data = array(
                                        'list_looked' => implode(",", $this->model_extension_module_looked->getLookedProducts($customer_id)),
                                    );
                                    $products = $this->model_catalog_product->getProducts($filter_data);
                }else{
                        if (isset($this->session->data['matrosite']['looked'])) {
                            $filter_data = array(
                                'list_looked' => implode(",", $this->session->data['matrosite']['looked']),
                            );
                            $products = $this->model_catalog_product->getProducts($filter_data);
                        } else {
                            $products = array();
                        }
                }

                $data['products'] = array();
                foreach($products as $product_info){
                    if ($product_info) {
                        if ($product_info['image']) {
                            $image = $this->model_tool_image->resize($product_info['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_wishlist_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_wishlist_height'));
                        } else {
                            $image = false;
                        }

                        if ($product_info['quantity'] <= 0) {
                            $stock = $product_info['stock_status'];
                        } elseif ($this->config->get('config_stock_display')) {
                            $stock = $product_info['quantity'];
                        } else {
                            $stock = $this->language->get('text_instock');
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

                        $data['products'][] = array(
                            'product_id' => $product_info['product_id'],
                            'thumb'      => $image,
                            'name'       => $product_info['name'],
                            'model'      => $product_info['model'],
                            'stock'      => $stock,
                            'price'      => $price,
                            'special'    => $special,
                            'href'       => $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
                        );
                    }
                }

                $data['text_model']  = $this->language->get('text_model');


                if ($data['products']) {
                    return $this->load->view('extension/module/matrosite_looked_carousel', $data);
                }

            }
        }else{
            $this->load->model('setting/setting');
            $this->load->language('extension/module/matrosite/looked');
            $this->load->model('catalog/product');
            $this->load->model('tool/image');

            if (isset($this->request->get['sort'])) {
                $sort = $this->request->get['sort'];
                $this->document->setRobots('noindex,follow');
            } else {
                $sort = 'p.sort_order';
            }
            if (isset($this->request->get['page'])) {
                $page = $this->request->get['page'];
                $this->document->setRobots('noindex,follow');
            } else {
                $page = 1;
            }
            if (isset($this->request->get['order'])) {
                $order = $this->request->get['order'];
                $this->document->setRobots('noindex,follow');
            } else {
                $order = 'ASC';
            }
            if (isset($this->request->get['limit'])) {
                $limit = (int)$this->request->get['limit'];
                $this->document->setRobots('noindex,follow');
            } else {
                $limit = $this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit');
            }
            if (isset($this->request->get['filter'])) {
                $filter = $this->request->get['filter'];
                $this->document->setRobots('noindex,follow');
            } else {
                $filter = '';
            }
            $setting = $this->model_setting_setting->getSetting('module_matrosite_looked');

            if ( !isset($setting['module_matrosite_looked_limit']) ) {
                $setting['module_matrosite_looked_limit'] = 4;
            }

            if ( (int)$setting['module_matrosite_looked_status'] == 1 ) {
                $data['heading_title'] = $this->language->get('matrosite_looked_title');
                    $this->load->model('extension/module/looked');

                    if ($this->customer->isLogged()) {
                        $this->load->model('extension/module/looked');
                        $customer_id = $this->customer->getId();
                        $filter_data = array(
                            'list_looked' => implode(",", $this->model_extension_module_looked->getLookedProducts($customer_id)),
                            'filter_filter' => $filter,
                            'sort' => $sort,
                            'order' => $order,
                            'start' => ($page - 1) * $limit,
                            'limit' => $limit,
                        );
                        $products = $this->model_catalog_product->getProducts($filter_data);
                        $product_total = $this->model_catalog_product->getTotalProducts($filter_data);
                    }else{
                        if (isset($this->session->data['matrosite']['looked'])) {
                            $filter_data = array(
                                'list_looked' => implode(",", $this->session->data['matrosite']['looked']),
                                'filter_filter' => $filter,
                                'sort' => $sort,
                                'order' => $order,
                                'start' => ($page - 1) * $limit,
                                'limit' => $limit,
                            );
                            $products = $this->model_catalog_product->getProducts($filter_data);
                            $product_total = $this->model_catalog_product->getTotalProducts($filter_data);
                        } else {
                            $products = array();
                            $product_total = 0;
                        }
                    }


                $data['products'] = array();
                foreach($products as $product_info){
                    if ($product_info) {
                        if ($product_info['image']) {
                            $image = $this->model_tool_image->resize($product_info['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_wishlist_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_wishlist_height'));
                        } else {
                            $image = false;
                        }

                        if ($product_info['quantity'] <= 0) {
                            $stock = $product_info['stock_status'];
                        } elseif ($this->config->get('config_stock_display')) {
                            $stock = $product_info['quantity'];
                        } else {
                            $stock = $this->language->get('text_instock');
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

                        $data['products'][] = array(
                            'product_id' => $product_info['product_id'],
                            'thumb'      => $image,
                            'name'       => $product_info['name'],
                            'model'      => $product_info['model'],
                            'stock'      => $stock,
                            'price'      => $price,
                            'special'    => $special,
                            'href'       => $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
                        );
                    }
                }

                $data['text_model']  = $this->language->get('text_model');


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
                    'href'  => $this->url->link('account/wishlist', 'sort=p.sort_order&order=ASC' . $url)
                );

                $data['sorts'][] = array(
                    'text'  => $this->language->get('text_name_asc'),
                    'value' => 'pd.name-ASC',
                    'href'  => $this->url->link('account/wishlist', 'sort=pd.name&order=ASC' . $url)
                );

                $data['sorts'][] = array(
                    'text'  => $this->language->get('text_name_desc'),
                    'value' => 'pd.name-DESC',
                    'href'  => $this->url->link('account/wishlist', 'sort=pd.name&order=DESC' . $url)
                );

                $data['sorts'][] = array(
                    'text'  => $this->language->get('text_price_asc'),
                    'value' => 'p.price-ASC',
                    'href'  => $this->url->link('account/wishlist', 'sort=p.price&order=ASC' . $url)
                );

                $data['sorts'][] = array(
                    'text'  => $this->language->get('text_price_desc'),
                    'value' => 'p.price-DESC',
                    'href'  => $this->url->link('account/wishlist', 'sort=p.price&order=DESC' . $url)
                );

                if ($this->config->get('config_review_status')) {
                    $data['sorts'][] = array(
                        'text'  => $this->language->get('text_rating_desc'),
                        'value' => 'rating-DESC',
                        'href'  => $this->url->link('account/wishlist', 'sort=rating&order=DESC' . $url)
                    );

                    $data['sorts'][] = array(
                        'text'  => $this->language->get('text_rating_asc'),
                        'value' => 'rating-ASC',
                        'href'  => $this->url->link('account/wishlist', 'sort=rating&order=ASC' . $url)
                    );
                }

                $data['sorts'][] = array(
                    'text'  => $this->language->get('text_model_asc'),
                    'value' => 'p.model-ASC',
                    'href'  => $this->url->link('account/wishlist', 'sort=p.model&order=ASC' . $url)
                );

                $data['sorts'][] = array(
                    'text'  => $this->language->get('text_model_desc'),
                    'value' => 'p.model-DESC',
                    'href'  => $this->url->link('account/wishlist', 'sort=p.model&order=DESC' . $url)
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
                        'href'  => $this->url->link('account/wishlist', $url . 'limit=' . $value)
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
                $pagination->url = $this->url->link('account/wishlist',  $url . '&page={page}');

                $data['pagination'] = $pagination->render();

                $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

                if ($page == 1) {
                    $this->document->addLink($this->url->link('account/wishlist'), 'canonical');
                } else {
                    $this->document->addLink($this->url->link('account/wishlist', 'page='. $page), 'canonical');
                }

                if ($page > 1) {
                    $this->document->addLink($this->url->link('account/wishlist',  (($page - 2) ? '&page='. ($page - 1) : '')), 'prev');
                }

                if ($limit && ceil($product_total / $limit) > $page) {
                    $this->document->addLink($this->url->link('account/wishlist', 'page='. ($page + 1)), 'next');
                }

                $data['sort'] = $sort;
                $data['order'] = $order;
                $data['limit'] = $limit;

                if ($data['products']) {
                    return $this->load->view('extension/module/matrosite_looked', $data);
                }

            }
        }
	}
}