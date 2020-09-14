<?php
class ControllerExtensionModuleSocnetauth2popup extends Controller {
	
	public function index($setting) {
	
		$this->load->language('extension/module/socnetauth2');
		
		if ($this->customer->isLogged()) {
	  		return false;
    	}
		
		if( !$this->config->get('socnetauth2_status') ) return false;
		
		if( empty( $_COOKIE['show_socauth2_popup'] ) )
		{
			$data['show_socauth2_popup'] = 1;
		}
		else
		{
			$data['show_socauth2_popup'] = 0;
		}
		
		$data['socnetauth2_mobile_control'] = $this->config->get('socnetauth2_mobile_control');
		
		
      	$data['socnetauth2_vkontakte_status'] = $this->config->get('socnetauth2_vkontakte_status');
      	$data['socnetauth2_odnoklassniki_status'] = $this->config->get('socnetauth2_odnoklassniki_status');
      	$data['socnetauth2_facebook_status'] = $this->config->get('socnetauth2_facebook_status');
      	$data['socnetauth2_twitter_status'] = $this->config->get('socnetauth2_twitter_status');
      	$data['socnetauth2_gmail_status'] = $this->config->get('socnetauth2_gmail_status');
      	$data['socnetauth2_mailru_status'] = $this->config->get('socnetauth2_mailru_status');
		
      	$data['heading_title1'] = $this->language->get('heading_title1');
      	$data['heading_title2'] = $this->language->get('heading_title2');
      	$data['text_skip'] = $this->language->get('text_skip');
		
		//$this->response->setOutput($this->load->view('extension/module/socnetauth2_popup', $data));
		return $this->load->view('extension/module/socnetauth2_popup', $data);
		
	}
}
?>