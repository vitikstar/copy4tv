<?php

class ControllerCheckoutComponentListProductRightPosition extends Controller {
    public  function declension( $num, $vars, $before = '', $after = '' )
    {
        if( $num == 0 ) // если число равно нулю
            return; // ничего не возвращаем
        $normal_num = $num; // сохраняем число в исходном виде
        $num = $num % 10; // определяем цифру, стоящую после десятка
        if( $num == 1 ) // если это единица
        {
            $num = $normal_num . ' ' . $vars[0];
        }else if( $num > 1 && $num < 5 ) // если это 2, 3, 4
        {
            $num = $normal_num . ' ' . $vars[1];
        }else
        {
            $num = $normal_num . ' ' . $vars[2];
        }
        return $before . $num . $after; // возвращаем строку
    }
    public function index() {
        $this->load->language('checkout/checkout');
        $data=array();
        $json=array();



        // Validate minimum quantity requirement
        $products = $this->cart->getProducts();
        $data['totals']=0;
        $data['totals_sum']=0;
        foreach ($products as $product) {
            $product_total = 0;

            foreach ($products as $product_2) {
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

            $image = ($product['image']) ? $this->model_tool_image->resize($product['image'], 80, 80) : $this->model_tool_image->resize("no_image.jpg", 80, 80);

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
            $data['totals_sum'] +=$product['price'] * $product['quantity'];

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
            $data['totals'] +=(int)$total;
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
        $count = count($products);
        $text_total_sum_tovar = $this->declension($count, array( $this->language->get('text_total_sum_tovar1'), $this->language->get('text_total_sum_tovar2'), $this->language->get('text_total_sum_tovar3')));
        $data['text_total_sum_tovar'] = $text_total_sum_tovar;
        $data['text_total_sum'] = $this->language->get('text_total_sum');
        $data['text_order_confirmed'] = $this->language->get('text_order_confirmed');
        $data['text_edit_order'] = $this->language->get('text_edit_order');

        $json['products'] = $this->load->view('checkout/component/list_product_right_position', $data);
        $json['ch-products-order-edit-wrap'] = '                                <span class="ch-products-order-edit" onclick="getOCwizardModal_smca(undefined,\'load\',1);">'. $data['text_edit_order'] .'</span>
                                <!--<a href="javascipt:void(0)" class="ch-products-promocode">Ввести промокод</a>-->';
        $json['ch-products-footer-total'] = '                                <div>'. $data['text_total_sum'] .'</div>
                                <div class="price">'. $data['totals_sum'] .'<span class="symbol-right"> грн</span></div>';
        $json['ch-products-footer-info'] = '<div>'. $data['text_total_sum_tovar'] .'</div>
                                    <div class="price">'. $data['totals'] .'<span class="symbol-right"> грн</span></div>';

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
