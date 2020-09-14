<?php

require_once( DIR_SYSTEM . "/engine/sirius_controller.php");


class ControllerExtensionModuleSiriusCustomFooter extends SiriusController {

    private $error = array();

    public function __construct($registry) {
        parent::__construct($registry);
        $this->_moduleName = "SIRIUS Custom Footer";
        $this->_moduleSysName = "sirius_custom_footer";
        $this->_logFile = $this->_moduleSysName . ".log";
        $this->debug = $this->config->get($this->_moduleSysName . "_debug");
    }

    public function index() {

        require_once(DIR_SYSTEM. '/engine/sirius_view.php');
        
        $data = $this->language->load('extension/module/' . $this->_moduleSysName);

        $this->document->setTitle($this->language->get('heading_title_raw'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

            $this->saveMenu();


            $this->model_setting_setting->editSetting("module_".$this->_moduleSysName, $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            if (isset($this->request->get['close'])) {
                $this->response->redirect($this->url->link('extension/module', 'user_token=' . $this->session->data['user_token'], 'SSL'));
            } else {
                $this->response->redirect($this->url->link('extension/module/' . $this->_moduleSysName, 'user_token=' . $this->session->data['user_token'], 'SSL'));
            }
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        }

        $data = $this->initBreadcrumbs(array(
            array("extension/module", "text_module"),
            array('module/' . $this->_moduleSysName, "heading_title_raw")
        ), $data);

        $data['action'] = $this->url->link('extension/module/' . $this->_moduleSysName, 'user_token=' . $this->session->data['user_token'], 'SSL');
        $data['cancel'] = $this->url->link('extension/module', 'user_token=' . $this->session->data['user_token'], 'SSL');
        $data['clear'] = $this->url->link('extension/module/' . $this->_moduleSysName . '/clear', 'user_token=' . $this->session->data['user_token'], 'SSL');
        $data['save'] = $this->url->link('extension/module/' . $this->_moduleSysName, 'user_token=' . $this->session->data['user_token'], 'SSL');
        $data['save_and_close'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . "&close=1", 'SSL');
        $data['close'] = $this->url->link('extension/module', 'user_token=' . $this->session->data['user_token'], 'SSL');


        $data = $this->initParams(array(
            array("module_".$this->_moduleSysName . "_status", 1),
            array("module_".$this->_moduleSysName . "_debug", 0),
            array("module_".$this->_moduleSysName . "_sections", array()),
            array("module_".$this->_moduleSysName . "_column_types", array(1 => 1, 2 => 1, 3 => 1, 4 => 0, 5 => 0)),
            array("module_".$this->_moduleSysName . "_column_names", array()),
            array("module_".$this->_moduleSysName . "_column_texts", array()),
            array("module_".$this->_moduleSysName . "_vk", ''),
            array("module_".$this->_moduleSysName . "_fb", ''),
            array("module_".$this->_moduleSysName . "_googleplus", ''),
            array("module_".$this->_moduleSysName . "_youtube", ''),
            array("module_".$this->_moduleSysName . "_twitter", '')
        ), $data);

//        print_r($data);
//        exit;

        $data['params'] = $data;
        $data['user_token'] = $this->session->data['user_token'];

        if (is_file(DIR_LOGS . $this->_logFile))
            $data["logs"] = substr(file_get_contents(DIR_LOGS . $this->_logFile), -10000);
        else
            $data["logs"] = "";

        $data['db'] = $this->db;

        $this->load->model('catalog/category');
        $data['categories'] = $this->model_catalog_category->getCategories(0);

        $this->load->model('catalog/manufacturer');
        $results = $this->model_catalog_manufacturer->getManufacturers();
        $data['manufacturers']=[];
        foreach ($results as $result) {
            $data['manufacturers'][] = array(
                'id' => $result['manufacturer_id'],
                'name' => $result['name']
            );
        }
        if (isset($this->request->post['sirius_custom_footer_vk'])) {
            $data['sirius_custom_footer_vk'] = $this->request->post['sirius_custom_footer_vk'];
        } else {
            $data['sirius_custom_footer_vk'] = $this->config->get('sirius_custom_footer_vk');
        }

        if (isset($this->request->post['sirius_custom_footer_fb'])) {
            $data['sirius_custom_footer_fb'] = $this->request->post['sirius_custom_footer_fb'];
        } else {
            $data['sirius_custom_footer_fb'] = $this->config->get('sirius_custom_footer_fb');
        }

        if (isset($this->request->post['sirius_custom_footer_googleplus'])) {
            $data['sirius_custom_footer_googleplus'] = $this->request->post['sirius_custom_footer_googleplus'];
        } else {
            $data['sirius_custom_footer_googleplus'] = $this->config->get('sirius_custom_footer_googleplus');
        }

        if (isset($this->request->post['sirius_custom_footer_youtube'])) {
            $data['sirius_custom_footer_youtube'] = $this->request->post['sirius_custom_footer_youtube'];
        } else {
            $data['sirius_custom_footer_youtube'] = $this->config->get('sirius_custom_footer_youtube');
        }

        if (isset($this->request->post['sirius_custom_footer_twitter'])) {
            $data['sirius_custom_footer_twitter'] = $this->request->post['sirius_custom_footer_twitter'];
        } else {
            $data['sirius_custom_footer_twitter'] = $this->config->get('sirius_custom_footer_twitter');
        }

        $this->load->model('catalog/information');
        $results_info = $this->model_catalog_information->getInformations();

        foreach ($results_info as $result) {
            $data['informations'][] = array(
                'id' => $result['information_id'],
                'title' => $result['title']
            );
        }
        $this->load->model('localisation/language');
        $data['languages'] = $this->model_localisation_language->getLanguages();

        $data['current_lang'] = (int) $this->config->get('config_language_id');
        $this->document->addScript('view/javascript/jquery/jquery.nestable.js');
        $this->document->addScript('view/javascript/' . $this->_moduleSysName . '.js');
        $this->document->addStyle('view/stylesheet/' . $this->_moduleSysName . '.css');

        if (isset($this->request->post['sirius_custom_footer_status'])) {
            $data['sirius_custom_footer_status'] = $this->request->post['sirius_custom_footer_status'];
        } else {
            $data['sirius_custom_footer_status'] = $this->config->get('sirius_custom_footer_status');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
//        echo"<pre>";
//        print_r($data);
//        echo"</pre>";
//
//        exit;
        $this->response->setOutput($this->load->view('extension/module/' . $this->_moduleSysName , $data));
    }

    public function clear() {
        $this->language->load('extension/module/' . $this->_moduleSysName);

        if (is_file(DIR_LOGS . $this->_logFile)) {
            $f = fopen(DIR_LOGS . $this->_logFile, "w");
            fclose($f);
        }

        $this->session->data['success'] = $this->language->get('text_success_clear');

        $this->response->redirect($this->url->link('extension/module' . $this->_moduleSysName, 'user_token=' . $this->session->data['user_token'], 'SSL'));
    }

    public function license() {
        $data = $this->language->load('extension/module/' . $this->_moduleSysName);

        $this->document->setTitle($this->language->get('heading_title_raw'));

        $data = $this->initBreadcrumbs(array(
            array("extension/module", "text_module"),
            array("extension/module/" . $this->_moduleSysName, "heading_title_raw")
        ), $data);

        $data['error_warning'] = "";

        $data['close'] = $this->url->link('extension/module', 'user_token=' . $this->session->data['user_token'], 'SSL');
        $data['action'] = $this->url->link('extension/module/' . $this->_moduleSysName, 'user_token=' . $this->session->data['user_token'], 'SSL');
        $data['recheck'] = $this->url->link('extension/module/' . $this->_moduleSysName, 'user_token=' . $this->session->data['user_token'], 'SSL');
        $data['params'] = $data;
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $this->response->setOutput($this->load->view('extension/module/' . $this->_moduleSysName , $data));
    }


    public function install() {
        $this->load->model("extension/module/" . $this->_moduleSysName);
        $this->registry->get("model_extension_module_" . $this->_moduleSysName)->install();
    }

    public function uninstall() {
        $this->load->model("extension/module/" . $this->_moduleSysName);
        $this->registry->get("model_extension_module_" . $this->_moduleSysName)->uninstall();
    }

    protected function saveMenu() {
        $this->load->model('extension/module/sirius_custom_footer');
        $this->registry->get("model_extension_module_" . $this->_moduleSysName)->empty_data();

        foreach (range(1, 5) as $i) {
            if (isset($this->request->post['title' . $i])) {
                $data['title'] = $this->request->post['title' . $i];
                $data['parent_id'] = $this->request->post['parent_id' . $i];
                $data['type'] = $this->request->post['type' . $i];
                $data['type_id'] = $this->request->post['type_id' . $i];
                $data['url'] = $this->request->post['url' . $i];
                $data['params'] = $this->request->post['params' . $i];
                $this->registry->get("model_extension_module_" . $this->_moduleSysName)->insert($data, "footer_menu_" . $i);
            }
        }
        $this->load->model('localisation/language');
        $languages = $this->model_localisation_language->getLanguages();
        foreach ($languages as $language) {
            $this->cache->delete("sirius_custom_footer_" . $language['language_id']);

        }
    }
    public function getMenuHtml($parent_id=0,$db=null,$table='',$prefix=0,$current_lang=0,$languages=array())
    {
        if(($this->request->server['REQUEST_METHOD'] == 'POST') and !$db){
            $this->load->model('localisation/language');
            $parent_id=$this->request->post['parent_id'];
            $prefix=$this->request->post['column_id'];
            $table="footer_menu_".$prefix;
            $languages=$this->model_localisation_language->getLanguages();
            $current_lang=$this->request->post['current_lang'];
            $db=$this->db;
        }
    echo $this->getMenuHtmlWrap($parent_id,$db,$table,$prefix,$current_lang,$languages);
    }
    public function getMenuHtmlWrap($parent_id=0,$db=null,$table='',$prefix=0,$current_lang=0,$languages=array()) {

            $sql="SELECT * FROM " . DB_PREFIX . "$table  WHERE " . DB_PREFIX . "$table.parent_id = '".$parent_id."' ORDER BY " . DB_PREFIX . "$table.id ASC";

        $query = $db->query($sql);

        $result=array();
        foreach ($query->rows as $value) {
            $result[] = $value;
        }

        if($result) {
            $html="";
            if($parent_id!=0) $html.="<ol class='dd-list'>";
            foreach($result as $value) {
                $lang_title = @unserialize($value["title"]);
                $lang_url = @unserialize($value["url"]);
                $params = $value["params"];
                if (!$lang_title) {
                    foreach ($languages as $language) {
                        $lang_title[$language['language_id']] = "";
                    }
                }
                if ($value['type'] == 'custom' && !$lang_url) {
                    foreach ($languages as $language) {
                        $lang_url[$language['language_id']] = "";
                    }
                }

                $html.="<li class='dd-item'>";
                $html.="<div class='dd-handle'>
                        <div class='bar'>
                            <span class='title'>".$lang_title[$current_lang]."</span>
                        </div>
                    </div>
                    <div class='panel panel-default info hide'>
                        <div class='panel-body'>
                            <input type='hidden' class='type' name='type".$prefix."[]' value='".$value['type']."'/>
                            <input type='hidden' class='parent_id' name='parent_id".$prefix."[]' value='".$value['parent_id']."'/>
                            <input type='hidden' class='type_id' name='type_id".$prefix."[]' value='".$value['type_id']."'/>
                            <div class='form-group'>
                                <label>Название: </label>";
                                foreach ($languages as $language) {
                                    $val = isset($lang_title[$language['language_id']]) ? $lang_title[$language['language_id']] : '';
                                    $html.="<div class='input-group input-item'>
                                        <span class='input-group-addon'><img src='view/image/flags/".$language['code'].".png' title='".$language['name']."' /></span>
                                        <input class='form-control' type='text' name='title".$prefix."[".$language['language_id']."][]' value='".$val."' />
                                    </div>";
                                 }
                $html.="</div>
                            <div class='form-group'>";
                                if($value['type']!='custom') {
                                    $html.="<label>".$value['type'] . "_id:"."</label>". $value['type_id'];
                                }
                $html.=" </div>
                            <div class='form-group'>";
                                if($value['type']=='custom') {
                                    $html.="<label>Ссылка:</label>";
                                }
                                foreach ($languages as $language) {
                                    if($value['type']=='custom') {
                                        $html.="<div class='input-group input-item'>
                                            <span class='input-group-addon'><img src='view/image/flags/".$language['code'].".png' title='".$language['name']."' /></span>
                                            <input class='input-item form-control' type='text' name='url".$prefix."[".$language['language_id']."][]' value='".$lang_url[$language['language_id']]."'/>
                                        </div>";
                                    } else {
                                        $html.="<input type='hidden' class='url' name='url".$prefix."[".$language['language_id']."][]' value=''/>";
                                    }
                                }
                $val = isset($params) ? $params : '';
                $html.="<div class='form-group'>
                                    <label>Параметр: </label>
                                    <input class='form-control' type='text' name='params".$prefix."[]' value='".$val."' />
                                </div>
                            </div>
                        </div>
                    </div>
                    <a class='btn btn-xs btn-danger remove' onclick='remove_item(this);'><i class='fa fa-trash-o'></i></a>
                    <a class='btn btn-xs btn-default explane' onclick='explane(this)'><i class='fa fa-chevron-down' aria-hidden='true'></i></a>";
                $html.=$this->getMenuHtmlWrap($value['id'],$db,$table,$prefix,$current_lang,$languages);
                $html.="</li>";
            }
            if($parent_id!=0) $html.="</ol>";
        }else{
            return;
        }
        return $html;
    }

    private function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/' . $this->_moduleSysName)) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

}

?>