<?php
class ControllerExtensionModuleOctMegamenu extends Controller {

    // @type: 1 - simple link, 2 - category, 3 - brand, 4 - product, 5 - information, 6 - login block, 7 - custom html block

    public function index() {

        $this->load->language('extension/module/oct_megamenu');

        $this->load->model('extension/module/oct_megamenu');
        $this->load->model('tool/image');

        $oct_megamenu_data = $this->config->get('oct_megamenu_data');
        $data['oct_megamenu_data'] = $oct_megamenu_data;

        $data['text_register'] = $this->language->get('text_register');
        $data['text_logout'] = $this->language->get('text_logout');
        $data['text_forgotten'] = $this->language->get('text_forgotten');
        $data['text_category'] = $this->language->get('text_category');
        $data['text_menu'] = $this->language->get('text_menu');
        $data['text_back'] = $this->language->get('text_back');
        $data['text_info'] = $this->language->get('text_info');
        $data['text_acc'] = $this->language->get('text_acc');
        $data['text_contacts'] = $this->language->get('text_contacts');
        $data['text_settings'] = $this->language->get('text_settings');
        $data['text_all_category'] = $this->language->get('text_all_category');
        $data['text_all_product'] = $this->language->get('text_all_product');
        $data['href_all_product']=$this->url->link('product/category', 'path=10000');

        $data['text_all_product'] = $this->language->get('text_all_product');

        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_password'] = $this->language->get('entry_password');

        $data['button_login'] = $this->language->get('button_login');
        $data['action'] = $this->url->link('account/login', '', 'SSL');
        $data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');
        $data['register'] = $this->url->link('account/register', '', 'SSL');
        $data['logout'] = $this->url->link('account/logout', '', 'SSL');

        $data['login_status'] = $this->customer->isLogged() ? true : false;

        if (isset($this->request->post['ocmm_login_email'])) {
            $data['email'] = $this->request->post['ocmm_login_email'];
        } else {
            $data['email'] = '';
        }

        if (isset($this->request->post['ocmm_login_password'])) {
            $data['password'] = $this->request->post['ocmm_login_password'];
        } else {
            $data['password'] = '';
        }

        $data['not_home'] = false;
        if($_SERVER['REQUEST_URI']!='/') $data['not_home']=true;

        $data['items'] = array();


        $menu_items = $this->cache->get('octemplates.megamenu.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id'));

        if (!$menu_items) {
            $results = $this->model_extension_module_oct_megamenu->getMegamenus();
            foreach ($results as $result) {

                if ($result['image']) {
                    $image = $this->model_tool_image->resize($result['image'], 16, 16);
                } else {
                    $image = false;
                }

                $childrens = array();

                if ($result['item_type'] == 2) { //категорії
                    $children_data = $this->model_extension_module_oct_megamenu->getMegamenuCategory($result['megamenu_id']);

                    $this->load->model('catalog/category');

                    foreach ($children_data as $category_id) {
                        $category_info = $this->model_catalog_category->getCategory($category_id);

                        if ($category_info) {
                            if ($category_info['image']) {
                                $category_image = $this->model_tool_image->resize($category_info['image'], $result['img_width'], $result['img_height']);
                            } else {
                                $category_image = $this->model_tool_image->resize('no-image.png', $result['img_width'], $result['img_height']);
                            }

                            $sub_categories = array();

                            if ($result['sub_categories']) {
                                $category_children = $this->model_catalog_category->getCategories($category_id);
                                foreach ($category_children as $child) {

                                    $category_info_child_3_level = $this->model_catalog_category->getCategories($child['category_id']);
                                    $child_3_level = array();
                                    if($category_info_child_3_level){
                                        $i=0;
                                        foreach ($category_info_child_3_level as $child3) {
                                            if ($child3['image']) {
                                                $sub3_category_image = $this->model_tool_image->resize($child3['image'], $result['img_width'], $result['img_height']);
                                            } else {
                                                $sub3_category_image = $this->model_tool_image->resize('no-image.png', $result['img_width'], $result['img_height']);
                                            }
                                            $child_3_level[$i]['name'] = $child3['name'];
                                            $child_3_level[$i]['sort_order']  = $child3['sort_order'];
                                            $child_3_level[$i]['thumb'] = ($result['show_img']) ? $sub3_category_image : false;
                                            $child_3_level[$i]['href']  = $this->url->link('product/category', 'path=' . $category_id . '_' . $child3['category_id']);
                                            $child_3_level[$i]['count']  = $this->num_decline( $this->model_catalog_product->getTotalProducts(['filter_category_id'  => $child3['category_id']]), 'товар, товара, товаров' );
                                            $i++;
                                        }
                                    }

                                    if ($child['image']) {
                                        $sub_category_image = $this->model_tool_image->resize($child['image'], $result['img_width'], $result['img_height']);
                                    } else {
                                        $sub_category_image = $this->model_tool_image->resize('no-image.png', $result['img_width'], $result['img_height']);
                                    }

                                    $sub_categories[] = array(
                                        'name'  => $child['name'],
                                        'child_3_level'  => $child_3_level,
                                        'sort_order'   => $child['sort_order'],
                                        'thumb' => ($result['show_img']) ? $sub_category_image : false,
                                        'count' => $this->num_decline( $this->model_catalog_product->getTotalProducts(['filter_category_id'  => $child['category_id']]), 'товар, товара, товаров' ),
                                        'href'  => $this->url->link('product/category', 'path=' . $category_id . '_' . $child['category_id'])

                                    );
                                }
                            }

                            $cs_sort_order = array();

                            foreach ($sub_categories as $key => $value) {
                                $cs_sort_order[$key] = $value['name'];
                            }

                            array_multisort($cs_sort_order, SORT_ASC, $sub_categories);

                            $childrens[] = array(
                                'category_id'  => $category_info['category_id'],
                                'sort_order'   => $category_info['sort_order'],
                                'thumb'        => ($result['show_img']) ? $category_image : false,
                                'name'         => $category_info['name'],
                                'children' 		 => $sub_categories,
                                'href'         => $this->url->link('product/category', 'path=' . $category_info['category_id'])
                            );
                        }
                    }

                    $c_sort_order = array();

                    foreach ($childrens as $key => $value) {
                        $c_sort_order[$key] = $value['name'];
                    }

                    array_multisort($c_sort_order, SORT_ASC, $childrens);
                }

                if ($result['item_type'] == 3) {
                    $children_data = $this->model_extension_module_oct_megamenu->getMegamenuManufacturer($result['megamenu_id']);

                    $this->load->model('catalog/manufacturer');

                    foreach ($children_data as $manufacturer_id) {
                        $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);

                        if ($manufacturer_info) {
                            if ($manufacturer_info['image']) {
                                $manufacturer_image = $this->model_tool_image->resize($manufacturer_info['image'], $result['img_width'], $result['img_height']);
                            } else {
                                $manufacturer_image = $this->model_tool_image->resize('no-image.png', $result['img_width'], $result['img_height']);
                            }

                            $childrens[] = array(
                                'manufacturer_id'  => $manufacturer_info['manufacturer_id'],
                                'sort_order'  		 => $manufacturer_info['sort_order'],
                                'thumb'       		 => ($result['show_img']) ? $manufacturer_image : false,
                                'name'        		 => $manufacturer_info['name'],
                                'href'        		 => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer_info['manufacturer_id'])
                            );
                        }
                    }

                    $m_sort_order = array();

                    foreach ($childrens as $key => $value) {
                        $m_sort_order[$key] = $value['name'];
                    }

                    array_multisort($m_sort_order, SORT_ASC, $childrens);
                }

                if ($result['item_type'] == 4) {
                    $children_data = $this->model_extension_module_oct_megamenu->getMegamenuProduct($result['megamenu_id']);

                    $this->load->model('catalog/product');

                    foreach ($children_data as $product_id) {
                        $product_info = $this->model_catalog_product->getProduct($product_id);

                        if ($product_info) {
                            if ($product_info['image']) {
                                $product_image = $this->model_tool_image->resize($product_info['image'], $result['img_width'], $result['img_height']);
                            } else {
                                $product_image = $this->model_tool_image->resize('no-image.png', $result['img_width'], $result['img_height']);
                            }

                            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                                $product_price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                            } else {
                                $product_price = false;
                            }

                            if ((float)$product_info['special']) {
                                $ptoduct_special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                            } else {
                                $ptoduct_special = false;
                            }

                            $childrens[] = array(
                                'product_id'  => $product_info['product_id'],
                                'sort_order'  => $product_info['sort_order'],
                                'thumb'       => ($result['show_img']) ? $product_image : false,
                                'name'        => $product_info['name'],
                                'price'       => $product_price,
                                'special'     => $ptoduct_special,
                                'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
                            );
                        }
                    }

                    $p_sort_order = array();

                    foreach ($childrens as $key => $value) {
                        $p_sort_order[$key] = $value['name'];
                    }

                    array_multisort($p_sort_order, SORT_ASC, $childrens);
                }

                if ($result['item_type'] == 5) {
                    $children_data = $this->model_extension_module_oct_megamenu->getMegamenuInformation($result['megamenu_id']);

                    $this->load->model('catalog/information');

                    foreach ($children_data as $information_id) {
                        $information_info = $this->model_catalog_information->getInformation($information_id);
                        if ($information_info) {
                            $childrens[] = array(
                                'href' 	=> $this->url->link('information/information', 'information_id=' .  $information_id),
                                'title' => $information_info['title'],
                                'sort_order' => $information_info['sort_order']
                            );
                        }
                    }

                    $i_sort_order = array();

                    foreach ($childrens as $key => $value) {
                        $i_sort_order[$key] = $value['title'];
                    }

                    array_multisort($i_sort_order, SORT_ASC, $childrens);
                }

                $menu_items[] = array(
                    'megamenu_id' 	 => $result['megamenu_id'],
                    'title'       	 => $result['title'],
                    'image'					 => $image,
                    'href'        	 => ($result['link'] == "#") ? "javascript:void(0);" : $result['link'],
                    'open_link_type' => $result['open_link_type'],
                    'description' 	 => ($result['info_text']) ? html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8') : '',
                    'custom_html'		 => ($result['custom_html']) ? html_entity_decode($result['custom_html'], ENT_QUOTES, 'UTF-8') : '',
                    'display_type' 	 => $result['display_type'],
                    'limit_item' 	   => $result['limit_item'],
                    'show_img'		   => $result['show_img'],
                    'children' 			 => (isset($childrens[0]['children'])) ? $childrens[0]['children'] : [],
                    'item_type'			 => $result['item_type']
                );

            }
            $this->cache->set('octemplates.megamenu.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id'), $menu_items);
        }

        $data['items'] = $menu_items;
        
        return $this->load->view('extension/module/oct_megamenu', $data);
    }

    public function login() {
        $json = array();

        $this->load->model('account/customer');
        $this->load->language('account/login');

        $this->event->trigger('pre.customer.login');

        // Check how many login attempts have been made.
        $login_info = $this->model_account_customer->getLoginAttempts($this->request->post['ocmm_login_email']);

        if ($login_info && ($login_info['total'] >= $this->config->get('config_login_attempts')) && strtotime('-1 hour') < strtotime($login_info['date_modified'])) {
            $json['error'] = $this->language->get('error_attempts');
        }

        // Check if customer has been approved.
        $customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['ocmm_login_email']);

        if ($customer_info && !$customer_info['approved']) {
            $json['error'] = $this->language->get('error_approved');
        }

        if (!isset($json['error'])) {
            if (!$this->customer->login($this->request->post['ocmm_login_email'], $this->request->post['ocmm_login_password'])) {
                $json['error'] = $this->language->get('error_login');

                $this->model_account_customer->addLoginAttempt($this->request->post['ocmm_login_email']);
            } else {
                $this->model_account_customer->deleteLoginAttempts($this->request->post['ocmm_login_email']);

                $this->event->trigger('post.customer.login');
            }
        }


        if (!isset($json['error'])) {
            unset($this->session->data['guest']);

            // Default Shipping Address
            $this->load->model('account/address');

            if ($this->config->get('config_tax_customer') == 'payment') {
                $this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
            }

            if ($this->config->get('config_tax_customer') == 'shipping') {
                $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
            }

            // Add to activity log
            $this->load->model('account/activity');

            $activity_data = array(
                'customer_id' => $this->customer->getId(),
                'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
            );

            $this->model_account_activity->addActivity('login', $activity_data);

            $json['redirect'] = $this->url->link('account/account', '', 'SSL');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    protected  function num_decline( $number, $titles, $param2 = '', $param3 = '' ){

        if( $param2 )
            $titles = [ $titles, $param2, $param3 ];

        if( is_string($titles) )
            $titles = preg_split( '/, */', $titles );

        if( empty($titles[2]) )
            $titles[2] = $titles[1]; // когда указано 2 элемента

        $cases = [ 2, 0, 1, 1, 1, 2 ];

        $intnum = abs( intval( strip_tags( $number ) ) );

        return "$number ". $titles[ ($intnum % 100 > 4 && $intnum % 100 < 20) ? 2 : $cases[min($intnum % 10, 5)] ];
    }
}