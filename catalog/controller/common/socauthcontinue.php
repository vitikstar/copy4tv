<?php
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerCommonSocauthcontinue extends Controller
{
        public function index()
        {
                $data = array();
                $route = (isset($this->request->get['redirect_uri'])) ? $this->request->get['redirect_uri'] : 'account/account';
                $data['redirect_uri'] = $this->url->link($route, '', true);
                $this->response->setOutput($this->load->view('common/socauthcontinue', $data));
        }
}
