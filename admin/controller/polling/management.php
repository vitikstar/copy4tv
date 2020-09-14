<?php
class ControllerPollingManagement extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('polling/management');
        $this->load->model('polling/management');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->getList();
    }


    public function add() {
        $this->load->language('polling/management');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('polling/management');

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

            $this->model_polling_management->addPolling($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('polling/management', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getForm();
    }

    public function edit() {
        $this->load->language('polling/management');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('polling/management');

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $this->model_polling_management->editPolling($this->request->get['polling_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('polling/management', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getForm();
    }

    public function delete() {
        $this->load->language('polling/management');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('polling/management');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $polling_id) {
                $this->model_polling_management->deletePolling($polling_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('polling/management', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getList();
    }

    protected function getForm() {

        $this->load->model('setting/setting');


        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('polling/management', 'user_token=' . $this->session->data['user_token'], true)
        );
        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        if (!isset($this->request->get['polling_id'])) {
            $data['action'] = $this->url->link('polling/management/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
        } else {
            $data['action'] = $this->url->link('polling/management/edit', 'user_token=' . $this->session->data['user_token'] . '&polling_id=' . $this->request->get['polling_id'] . $url, true);
        }


        $data['cancel'] = $this->url->link('polling/management', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

        if (isset($this->request->post['module_polling_signal_device_status'])) {
            $data['module_polling_signal_device_status'] = $this->request->post['module_polling_signal_device_status'];
        } else {
            $data['module_polling_signal_device_status'] = $this->config->get('module_polling_signal_device_status');
        }



        $this->load->model('polling/management');
        $this->load->model('localisation/language');
        $data['languages'] = $this->model_localisation_language->getLanguages();

        $this->load->model('tool/image');

        $answers = array();
        $polling_info = array();
        $polling_info_description = array();

        $data['status'] = 0;

        if(isset($this->request->post['status'])){
            $data['status'] = $this->request->post['status'];
        }

        if (isset($this->request->post['answer'])) {
            $answers = $this->request->post['answer'];
        } elseif(isset($this->request->get['polling_id'])) {
            $answers = $this->model_polling_management->getPollingPosible($this->request->get['polling_id']);
            $polling_info = $this->model_polling_management->getPolling($this->request->get['polling_id']);

            $polling_info_description = $this->model_polling_management->getPollingDescription($this->request->get['polling_id']);
            $data['status'] = $polling_info['status'];
        }

        $stats = $this->model_polling_management->statsPolling($this->request->get['polling_id']);
        $data['stats'] = $stats[$this->request->get['polling_id']];
        $data['question'] = array();
        foreach ($polling_info_description as $value) {
                $data['question'][$value['language_id']] = array(
                    'question'      => $value['question'],
                );
        }

        $data['answers'] = array();

        foreach ($answers as $key => $value) {
            foreach ($value as $answer) {
                $data['answers'][$key][] = array(
                    'polling_possible'      => $answer['title'],
                    'sort_order' => $answer['sort_order']
                );
            }
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('polling/management', $data));
    }


    protected function getList() {
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'name';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('polling/management', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        $data['add'] = $this->url->link('polling/management/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['delete'] = $this->url->link('polling/management/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

        $data['pollings'] = array();

        $filter_data = array(
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $polling_total = $this->model_polling_management->getTotalPollings();

        $results = $this->model_polling_management->getPollings($filter_data);

        foreach ($results as $result) {
            $data['pollings'][] = array(
                'polling_id' => $result['polling_id'],
                'question'      => $result['question'],
                'status'    => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'edit'      => $this->url->link('polling/management/edit', 'user_token=' . $this->session->data['user_token'] . '&polling_id=' . $result['polling_id'] . $url, true)
            );
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array)$this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        $url = '';

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_name'] = $this->url->link('polling/management', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url, true);
        $data['sort_status'] = $this->url->link('polling/management', 'user_token=' . $this->session->data['user_token'] . '&sort=status' . $url, true);

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $polling_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('polling/management', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($polling_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($polling_total - $this->config->get('config_limit_admin'))) ? $polling_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $polling_total, ceil($polling_total / $this->config->get('config_limit_admin')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('polling/list', $data));
    }
    protected function validate() {
        if (!$this->user->hasPermission('modify', 'polling/management')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'polling/management')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}