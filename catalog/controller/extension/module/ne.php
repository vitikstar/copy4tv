<?php

class ControllerExtensionModuleNe extends Controller {

    private $_name = 'ne';
    private $template;

    public function index($setting) {
        static $module = 0;

        $this->load->model('extension/module/' . $this->_name);

        if ($setting && !empty($setting['status']) && ((!empty($setting['show_for']) && !$this->customer->getNewsletter()) || (empty($setting['show_for']) && !$this->customer->isLogged()))) {
            $this->load->language('extension/module/' . $this->_name);

            $data['config_language_id'] = $this->config->get('config_language_id');

            $data['text_subscribe'] = $this->language->get('text_subscribe');
            $data['text_close'] = $this->language->get('text_close');
            $data['text_connection_error'] = $this->language->get('text_connection_error');

            $data['entry_name'] = $this->language->get('entry_name');
            $data['entry_firstname'] = $this->language->get('entry_firstname');
            $data['entry_lastname'] = $this->language->get('entry_lastname');
            $data['entry_email'] = $this->language->get('entry_email');
            $data['entry_list'] = $this->language->get('entry_list');

            if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
                $data['subscribe'] = $this->url->link('extension/module/ne/subscribe', 'box=' . $setting['subscribe_box_id'], true);
            } else {
                $data['subscribe'] = $this->url->link('extension/module/ne/subscribe', 'box=' . $setting['subscribe_box_id']);
            }

            $data['subscribe'] = html_entity_decode($data['subscribe'], ENT_QUOTES, 'UTF-8');

            if ($this->config->get('ne_hide_marketing')) {
                $data['marketing_list'] = false;
            } else {
                $marketing_list = $this->config->get('ne_marketing_list');
                $data['marketing_list'] = isset($marketing_list[$this->config->get('config_store_id')]) ? $marketing_list[$this->config->get('config_store_id')] : array();
            }

            $data['language_id'] = $this->config->get('config_language_id');

            $data['module'] = $module++;

            $data['is_logged'] = $this->customer->isLogged();
            $data['type'] = empty($setting['type']) ? 1 : (int)$setting['type'];

            $data['border'] = !isset($setting['border']) ? 0 : (int)$setting['border'];
            $data['border_radius'] = !isset($setting['border_radius']) ? 6 : (int)$setting['border_radius'];
            $data['border_width'] = !isset($setting['border_width']) ? 1 : (int)$setting['border_width'];
            $data['border_color'] = empty($setting['border_color']) ? '#cccccc' : $setting['border_color'];
            $data['shadow'] = !isset($setting['shadow']) ? 1 : (int)$setting['shadow'];

            $data['fields'] = empty($setting['fields']) ? 1 : (int)$setting['fields'];
            $data['field_orientation'] = !isset($setting['field_orientation']) ? 0 : (int)$setting['field_orientation'];
            $data['field_border_radius'] = !isset($setting['field_border_radius']) ? 4 : (int)$setting['field_border_radius'];
            $data['field_border_color'] = empty($setting['field_border_color']) ? '#cccccc' : $setting['field_border_color'];
            $data['field_active_border_color'] = empty($setting['field_active_border_color']) ? '#66afe9' : $setting['field_active_border_color'];
            $data['button_border_radius'] = !isset($setting['button_border_radius']) ? 4 : (int)$setting['button_border_radius'];
            $data['button_background_color'] = empty($setting['button_background_color']) ? '#0090b9' : $setting['button_background_color'];
            $data['button_text_color'] = empty($setting['button_text_color']) ? '#ffffff' : $setting['button_text_color'];
            $data['button_text'] = empty($setting['button_text'][$this->config->get('config_language_id')]) ? '' : $setting['button_text'][$this->config->get('config_language_id')];
            $data['form_background_color'] = empty($setting['form_background_color']) ? '#ffffff' : $setting['form_background_color'];
            $data['form_success_text_color'] = empty($setting['form_success_text_color']) ? '#8fbb6c' : $setting['form_success_text_color'];
            $data['form_error_text_color'] = empty($setting['form_error_text_color']) ? '#f56b6b' : $setting['form_error_text_color'];
            $data['list_type'] = empty($setting['list_type']) ? 0 : (int)$setting['list_type'];
            $data['accept'] = !isset($setting['accept']) ? 0 : (int)$setting['accept'];
            $data['accept_text'] = empty($setting['accept_text'][$this->config->get('config_language_id')]) ? '' : html_entity_decode($setting['accept_text'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');

            $data['heading_text_color'] = empty($setting['heading_text_color']) ? '#ffffff' : $setting['heading_text_color'];
            $data['heading'] = empty($setting['heading'][$this->config->get('config_language_id')]) ? '' : $setting['heading'][$this->config->get('config_language_id')];
            $data['content_text_color'] = empty($setting['content_text_color']) ? '#e6e6e6' : $setting['content_text_color'];
            $data['text'] = empty($setting['text'][$this->config->get('config_language_id')]) ? '' : $setting['text'][$this->config->get('config_language_id')];
            $data['content_divider'] = empty($setting['content_divider']) ? 0 : (int)$setting['content_divider'];
            $data['content_background_color'] = empty($setting['content_background_color']) ? '#0090b7' : $setting['content_background_color'];

            $data['image'] = empty($setting['image_custom']) ? (empty($setting['image']) ? 0 : (int)$setting['image']) : $setting['image_custom'];
            $data['image_position'] = empty($setting['image_position']) ? 1 : (int)$setting['image_position'];
            $data['image_width'] = empty($setting['image_width']) ? 180 : (int)$setting['image_width'];
            $data['hide_image_on_mobile'] = empty($setting['hide_image_on_mobile']) ? 0 : (int)$setting['hide_image_on_mobile'];
            $data['image_circled'] = empty($setting['image_circled']) ? 0 : (int)$setting['image_circled'];

            $data['css_code'] = empty($setting['css_code']) ? '' : $setting['css_code'];

            $this->load->model('tool/image');

            if ($data['image'] && is_file(DIR_IMAGE . $data['image'])) {
                $data['image'] = HTTPS_SERVER . 'image/' . $data['image'];
            } elseif ($data['image'] && is_file(DIR_IMAGE . 'ne/' . $data['image'] . '.png')) {
                $data['image'] = HTTPS_SERVER . 'image/ne/' . $data['image'] . '.png';
            } else {
                $data['image'] = 0;
            }

            $data['class'] = '';

            if ($data['content_divider']) {
                $data['class'] .= 'ne-with-divider ';

                if ($data['content_divider'] == 1) {
                    $data['class'] .= 'ne-divider-triangle ';
                } elseif ($data['content_divider'] == 2) {
                    $data['class'] .= 'ne-divider-wide-triangle ';
                } elseif ($data['content_divider'] == 3) {
                    $data['class'] .= 'ne-divider-zigzag ';
                } elseif ($data['content_divider'] == 4) {
                    $data['class'] .= 'ne-divider-curve ';
                }
            }

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/stylesheet/ne/bootstrap.css')) {
                $this->document->addStyle(basename(DIR_APPLICATION) . '/view/theme/' . $this->config->get('config_template') . '/stylesheet/ne/bootstrap.css');
            } else {
                $this->document->addStyle(basename(DIR_APPLICATION) . '/view/theme/default/stylesheet/ne/bootstrap.css');
            }

            if ($data['type'] == 1) {
                $this->template = $this->_name . '/subscribe_box';
            } else {
                $data['popup_delay'] = empty($setting['popup_delay']) ? 0 : (int)$setting['popup_delay'];
                $data['popup_timeout'] = empty($setting['popup_timeout']) ? 0 : (int)$setting['popup_timeout'];
                $data['repeat_time'] = empty($setting['repeat_time']) ? 0 : (int)$setting['repeat_time'];
                $data['popup_width'] = empty($setting['popup_width']) ? 320 : (int)$setting['popup_width'];
                $data['flyin_position'] = empty($setting['flyin_position']) ? 4 : (int)$setting['flyin_position'];

                if ($data['type'] == 2 || $data['type'] == 3) {
                    $data['class'] .= 'ne-modal-popup ';
                } elseif ($data['type'] == 4) {
                    $data['class'] .= 'ne-fly-in ';

                    if ($data['flyin_position'] == 1) {
                        $data['class'] .= 'ne-top-left ';
                    } elseif ($data['flyin_position'] == 2) {
                        $data['class'] .= 'ne-top-right ';
                    } elseif ($data['flyin_position'] == 3) {
                        $data['class'] .= 'ne-bottom-left ';
                    } elseif ($data['flyin_position'] == 4) {
                        $data['class'] .= 'ne-bottom-right ';
                    }
                }

                $this->document->addScript(basename(DIR_APPLICATION) . '/view/javascript/ne/bootstrap.min.js');

                $this->document->addScript(basename(DIR_APPLICATION) . '/view/javascript/ne/jquery.slimscroll.min.js');

                if ($data['popup_timeout']) {
                    $this->document->addScript(basename(DIR_APPLICATION) . '/view/javascript/ne/jquery.countdown.js');
                }

                if ($data['type'] == '2') {
                    $this->template = $this->_name . '/subscribe_button';
                } else {
                    $this->template = $this->_name . '/subscribe_modal';
                }
            }

            $this->document->addScript(basename(DIR_APPLICATION) . '/view/javascript/ne/jquery.cookie.js');

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/stylesheet/ne/subscribe.css')) {
                $this->document->addStyle(basename(DIR_APPLICATION) . '/view/theme/' . $this->config->get('config_template') . '/stylesheet/ne/subscribe.css');
            } else {
                $this->document->addStyle(basename(DIR_APPLICATION) . '/view/theme/default/stylesheet/ne/subscribe.css');
            }

            if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
                $module_css = $this->url->link('extension/module/ne/css', 'box=' . $setting['subscribe_box_id'], true);
            } else {
                $module_css = $this->url->link('extension/module/ne/css', 'box=' . $setting['subscribe_box_id']);
            }

            $module_css = html_entity_decode($module_css, ENT_QUOTES, 'UTF-8');

            $this->document->addStyle($module_css);

            return $this->load->view($this->template, $data);
        }

        return false;
    }

    public function subscribe() {
        $subscribe_box_id = empty($this->request->get['box']) ? 0 : $this->request->get['box'];

        $this->load->model('extension/module/' . $this->_name);

        $subscribe_box_info = $this->model_extension_module_ne->getSubscribeBox($subscribe_box_id);

        if ($subscribe_box_info && !empty($subscribe_box_info['status']) && (!empty($subscribe_box_info['show_for']) || empty($subscribe_box_info['show_for']) && !$this->customer->isLogged())) {
            $this->load->language('extension/module/' . $this->_name);
            $out = array('message' => $this->language->get('text_subscribe_no_list'), 'type' => 'warning');

            if ($this->config->get('ne_hide_marketing')) {
                $marketing_list = array();
            } else {
                $marketing_list = $this->config->get('ne_marketing_list');
            }

            if (!empty($subscribe_box_info['accept']) && empty($this->request->post['accept'])) {
                $out = array('message' => (empty($subscribe_box_info['accept_error'][$this->config->get('config_language_id')]) ? $this->language->get('text_accept_error') : $subscribe_box_info['accept_error'][$this->config->get('config_language_id')]), 'type' => 'warning');
            } elseif ((isset($this->request->post['list']) && is_array($this->request->post['list']) && count($this->request->post['list'])) || empty($marketing_list)) {
                if (!empty($subscribe_box_info['show_for']) && $this->customer->isLogged()) {
                    $out = array('message' => $this->language->get('text_subscribe_success'), 'type' => 'success');
                    $this->model_extension_module_ne->subscribeLoggedIn();
                } else {
                    if ($subscribe_box_info['fields'] > 1 && empty($this->request->post['name']) || $subscribe_box_info['fields'] == 3 && empty($this->request->post['lastname'])) {
                        $out = array('message' => $this->language->get('text_subscribe_not_valid_data'), 'type' => 'warning');
                    } else {
                        $this->request->post['email'] = empty($this->request->post['email']) ? '' : $this->request->post['email'];
                        $this->request->post['email'] = htmlspecialchars($this->request->post['email']);

                        if ($this->request->post['email'] && filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
                            $result = $this->model_extension_module_ne->subscribe($this->request->post, $this->config->get($this->_name . '_key'), isset($this->request->post['list']) ? $this->request->post['list'] : array());
                            if ($result) {
                                if ($this->config->get('ne_subscribe_confirmation')) {
                                    $out = array('message' => $this->language->get('text_subscribe_confirmation'), 'type' => 'success');
                                } else {
                                    $out = array('message' => $this->language->get('text_subscribe_success'), 'type' => 'success');
                                }
                            } else {
                                $out = array('message' => $this->language->get('text_subscribe_exists'), 'type' => 'success');
                            }
                        } else {
                            $out = array('message' => $this->language->get('text_subscribe_not_valid_email'), 'type' => 'warning');
                        }
                    }
                }
            }

            $this->response->addHeader('Content-type: application/json');
            $this->response->setOutput($out ? json_encode($out) : '');
        }
    }

    public function css() {
        $subscribe_box_id = empty($this->request->get['box']) ? 0 : $this->request->get['box'];

        $this->load->model('extension/module/' . $this->_name);
        $subscribe_box_info = $this->model_extension_module_ne->getSubscribeBox($subscribe_box_id);

        $out = '';

        if ($subscribe_box_info) {
            $subscribe_box_info['field_active_border_color'] = empty($subscribe_box_info['field_active_border_color']) ? '#66afe9' : $subscribe_box_info['field_active_border_color'];
            $subscribe_box_info['content_background_color'] = empty($subscribe_box_info['content_background_color']) ? '#0090b7' : $subscribe_box_info['content_background_color'];
            $subscribe_box_info['form_background_color'] = empty($subscribe_box_info['form_background_color']) ? '#ffffff' : $subscribe_box_info['form_background_color'];
            $subscribe_box_info['button_background_color'] = empty($subscribe_box_info['button_background_color']) ? '#0090b9' : $subscribe_box_info['button_background_color'];
            $subscribe_box_info['css_code'] = empty($subscribe_box_info['css_code']) ? '' : $subscribe_box_info['css_code'];

            $out .= '.ne-bootstrap .ne-spin {';
            $out .= '   fill:' . $subscribe_box_info['button_background_color'] . ' !important;';
            $out .= '}' . PHP_EOL;

            $out .= '.ne-bootstrap .ne-submit:hover, .ne-bootstrap .ne-submit:focus {';
            $out .= '   background:' . $subscribe_box_info['button_background_color'] . ' !important;';
            $out .= '}' . PHP_EOL;

            $out .= '.ne-bootstrap .form-control:focus {';
            $out .= '   border-color:' . $subscribe_box_info['field_active_border_color'] . ' !important;';
            $out .= '}' . PHP_EOL;

            $out .= '.ne-bootstrap.ne-divider-triangle .modal-body:before {';
            $out .= '   border-top-color:' . $subscribe_box_info['content_background_color'] . ' !important;';
            $out .= '}' . PHP_EOL;

            $out .= '.ne-bootstrap.ne-divider-zigzag .modal-body:before {';
            $out .= '   background: linear-gradient(45deg, transparent 33.33%, ' . $subscribe_box_info['form_background_color'] . ' 33.33%, ' . $subscribe_box_info['form_background_color'] . ' 66.66%, transparent 66.66%), linear-gradient(-45deg, transparent 33.33%, ' . $subscribe_box_info['form_background_color'] . ' 33.33%, ' . $subscribe_box_info['form_background_color'] . ' 66.66%, transparent 66.66%) !important;';
            $out .= '   background-size: 20px 40px !important;';
            $out .= '}' . PHP_EOL;

            $out .= $subscribe_box_info['css_code'];
        }

        $this->response->addHeader('Content-type: text/css');
        $this->response->setOutput($out);
    }

}