<?php
class ControllerExtensionModulesocnetauth2popup extends Controller {

	public function index() 
	{
		$this->response->redirect(HTTPS_SERVER . 'index.php?route=extension/module/socnetauth2&user_token=' . $this->session->data['user_token']);
	}
	
	public function install()
	{
		$this->response->redirect(HTTPS_SERVER . 'index.php?route=extension/extension/module/install&user_token='.$this->session->data['user_token'].
		'&extension=socnetauth2');
	}
	
	public function uninstall()
	{
		$this->response->redirect(HTTPS_SERVER . 'index.php?route=extension/extension/module/uninstall&user_token='.$this->session->data['user_token'].
		'&extension=socnetauth2');
	}
}

?>