<?php
require_once( DIR_SYSTEM . "/engine/sirius_controller.php");
class ControllerExtensionModuleSiriusCategorySelected extends SiriusController {
	public function index($setting) {
		$this->load->language('extension/module/category_selected');

        $this->load->model('catalog/product');

        $this->load->model('tool/image');

        $data['products'] = array();

        if (!isset($setting['limit'])) {
            $setting['limit'] = 4;
        }
		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$data['categories'] = array();

//		$categories = $this->model_catalog_category->getCategories(0);
//
//		foreach ($categories as $category) {
//			$children_data = array();
//
//			if ($category['category_id'] == $data['category_id']) {
//				$children = $this->model_catalog_category->getCategories($category['category_id']);
//
//				foreach($children as $child) {
//					$filter_data = array('filter_category_id' => $child['category_id'], 'filter_sub_category' => true);
//
//					$children_data[] = array(
//						'category_id' => $child['category_id'],
//						'name' => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
//						'href' => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
//					);
//				}
//			}
//
//			$filter_data = array(
//				'filter_category_id'  => $category['category_id'],
//				'filter_sub_category' => true
//			);
//
//			$data['categories'][] = array(
//				'category_id' => $category['category_id'],
//				'name'        => $category['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
//				'children'    => $children_data,
//				'href'        => $this->url->link('product/category', 'path=' . $category['category_id'])
//			);
//		}
        if (!empty($setting['category'])) {
            $categories = array_slice($setting['category'], 0, (int)$setting['limit']);

            foreach ($categories as $category_id) {
                $category_info = $this->model_catalog_category->getCategory($category_id);

                if ($category_info) {
                    if ($category_info['icon']) {
                        $icon = $this->model_tool_image->resize($category_info['icon'], $setting['width'], $setting['height']);
                    } else {
                        $icon = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
                    }
                    $product_total = $this->model_catalog_product->getTotalProducts(['filter_category_id' => $category_id]);
                    $name = "";
                    $name_word_array = explode(" ",$category_info['name']);
                    $i=0;
                    foreach ($name_word_array as $word){
                        $name = $name." ".$word;
                        if($i<1)  $name = $name."<br>";
                        $i++;
                    }
                    $data['categories'][] = array(
                        'product_total'=> $product_total,
                        'declension'=> $this->numberof($product_total,'товар',['','а','ов']),
                        'category_id'  => $category_info['category_id'],
                        'thumb'       => $icon,
                        'name'        => $name,
                        'description' => utf8_substr(strip_tags(html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                        'href'        => $this->url->link('product/category', 'path=' . $category_info['category_id'])
                    );
                }
            }
        }

        if ($data['categories']) {
            return $this->load->view('extension/module/sirius_category_selected', $data);
        }
	}
}