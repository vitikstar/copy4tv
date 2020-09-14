<?php

class ControllerCheckoutComponentEditOrder extends Controller {
    static $_module_name    = 'ocdev_smart_cart';
    public function index() {
        $data = array();
        $this->document->addStyle('catalog/view/theme/default/stylesheet/ocdev_smart_cart/stylesheet.css');


            // connect models array
        $models = array('catalog/product', 'tool/image', 'tool/upload', 'setting/extension', 'extension/module/'.self::$_module_name);
        foreach ($models as $model) {
            $this->load->model($model);
        }

        $data = array_merge($data, $this->load->language('extension/module/'.self::$_module_name), $this->config->get(self::$_module_name.'_text_data'), $this->config->get(self::$_module_name.'_form_data'));

        $text_data  = (array)$this->config->get(self::$_module_name.'_text_data');
        $form_data  = (array)$this->config->get(self::$_module_name.'_form_data');

        $language_code = $this->session->data['language'];

        if (isset($text_data[$language_code])) {
            $data['heading_title'] = $text_data[$language_code]['heading'];
            $data['text_empty']   = html_entity_decode($text_data[$language_code]['empty_text'], ENT_QUOTES, 'UTF-8');
        }

        if (isset($this->request->request['remove'])) {
            $this->cart->remove($this->request->request['remove']);
            unset($this->session->data['vouchers'][$this->request->request['remove']]);
        }

        if (isset($this->request->request['update'])) {
            $this->cart->update($this->request->request['update'], $this->request->request['quantity']);
        }

        if (isset($this->request->request['add'])) {
            $this->cart->add($this->request->request['add'], $this->request->request['quantity']);
        }

        $data['button_cart'] = $this->language->get('button_cart');
        $data['checkout_link'] = $this->url->link('checkout/checkout', '', true);
        $data['save_cart_email'] = $this->customer->isLogged() ? (string)$this->customer->getEmail() : "";

        $points = $this->customer->getRewardPoints();
        $data['text_reward_title'] = sprintf($this->language->get('text_reward_title_heading'), $points);

        // cart products
        if (!$this->cart->hasStock() && (!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning'))) {
            $data['error_stock'] = $this->language->get('error_stock');
        } elseif (isset($this->session->data['error'])) {
            $data['error_stock'] = $this->session->data['error'];

            unset($this->session->data['error']);
        } else {
            $data['error_stock'] = '';
        }

        $data['products'] = array();


        foreach ($this->cart->getProducts() as $product) {
            $product_total = 0;

            foreach ($this->cart->getProducts() as $product_2) {
                if ($product_2['product_id'] == $product['product_id']) {
                    $product_total += $product_2['quantity'];
                }
            }

            $product_info = $this->model_catalog_product->getProduct($product['product_id']);

            if ($product_info['quantity'] <= 0) {
                $stock_text = $product_info['stock_status'];
            } elseif ($this->config->get('config_stock_display')) {
                $stock_text = $product_info['quantity'];
            } else {
                $stock_text = $this->language->get('text_instock');
            }

            $image = ($product['image']) ? $this->model_tool_image->resize($product['image'], $form_data['main_image_width'], $form_data['main_image_height']) : $this->model_tool_image->resize("no_image.jpg", $form_data['main_image_width'], $form_data['main_image_height']);

            $option_data = array();

            foreach ($product['option'] as $option) {
                if ($option['type'] != 'file') {
                    $value = $option['value'];
                } else {
                    $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);
                    $value = ($upload_info) ? $upload_info['name'] : '';
                }

                $option_data[] = array(
                    'name'  => $option['name'],
                    'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20).'..' : $value)
                );
            }

            // display price
            $price = (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) ? $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']) : false;

            // display tax
            if ($this->config->get('config_tax')) {
                $tax = $this->currency->format((float)$product['price'], $this->session->data['currency']);
                $tax_total = $this->currency->format(($product['price'] * $product['quantity']), $this->session->data['currency']);
            } else {
                $tax = false;
                $tax_total = false;
            }

            // display unit total
            $total = (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) ? $this->currency->format(($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']), $this->session->data['currency']) : false;

            $data['products'][] = array(
                'key'         => $product['cart_id'],
                'product_id'  => $product['product_id'],
                'thumb'       => $image,
                'name'        => $product['name'],
                'model'       => $product['model'],
                'ean'         => $product_info['ean'],
                'jan'         => $product_info['jan'],
                'isbn'        => $product_info['isbn'],
                'mpn'         => $product_info['mpn'],
                'location'    => $product_info['location'],
                'option'      => $option_data,
                'quantity'    => $product['quantity'],
                'stock_text'  => $stock_text,
                'stock'       => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
                'reward'      => $product['reward'] ? $product['reward'] : '',
                'price'       => $price,
                'tax'         => $tax,
                'tax_total'   => $tax_total,
                'total'       => $total,
                'href'        => $this->url->link('product/product', 'product_id='.$product['product_id'], true)
            );
        }

        // totals
        $total_data = array();
        $total = 0;
        $taxes = $this->cart->getTaxes();

        // display prices
        if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
            $sort_order = array();

            $results = $this->model_setting_extension->getExtensions('total');

            foreach ($results as $key => $value) {
                $sort_order[$key] = $this->config->get('total_' . $value['code'].'_sort_order');
            }

            array_multisort($sort_order, SORT_ASC, $results);

            foreach ($results as $result) {
                if ($this->config->get('total_' . $result['code'].'_status')) {
                    $this->load->model('extension/total/'.$result['code']);

                    $this->{'model_extension_total_'.$result['code']}->getTotal(array('totals' => &$total_data, 'total' => &$total, 'taxes' => &$taxes));
                }

                $sort_order = array();

                foreach ($total_data as $key => $value) {
                    $sort_order[$key] = $value['sort_order'];
                }

                array_multisort($sort_order, SORT_ASC, $total_data);
            }

            foreach ($total_data as $value) {
                if ($value['code'] == 'total') {
                    $data['total'] = $this->currency->format($value['value'], $this->session->data['currency']);
                }
            }
        }

        $data['cart_weight'] = $this->weight->format($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));

        // cross-sell && up-sell products
        $data['cross_sell_products'] = array();

        if ($form_data['marketing_type'] == 2) {

            $data['m_hide_sub_img'] = $data['hide_up_sell_sub_img'] ? $data['hide_up_sell_sub_img'] : '';
            $data['m_hide_product_price'] = $data['hide_up_sell_product_price'] ? $data['hide_up_sell_product_price'] : '';
            $data['m_hide_product_addto_cart_button'] = $data['hide_up_sell_product_addto_cart_button'] ? $data['hide_up_sell_product_addto_cart_button'] : '';

            $upsell_data = $this->config->get(self::$_module_name.'_upsell_data');

            $products_in_cart = array();

            foreach ($this->cart->getProducts() as $product) {
                $products_in_cart[] = $product['product_id'];
            }

            $upsell_products = array();
            if (!empty($upsell_data))
                foreach ($upsell_data as $result) {

                    if ($result['cart_products'] && $result['type']) {

                        foreach ($result['cart_products'] as $pr_id) {

                            if (in_array($pr_id, $products_in_cart)) {

                                if ($result['type'] == 1) {
                                    $related_products = $this->model_catalog_product->getProductRelated($pr_id);

                                    foreach ($related_products as $upsel_product) {
                                        $upsell_products[] = $upsel_product['product_id'];
                                    }
                                }

                                if ($result['type'] == 2 && $result['upsell_products']) {
                                    foreach ($result['upsell_products'] as $upsel_product) {
                                        $upsell_products[] = $upsel_product;
                                    }
                                }

                                if ($result['type'] == 3 && $result['upsell_products']) {
                                    $related_products = $this->model_catalog_product->getProductRelated($pr_id);

                                    foreach ($related_products as $upsel_product) {
                                        $upsell_products[] = $upsel_product['product_id'];
                                    }

                                    foreach ($result['upsell_products'] as $upsel_product) {
                                        $upsell_products[] = $upsel_product;
                                    }
                                }

                                $upsell_products = array_unique($upsell_products);
                                $upsell_products = array_slice($upsell_products, 0, $form_data['limit_up_sell_products']);
                            }
                        }
                    }
                }

            $data_x = array(
                'sort'        => 'p.date_added',
                'order'       => 'DESC',
                'start'       => isset($this->request->request['start']) ? (int)$this->request->request['start'] : 0,
                'end'         => isset($this->request->request['end']) ? (int)$this->request->request['end'] : 3,
                'products_id' => implode(',', $upsell_products)
            );

            $data['ajax_all_products'] = $upsell_products ? count($upsell_products) : 0;

            $ajax_products = $this->{'model_extension_module_'.self::$_module_name}->getCrossSellProducts($data_x);

            foreach($ajax_products as $product) {
                if (in_array($product['product_id'], $upsell_products)) {

                    $image = ($product['image']) ? $this->model_tool_image->resize($product['image'], $form_data['sub_up_sell_images_width'], $form_data['sub_up_sell_images_height']) : $this->model_tool_image->resize("no_image.jpg", $form_data['sub_up_sell_images_width'], $form_data['sub_up_sell_images_height']);
                    $price = (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) ? $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']) : false;
                    $special = ((float)$product['special']) ? $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']) : false;

                    $data['cross_sell_products'][] = array(
                        'product_id'    => $product['product_id'],
                        'thumb'         => $image,
                        'name'          => $product['name'],
                        'price'         => $price,
                        'special'       => $special,
                        'href'          => $this->url->link('product/product', 'product_id='.$product['product_id'], true)
                    );
                }
            }

            if ($form_data['randomize']) {
                shuffle($data['cross_sell_products']);
            }

        } else {

            $data['m_hide_sub_img'] = $data['hide_sub_img'] ? $data['hide_sub_img'] : '';
            $data['m_hide_product_price'] = $data['hide_product_price'] ? $data['hide_product_price'] : '';
            $data['m_hide_product_addto_cart_button'] = $data['hide_product_addto_cart_button'] ? $data['hide_product_addto_cart_button'] : '';

            if (!empty($form_data['check']) && $form_data['check'] == 3) {

                $products = array();

                if (isset($form_data['cross_sell_products'])) {
                    foreach ($form_data['cross_sell_products'] as $product_id) {
                        $products[] = $product_id;
                    }
                }

                $data_x = array(
                    'sort'        => 'p.date_added',
                    'order'       => 'DESC',
                    'start'       => isset($this->request->request['start']) ? (int)$this->request->request['start'] : 0,
                    'end'         => isset($this->request->request['end']) ? (int)$this->request->request['end'] : 3,
                    'products_id' => implode(',', $products)
                );

                $data['ajax_all_products'] = $form_data['cross_sell_products'] ? count($form_data['cross_sell_products']) : 0;

                $ajax_products = $this->{'model_extension_module_'.self::$_module_name}->getCrossSellProducts($data_x);

                foreach($ajax_products as $product) {
                    if (in_array($product['product_id'], $products)) {

                        $image = ($product['image']) ? $this->model_tool_image->resize($product['image'], $form_data['sub_images_width'], $form_data['sub_images_height']) : $this->model_tool_image->resize("no_image.jpg", $form_data['sub_images_width'], $form_data['sub_images_height']);
                        $price = (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) ? $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']) : false;
                        $special = ((float)$product['special']) ? $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']) : false;

                        $data['cross_sell_products'][] = array(
                            'product_id'    => $product['product_id'],
                            'thumb'         => $image,
                            'name'          => $product['name'],
                            'price'         => $price,
                            'special'       => $special,
                            'href'          => $this->url->link('product/product', 'product_id='.$product['product_id'], true)
                        );
                    }
                }

            } else {

                if ($form_data['check']) {

                    if ($form_data['check'] == 1) {
                        $data_x = array(
                            'start'               => isset($this->request->request['start']) ? (int)$this->request->request['start'] : 0,
                            'end'                 => isset($this->request->request['end']) ? (int)$this->request->request['end'] : 3,
                            'filter_category_id'  => (!empty($form_data['cross_sell_categories']) && $form_data['check'] == 1) ? $form_data['cross_sell_categories'] : false,
                            'filter_sub_category' => true
                        );

                        $data['ajax_all_products'] = $this->{'model_extension_module_'.self::$_module_name}->getCrossSellTotalProducts($data_x);
                    }

                    if ($form_data['check'] == 2) {
                        $data_x = array(
                            'start'                  => isset($this->request->request['start']) ? (int)$this->request->request['start'] : 0,
                            'end'                    => isset($this->request->request['end']) ? (int)$this->request->request['end'] : 3,
                            'filter_manufacturer_id' => (!empty($form_data['cross_sell_manufacturers']) && $form_data['check'] == 2) ? $form_data['cross_sell_manufacturers'] : false
                        );

                        $data['ajax_all_products'] = $this->{'model_extension_module_'.self::$_module_name}->getCrossSellTotalProducts($data_x);
                    }

                    $ajax_products = $this->{'model_extension_module_'.self::$_module_name}->getCrossSellProducts($data_x);

                    foreach ($ajax_products as $product) {
                        $image = ($product['image']) ? $this->model_tool_image->resize($product['image'], $form_data['sub_images_width'], $form_data['sub_images_height']) : $this->model_tool_image->resize("no_image.jpg", $form_data['sub_images_width'], $form_data['sub_images_height']);
                        $price = (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) ? $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']) : false;
                        $special = ((float)$product['special']) ? $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']) : false;

                        $data['cross_sell_products'][] = array(
                            'product_id' => $product['product_id'],
                            'thumb'      => $image,
                            'name'       => $product['name'],
                            'price'      => $price,
                            'special'    => $special,
                            'href'       => $this->url->link('product/product', 'product_id='.$product['product_id'], true)
                        );
                    }
                }
            }
        }
        //$setting['heading_title']=$this->language->get('heading_title_this_product_buy');

        $setting['width']=300;
        $setting['height']=300;
        $setting['limit']=20;
        $setting['cart_popup_route'] = true;
        $setting['heading_title']='С этими товарами покупают';
        $data['buy_together'] = $this->load->controller('product/buy_width_product',$setting);
        $data['customer_status'] = $this->customer->isLogged() ? 1 : 0;
        $data['coupon'] = $this->load->controller('extension/module/'.self::$_module_name.'/coupon_index');
        $data['voucher'] = $this->load->controller('extension/module/'.self::$_module_name.'/voucher_index');
        $data['reward'] = $this->load->controller('extension/module/'.self::$_module_name.'/reward_index');
        $data['shipping'] = $this->load->controller('extension/module/'.self::$_module_name.'/shipping_index');



        $view = $this->load->view('checkout/component/edit_order', $data);

        $this->response->setOutput($view);
    }
}