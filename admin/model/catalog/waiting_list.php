<?php

class ModelCatalogWaitingList extends Model
{
    public function getProductsRequest($data){
        $sql = "SELECT * FROM " . DB_PREFIX . "report_appearance ra LEFT JOIN " . DB_PREFIX . "product p ON(p.product_id=ra.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
            preg_match('/(.*)(WHERE pd\.language_id.*)/', $sql, $sql_crutch_matches);
            if (isset($sql_crutch_matches[2])) {
                $sql = $sql_crutch_matches[1] . " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)" . $sql_crutch_matches[2];
            } else {
                $data['filter_category'] = null;
            }
        }

        if (!empty($data['filter_customer_name'])) {
            $sql .= " AND ra.report_appearance_name LIKE '%" . $this->db->escape($data['filter_customer_name']) . "%'";
        }
        if (!empty($data['filter_product_name'])) {
            $sql .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_product_name']) . "%'";
        }
        if (!empty($data['filter_customer_telephone'])) {
            $sql .= " AND ra.telephone LIKE '%" . $this->db->escape($data['filter_customer_telephone']) . "%'";
        }

        if (!empty($data['filter_model'])) {
            $sql .= " AND p.model LIKE '%" . $this->db->escape($data['filter_model']) . "%'";
        }

        if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
            if (!empty($data['filter_category']) && !empty($data['filter_sub_category'])) {
                $implode_data = array();

                $this->load->model('catalog/category');

                $categories = $this->model_catalog_category->getCategoriesChildren($data['filter_category']);

                foreach ($categories as $category) {
                    $implode_data[] = "p2c.category_id = '" . (int)$category['category_id'] . "'";
                }

                $sql .= " AND (" . implode(' OR ', $implode_data) . ")";
            } else {
                if ((int)$data['filter_category'] > 0) {
                    $sql .= " AND p2c.category_id = '" . (int)$data['filter_category'] . "'";
                } else {
                    $sql .= " AND p2c.category_id IS NULL";
                }
            }
        }

        if (isset($data['filter_manufacturer_id']) && !is_null($data['filter_manufacturer_id'])) {
            $sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
        }


        if (isset($data['filter_price_min']) && !is_null($data['filter_price_min'])) {
            $sql .= " AND p.price >= '" . (float)$data['filter_price_min'] . "'";
        }

        if (isset($data['filter_price_max']) && !is_null($data['filter_price_max'])) {
            $sql .= " AND p.price <= '" . (float)$data['filter_price_max'] . "'";
        }


        if (isset($data['filter_quantity_min']) && !is_null($data['filter_quantity_min'])) {
            $sql .= " AND p.quantity >= '" . (int)$data['filter_quantity_min'] . "'";
        }

        if (isset($data['filter_quantity_max']) && !is_null($data['filter_quantity_max'])) {
            $sql .= " AND p.quantity <= '" . (int)$data['filter_quantity_max'] . "'";
        }
        if (isset($data['filter_date_report_create']) and !empty($data['filter_date_report_create'])) {
            $sql .= " AND DATE(ra.date_report_create) = '" . $data['filter_date_report_create'] . "'";
        }


        //$sql .= " GROUP BY p.product_id";

        $sort_data = array(
            'ra.report_appearance_name',
            'ra.date_report_create',
            'ra.telephone',
            'pd.product_name',
            'p.model',
            'p.price',
            'p.quantity',
            'p.status',
            'p.noindex',
            'p.sort_order'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY pd.name";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }
        $query = $this->db->query($sql);

        return $query->rows;
    }
    public function getTotalProductsRequest($data){
        $sql = "SELECT COUNT(DISTINCT ra.report_appearance_id) AS total FROM " . DB_PREFIX . "report_appearance ra LEFT JOIN " . DB_PREFIX . "product p ON(p.product_id=ra.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
            preg_match('/(.*)(WHERE pd\.language_id.*)/', $sql, $sql_crutch_matches);
            if (isset($sql_crutch_matches[2])) {
                $sql = $sql_crutch_matches[1] . " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)" . $sql_crutch_matches[2];
            } else {
                $data['filter_category'] = null;
            }
        }

        if (!empty($data['filter_name'])) {
            $sql .= " AND ra.report_appearance_name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }
        if (!empty($data['filter_telephone'])) {
            $sql .= " AND ra.telephone LIKE '%" . $this->db->escape($data['filter_telephone']) . "%'";
        }

        if (!empty($data['filter_model'])) {
            $sql .= " AND p.model LIKE '%" . $this->db->escape($data['filter_model']) . "%'";
        }

        if (isset($data['filter_category']) && !is_null($data['filter_category'])) {
            if (!empty($data['filter_category']) && !empty($data['filter_sub_category'])) {
                $implode_data = array();

                $this->load->model('catalog/category');

                $categories = $this->model_catalog_category->getCategoriesChildren($data['filter_category']);

                foreach ($categories as $category) {
                    $implode_data[] = "p2c.category_id = '" . (int)$category['category_id'] . "'";
                }

                $sql .= " AND (" . implode(' OR ', $implode_data) . ")";
            } else {
                if ((int)$data['filter_category'] > 0) {
                    $sql .= " AND p2c.category_id = '" . (int)$data['filter_category'] . "'";
                } else {
                    $sql .= " AND p2c.category_id IS NULL";
                }
            }
        }

        if (isset($data['filter_manufacturer_id']) && !is_null($data['filter_manufacturer_id'])) {
            $sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
        }


        if (isset($data['filter_price_min']) && !is_null($data['filter_price_min'])) {
            $sql .= " AND p.price >= '" . (float)$data['filter_price_min'] . "'";
        }

        if (isset($data['filter_price_max']) && !is_null($data['filter_price_max'])) {
            $sql .= " AND p.price <= '" . (float)$data['filter_price_max'] . "'";
        }


        if (isset($data['filter_quantity_min']) && !is_null($data['filter_quantity_min'])) {
            $sql .= " AND p.quantity >= '" . (int)$data['filter_quantity_min'] . "'";
        }

        if (isset($data['filter_quantity_max']) && !is_null($data['filter_quantity_max'])) {
            $sql .= " AND p.quantity <= '" . (int)$data['filter_quantity_max'] . "'";
        }
        if (isset($data['date_report_create']) && !is_null($data['date_report_create'])) {
           // $sql .= " AND ra.date_report_create <= '" . (int)$data['filter_quantity_max'] . "'";
        }


        //$sql .= " GROUP BY p.product_id";

        $sort_data = array(
            'ra.report_appearance_name',
            'ra.date_report_create',
            'p.model',
            'p.price',
            'p.quantity',
            'p.status',
            'p.noindex',
            'p.sort_order'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY pd.name";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }
}