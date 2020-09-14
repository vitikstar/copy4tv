<?php
/*------------------------------------------------------------------------
# Customer Reviews
# ------------------------------------------------------------------------
# The Krotek
# Copyright (C) 2011-2019 The Krotek. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: https://thekrotek.com
# Support: support@thekrotek.com
-------------------------------------------------------------------------*/
require_once( DIR_SYSTEM . "/engine/sirius_model.php");
require_once( DIR_SYSTEM . "/engine/sirius_controller.php");


class ModelExtensionModuleSiriusCustomFooter extends SiriusModel
{
    protected function initParams($items, $data)
    {
        foreach ($items as $item) {
             if ($this->hasSetting($item[0])) {
                $data[$item[0]] = $this->getSetting($item[0]);
            } else if (isset($item[1])) {
                $data[$item[0]] = $item[1]; // default value
            }
        }
        return $data;
    }
    public function getData()
    {
        $current_lang = (int) $this->config->get('config_language_id');
        $data = $this->cache->get('sirius.custom.footer.data.' . $current_lang . '.' . (int)$this->config->get('config_store_id'));

        if (!$data || !is_array($data)) {

            $data = array();

            $data = $this->initParams(array(
                array("module_".$this->_moduleSysName . "custom_footer_status", 1),
                array("module_".$this->_moduleSysName . "custom_footer_debug", 0),
                array("module_".$this->_moduleSysName . "custom_footer_sections", array()),
                array("module_".$this->_moduleSysName . "custom_footer_column_names", array()),
                array("module_".$this->_moduleSysName . "custom_footer_column_texts", array()),
                array("module_".$this->_moduleSysName . "custom_footer_vk", ''),
                array("module_".$this->_moduleSysName . "custom_footer_fb", ''),
                array("module_".$this->_moduleSysName . "custom_footer_googleplus", ''),
                array("module_".$this->_moduleSysName . "custom_footer_youtube", ''),
                array("module_".$this->_moduleSysName . "custom_footer_twitter", '')
            ), $data);

            $column_texts_sirius=[];
            foreach ($data['module_sirius_custom_footer_column_texts'] as $k=>$item) $column_texts_sirius[$k]=html_entity_decode(trim($item[$current_lang]));

            $column_sirius=array();
            foreach ($data['module_sirius_custom_footer_column_names'] as $key=>$item){
                if(!empty($item[$current_lang])){
                    if(empty($column_texts_sirius[$key])){
                        $column_sirius[$key] = [
                            'name'=>$item[$current_lang],
                            'menu'=>$this->parseMenuTable(DB_PREFIX . 'footer_menu_' . $key)
                        ];
                    }else{
                        $column_sirius[$key] = $column_texts_sirius[$key];
                    }
                }
            }
            $data['column_sirius']=$column_sirius;

            $sections_sirius=[];
            foreach ($data['module_sirius_custom_footer_sections'] as $k=>$item) $sections_sirius[$k]=html_entity_decode(trim($item[$current_lang]));
            $data['sections_sirius']=$sections_sirius;



            unset($data['module_sirius_custom_footer_column_names']);   // видаляємо бо воно нам потрібно було для формування масива меню і не більше
            unset($data['module_sirius_custom_footer_sections']);
            unset($data['module_sirius_custom_footer_column_texts']);

            $this->cache->set('sirius.custom.footer.data.' . $current_lang . '.' . (int)$this->config->get('config_store_id'), $data);
        }


        return $data;
    }
    protected function getSetting($code, $store_id = 0) {

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `key` = '" . $this->db->escape($code) . "'");

            if (!$query->row['serialized']) {
                $setting_data = $query->row['value'];
            } else {
                $setting_data = json_decode($query->row['value'], true);
            }

        return $setting_data;
    }
    protected function hasSetting($code, $store_id = 0) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `key` = '" . $this->db->escape($code) . "'");
        return $query->row;
    }
    protected function parseMenuTable($table_name=false,$parent_id=0){
        $current_lang = (int) $this->config->get('config_language_id');
        $sql = "SELECT * FROM " . $table_name;
        if($parent_id) $sql .= " WHERE parent_id='". $parent_id ."'";

        $query = $this->db->query($sql);
        $menu =[];
        if($query->num_rows){
            foreach ($query->rows as $item){
                $title = unserialize($item['title'])[$current_lang];
                $url = "#";
                if($item['type']=='category'){
                    $link = $this->registry->get('url');
                    $url = $link->link('catalog/category','category_id=' . $item['type_id']);
                }elseif($item['type']=='information' || $item['type']=='infomation'){
                    $link = $this->registry->get('url');
                    $url = $link->link('catalog/information','information_id=' . $item['type_id']);
                }elseif($item['type']=='custom'){
                    $url = unserialize($item['url'])[$current_lang];
                }
                if(key_exists($item['parent_id'],$menu)) $menu[$item['parent_id']]['child']=$this->parseMenuTable($table_name,$item['parent_id']);
                else
                $menu[$item['id']] = ['title'=>$title,'url'=>$url];
            }
            return $menu;
        }else{
            return;
        }

    }
}

?>