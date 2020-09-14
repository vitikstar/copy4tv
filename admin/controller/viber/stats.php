<?php

class ControllerViberStats extends Controller
{
    private $error = array();

    public function index()
    {

        $this->load->model('viber/stats');


        $this->load->language('viber/stats');

        $this->document->setTitle($this->language->get('heading_title'));


        if (isset($this->request->get['filter_date'])) {
            $filter_date = $this->request->get['filter_date'];
        } else {
            $filter_date = null;
        }



        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'datetime';
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

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . $this->request->get['filter_name'];
        }
        if (isset($this->request->get['filter_telephone'])) {
            $url .= '&filter_telephone=' . $this->request->get['filter_telephone'];
        }


        if (isset($this->request->get['filter_subscribed'])) {
            $url .= '&filter_subscribed=' . $this->request->get['filter_subscribed'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }


        $data_param = array(
            'filter_date' => $filter_date,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $marketings_total = $this->model_viber_stats->getTotal($data_param);



        $data['response_result'] = $this->model_viber_stats->getList($data_param);


        $data['heading_title'] = $this->language->get('heading_title');

        $data['entry_yes'] = $this->language->get('entry_yes');
        $data['entry_no'] = $this->language->get('entry_no');
        $data['entry_subscribers'] = $this->language->get('entry_subscribers');
        $data['help_subscribers'] = $this->language->get('help_subscribers');

        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_add_info'] = $this->language->get('text_add_info');
        $data['text_default'] = $this->language->get('text_default');
        $data['text_marketing_subscribers'] = $this->language->get('text_marketing_subscribers');
        $data['text_marketing_subscribers_list'] = $this->language->get('text_marketing_subscribers_list');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_telephone'] = $this->language->get('column_telephone');
        $data['column_subscribed'] = $this->language->get('column_subscribed');
        $data['column_list'] = $this->language->get('column_list');
        $data['column_actions'] = $this->language->get('column_actions');
        $data['column_store'] = $this->language->get('column_store');
        $data['column_language'] = $this->language->get('column_language');



        $data['user_token'] = $this->session->data['user_token'];


        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = $this->config->get('ne_warning');
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . $this->request->get['filter_name'];
        }


        if (isset($this->request->get['filter_subscribed'])) {
            $url .= '&filter_subscribed=' . $this->request->get['filter_subscribed'];
        }


        if ($order == 'ASC') {
            $url .= '&order=' . 'DESC';
        } else {
            $url .= '&order=' . 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_name'] = $this->url->link('viber/stats', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url, true);
        $data['sort_telephone'] = $this->url->link('viber/stats', 'user_token=' . $this->session->data['user_token'] . '&sort=telephone' . $url, true);
        $data['sort_subscribed'] = $this->url->link('viber/stats', 'user_token=' . $this->session->data['user_token'] . '&sort=ntd.name' . $url, true);
        $data['sort_store'] = $this->url->link('viber/stats', 'user_token=' . $this->session->data['user_token'] . '&sort=store_id' . $url, true);
        $data['sort_language'] = $this->url->link('viber/stats', 'user_token=' . $this->session->data['user_token'] . '&sort=language_code' . $url, true);

        $url = '';


        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . $this->request->get['filter_name'];
        }
        if (isset($this->request->get['filter_telephone'])) {
            $url .= '&filter_telephone=' . $this->request->get['filter_telephone'];
        }


        if (isset($this->request->get['filter_subscribed'])) {
            $url .= '&filter_subscribed=' . $this->request->get['filter_subscribed'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array)$this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        $pagination = new Pagination();
        $pagination->total = $marketings_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('viber/stats', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($marketings_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($marketings_total - $this->config->get('config_limit_admin'))) ? $marketings_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $marketings_total, ceil($marketings_total / $this->config->get('config_limit_admin')));

        $this->load->model('setting/store');

        $data['stores'] = $this->model_setting_store->getStores();

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();


        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');


        $this->response->setOutput($this->load->view('viber/stats', $data));
    }
}