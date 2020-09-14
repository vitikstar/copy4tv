<?php
class ControllerExtensionModulePollingPossible extends Controller {
    protected $main_polling_id;


	public function index() {

        $this->load->model('extension/module/polling_result');

	    $this->main_polling_id = $this->model_extension_module_polling_result->getMainPlollingId();


        if(!$this->checkPollingCustomer($this->main_polling_id)){
            $data=array();
            $this->load->model('extension/module/polling_result');

            $data['polling_possible'] = $this->model_extension_module_polling_result->getAnswerOptions($this->main_polling_id);


            return $this->load->view('extension/module/polling_possible', $data);
        }
	}
	public function polling(){
	    if(!$this->customer->getId()) exit;
        if($this->checkPollingCustomer($this->main_polling_id)) exit;
        if(!isset($this->request->post['answer_id'])) exit;
        if(!isset($this->request->post['polling_id'])) exit;
        $this->load->model('extension/module/polling_result');

        $this->model_extension_module_polling_result->addAnswer($this->request->post);
        $json = array();
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
	}
    protected function checkPollingCustomer($polling_id){
	    $query = $this->db->query("SELECT * FROM oc_polling_result WHERE customer_id='".$this->customer->getId()."' AND polling_id='". $polling_id ."'");
	    return ($query->row) ? true : false;
	}
}