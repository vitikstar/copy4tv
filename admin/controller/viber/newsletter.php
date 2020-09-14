<?php
class ControllerViberNewsletter extends Controller {
    private $error = array();

    public function index() {


            $this->document->setTitle("Viber рассылка настройки");

            $this->load->model('setting/setting');

            if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
                $this->model_setting_setting->editSetting('viber_newsletter', $this->request->post);


                $this->session->data['success'] = "Сохранено";

                $this->response->redirect($this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'] , true));
            }


            if (isset($this->error['viber_newsletter_gateway_auth_token'])) {
                $data['error_viber_newsletter_gateway_auth_token'] = $this->error['viber_newsletter_gateway_auth_token'];
            } else {
                $data['error_viber_newsletter_gateway_auth_token'] = '';
            }
            if (isset($this->error['viber_newsletter_gateway_sender_name'])) {
                $data['error_viber_newsletter_gateway_sender_name'] = $this->error['viber_newsletter_gateway_sender_name'];
            } else {
                $data['error_viber_newsletter_gateway_sender_name'] = '';
            }


            $data['breadcrumbs'] = array();

            $data['breadcrumbs'][] = array(
                'text' => "Главная",
                'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
            );


            $data['breadcrumbs'][] = array(
                'text' => "Viber рассылка настройки",
                'href' => $this->url->link('viber/newsletter', 'user_token=' . $this->session->data['user_token'], true)
            );

            $data['action'] = $this->url->link('viber/newsletter', 'user_token=' . $this->session->data['user_token'], true);

            $data['cancel'] = $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'] , true);



            if (isset($this->request->post['viber_newsletter_gateway_auth_token'])) {
                $data['viber_newsletter_gateway_auth_token'] = $this->request->post['viber_newsletter_gateway_auth_token'];
            } else {
                $data['viber_newsletter_gateway_auth_token'] = $this->config->get('viber_newsletter_gateway_auth_token');
            }

            if (isset($this->request->post['viber_newsletter_gateway_sender_name'])) {
                $data['viber_newsletter_gateway_sender_name'] = $this->request->post['viber_newsletter_gateway_sender_name'];
            } else {
                $data['viber_newsletter_gateway_sender_name'] = $this->config->get('viber_newsletter_gateway_sender_name');
            }


            $data['header'] = $this->load->controller('common/header');
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['footer'] = $this->load->controller('common/footer');

            $this->response->setOutput($this->load->view('viber/newsletter', $data));
        }
    protected function validate() {

        if (!$this->request->post['viber_newsletter_gateway_auth_token']) {
            $this->error['viber_newsletter_gateway_auth_token'] = "Заполните";
        }
        if (!$this->request->post['viber_newsletter_gateway_sender_name']) {
            $this->error['viber_newsletter_gateway_sender_name'] = "Заполните";
        }


        return !$this->error;
    }
}