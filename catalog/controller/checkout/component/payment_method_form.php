<?php

class ControllerCheckoutComponentPaymentMethodForm extends Controller {
	public function index() {



		$this->load->language('checkout/checkout');

       $payment_method_val = (isset($this->session->data['payment_method_val'])) ? $this->session->data['payment_method_val'] : '';

        // Totals
        $totals = array();
        $taxes = $this->cart->getTaxes();
        $total = 0;

        // Because __call can not keep var references so we put them into an array.
        $total_data = array(
            'totals' => &$totals,
            'taxes'  => &$taxes,
            'total'  => &$total
        );

        $this->load->model('setting/extension');

        $sort_order = array();

        $results = $this->model_setting_extension->getExtensions('total');

        foreach ($results as $key => $value) {
            $sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
        }

        array_multisort($sort_order, SORT_ASC, $results);

        foreach ($results as $result) {
            if ($this->config->get('total_' . $result['code'] . '_status')) {
                $this->load->model('extension/total/' . $result['code']);

                // We have to put the totals in an array so that they pass by reference.
                $this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
            }
        }

        // Payment Methods
        $method_data = array();

        $this->load->model('setting/extension');

        $results = $this->model_setting_extension->getExtensions('payment');

        $recurring = $this->cart->hasRecurringProducts();

        foreach ($results as $result) {
            if ($this->config->get('payment_' . $result['code'] . '_status')) {

                $this->load->model('extension/payment/' . $result['code']);

                $method = $this->{'model_extension_payment_' . $result['code']}->getMethod(array('zone_id'=>0,'country_id'=>0), $total);

                if ($method) {
                    if ($recurring) {
                        if (property_exists($this->{'model_extension_payment_' . $result['code']}, 'recurringPayments') && $this->{'model_extension_payment_' . $result['code']}->recurringPayments()) {
                            $method_data[$result['code']] = $method;
                        }
                    } else {
                        $method_data[$result['code']] = $method;
                    }
                }
            }
        }

        $sort_order = array();

        foreach ($method_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $method_data);

        $this->session->data['payment_methods'] = $data['payment_methods'] = $method_data;

        $this->session->data['shipping_method']['step'][3]=true;

        $this->response->setOutput($this->load->view('checkout/component/payment_method_form', $data));
	}

    public function save(){
        $this->load->language('checkout/checkout');
        $this->load->model('setting/extension');

        $json = array();
        foreach ($this->request->post as $k=>$item){
            $this->session->data[$k] = $item;
        }
        $this->session->data['payment_address']['address_1'] = $this->request->post['payment_address_1'];
       // $this->session->data['payment_address']['city']
       // $this->session->data['payment_address']['postcode']
       // $this->session->data['payment_address']['zone']
       // $this->session->data['payment_address']['zone_id']
       // $this->session->data['payment_address']['country']
       // $this->session->data['payment_address']['country_id']
       
        $this->session->data['payment_method']['code'] = $this->request->post['payment_method_val'];
        $results = $this->model_setting_extension->getExtensions('payment');

        foreach ($results as $result) {
            if ($this->config->get('payment_' . $result['code'] . '_status')) {

                $this->load->model('extension/payment/' . $result['code']);

                $method = $this->{'model_extension_payment_' . $result['code']}->getMethod(array('zone_id'=>0,'country_id'=>0),0);

                        $method_data[$result['code']] = $method['title'];
            }
        }
        $this->session->data['payment_method']['title'] = $method_data[$this->request->post['payment_method_val']];

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}