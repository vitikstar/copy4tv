<?php
/* All rights reserved belong to the module, the module developers http://opencartadmin.com */
// https://opencartadmin.com © 2011-2019 All Rights Reserved
// Distribution, without the author's consent is prohibited
// Commercial license
class ControllerAgooaAuthor extends Controller
{
	private $error = array();
	protected $data;

	public function authorcomplete()
	{
		if ($this->validate()) {

	        if (file_exists(DIR_APPLICATION.'model/sale/customer.php')) {
	        	$this->load->model('sale/customer');
				$model_customer = 'model_sale_customer';
			} else {
				$this->load->model('customer/customer');
				$model_customer = 'model_customer_customer';
			}

			$json               = array();
			if (isset($this->request->get['filter_name'])) {

				if (isset($this->request->get['filter_name'])) {
					$filter_name = $this->request->get['filter_name'];
				} else {
					$filter_name = '';
				}

				if (isset($this->request->get['limit'])) {
					$limit = $this->request->get['limit'];
				} else {
					$limit = 20;
				}
				$data    = array(
					'filter_name' => $filter_name,
					'start' => 0,
					'limit' => $limit
				);

				$results = $this->$model_customer->getCustomers($data);
				foreach ($results as $result) {
					$json[] = array(
						'customer_id' => $result['customer_id'],
						'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
					);
				}
			}

			$this->response->setOutput(json_encode($json));
		}
	}

	private function validate()
	{
		if (!$this->user->hasPermission('modify', 'agooa/author')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	public function cont($cont)
	{
		$file  = DIR_CATALOG . 'controller/' . $cont . '.php';
		if (file_exists($file)) {
           $this->cont_loading($cont, $file);
		} else {
			$file  = DIR_APPLICATION . 'controller/' . $cont . '.php';
            if (file_exists($file)) {
             	$this->cont_loading($cont, $file);
            } else {
				trigger_error('Error: Could not load controller ' . $cont . '!');
				exit();
			}
		}
	}
	private function cont_loading ($cont, $file)
	{
			$class = 'Controller' . preg_replace('/[^a-zA-Z0-9]/', '', $cont);
			include_once($file);
			$this->registry->set('controller_' . str_replace('/', '_', $cont), new $class($this->registry));
	}
}
