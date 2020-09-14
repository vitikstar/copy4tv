<?php

class ControllerCheckoutComponentLoginAuth extends Controller {
    private $error=array();
    public function index() {

        $data = $this->load->language('checkout/component/login_auth');
        $this->load->model('account/customer');
        $this->load->model('tool/image');
        if($this->customer->isLogged()){
            $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
            $customer_id = $customer_info['customer_id'];
            $data['avatar'] = ($customer_info['avatar']) ? $this->model_tool_image->resize($customer_info['avatar'],148,148) : $this->model_tool_image->resize('placeholder.png',148,148);


            $data['firstname'] = $customer_info['firstname'];
            $data['lastname'] = $customer_info['lastname'];
            $data['telephone'] = $customer_info['telephone'];
            $data['email'] = $customer_info['email'];
            $data['address'] = '';
        }else{
            exit();
        }
        $view = $this->load->view('checkout/component/login_auth', $data);
        $this->response->setOutput($view);
    }
}
