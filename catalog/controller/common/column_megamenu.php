<?php
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerCommonColumnMegamenu extends Controller {
	public function index() {
		$data['modules'] = array();
			if ($this->config->get('module_oct_megamenu_status')) {
				$module_data = $this->load->controller('extension/module/oct_megamenu');
				if ($module_data) {
					$data['modules'][] = $module_data;
				}
			}
		return $this->load->view('common/column_left', $data);
	}
}
