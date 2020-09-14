<?php
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerAccountOrder extends Controller
{
    public $show_limit_order = 5;
    public function index()
    {
//
//        $result = $this->db->query("SELECT * FROM `oc_seo_url`");
//        foreach ($result->rows as $row){
//             $this->db->query("INSERT INTO  `oc_seo_url` SET store_id=0, language_id=3,query='".$this->db->escape($row['query'])."',keyword='".$this->db->escape($row['keyword'])."'");
//        }
//exit;
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/order', '', true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        $this->load->language('account/account');
        $data['text_catalog'] = $this->language->get('heading_title');


        $this->load->language('account/order');

        $this->document->setTitle($this->language->get('heading_title'));
        $this->document->setRobots('noindex,follow');

        $url = '';


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


        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/account', '', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('account/order', $url, true)
        );

        $data['orders'] = array();

        $this->load->model('account/order');

        $data['order_total'] = $this->model_account_order->getTotalOrders();
        $data['show_limit_order'] = $this->show_limit_order;


        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');

$data['header'] = $this->load->controller('common/header');
$data['column_megamenu'] = $this->load->controller('common/column_megamenu');


        $this->response->setOutput($this->load->view('account/order_list', $data));
    }
    public function loadOrder()
    {

        $this->load->language('account/order');

        if ($this->request->get['show_display_item_order']) {
            $show_display_item_order = $start = $this->request->get['show_display_item_order'];
        } else {
            $show_display_item_order = $this->show_limit_order;
            $start = 0;
        }

        $data['orders'] = array();

        $this->load->model('account/order');

//print_my( $this->model_account_order->getOrders());
        $results = $this->model_account_order->getOrders($start, $this->request->get['limit']);
        $count_item_order = $this->countShowItemOrder($show_display_item_order);
        $data['text_show_limit_order']  = ($count_item_order) ? $this->language->get('text_show_limit_order')." ".$this->config->num_decline($this->request->get['limit'] , $this->language->get('text_show_limit_order_1')) : 0;


        foreach ($results as $result) {
            $products_list = array();
            $products_img_small = array();

            $order_id = $result['order_id'];
            $products = $this->model_account_order->getOrderProducts($order_id);

            $products_mini_image_total=0;
            foreach ($products as $product) {
                $products_mini_image_total++;
                $option_data = array();

                $options = $this->model_account_order->getOrderOptions($order_id, $product['order_product_id']);

                foreach ($options as $option) {
                    if ($option['type'] != 'file') {
                        $value = $option['value'];
                    } else {
                        $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

                        if ($upload_info) {
                            $value = $upload_info['name'];
                        } else {
                            $value = '';
                        }
                    }

                    $option_data[] = array(
                        'name' => $option['name'],
                        'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
                    );
                }

                $product_info = $this->model_catalog_product->getProduct($product['product_id']);

                if ($product_info) {
                    $reorder = $this->url->link('account/order/reorder', 'order_id=' . $order_id . '&order_product_id=' . $product['order_product_id'], true);
                } else {
                    $reorder = '';
                }
                $products_list[] = array(
                    'name' => $product['name'],
                    'model' => $product['model'],
                    'product_id' => $product['product_id'],
                    'available' => $product_info['quantity'],
                    'available_text' => ($product_info['quantity']<0) ? $this->language->get('text_no_available') : '',
                    'ac_order_product_no_available' => ($product_info['quantity']>0) ? false : true,
                    'option' => $option_data,
                    'quantity' => $product['quantity'],
                    'price' => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $result['currency_code'], $result['currency_value']),
                    'total' => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $result['currency_code'], $result['currency_value']),
                    'reorder' => $reorder,
                    'big' => $this->model_tool_image->resize($product_info['image'], 60, 60),
                    'href' => $this->url->link('product/product', 'product_id=' . $product['product_id']),
                    'return' => $this->url->link('account/return/add', 'order_id=' . $result['order_id'] . '&product_id=' . $product['product_id'], true)
                );
                if($products_mini_image_total<=COUNT_SHOW_MINI_IMG_ORDER) $products_img_small[] = $this->model_tool_image->resize($product_info['image'], 30, 30);
            }
            $product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);
            $voucher_total = $this->model_account_order->getTotalOrderVouchersByOrderId($result['order_id']);
            $products_total = $product_total + $voucher_total;
            $additional_products_mini_image_total =  ($products_mini_image_total>COUNT_SHOW_MINI_IMG_ORDER) ? ($products_mini_image_total-COUNT_SHOW_MINI_IMG_ORDER) : $products_mini_image_total;

            $data['orders'][] = array(
                'order_id' => $order_id,
                'name' => $result['firstname'] . ' ' . $result['lastname'],
                'status' => $result['status'],
                'status_id' => $result['status_id'],
                'type_delivery_name' => $result['type_delivery_name'],
                'service_delivery_name' => $result['service_delivery_name'],
                'payment_method_name' => $result['payment_method_name'],
                'branch_new_post' => $result['branch_new_post'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'products_total' => $products_total,
                'product_list' => $products_list,
                'products_img_small' => $products_img_small,
                'reorder' => $this->url->link('account/order/reorder', 'order_id=' . $order_id , true),
                'additional_products_mini_image_total' => $additional_products_mini_image_total,
                'total' => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
                'view' => $this->url->link('account/order/info', 'order_id=' . $order_id, true),
            );
        }
//
//        print_my($data['orders']);
//        exit;


        $this->response->setOutput($this->load->view('account/load_order', $data));
    }

    public function info()
    {
        $this->load->language('account/order');

        if (isset($this->request->get['order_id'])) {
            $order_id = $this->request->get['order_id'];
        } else {
            $order_id = 0;
        }

        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/order/info', 'order_id=' . $order_id, true);

            $this->response->redirect($this->url->link('account/login', '', true));
        }

        $this->load->model('account/order');

        $order_info = $this->model_account_order->getOrder($order_id);

        if ($order_info) {
            $this->document->setTitle($this->language->get('text_order'));

            $url = '';

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
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

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_account'),
                'href' => $this->url->link('account/account', '', true)
            );

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('account/order', $url, true)
            );

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_order'),
                'href' => $this->url->link('account/order/info', 'order_id=' . $this->request->get['order_id'] . $url, true)
            );

            if (isset($this->session->data['error'])) {
                $data['error_warning'] = $this->session->data['error'];

                unset($this->session->data['error']);
            } else {
                $data['error_warning'] = '';
            }

            if (isset($this->session->data['success'])) {
                $data['success'] = $this->session->data['success'];

                unset($this->session->data['success']);
            } else {
                $data['success'] = '';
            }

            if ($order_info['invoice_no']) {
                $data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
            } else {
                $data['invoice_no'] = '';
            }

            $data['order_id'] = $this->request->get['order_id'];
            $data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));

            if ($order_info['payment_address_format']) {
                $format = $order_info['payment_address_format'];
            } else {
                $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
            }

            $find = array(
                '{firstname}',
                '{lastname}',
                '{company}',
                '{address_1}',
                '{address_2}',
                '{city}',
                '{postcode}',
                '{zone}',
                '{zone_code}',
                '{country}'
            );

            $replace = array(
                'firstname' => $order_info['payment_firstname'],
                'lastname' => $order_info['payment_lastname'],
                'company' => $order_info['payment_company'],
                'address_1' => $order_info['payment_address_1'],
                'address_2' => $order_info['payment_address_2'],
                'city' => $order_info['payment_city'],
                'postcode' => $order_info['payment_postcode'],
                'zone' => $order_info['payment_zone'],
                'zone_code' => $order_info['payment_zone_code'],
                'country' => $order_info['payment_country']
            );

            $data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

            $data['payment_method'] = $order_info['payment_method'];

            if ($order_info['shipping_address_format']) {
                $format = $order_info['shipping_address_format'];
            } else {
                $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
            }

            $find = array(
                '{firstname}',
                '{lastname}',
                '{company}',
                '{address_1}',
                '{address_2}',
                '{city}',
                '{postcode}',
                '{zone}',
                '{zone_code}',
                '{country}'
            );

            $replace = array(
                'firstname' => $order_info['shipping_firstname'],
                'lastname' => $order_info['shipping_lastname'],
                'company' => $order_info['shipping_company'],
                'address_1' => $order_info['shipping_address_1'],
                'address_2' => $order_info['shipping_address_2'],
                'city' => $order_info['shipping_city'],
                'postcode' => $order_info['shipping_postcode'],
                'zone' => $order_info['shipping_zone'],
                'zone_code' => $order_info['shipping_zone_code'],
                'country' => $order_info['shipping_country']
            );

            $data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

            $data['shipping_method'] = $order_info['shipping_method'];

            $this->load->model('catalog/product');
            $this->load->model('tool/upload');

            // Products
            $data['products'] = array();

            $products = $this->model_account_order->getOrderProducts($this->request->get['order_id']);

            foreach ($products as $product) {
                $option_data = array();

                $options = $this->model_account_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);

                foreach ($options as $option) {
                    if ($option['type'] != 'file') {
                        $value = $option['value'];
                    } else {
                        $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

                        if ($upload_info) {
                            $value = $upload_info['name'];
                        } else {
                            $value = '';
                        }
                    }

                    $option_data[] = array(
                        'name' => $option['name'],
                        'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
                    );
                }

                $product_info = $this->model_catalog_product->getProduct($product['product_id']);

                if ($product_info) {
                    $reorder = $this->url->link('account/order/reorder', 'order_id=' . $order_id . '&order_product_id=' . $product['order_product_id'], true);
                } else {
                    $reorder = '';
                }

                $data['products'][] = array(
                    'name' => $product['name'],
                    'model' => $product['model'],
                    'option' => $option_data,
                    'quantity' => $product['quantity'],
                    'price' => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                    'total' => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
                    'reorder' => $reorder,
                    'return' => $this->url->link('account/return/add', 'order_id=' . $order_info['order_id'] . '&product_id=' . $product['product_id'], true)
                );
            }

            // Voucher
            $data['vouchers'] = array();

            $vouchers = $this->model_account_order->getOrderVouchers($this->request->get['order_id']);

            foreach ($vouchers as $voucher) {
                $data['vouchers'][] = array(
                    'description' => $voucher['description'],
                    'amount' => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
                );
            }

            // Totals
            $data['totals'] = array();

            $totals = $this->model_account_order->getOrderTotals($this->request->get['order_id']);

            foreach ($totals as $total) {
                $data['totals'][] = array(
                    'title' => $total['title'],
                    'text' => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
                );
            }

            $data['comment'] = nl2br($order_info['comment']);

            // History
            $data['histories'] = array();

            $results = $this->model_account_order->getOrderHistories($this->request->get['order_id']);

            foreach ($results as $result) {
                $data['histories'][] = array(
                    'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                    'status' => $result['status'],
                    'comment' => $result['notify'] ? nl2br($result['comment']) : ''
                );
            }

            $data['continue'] = $this->url->link('account/order', '', true);

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');

$data['header'] = $this->load->controller('common/header');
$data['column_megamenu'] = $this->load->controller('common/column_megamenu');

            $this->response->setOutput($this->load->view('account/order_info', $data));
        } else {
            return new Action('error/not_found');
        }
    }

    public function reorder()
    {
        $this->load->language('account/order');

        if (isset($this->request->get['order_id'])) {
            $order_id = $this->request->get['order_id'];
        } else {
            $order_id = 0;
        }

        $this->load->model('account/order');

        $order_product_info = $this->model_account_order->getOrderProducts($order_id);

        if ($order_product_info) {
            if (isset($this->request->get['order_product_id'])) {
                $order_product_id = $this->request->get['order_product_id'];
            } else {
                $order_product_id = 0;
            }

            foreach ($order_product_info as $item){
                    $this->load->model('catalog/product');

                    $product_info = $this->model_catalog_product->getProduct($item['product_id']);

                    if ($product_info) {
                        $option_data = array();

                        $order_options = $this->model_account_order->getOrderOptions($item['order_id'], $item['order_product_id']);

                        foreach ($order_options as $order_option) {
                            if ($order_option['type'] == 'select' || $order_option['type'] == 'radio' || $order_option['type'] == 'image') {
                                $option_data[$order_option['product_option_id']] = $order_option['product_option_value_id'];
                            } elseif ($order_option['type'] == 'checkbox') {
                                $option_data[$order_option['product_option_id']][] = $order_option['product_option_value_id'];
                            } elseif ($order_option['type'] == 'text' || $order_option['type'] == 'textarea' || $order_option['type'] == 'date' || $order_option['type'] == 'datetime' || $order_option['type'] == 'time') {
                                $option_data[$order_option['product_option_id']] = $order_option['value'];
                            } elseif ($order_option['type'] == 'file') {
                                $option_data[$order_option['product_option_id']] = $this->encryption->encrypt($this->config->get('config_encryption'), $order_option['value']);
                            }
                        }

                        $this->cart->add($item['product_id'], $item['quantity'], $option_data);

                        //$this->session->data['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $product_info['product_id']), $product_info['name'], $this->url->link('checkout/cart'));

                        unset($this->session->data['shipping_method']);
                        unset($this->session->data['shipping_methods']);
                        unset($this->session->data['payment_method']);
                        unset($this->session->data['payment_methods']);
                    } else {
                        $this->session->data['error'] = sprintf($this->language->get('error_reorder'), $item['name']);
                    }
            }
        }

        $this->response->redirect($this->url->link('checkout/checkout'));
    }

    public function addToCart(){
        $json = array();

        $this->load->language('account/order');

        if (isset($this->request->get['order_id'])) {
            $order_id = $this->request->get['order_id'];
        } else {
            $order_id = 0;
        }

        $this->load->model('account/order');

        $order_product_info = $this->model_account_order->getOrderProducts($order_id);

            if (isset($this->request->get['order_product_id'])) {
                $order_product_id = $this->request->get['order_product_id'];
            } else {
                $order_product_id = 0;
            }

            foreach ($order_product_info as $item){
                if(!in_array($item['product_id'],$this->request->post['order_product'])) continue;
                $this->load->model('catalog/product');

                $product_info = $this->model_catalog_product->getProduct($item['product_id']);

                if ($product_info) {
                    $option_data = array();

                    $order_options = $this->model_account_order->getOrderOptions($item['order_id'], $item['order_product_id']);

                    foreach ($order_options as $order_option) {
                        if ($order_option['type'] == 'select' || $order_option['type'] == 'radio' || $order_option['type'] == 'image') {
                            $option_data[$order_option['product_option_id']] = $order_option['product_option_value_id'];
                        } elseif ($order_option['type'] == 'checkbox') {
                            $option_data[$order_option['product_option_id']][] = $order_option['product_option_value_id'];
                        } elseif ($order_option['type'] == 'text' || $order_option['type'] == 'textarea' || $order_option['type'] == 'date' || $order_option['type'] == 'datetime' || $order_option['type'] == 'time') {
                            $option_data[$order_option['product_option_id']] = $order_option['value'];
                        } elseif ($order_option['type'] == 'file') {
                            $option_data[$order_option['product_option_id']] = $this->encryption->encrypt($this->config->get('config_encryption'), $order_option['value']);
                        }
                    }

                    $this->cart->add($item['product_id'], $item['quantity'], $option_data);

                    //$this->session->data['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $product_info['product_id']), $product_info['name'], $this->url->link('checkout/cart'));

                    unset($this->session->data['shipping_method']);
                    unset($this->session->data['shipping_methods']);
                    unset($this->session->data['payment_method']);
                    unset($this->session->data['payment_methods']);
                } else {
                    $this->session->data['error'] = sprintf($this->language->get('error_reorder'), $item['name']);
                }
            }

$json['redirect'] = $this->url->link('checkout/checkout');
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function countShowItemOrder($show_item_display=0){
        $this->load->model('account/order');

        $order_total = $this->model_account_order->getTotalOrders();
        $show_limit_order = $this->show_limit_order;
        $merge_count = $order_total - ($show_limit_order+$show_item_display);

        if($show_item_display>=$order_total) return 0;
        return ($merge_count<0) ? $order_total-$show_item_display : $show_limit_order;
    }
}