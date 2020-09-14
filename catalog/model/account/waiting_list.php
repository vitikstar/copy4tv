<?php

class ModelAccountWaitingList extends Model
{

    public function getListAvailable($customer_id=0)
    {
       // $sql = "SELECT pd.description,p.minimum,p.metka_id,p.image,p.stock_status_id,p.tax_class_id,p.model,p.price,p.quantity,pd.name,o.order_id,p.product_id, o.firstname, o.lastname, p.status as status, o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) LEFT JOIN " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id) LEFT JOIN " . DB_PREFIX . "product p ON (op.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (pd.product_id = p.product_id) WHERE o.customer_id = '" . (int)$this->customer->getId() . "' AND o.order_status_id > '0' AND o.store_id = '" . (int)$this->config->get('config_store_id') . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY p.product_id ORDER BY o.order_id DESC";
        $sql =  "SELECT pd.description,p.minimum,p.metka_id,p.image,p.stock_status_id,p.tax_class_id,p.model,p.price,p.quantity,pd.name,p.product_id,  p.status as status  FROM `oc_report_appearance` ra LEFT JOIN `oc_product` p ON(p.product_id=ra.product_id) LEFT JOIN `oc_product_description` pd ON(pd.product_id=ra.product_id) WHERE  pd.language_id='". (int)$this->config->get('config_language_id') ."' AND (p.quantity>0 OR p.stock_status_id=7) AND ra.customer_id='". $customer_id ."'";

        $query = $this->db->query($sql);

        $data = array();

        $this->load->model('account/wishlist');

        $wishlist = $this->model_account_wishlist->getWishlist();
        $this->load->model("extension/module/p_review");
        foreach ($query->rows as $result) {
            if ($result['quantity'] > 0) {
                //   if($result['stock_status_id']==7){
                if ($result['image']) {
                    $image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
                }
                if (isset($this->session->data['compare'])) {
                    $compare_count = (in_array($result['product_id'], $this->session->data['compare'])) ? count($this->session->data['compare']) : '';
                } else {
                    $compare_count = '';
                }
                if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                } else {
                    $price = false;
                }

//                    if ((float)$result['special']) {
//                        $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
//                    } else {
//                        $special = false;
//                    }
//
//                    if ($this->config->get('config_tax')) {
//                        $tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
//                    } else {
//                        $tax = false;
//                    }
                // up
                $attribute_groups = $this->model_catalog_product->getProductAttributes($result['product_id']);
                // end up

                $data[] = array(
                    'product_id' => $result['product_id'],
                    'model' => $result['model'],
                    'thumb' => $image,
                    'name' => $result['name'],
                    'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                    'price' => $price,
                    //'special'     => $special,
                    // 'tax'         => $tax,
                    'class_name' => (in_array($result['product_id'], $wishlist)) ? 'products-wish-btn-active' : 'products-wish-btn',
                    'minimum' => $result['minimum'] > 0 ? $result['minimum'] : 1,
                    //'rating'      => $result['rating'],
                    'compare_count' => $compare_count,
                    // up
                    'reviews' => $this->model_extension_module_p_review->getTotalReviews($result['product_id']),
                    'metka_id' => $result['metka_id'],
                    'attribute_groups' => $attribute_groups,
                    // end up
                    'href' => $this->url->link('product/product', 'product_id=' . $result['product_id'])
                );
                //  }
            }
        }
        return $data;
    }

    public function getListNotAvailable($customer_id)
    {
        $sql =  "SELECT pd.description,p.minimum,p.metka_id,p.image,p.stock_status_id,p.tax_class_id,p.model,p.price,p.quantity,pd.name,p.product_id,  p.status as status  FROM `oc_report_appearance` ra LEFT JOIN `oc_product` p ON(p.product_id=ra.product_id) LEFT JOIN `oc_product_description` pd ON(pd.product_id=ra.product_id) WHERE  pd.language_id='". (int)$this->config->get('config_language_id') ."' AND (p.quantity<=1 OR p.stock_status_id=5) AND ra.customer_id='". $customer_id ."'";

        $query = $this->db->query($sql);

        $data = array();
        $wishlist = $this->model_account_wishlist->getWishlist();
        $this->load->model("extension/module/p_review");
        foreach ($query->rows as $result) {
            if ($result['quantity'] <= 0) {
                if ($result['stock_status_id'] != 6) {
                    if ($result['image']) {
                        $image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
                    } else {
                        $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
                    }
                    if (isset($this->session->data['compare'])) {
                        $compare_count = (in_array($result['product_id'], $this->session->data['compare'])) ? count($this->session->data['compare']) : '';
                    } else {
                        $compare_count = '';
                    }
                    if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                        $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                    } else {
                        $price = false;
                    }

//                    if ((float)$result['special']) {
//                        $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
//                    } else {
//                        $special = false;
//                    }
//
//                    if ($this->config->get('config_tax')) {
//                        $tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
//                    } else {
//                        $tax = false;
//                    }
                    // up
                    $attribute_groups = $this->model_catalog_product->getProductAttributes($result['product_id']);
                    // end up

                    $data[] = array(
                        'product_id' => $result['product_id'],
                        'model' => $result['model'],
                        'thumb' => $image,
                        'name' => $result['name'],
                        'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                        'price' => $price,
                        //'special'     => $special,
                        // 'tax'         => $tax,
                        'class_name' => (in_array($result['product_id'], $wishlist)) ? 'products-wish-btn-active' : 'products-wish-btn',
                        'minimum' => $result['minimum'] > 0 ? $result['minimum'] : 1,
                        //'rating'      => $result['rating'],
                        'compare_count' => $compare_count,
                        // up
                        'reviews' => $this->model_extension_module_p_review->getTotalReviews($result['product_id']),
                        'metka_id' => $result['metka_id'],
                        'attribute_groups' => $attribute_groups,
                        // end up
                        'href' => $this->url->link('product/product', 'product_id=' . $result['product_id'])
                    );
                }
            }
        }
        return $data;
    }

    public function getListExpectedDelivery($customer_id)
    {
        $sql =  "SELECT pd.description,p.minimum,p.metka_id,p.image,p.stock_status_id,p.tax_class_id,p.model,p.price,p.quantity,pd.name,p.product_id,  p.status as status  FROM `oc_report_appearance` ra LEFT JOIN `oc_product` p ON(p.product_id=ra.product_id) LEFT JOIN `oc_product_description` pd ON(pd.product_id=ra.product_id) WHERE  pd.language_id='". (int)$this->config->get('config_language_id') ."' AND  p.stock_status_id=8 AND ra.customer_id='". $customer_id ."'";



        $query = $this->db->query($sql);

        $data = array();
        $wishlist = $this->model_account_wishlist->getWishlist();
        $this->load->model("extension/module/p_review");
        foreach ($query->rows as $result) {
                    if ($result['image']) {
                        $image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
                    } else {
                        $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
                    }
                    if (isset($this->session->data['compare'])) {
                        $compare_count = (in_array($result['product_id'], $this->session->data['compare'])) ? count($this->session->data['compare']) : '';
                    } else {
                        $compare_count = '';
                    }
                    if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                        $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                    } else {
                        $price = false;
                    }

//                    if ((float)$result['special']) {
//                        $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
//                    } else {
//                        $special = false;
//                    }
//
//                    if ($this->config->get('config_tax')) {
//                        $tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
//                    } else {
//                        $tax = false;
//                    }
                    // up
                    $attribute_groups = $this->model_catalog_product->getProductAttributes($result['product_id']);
                    // end up

                    $data[] = array(
                        'product_id' => $result['product_id'],
                        'model' => $result['model'],
                        'thumb' => $image,
                        'name' => $result['name'],
                        'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                        'price' => $price,
                        //'special'     => $special,
                        // 'tax'         => $tax,
                        'class_name' => (in_array($result['product_id'], $wishlist)) ? 'products-wish-btn-active' : 'products-wish-btn',
                        'minimum' => $result['minimum'] > 0 ? $result['minimum'] : 1,
                        //'rating'      => $result['rating'],
                        'compare_count' => $compare_count,
                        // up
                        'reviews' => $this->model_extension_module_p_review->getTotalReviews($result['product_id']),
                        'metka_id' => $result['metka_id'],
                        'attribute_groups' => $attribute_groups,
                        // end up
                        'href' => $this->url->link('product/product', 'product_id=' . $result['product_id'])
                    );

        }
        return $data;
    }
}