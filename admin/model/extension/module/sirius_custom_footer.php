<?php

require_once( DIR_SYSTEM . "/engine/sirius_model.php");

class ModelExtensionModuleSiriusCustomFooter extends SiriusModel {

    public function __construct($registry) {
        parent::__construct($registry);
        $this->_moduleName = "";
        $this->_moduleSysName = "sirius_custom_footer";
        $this->_logFile = $this->_moduleSysName . ".log";
        $this->debug = $this->config->get($this->_moduleSysName . "_debug") == 1;
    }

    public function insert($data, $table) {
        $title_array = array();
        $url_array = array();

        foreach ($data['title'] as $key => $value) {
            foreach ($value as $vid => $vdata) {
                $title_array[$vid][$key] = htmlspecialchars_decode($vdata);
                if (!empty($data["url"]) && !empty($data["url"][$key]) && !empty($data["url"][$key][$vid]))
                    $url_array[$vid][$key] = $data["url"][$key][$vid];
            }
        }


        foreach ($title_array as $key => $value) {
            $sql = "INSERT INTO " . DB_PREFIX . "$table SET params = '" . $data['params'][$key] . "', title = '" . serialize($value) . "', url = '" . ( (!empty($data["url"]) && !empty($url_array[$key])) ? serialize($url_array[$key]) : "") . "', parent_id = '" . (int) $data['parent_id'][$key] . "', type = '" . $data['type'][$key] . "', type_id = '" . (int) $data['type_id'][$key] . "'";
            $this->debug($sql);
            $this->db->query($sql);
        }
    }

    public function empty_data() {
        foreach (range(1, 5) as $i) {
            $this->db->query("TRUNCATE " . DB_PREFIX . "footer_menu_${i}");
        }
    }

    public function install() {

        $this->language->load('module/' . $this->_moduleSysName);
        $this->load->model('localisation/language');

        $languages = $this->model_localisation_language->getLanguages();

        foreach (range(1, 5) as $i) {
            $column_types[$i] = $this->language->get('param_footer_column_types_' . $i);
            foreach ($languages as $language) {
                $column_names[$i][$language['language_id']] = $this->language->get('param_footer_column_names_' . $i);
            }
        }
        foreach (range(1, 4) as $i) {
            foreach ($languages as $language) {
                $sections[$i][$language['language_id']] = $this->language->get('param_footer_sections_' . $i);
                $column_texts[$i][$language['language_id']] = $this->language->get('param_footer_column_texts_' . $i);
            }
        }

        $params = array(
            $this->_moduleSysName . '_status' => 1,
            $this->_moduleSysName . '_debug' => 0,
            $this->_moduleSysName . '_column_types' => $column_types,
            $this->_moduleSysName . '_column_names' => $column_names,
            $this->_moduleSysName . '_sections' => $sections,
            $this->_moduleSysName . '_column_texts' => $column_texts,
        );
        $this->load->model('setting/setting');
        $this->model_setting_setting->editSetting($this->_moduleSysName, $params);

        foreach (range(1, 5) as $i) {
            $sql = "CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "footer_menu_" . $i . " (
				id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
				parent_id INT NOT NULL DEFAULT 0,
				type_id INT,
				title TEXT NOT NULL,
				url TEXT NOT NULL,
                                params TEXT NOT NULL,
				type VARCHAR(128) NOT NULL
			) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
            //$this->log($sql);
            $this->db->query($sql);
        }

        foreach (range(0, 3) as $i) {
            foreach ($languages as $language) {
                $title_2[$i][$language['language_id']] = $this->language->get('param_footer_title_2_' . $i);
                $title_4[$i][$language['language_id']] = $this->language->get('param_footer_title_4_' . $i);
                $title_5[$i][$language['language_id']] = $this->language->get('param_footer_title_5_' . $i);
                $url_2[$i][$language['language_id']] = $this->language->get('param_footer_url_2');
                $url_4[$i][$language['language_id']] = $this->language->get('param_footer_url_4_' . $i);
                $url_5[$i][$language['language_id']] = $this->language->get('param_footer_url_5_' . $i);
            }
        }

        foreach (range(0, 2) as $i) {
            foreach ($languages as $language) {
                $title_3[$i][$language['language_id']] = $this->language->get('param_footer_title_3_' . $i);
                $url_3[$i][$language['language_id']] = $this->language->get('param_footer_url_3_' . $i);
            }
        }


        return TRUE;
    }

    public function uninstall() {
        foreach (range(1, 5) as $i) {
            $this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "footer_menu_${i}");
        }
        $this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `code` = '" . $this->_moduleSysName . "'");
        return TRUE;
    }

}

?>