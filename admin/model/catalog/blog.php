<?php
/* All rights reserved belong to the module, the module developers http://opencartadmin.com */
// https://opencartadmin.com © 2011-2019 All Rights Reserved
// Distribution, without the author's consent is prohibited
// Commercial license
if (!class_exists('ModelCatalogBlog', false)) {
class ModelCatalogBlog extends Model
{
	public function addBlog($data) {

		$this->cache->delete('blog');

		if ($data['sort_order'] != '') {
			$sort_order = (int) $data['sort_order'];
		} else {
			$query      = $this->db->query("SELECT MAX(sort_order) as maxis FROM " . DB_PREFIX . "blog WHERE parent_id = '" . (int) $data['parent_id'] . "' ");
			$sort_order = (int) $query->row['maxis'] + 1;
		}

		if (isset($data['index_page']) && is_array($data['index_page'])) {
   			$index_page = implode($data['index_page'],',');
		} else {
			$index_page = '';
		}


		$this->db->query("INSERT INTO " . DB_PREFIX . "blog SET
		parent_id = '" . (int) $data['parent_id'] . "',
		sort_order = '" . $sort_order . "',
		status = '" . (int) $data['status'] . "',
		index_page = '" . $this->db->escape($index_page) . "',
		design ='" . (serialize($data['blog_design'])) . "',
		date_modified = NOW(),
		date_added = NOW()
		");

		$blog_id = $this->db->getLastId();

		$this->db->query("
		UPDATE " . DB_PREFIX . "blog
		SET
		`path` = '" . $this->setSEOPathbyBlog((int) $blog_id) . "'
		WHERE
		blog_id = '" . (int) $blog_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "blog SET image = '" . $this->db->escape($data['image']) . "' WHERE blog_id = '" . (int) $blog_id . "'");
		}

		foreach ($data['blog_description'] as $language_id => $value) {

			$this->db->query("INSERT INTO " . DB_PREFIX . "blog_description SET blog_id = '" . (int) $blog_id . "',
			language_id = '" . (int) $language_id . "',
			name = '" . $this->db->escape($value['name']) . "',
			meta_title = '" . $this->db->escape($value['meta_title']) . "',
			meta_h1 = '" . $this->db->escape($value['meta_h1']) . "',
			meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "',
			meta_description = '" . $this->db->escape($value['meta_description']) . "',
			description = '" . $this->db->escape($value['description']) . "'");
		}
		if (isset($data['blog_store'])) {
			foreach ($data['blog_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "blog_to_store SET blog_id = '" . (int) $blog_id . "', store_id = '" . (int) $store_id . "'");
			}
		}

		if (isset($data['blog_layout'])) {
			foreach ($data['blog_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "blog_to_layout SET blog_id = '" . (int) $blog_id . "', store_id = '" . (int) $store_id . "', layout_id = '" . (int) $layout['layout_id'] . "'");
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "blog_access WHERE blog_id = '" . (int) $blog_id . "'");
		if (isset($data['customer_groups_blog'])) {
			foreach ($data['customer_groups_blog'] as $customer_grops_blog_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "blog_access SET blog_id = '" . (int) $blog_id . "', customer_group_id = '" . (int) $customer_grops_blog_id . "'");
			}
		}

		if ($data['keyword']) {
			foreach ($data['keyword'] as $lang_id => $keyword) {
				if ($keyword!='') {
					$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias_blog
					SET
					query = 'blog_id=" . (int) $blog_id . "',
					blog_id = '" . (int) $blog_id . "',
					keyword = '" . $this->db->escape($keyword) . "' ,
					language_id = '" . (int) $lang_id . "'");
				}
			}
		}

		return $blog_id;
	}

	public function editBlog($blog_id, $data) {

		$this->cache->delete('blog');

		if (isset($data['index_page']) && is_array($data['index_page'])) {
   			$index_page = implode($data['index_page'],',');
		} else {
			$index_page = '';
		}

		$this->db->query("UPDATE " . DB_PREFIX . "blog
		SET
		parent_id = '" . (int) $data['parent_id'] . "',
		design ='" . (serialize($data['blog_design'])) . "',
		sort_order = '" . (int) $data['sort_order'] . "',
		status = '" . (int) $data['status'] . "',
		index_page = '" . $this->db->escape($index_page) . "',
		date_modified = NOW()
		WHERE
		blog_id = '" . (int) $blog_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "blog
			SET
			image = '" . $this->db->escape($data['image']) . "'
			WHERE
			blog_id = '" . (int) $blog_id . "'");
		}


		$query = $this->db->query("SELECT blog_id FROM " . DB_PREFIX . "blog");

		if (!empty($query->rows)) {
			foreach ($query->rows as $row) {
				if (isset($row['blog_id']) && $row['blog_id']) {
                	$this->db->query("
                	UPDATE " . DB_PREFIX . "blog
                	SET
                	`path` = '" . $this->model_catalog_blog->setSEOPathByBlog($row['blog_id']) . "'
                	WHERE
                	`blog_id` = '" . (int) $row['blog_id'] . "' ");
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "blog_description WHERE blog_id = '" . (int) $blog_id . "'");
		foreach ($data['blog_description'] as $language_id => $value) {

			$this->db->query("INSERT INTO " . DB_PREFIX . "blog_description SET blog_id = '" . (int) $blog_id . "', language_id = '" . (int) $language_id . "',
			name = '" . $this->db->escape($value['name']) . "',
			meta_title = '" . $this->db->escape($value['meta_title']) . "',
			meta_h1 = '" . $this->db->escape($value['meta_h1']) . "',
			meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "',
			meta_description = '" . $this->db->escape($value['meta_description']) . "',
			description = '" . $this->db->escape($value['description']) . "'");
		}


		$this->db->query("DELETE FROM " . DB_PREFIX . "blog_to_store WHERE blog_id = '" . (int) $blog_id . "'");
		if (isset($data['blog_store'])) {
			foreach ($data['blog_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "blog_to_store SET blog_id = '" . (int) $blog_id . "', store_id = '" . (int) $store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "blog_to_layout WHERE blog_id = '" . (int) $blog_id . "'");
		if (isset($data['blog_layout'])) {
			foreach ($data['blog_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "blog_to_layout SET blog_id = '" . (int) $blog_id . "', store_id = '" . (int) $store_id . "', layout_id = '" . (int) $layout['layout_id'] . "'");
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "blog_access WHERE blog_id = '" . (int) $blog_id . "'");
		if (isset($data['customer_groups_blog'])) {
			foreach ($data['customer_groups_blog'] as $customer_grops_blog_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "blog_access SET blog_id = '" . (int) $blog_id . "', customer_group_id = '" . (int) $customer_grops_blog_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias_blog WHERE query = 'blog_id=" . (int) $blog_id . "'");
		if ($data['keyword']) {
			foreach ($data['keyword'] as $lang_id => $keyword) {
				if ($keyword!='') {
					$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias_blog
					SET
					query = 'blog_id=" . (int) $blog_id . "',
					blog_id = '" . (int) $blog_id . "',
					keyword = '" . $this->db->escape($keyword) . "' ,
					language_id = '" . (int) $lang_id . "'");
				}
			}
		}

		return true;
	}

	public function deleteBlog($blog_id) {
		$this->cache->delete('blog');
		$blog = $this->getBlog($blog_id);
		$parent_id = $blog['parent_id'];

        $this->db->query("UPDATE " . DB_PREFIX . "blog SET parent_id = '" . (int) $parent_id . "' WHERE parent_id = '" . (int) $blog_id . "'");

		$this->db->query("DELETE FROM `" . DB_PREFIX . "blog` WHERE blog_id = '" . (int) $blog_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "blog_description` WHERE blog_id = '" . (int) $blog_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "blog_to_store` WHERE blog_id = '" . (int) $blog_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "record_to_blog` WHERE blog_id = '" . (int) $blog_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "blog_access` WHERE blog_id = '" . (int) $blog_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "blog_to_layout` WHERE blog_id = '" . (int) $blog_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias_blog` WHERE query = 'blog_id=" . (int) $blog_id . "'");

		$query = $this->db->query("SELECT blog_id FROM `" . DB_PREFIX . "blog` WHERE parent_id = '" . (int) $blog_id . "'");

		foreach ($query->rows as $result) {
			$this->deleteBlog($result['blog_id']);
		}

		return true;

	}

	public function copyBlog($blog_id) {
   		$this->cache->delete('blog');
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE code = '" . $this->db->escape($this->config->get('config_language')) . "'");

		if ($query->num_rows) {
			$language_id =  $query->row['language_id'];
		}

		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "blog b
		LEFT JOIN " . DB_PREFIX . "blog_description bd ON (b.blog_id = bd.blog_id)
		WHERE
		b.blog_id = '" . (int) $blog_id . "'
		AND bd.language_id = '" . (int) $language_id . "'");

		if ($query->num_rows) {

			$data = $query->row;

			if (!isset($data) || !is_array($data)) {
				$data = array();
			}

			if (isset($data['design']) || !is_array($data['design'])) {
				$data['blog_design'] = unserialize($data['design']);
			} else {
				$data['blog_design'] = array();
			}

			$data['status'] = '0';
            $data['keyword'] = Array();

			$data  = array_merge($data, array(
				'blog_description' => $this->getBlogDescriptions($blog_id)
			));

			$data = array_merge($data, array(
				'blog_layout' => $this->getBlogLayouts($blog_id)
			));
			$data = array_merge($data, array(
				'blog_store' => $this->getBlogStores($blog_id)
			));
			$data = array_merge($data, array(
				'customer_groups_blog' => $this->getBlogAccess($blog_id)
			));
			$data = array_merge($data, array(
				'index_page' => $this->getBlogIndexPage($blog_id)
			));

			$blog_id = $this->addBlog($data);
			return  $blog_id;
		} else {
			return false;
		}
	}



	public function getBlogIndexPage($blog_id) {
		$query = $this->db->query("SELECT index_page FROM " . DB_PREFIX . "blog WHERE blog_id = '" . (int) $blog_id . "'");
		$index_page = explode(',', trim($query->row['index_page']));
		return $index_page;
	}



	public function getBlog($blog_id) {

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE code = '" . $this->db->escape($this->config->get('config_language')) . "'");

		if ($query->num_rows) {
			$language_id =  $query->row['language_id'];
		}

		$query = $this->db->query("SELECT
		DISTINCT *
		FROM " . DB_PREFIX . "blog b
		LEFT JOIN " . DB_PREFIX . "blog_description cd ON (b.blog_id = cd.blog_id)
		WHERE
		cd.language_id = '" . (int) $language_id . "'
		AND
		b.blog_id = '" . (int) $blog_id . "'");

		return $query->row;
	}

	public function getBlogKeywords($blog_id)
	{
		$keys= array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias_blog WHERE query = 'blog_id=" . (int) $blog_id . "'");
         foreach ($query->rows as $num => $row) {
         	$keys[$row['language_id']] = $row['keyword'];
         }

		return $keys;
	}


	public function getCategory($category_id) {

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE code = '" . $this->db->escape($this->config->get('config_language')) . "'");

		if ($query->num_rows) {
			$language_id =  $query->row['language_id'];
		}

		$query = $this->db->query("SELECT DISTINCT *,
		(SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id . "') AS keyword
		FROM " . DB_PREFIX . "category c
		LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id)
		WHERE
		cd.language_id = '" . (int) $language_id . "'
		AND
		c.category_id = '" . (int)$category_id . "'");

		return $query->row;
	}


	public function getCategories($parent_id = 0, $mark_category = 'blog') {

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE code = '" . $this->db->escape($this->config->get('config_language')) . "'");

			if ($query->num_rows) {
				$language_id =  $query->row['language_id'];
			}

			$blog_data = array();

			$query = $this->db->query("
			SELECT * FROM " . DB_PREFIX . $mark_category." c
			LEFT JOIN " . DB_PREFIX . $mark_category."_description cd ON (c.".$mark_category."_id = cd.".$mark_category."_id)
			WHERE c.parent_id = '" . (int) $parent_id . "' AND cd.language_id = '" . (int) $language_id . "'
			ORDER BY c.sort_order, cd.name ASC");

			foreach ($query->rows as $result) {
				$blog_data[] = array(
					$mark_category.'_id' => $result[$mark_category.'_id'],
					'image' => $result['image'],
					'name' => $this->getPath($result[$mark_category.'_id'], $mark_category),
					'status' => $result['status'],
					'sort_order' => $result['sort_order']
				);
				$blog_data = array_merge($blog_data, $this->getCategories($result[$mark_category.'_id'], $mark_category));
			}

		return $blog_data;
	}

	public function getBlogAuto($data) {
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE code = '" . $this->db->escape($this->config->get('config_language')) . "'");

			if ($query->num_rows) {
				$language_id =  $query->row['language_id'];
			}

			$blog_data = array();
			$query     = $this->db->query("SELECT *
			FROM " . DB_PREFIX . "blog c
			LEFT JOIN " . DB_PREFIX . "blog_description cd ON (c.blog_id = cd.blog_id)
			WHERE
			cd.language_id = '" . (int) $language_id . "'
			AND
			LCASE(cd.name) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'
			");
			foreach ($query->rows as $result) {
				$blog_data[$result['blog_id']] = array(
					'blog_id' => $result['blog_id'],
					'name' => $result['name'],
				);
			}

		return $blog_data;
	}

	public function getCategoryAuto($data)
	{
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE code = '" . $this->db->escape($this->config->get('config_language')) . "'");

			if ($query->num_rows) {
				$language_id =  $query->row['language_id'];
			}

			$blog_data = array();
			$query     = $this->db->query("SELECT *
			FROM " . DB_PREFIX . "category c
			LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id)
			WHERE
			cd.language_id = '" . (int) $language_id . "'
			AND
			LCASE(cd.name) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'
			");
			foreach ($query->rows as $result) {
				$blog_data[$result['category_id']] = array(
					'category_id' => $result['category_id'],
					'name' => $result['name'],
				);
			}

		return $blog_data;
	}

	public function getManufacturer($manufacturer_id) {


			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE code = '" . $this->db->escape($this->config->get('config_language')) . "'");

			if ($query->num_rows) {
				$language_id =  $query->row['language_id'];
			}

		if ($this->table_exists(DB_PREFIX . "manufacturer_description")) {
          	$sql_lj = "LEFT JOIN " . DB_PREFIX . "manufacturer_description cd ON (c.manufacturer_id = cd.manufacturer_id)";
          	$sql_where = "cd.language_id = '" . (int) $language_id . "' AND ";
		} else {
			$sql_where = $sql_lj = "";
		}

		$query = $this->db->query("SELECT DISTINCT *,
		(SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id=" . (int)$manufacturer_id . "') AS keyword
		FROM " . DB_PREFIX . "manufacturer c
        ".$sql_lj."
		WHERE
        ".$sql_where."
		c.manufacturer_id = '" . (int)$manufacturer_id . "'");

		return $query->row;
	}


	public function getManufacturerAuto($data) {

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE code = '" . $this->db->escape($this->config->get('config_language')) . "'");

			if ($query->num_rows) {
				$language_id =  $query->row['language_id'];
			}

			$blog_data = array();

			if ($this->table_exists(DB_PREFIX . "manufacturer_description")) {
	          	$sql_lj = "LEFT JOIN " . DB_PREFIX . "manufacturer_description cd ON (c.manufacturer_id = cd.manufacturer_id)";
	          	$sql_where = "cd.language_id = '" . (int) $language_id . "' AND ";
			} else {
				$sql_where = $sql_lj = "";
			}
			$query     = $this->db->query("SELECT *
			FROM " . DB_PREFIX . "manufacturer c
			".$sql_lj."
			WHERE
			".$sql_where."
			LCASE(c.name) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'
			");
			foreach ($query->rows as $result) {
				$blog_data[$result['manufacturer_id']] = array(
					'manufacturer_id' => $result['manufacturer_id'],
					'name' => $result['name'],
				);
			}

		return $blog_data;
	}


	public function getPath($category_id, $mark_category = 'blog') {

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE code = '" . $this->db->escape($this->config->get('config_language')) . "'");

		if ($query->num_rows) {
			$language_id =  $query->row['language_id'];
		}

		$sql = "SELECT name, parent_id
		FROM " . DB_PREFIX . $mark_category." c
		LEFT JOIN " . DB_PREFIX . $mark_category."_description cd ON (c.".$mark_category."_id = cd.".$mark_category."_id)
		WHERE c.".$mark_category."_id = '" . (int) $category_id . "'
		AND cd.language_id = '" . (int) $language_id . "' ORDER BY c.sort_order, cd.name ASC";
		$query = $this->db->query($sql);

		if ($query->row['parent_id']) {
			return $this->getPath($query->row['parent_id'], $mark_category ) . $this->language->get('text_separator') . $query->row['name'];
		} else {
			return $query->row['name'];
		}
	}



	public function getBlogDescriptions($blog_id) {
		$blog_description_data = array();
		$query                 = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_description WHERE blog_id = '" . (int) $blog_id . "'");
		foreach ($query->rows as $result) {
			$blog_description_data[$result['language_id']] = array(
				'name' => $result['name'],
				'meta_title' => $result['meta_title'],
				'meta_h1' => $result['meta_h1'],
				'meta_keyword' => $result['meta_keyword'],
				'meta_description' => $result['meta_description'],
				'description' => $result['description']
			);
		}
		return $blog_description_data;
	}

	public function getBlogAccess($blog_id) {
		$query  = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_access WHERE blog_id = '" . (int) $blog_id . "'");
		$blog_access = array();
		foreach ($query->rows as $result) {
			$blog_access[$result['customer_group_id']] = $result['customer_group_id'];
		}
		return $blog_access;
	}

	public function getBlogStores($blog_id) {
		$blog_store_data = array();
		$query           = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_to_store WHERE blog_id = '" . (int) $blog_id . "'");
		foreach ($query->rows as $result) {
			$blog_store_data[] = $result['store_id'];
		}
		return $blog_store_data;
	}

	public function getBlogLayouts($blog_id) {
		$blog_layout_data = array();
		$query            = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_to_layout WHERE blog_id = '" . (int) $blog_id . "'");
		foreach ($query->rows as $result) {
			$blog_layout_data[$result['store_id']]['layout_id'] = $result['layout_id'];
		}
		return $blog_layout_data;
	}

	public function getTotalCategories() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blog");
		return $query->row['total'];
	}

	public function getTotalCategoriesByImageId($image_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blog WHERE image_id = '" . (int) $image_id . "'");
		return $query->row['total'];
	}

	public function getTotalCategoriesByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blog_to_layout WHERE layout_id = '" . (int) $layout_id . "'");
		return $query->row['total'];
	}

	private function table_exists($tableName) {
		$found= false;
		$like   = addcslashes($tableName, '%_\\');
		$result = $this->db->query("SHOW TABLES LIKE '" . $this->db->escape($like) . "';");
		$found  = $result->num_rows > 0;
		return $found;
	}


	public function setSEOPathByBlog($blog_id) {

		if (utf8_strpos($blog_id, '_') !== false) {
			$array_blog_id = explode('_', $blog_id);
			$blog_id = end($array_blog_id);
		}

		if ($blog_id < 1)
			return false;

		$blog_info = $this->getBlog($blog_id);

		if (isset($blog_info['design']) && $blog_info['design'] != '') {
			$blog_design[$blog_id] = unserialize($blog_info['design']);
		} else {
			$blog_design[$blog_id] = Array();
		}

		if (isset($blog_design[$blog_id]['blog_short_path_category']) && $blog_design[$blog_id]['blog_short_path_category'] == 1) {
			$cache_pathbyblog[$blog_id]['path'] = $blog_id;
		} else {
				$flag_end = false;
				$blog_path = (int) $blog_id;
				$category_path = $blog_path;
				$array_path = array();
				$level = 0;
				do {
					if (!isset($array_path[$blog_path])) {
						$array_path[$blog_path] = $blog_path;
						$sql = "SELECT parent_id as parent_id FROM " . DB_PREFIX . "blog WHERE blog_id = '" . (int) $blog_path . "'";
						$query = $this->db->query($sql);
						if ((isset($query->row['parent_id']) && $query->row['parent_id'] == 0) || $level == 50) {
							$flag_end = true;
						} else {
							if (isset($query->row['parent_id'])) {
								$blog_path = $query->row['parent_id'];
							}
							$category_path = $blog_path . '_' . $category_path;
						}
						$level++;
					} else {
						$flag_end = true;
					}
				} while (!$flag_end);

				$cache_pathbyblog[$blog_id]['path'] = $category_path;
		}

		return  $cache_pathbyblog[$blog_id]['path'];

	}
	public function switchstatus($id, $mark_name = 'blog') {

		$sql = "UPDATE `" . DB_PREFIX .$mark_name."` SET `status` = abs(`status` - 1) WHERE ".$mark_name."_id = '".(int)$id."'";

		$this->db->query($sql);

		$sql = "SELECT status FROM `" . DB_PREFIX .$mark_name."` WHERE ".$mark_name."_id = '" . (int) $id . "'";

		$query = $this->db->query($sql);


		$this->cache->delete('blog');
		$this->cache->delete('record');

		return $query->row['status'];
	}

}
}