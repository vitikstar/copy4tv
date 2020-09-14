<?php

class ControllerNeSubscribeBox extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('ne/subscribe_box');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('ne/subscribe_box');

        $data['edit'] = $this->url->link('ne/subscribe_box/update', 'user_token=' . $this->session->data['user_token'] . '&id=', true);
        $data['insert'] = $this->url->link('ne/subscribe_box/insert', 'user_token=' . $this->session->data['user_token'], true);
        $data['delete'] = $this->url->link('ne/subscribe_box/delete', 'user_token=' . $this->session->data['user_token'], true);
        $data['copy'] = $this->url->link('ne/subscribe_box/copy', 'user_token=' . $this->session->data['user_token'], true);

        $data['subscribe_boxes'] = $this->model_ne_subscribe_box->getList();

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_subscribe_boxes'] = $this->language->get('text_subscribe_boxes');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_last_change'] = $this->language->get('column_last_change');
        $data['column_actions'] = $this->language->get('column_actions');
        $data['column_status'] = $this->language->get('column_status');

        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_insert'] = $this->language->get('button_insert');
        $data['button_copy'] = $this->language->get('button_copy');
        $data['button_edit'] = $this->language->get('button_edit');

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

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array)$this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        if (isset($this->session->data['warning'])) {
            $data['error_warning'] = $this->session->data['warning'];
            unset($this->session->data['warning']);
        }

        $this->load->language('extension/extension');

        $data['text_layout'] = sprintf($this->language->get('text_layout'), $this->url->link('design/layout', 'user_token=' . $this->session->data['user_token'], true));

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('ne/subscribe_box_list', $data));
    }

    public function update() {
        $this->load->language('ne/subscribe_box');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('ne/subscribe_box');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_ne_subscribe_box->save(array_merge($this->request->post, array('id' => $this->request->get['id'])));
            $this->model_ne_subscribe_box->refreshModules($this->language->get('text_subscribe_box') . ' - ');
            $this->session->data['success'] = $this->language->get('text_success_save');

            $this->response->redirect($this->url->link('ne/subscribe_box', 'user_token=' . $this->session->data['user_token'], true));
        }

        $this->form();
    }

    public function insert() {
        $this->load->language('ne/subscribe_box');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('ne/subscribe_box');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_ne_subscribe_box->save($this->request->post);
            $this->model_ne_subscribe_box->refreshModules($this->language->get('text_subscribe_box') . ' - ');
            $this->session->data['success'] = $this->language->get('text_success_save');

            $this->response->redirect($this->url->link('ne/subscribe_box', 'user_token=' . $this->session->data['user_token'], true));
        }

        $this->form();
    }

    private function form() {
        if (isset($this->request->get['id'])) {
            $subscribe_box_id = $this->request->get['id'];
            $subscribe_box_info = $this->model_ne_subscribe_box->get($subscribe_box_id);
        } else {
            $subscribe_box_info = array();
        }

        $this->document->addScript('view/javascript/ne/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js');
        $this->document->addStyle('view/javascript/ne/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['entry_field_orientation'] = $this->language->get('entry_field_orientation');
        $data['entry_field_border_radius'] = $this->language->get('entry_field_border_radius');
        $data['entry_field_border_color'] = $this->language->get('entry_field_border_color');
        $data['entry_field_active_border_color'] = $this->language->get('entry_field_active_border_color');
        $data['entry_button_border_radius'] = $this->language->get('entry_button_border_radius');
        $data['entry_background_color'] = $this->language->get('entry_background_color');
        $data['entry_button_text'] = $this->language->get('entry_button_text');
        $data['entry_text_color'] = $this->language->get('entry_text_color');
        $data['entry_heading_text'] = $this->language->get('entry_heading_text');
        $data['entry_hide_on_mobile'] = $this->language->get('entry_hide_on_mobile');
        $data['entry_content_divider'] = $this->language->get('entry_content_divider');
        $data['entry_border'] = $this->language->get('entry_border');
        $data['entry_border_radius'] = $this->language->get('entry_border_radius');
        $data['entry_border_width'] = $this->language->get('entry_border_width');
        $data['entry_border_color'] = $this->language->get('entry_border_color');
        $data['entry_shadow'] = $this->language->get('entry_shadow');
        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_show_for'] = $this->language->get('entry_show_for');
        $data['entry_fields'] = $this->language->get('entry_fields');
        $data['entry_text'] = $this->language->get('entry_text');
        $data['entry_type'] = $this->language->get('entry_type');
        $data['entry_popup_delay'] = $this->language->get('entry_popup_delay');
        $data['entry_popup_timeout'] = $this->language->get('entry_popup_timeout');
        $data['entry_popup_repeat_time'] = $this->language->get('entry_popup_repeat_time');
        $data['entry_popup_width'] = $this->language->get('entry_popup_width');
        $data['entry_list_type'] = $this->language->get('entry_list_type');
        $data['entry_position'] = $this->language->get('entry_position');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_custom_image'] = $this->language->get('entry_custom_image');
        $data['entry_button_background_color'] = $this->language->get('entry_button_background_color');
        $data['entry_button_text_color'] = $this->language->get('entry_button_text_color');
        $data['entry_heading_text_color'] = $this->language->get('entry_heading_text_color');
        $data['entry_success_text_color'] = $this->language->get('entry_success_text_color');
        $data['entry_error_text_color'] = $this->language->get('entry_error_text_color');
        $data['entry_css_code'] = $this->language->get('entry_css_code');
        $data['entry_image_width'] = $this->language->get('entry_image_width');
        $data['entry_image_circled'] = $this->language->get('entry_image_circled');
        $data['entry_accept'] = $this->language->get('entry_accept');
        $data['entry_accept_text'] = $this->language->get('entry_accept_text');
        $data['entry_accept_error'] = $this->language->get('entry_accept_error');

        $data['help_popup_delay'] = $this->language->get('help_popup_delay');
        $data['help_popup_timeout'] = $this->language->get('help_popup_timeout');
        $data['help_popup_repeat_time'] = $this->language->get('help_popup_repeat_time');

        $data['text_form_settings'] = $this->language->get('text_form_settings');
        $data['text_stacked'] = $this->language->get('text_stacked');
        $data['text_stacked_compact'] = $this->language->get('text_stacked_compact');
        $data['text_inline'] = $this->language->get('text_inline');
        $data['text_subscribe'] = $this->language->get('text_subscribe');
        $data['text_form_success'] = $this->language->get('text_form_success');
        $data['text_form_error'] = $this->language->get('text_form_error');
        $data['text_content_settings'] = $this->language->get('text_content_settings');
        $data['text_content_heading'] = $this->language->get('text_content_heading');
        $data['text_content_text'] = $this->language->get('text_content_text');
        $data['text_custom_image_text'] = $this->language->get('text_custom_image_text');
        $data['text_content_image_settings'] = $this->language->get('text_content_image_settings');
        $data['text_box_settings'] = $this->language->get('text_box_settings');
        $data['text_everyone'] = $this->language->get('text_everyone');
        $data['text_settings'] = $this->language->get('text_settings');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_all'] = $this->language->get('text_all');
        $data['text_guests'] = $this->language->get('text_guests');
        $data['text_only_email'] = $this->language->get('text_only_email');
        $data['text_email_name'] = $this->language->get('text_email_name');
        $data['text_email_full'] = $this->language->get('text_email_full');
        $data['text_content_box'] = $this->language->get('text_content_box');
        $data['text_modal_popup'] = $this->language->get('text_modal_popup');
        $data['text_flyin_popup'] = $this->language->get('text_flyin_popup');
        $data['text_content_box_to_modal'] = $this->language->get('text_content_box_to_modal');
        $data['text_popup_settings'] = $this->language->get('text_popup_settings');
        $data['text_checkboxes'] = $this->language->get('text_checkboxes');
        $data['text_radio_buttons'] = $this->language->get('text_radio_buttons');
        $data['text_subscribe_box'] = $this->language->get('text_subscribe_box');
        $data['text_top_left'] = $this->language->get('text_top_left');
        $data['text_top_right'] = $this->language->get('text_top_right');
        $data['text_bottom_left'] = $this->language->get('text_bottom_left');
        $data['text_bottom_right'] = $this->language->get('text_bottom_right');
        $data['text_top'] = $this->language->get('text_top');
        $data['text_bottom'] = $this->language->get('text_bottom');
        $data['text_left'] = $this->language->get('text_left');
        $data['text_right'] = $this->language->get('text_right');
        $data['text_above_text'] = $this->language->get('text_above_text');
        $data['text_below_text'] = $this->language->get('text_below_text');
        $data['text_left_from_text'] = $this->language->get('text_left_from_text');
        $data['text_right_from_text'] = $this->language->get('text_right_from_text');
        $data['text_none'] = $this->language->get('text_none');
        $data['text_triangle'] = $this->language->get('text_triangle');
        $data['text_wide_triangle'] = $this->language->get('text_wide_triangle');
        $data['text_zigzag'] = $this->language->get('text_zigzag');
        $data['text_curve'] = $this->language->get('text_curve');
        $data['text_custom_styles'] = $this->language->get('text_custom_styles');
        $data['text_accept'] = $this->language->get('text_accept');
        $data['text_accept_text'] = $this->language->get('text_accept_text');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = $this->config->get('ne_warning');
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        if (isset($this->request->post['field_orientation'])) {
            $data['field_orientation'] = $this->request->post['field_orientation'];
        } elseif (isset($subscribe_box_info['field_orientation'])) {
            $data['field_orientation'] = (int)$subscribe_box_info['field_orientation'];
        } else {
            $data['field_orientation'] = 0;
        }

        if (isset($this->request->post['field_border_radius'])) {
            $data['field_border_radius'] = $this->request->post['field_border_radius'];
        } elseif (isset($subscribe_box_info['field_border_radius'])) {
            $data['field_border_radius'] = (int)$subscribe_box_info['field_border_radius'];
        } else {
            $data['field_border_radius'] = 4;
        }

        if (isset($this->request->post['field_border_color'])) {
            $data['field_border_color'] = $this->request->post['field_border_color'];
        } elseif (!empty($subscribe_box_info['field_border_color'])) {
            $data['field_border_color'] = $subscribe_box_info['field_border_color'];
        } else {
            $data['field_border_color'] = '#CCCCCC';
        }

        if (isset($this->request->post['field_active_border_color'])) {
            $data['field_active_border_color'] = $this->request->post['field_active_border_color'];
        } elseif (!empty($subscribe_box_info['field_active_border_color'])) {
            $data['field_active_border_color'] = $subscribe_box_info['field_active_border_color'];
        } else {
            $data['field_active_border_color'] = '#66AFE9';
        }

        if (isset($this->request->post['button_border_radius'])) {
            $data['button_border_radius'] = $this->request->post['button_border_radius'];
        } elseif (isset($subscribe_box_info['button_border_radius'])) {
            $data['button_border_radius'] = (int)$subscribe_box_info['button_border_radius'];
        } else {
            $data['button_border_radius'] = 4;
        }

        if (isset($this->request->post['form_background_color'])) {
            $data['form_background_color'] = $this->request->post['form_background_color'];
        } elseif (!empty($subscribe_box_info['form_background_color'])) {
            $data['form_background_color'] = $subscribe_box_info['form_background_color'];
        } else {
            $data['form_background_color'] = '#ffffff';
        }

        if (isset($this->request->post['button_text'])) {
            $data['button_text'] = $this->request->post['button_text'];
        } elseif (!empty($subscribe_box_info['button_text'])) {
            $data['button_text'] = $subscribe_box_info['button_text'];
        } else {
            $data['button_text'] = array();
        }

        if (isset($this->request->post['accept'])) {
            $data['accept'] = $this->request->post['accept'];
        } elseif (isset($subscribe_box_info['accept'])) {
            $data['accept'] = (int)$subscribe_box_info['accept'];
        } else {
            $data['accept'] = 0;
        }

        if (isset($this->request->post['accept_text'])) {
            $data['accept_text'] = $this->request->post['accept_text'];
        } elseif (!empty($subscribe_box_info['accept_text'])) {
            $data['accept_text'] = $subscribe_box_info['accept_text'];
        } else {
            $data['accept_text'] = array();
        }

        if (isset($this->request->post['accept_error'])) {
            $data['accept_error'] = $this->request->post['accept_error'];
        } elseif (!empty($subscribe_box_info['accept_error'])) {
            $data['accept_error'] = $subscribe_box_info['accept_error'];
        } else {
            $data['accept_error'] = array();
        }

        if (isset($this->request->post['hide_image_on_mobile'])) {
            $data['hide_image_on_mobile'] = $this->request->post['hide_image_on_mobile'];
        } elseif (isset($subscribe_box_info['hide_image_on_mobile'])) {
            $data['hide_image_on_mobile'] = (int)$subscribe_box_info['hide_image_on_mobile'];
        } else {
            $data['hide_image_on_mobile'] = 1;
        }

        if (isset($this->request->post['image_circled'])) {
            $data['image_circled'] = $this->request->post['image_circled'];
        } elseif (isset($subscribe_box_info['image_circled'])) {
            $data['image_circled'] = (int)$subscribe_box_info['image_circled'];
        } else {
            $data['image_circled'] = 0;
        }

        if (isset($this->request->post['content_divider'])) {
            $data['content_divider'] = $this->request->post['content_divider'];
        } elseif (isset($subscribe_box_info['content_divider'])) {
            $data['content_divider'] = (int)$subscribe_box_info['content_divider'];
        } else {
            $data['content_divider'] = 0;
        }

        if (isset($this->request->post['content_background_color'])) {
            $data['content_background_color'] = $this->request->post['content_background_color'];
        } elseif (!empty($subscribe_box_info['content_background_color'])) {
            $data['content_background_color'] = $subscribe_box_info['content_background_color'];
        } else {
            $data['content_background_color'] = '#0090b7';
        }

        if (isset($this->request->post['border'])) {
            $data['border'] = $this->request->post['border'];
        } elseif (isset($subscribe_box_info['border'])) {
            $data['border'] = (int)$subscribe_box_info['border'];
        } else {
            $data['border'] = 0;
        }

        if (isset($this->request->post['border_radius'])) {
            $data['border_radius'] = $this->request->post['border_radius'];
        } elseif (isset($subscribe_box_info['border_radius'])) {
            $data['border_radius'] = (int)$subscribe_box_info['border_radius'];
        } else {
            $data['border_radius'] = 6;
        }

        if (isset($this->request->post['border_width'])) {
            $data['border_width'] = $this->request->post['border_width'];
        } elseif (isset($subscribe_box_info['border_width'])) {
            $data['border_width'] = (int)$subscribe_box_info['border_width'];
        } else {
            $data['border_width'] = 1;
        }

        if (isset($this->request->post['image_width'])) {
            $data['image_width'] = $this->request->post['image_width'];
        } elseif (isset($subscribe_box_info['image_width'])) {
            $data['image_width'] = (int)$subscribe_box_info['image_width'];
        } else {
            $data['image_width'] = 180;
        }

        if (isset($this->request->post['border_color'])) {
            $data['border_color'] = $this->request->post['border_color'];
        } elseif (!empty($subscribe_box_info['border_color'])) {
            $data['border_color'] = $subscribe_box_info['border_color'];
        } else {
            $data['border_color'] = '#CCCCCC';
        }

        if (isset($this->request->post['shadow'])) {
            $data['shadow'] = $this->request->post['shadow'];
        } elseif (isset($subscribe_box_info['shadow'])) {
            $data['shadow'] = (int)$subscribe_box_info['shadow'];
        } else {
            $data['shadow'] = 1;
        }

        if (isset($this->request->post['css_code'])) {
            $data['css_code'] = $this->request->post['css_code'];
        } elseif (!empty($subscribe_box_info['css_code'])) {
            $data['css_code'] = $subscribe_box_info['css_code'];
        } else {
            $data['css_code'] = '';
        }

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (!empty($subscribe_box_info['name'])) {
            $data['name'] = $subscribe_box_info['name'];
        } else {
            $data['name'] = '';
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (isset($subscribe_box_info['status'])) {
            $data['status'] = (int)$subscribe_box_info['status'];
        } else {
            $data['status'] = 0;
        }

        if (isset($this->request->post['show_for'])) {
            $data['show_for'] = $this->request->post['show_for'];
        } elseif (isset($subscribe_box_info['show_for'])) {
            $data['show_for'] = (int)$subscribe_box_info['show_for'];
        } else {
            $data['show_for'] = 0;
        }

        if (isset($this->request->post['fields'])) {
            $data['fields'] = $this->request->post['fields'];
        } elseif (!empty($subscribe_box_info['fields'])) {
            $data['fields'] = (int)$subscribe_box_info['fields'];
        } else {
            $data['fields'] = 1;
        }

        if (isset($this->request->post['type'])) {
            $data['type'] = $this->request->post['type'];
        } elseif (!empty($subscribe_box_info['type'])) {
            $data['type'] = (int)$subscribe_box_info['type'];
        } else {
            $data['type'] = 1;
        }

        if (isset($this->request->post['list_type'])) {
            $data['list_type'] = $this->request->post['list_type'];
        } elseif (isset($subscribe_box_info['list_type'])) {
            $data['list_type'] = (int)$subscribe_box_info['list_type'];
        } else {
            $data['list_type'] = 0;
        }

        if (isset($this->request->post['popup_delay'])) {
            $data['popup_delay'] = $this->request->post['popup_delay'];
        } elseif (isset($subscribe_box_info['popup_delay'])) {
            $data['popup_delay'] = (int)$subscribe_box_info['popup_delay'];
        } else {
            $data['popup_delay'] = 0;
        }

        if (isset($this->request->post['popup_timeout'])) {
            $data['popup_timeout'] = $this->request->post['popup_timeout'];
        } elseif (isset($subscribe_box_info['popup_timeout'])) {
            $data['popup_timeout'] = (int)$subscribe_box_info['popup_timeout'];
        } else {
            $data['popup_timeout'] = 0;
        }

        if (isset($this->request->post['repeat_time'])) {
            $data['repeat_time'] = $this->request->post['repeat_time'];
        } elseif (isset($subscribe_box_info['repeat_time'])) {
            $data['repeat_time'] = (int)$subscribe_box_info['repeat_time'];
        } else {
            $data['repeat_time'] = 1;
        }

        if (isset($this->request->post['flyin_position'])) {
            $data['flyin_position'] = $this->request->post['flyin_position'];
        } elseif (!empty($subscribe_box_info['flyin_position'])) {
            $data['flyin_position'] = (int)$subscribe_box_info['flyin_position'];
        } else {
            $data['flyin_position'] = 4;
        }

        if (isset($this->request->post['popup_width'])) {
            $data['popup_width'] = $this->request->post['popup_width'];
        } elseif (isset($subscribe_box_info['popup_width'])) {
            $data['popup_width'] = (int)$subscribe_box_info['popup_width'];
        } else {
            $data['popup_width'] = 320;
        }

        if (isset($this->request->post['heading'])) {
            $data['heading'] = $this->request->post['heading'];
        } elseif (!empty($subscribe_box_info['heading'])) {
            $data['heading'] = $subscribe_box_info['heading'];
        } else {
            $data['heading'] = array();
        }

        if (isset($this->request->post['text'])) {
            $data['text'] = $this->request->post['text'];
        } elseif (!empty($subscribe_box_info['text'])) {
            $data['text'] = $subscribe_box_info['text'];
        } else {
            $data['text'] = array();
        }

        if (isset($this->request->post['heading_text_color'])) {
            $data['heading_text_color'] = $this->request->post['heading_text_color'];
        } elseif (!empty($subscribe_box_info['heading_text_color'])) {
            $data['heading_text_color'] = $subscribe_box_info['heading_text_color'];
        } else {
            $data['heading_text_color'] = '#000000';
        }

        if (isset($this->request->post['content_text_color'])) {
            $data['content_text_color'] = $this->request->post['content_text_color'];
        } elseif (!empty($subscribe_box_info['content_text_color'])) {
            $data['content_text_color'] = $subscribe_box_info['content_text_color'];
        } else {
            $data['content_text_color'] = '#222222';
        }

        if (isset($this->request->post['button_background_color'])) {
            $data['button_background_color'] = $this->request->post['button_background_color'];
        } elseif (!empty($subscribe_box_info['button_background_color'])) {
            $data['button_background_color'] = $subscribe_box_info['button_background_color'];
        } else {
            $data['button_background_color'] = '#0090B9';
        }

        if (isset($this->request->post['button_text_color'])) {
            $data['button_text_color'] = $this->request->post['button_text_color'];
        } elseif (!empty($subscribe_box_info['button_text_color'])) {
            $data['button_text_color'] = $subscribe_box_info['button_text_color'];
        } else {
            $data['button_text_color'] = '#FFFFFF';
        }

        if (isset($this->request->post['form_success_text_color'])) {
            $data['form_success_text_color'] = $this->request->post['form_success_text_color'];
        } elseif (!empty($subscribe_box_info['form_success_text_color'])) {
            $data['form_success_text_color'] = $subscribe_box_info['form_success_text_color'];
        } else {
            $data['form_success_text_color'] = '#8FBB6C';
        }

        if (isset($this->request->post['form_error_text_color'])) {
            $data['form_error_text_color'] = $this->request->post['form_error_text_color'];
        } elseif (!empty($subscribe_box_info['form_error_text_color'])) {
            $data['form_error_text_color'] = $subscribe_box_info['form_error_text_color'];
        } else {
            $data['form_error_text_color'] = '#F56B6B';
        }

        if (isset($this->request->post['image'])) {
            $data['image'] = $this->request->post['image'];
        } elseif (isset($subscribe_box_info['image'])) {
            $data['image'] = (int)$subscribe_box_info['image'];
        } else {
            $data['image'] = 0;
        }

        if (isset($this->request->post['image_position'])) {
            $data['image_position'] = $this->request->post['image_position'];
        } elseif (!empty($subscribe_box_info['image_position'])) {
            $data['image_position'] = (int)$subscribe_box_info['image_position'];
        } else {
            $data['image_position'] = 1;
        }

        $this->load->model('tool/image');

        if (isset($this->request->post['image_custom']) && is_file(DIR_IMAGE . $this->request->post['image_custom'])) {
            $data['thumb'] = $this->model_tool_image->resize($this->request->post['image_custom'], 100, 100);
        } elseif (isset($subscribe_box_info['image_custom']) && is_file(DIR_IMAGE . $subscribe_box_info['image_custom'])) {
            $data['thumb'] = $this->model_tool_image->resize($subscribe_box_info['image_custom'], 100, 100);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('ne/no_image.png', 100, 100);
        }

        if (isset($this->request->post['image_custom'])) {
            $data['image_custom'] = $this->request->post['image_custom'];
        } elseif (!empty($subscribe_box_info['image_custom'])) {
            $data['image_custom'] = $subscribe_box_info['image_custom'];
        } else {
            $data['image_custom'] = '';
        }

        $data['placeholder'] = $this->model_tool_image->resize('ne/no_image.png', 100, 100);

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        if (!isset($subscribe_box_id)) {
            $data['action'] = $this->url->link('ne/subscribe_box/insert', 'user_token=' . $this->session->data['user_token'], true);
        } else {
            $data['action'] = $this->url->link('ne/subscribe_box/update', 'user_token=' . $this->session->data['user_token'] . '&id=' . $subscribe_box_id, true);
        }

        $data['cancel'] = $this->url->link('ne/subscribe_box', 'user_token=' . $this->session->data['user_token'], true);

        $data['user_token'] = $this->session->data['user_token'];

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = $this->config->get('ne_warning');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('ne/subscribe_box_form', $data));
    }

    public function delete() {
        $this->load->language('ne/subscribe_box');
        $this->load->model('ne/subscribe_box');

        if (isset($this->request->post['selected']) && $this->validate()) {
            foreach ($this->request->post['selected'] as $subscribe_box_id) {
                if (!$this->model_ne_subscribe_box->delete($subscribe_box_id)) {
                    $this->error['warning'] = $this->language->get('error_delete');
                }
            }
            if (isset($this->error['warning'])) {
                $this->session->data['warning'] = $this->error['warning'];
            } else {
                $this->session->data['success'] = $this->language->get('text_success');
            }
            $this->model_ne_subscribe_box->refreshModules($this->language->get('text_subscribe_box') . ' - ');
        }

        $this->response->redirect($this->url->link('ne/subscribe_box', 'user_token=' . $this->session->data['user_token'], true));
    }

    public function copy() {
        if (isset($this->request->post['selected']) && $this->validate()) {
            $this->load->language('ne/subscribe_box');
            $this->load->model('ne/subscribe_box');

            foreach ($this->request->post['selected'] as $subscribe_box_id) {
                if (!$this->model_ne_subscribe_box->copy($subscribe_box_id)) {
                    $this->error['warning'] = $this->language->get('error_copy');
                }
            }
            if (isset($this->error['warning'])) {
                $this->session->data['warning'] = $this->error['warning'];
            } else {
                $this->session->data['success'] = $this->language->get('text_success_copy');
            }
            $this->model_ne_subscribe_box->refreshModules($this->language->get('text_subscribe_box') . ' - ');
        }

        $this->response->redirect($this->url->link('ne/subscribe_box', 'user_token=' . $this->session->data['user_token'], true));
    }

    private function validateSave() {
        if (!$this->user->hasPermission('modify', 'ne/subscribe_box')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    private function validateForm() {
        if (!$this->user->hasPermission('modify', 'ne/subscribe_box')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (empty($this->request->post['name'])) {
            $this->error['name'] = $this->language->get('error_name');
        }

        return !$this->error;
    }

    private function validate() {
        if (!$this->user->hasPermission('modify', 'ne/subscribe_box')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

}