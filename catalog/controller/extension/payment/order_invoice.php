<?php

class ControllerExtensionPaymentOrderInvoice extends Controller
{
    protected function log( $message ){
        file_put_contents(DIR_LOGS . $this->config->get("config_error_filename") , date("Y-m-d H:i:s - ") . "Order Invoice " . $message . "\r\n", FILE_APPEND );
    }

    public function index()
    {
        $this->load->language('extension/payment/order_invoice');

        $data['text_instruction'] = $this->language->get('text_instruction');
        $data['text_payment'] = $this->language->get('text_payment');
        $data['text_printpay'] = str_replace('{href}', $this->url->link('extension/payment/order_invoice/printpay', '', true), $this->language->get('text_printpay'));
        $data['text_loading'] = $this->language->get('text_loading');

        if ($this->customer->isLogged()) {
            $data['text_order_history'] = str_replace('{href}', $this->url->link('account/order', '', true), $this->language->get('text_order_history'));
        } else {
            $data['text_order_history'] = '';
        }

        $data['button_confirm'] = $this->language->get('button_confirm');

        $data['continue'] = $this->url->link('checkout/success');

        return $this->load->view('extension/payment/order_invoice', $data);
        
    }

    // генерирует форму Счета
    public function prepare_print()
    {
       
        $this->load->model('extension/payment/order_invoice');
        $this->load->model('checkout/order');

        if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/order_invoice.css')) {
            $data["style"] = '/catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/order_invoice.css';
        } else {
            $data["style"] = 'catalog/view/theme/default/stylesheet/order_invoice.css';
        }

        // если клиент заказа авторизован и осуществлен запрос GET
        if (!empty($this->request->get['order_id'])) {
            $order_info = $this->model_extension_payment_order_invoice->getOrder($this->request->get['order_id']);
        } else {
            $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        }

        $this->load->language('extension/payment/order_invoice');

        $data['button_confirm'] = $this->language->get('button_confirm');
        $data['button_back'] = $this->language->get('button_back');

        $data['supplier_info'] = nl2br($this->config->get('payment_order_invoice_supplier_info_' . $this->config->get('config_language_id')));
        $data['sheff'] = nl2br($this->config->get('payment_order_invoice_sheff'));

        $this->load->model('tool/image');
        if ($this->config->get('payment_order_invoice_image')) {
            $data['image'] = '/image/'.$this->config->get('payment_order_invoice_image');
        } else {
            $data['image'] = '';
        }
        if ($this->config->get('payment_order_invoice_sign')) {
            $data['sign'] = '/image/'.$this->config->get('payment_order_invoice_sign');
        } else {
            $data['sign'] = '';
        }

        // получаем товары из корзины
        $data['products'] = $this->cart->getProducts();

        // сумма без НДС
        $data['total'] = $this->model_extension_payment_order_invoice->getTotal($data['products']);

        if ($this->cart->hasProducts()){

            // Totals
            $this->load->model('setting/extension');

            $totals = array();
            $taxes = $this->cart->getTaxes();
            $total = 0;
            
            // Because __call can not keep var references so we put them into an array.             
            $total_data = array(
                'totals' => &$totals,
                'taxes'  => &$taxes,
                'total'  => &$total
            );
            
            // Display prices
            if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                $sort_order = array();
                $results = $this->model_setting_extension->getExtensions('total');
                foreach ($results as $key => $value) {
                    $sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
                }
                array_multisort($sort_order, SORT_ASC, $results);
                foreach ($results as $result) {
                    if ($this->config->get('total_' . $result['code'] . '_status')) {
                        $this->load->model('extension/total/' . $result['code']);
                        $this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
                    }
                }
                $sort_order = array();
                foreach ($totals as $key => $value) {
                    $sort_order[$key] = $value['sort_order'];
                }
                array_multisort($sort_order, SORT_ASC, $totals);
            }
            $data['totals'] = array();
            foreach ($totals as $total) {
                $data['totals'][] = array(
                    'title' => $total['title'],
                    'text'  => sprintf('%01.2f', round($total['value'], 2))
                );
            }
            $summary = sprintf('%01.2f', round($total['value'], 2));
        } else {
            $this->load->model('account/order');
            $data['totals'] = array();
            $totals = $this->model_account_order->getOrderTotals($this->request->get['order_id']);
            foreach ($totals as $total) {
                $data['totals'][] = array(
                    'title' => $total['title'],
                    'text'  => sprintf('%01.2f', round($total['value'], 2))
                );
            }
            $summary = sprintf('%01.2f', round($total['value'], 2));
        }

        // для админки
        if (empty($data['products'])) {
            $data['products'] = $this->model_extension_payment_order_invoice->getOrderProducts($this->request->get['order_id']);
            // сумма без НДС
            $data['total'] = $this->model_extension_payment_order_invoice->getTotal($data['products']);
        }

        // сумма (+ НДС)
        $percent = (int)$this->config->get('payment_order_invoice_tax_status');
        if ($percent >= 0) {
            $data['total_price'] = sprintf('%01.2f', round(($data['total'] * (1 + ($percent/100))), 2));
            $data['total_tax'] = sprintf('%01.2f', round(($data['total'] * ($percent/100)), 2));
        } else {
            $data['total_price'] = sprintf('%01.2f', round($data['total'], 2));
            $data['total_tax'] = sprintf('%01.2f', round(0, 2));
        }

        $data['percent'] = $percent;

        // подгружаем хелпер для вывода суммы прописью
        $this->load->helper('num2str');

        // окончательная сумма прописью (украинский)
        if ($percent == -1) {
            $data['sum_str'] = num2str($summary);
        } else {
            $data['sum_str'] = num2str($data['total_price']);
        }
        //$data['tax_str'] = num2str($data['total_tax']);


        $data['order_id'] = $order_info['order_id'];

        // имя плательщика
        $data['name'] = $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'];

        // телефон плательщика
        $data['telephone'] = $order_info['telephone'];

        // дата создания заказа
        $data['date_added'] = getdate(strtotime($order_info['date_added']));

        // срок действия счета (+2 дня от даты заказа)
        $data['deadline'] = date('d.m.y', strtotime($order_info['date_added'])+172800);

        // від 29 серпня 2017 р."
        $data['date_added'] = $data['date_added']['mday'] . ' ' . $this->language->get('text_month_'.$data['date_added']['mon']) . ' ' . $data['date_added']['year'];

        // префикс + номер счета
        $data['invoice'] = $order_info['invoice_prefix'].$order_info['invoice_no'];

        // генерация номера счет-фактуры
        // если есть данные о заказе и нет номера счет-фактуры, то...
        if (!$order_info['invoice_no'])
        {
            // определяем максимальное число номера
            $query = $this->db->query("SELECT MAX(invoice_no) AS invoice_no FROM `" . DB_PREFIX . "order` WHERE invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "'");

            if ($query->row['invoice_no']) {
                // если номер больше нуля - прибавляем 1
                $invoice_no = $query->row['invoice_no'] + 1;
            } else {
                // иначе - 1
                $invoice_no = 1;
            }

            // сохраняем полученные значения
            $this->db->query("UPDATE `" . DB_PREFIX . "order` SET invoice_no = '" . (int) $invoice_no . "', invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "' WHERE order_id = '" . (int) $order_info['order_id'] . "'");

            // выводим в браузер
            $data['invoice'] =  $order_info['invoice_prefix'] . $invoice_no;
        }


        if (!$order_info['payment_address_2']) {
            $data['address'] = $order_info['payment_zone'] . ', ' . $order_info['payment_city'] . ', ' . $order_info['payment_address_1'];
        } else  {
            $data['address'] = $order_info['payment_zone'] . ', ' . $order_info['payment_city'] . ', ' . $order_info['payment_address_1'] . ', ' . $order_info['payment_address_2'];
        }

        $data['postcode'] = $order_info['payment_postcode'];

        return $data;

    }

    public function printpay()
    {
        $data = $this->prepare_print();
        $this->response->setOutput($this->load->view('extension/payment/order_invoice_invoice', $data));
    }

    public function invoice()
    {
        $data = $this->prepare_print();
        $this->response->setOutput($this->load->view('extension/payment/order_invoice_invoice', $data));
    }

    public function tax()
    {
        $data = $this->prepare_print();
        $this->response->setOutput($this->load->view('extension/payment/order_invoice_tax', $data));
    }

    // подтверждение заказа
    public function confirm()    {
        $this->load->language('extension/payment/order_invoice');
        $this->load->model('checkout/order');
        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        if ($order_info)
        {
            $comment = $this->language->get('text_instruction') . "\n\n";
            $comment .= $this->language->get('text_payment'). "\n\n";
            $comment .= str_replace('{href}', $this->url->link('extension/payment/order_invoice/printpay', 'order_id=' . $order_info['order_id'], true), $this->language->get('text_printpay')) . "\n\n";
            
            $this->model_checkout_order->addOrderHistory($order_info['order_id'], $this->config->get('payment_order_invoice_order_status_id'), $comment, true);

        }
    }

}

?>