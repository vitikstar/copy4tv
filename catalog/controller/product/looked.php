<?php

class ControllerProductLooked extends Controller
{
    public function index()
    {
        $this->load->language('product/looked');
        $data['heading_title'] = $this->language->get('heading_title');



        $this->load->model('catalog/product');

        $this->load->model('tool/image');

        $data['buy_one_click_text'] = $this->language->get('buy_one_click_text');
        $data['text_model'] = $this->language->get('text_model');
        $data['text_empty'] = $this->language->get('text_empty');


        if (isset($this->request->get['filter'])) {
            $filter = $this->request->get['filter'];
            $this->document->setRobots('noindex,follow');
        } else {
            $filter = '';
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
            $this->document->setRobots('noindex,follow');
        } else {
            $sort = 'p.date_added';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
            $this->document->setRobots('noindex,follow');
        } else {
            $order = 'DESC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
            $this->document->setRobots('noindex,follow');
        } else {
            $page = 1;
        }

        if (isset($this->request->get['limit'])) {
            $limit = (int)$this->request->get['limit'];
            $this->document->setRobots('noindex,follow');
        } else {
            $limit = $this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit');
        }

        $data['main_class'] = 'active';
        $data['catalog_category_wrapper_class'] = '';
        $data['wide_class'] = '';
        $data['price_class'] = '';
        $data['button-view-type'] = '';

        if (isset($this->session->data['button-view-type'])) {
            $data['button-view-type'] = $this->session->data['button-view-type'];
            if ($this->session->data['button-view-type'] == 'button-price-catalog') {
                $data['main_class'] = '';
                $data['wide_class'] = '';
                $data['price_class'] = 'active';
                $data['catalog_category_wrapper_class'] = 'price-catalog wide-price-catalog';
            } elseif ($this->session->data['button-view-type'] == 'button-wide-catalog') {
                $data['main_class'] = '';
                $data['wide_class'] = 'active';
                $data['price_class'] = '';
                $data['catalog_category_wrapper_class'] = 'wide-catalog wide-price-catalog';
            } elseif ($this->session->data['button-view-type'] == 'button-main-catalog') {
                $data['main_class'] = 'active';
                $data['wide_class'] = '';
                $data['price_class'] = '';
                $data['catalog_category_wrapper_class'] = '';
            }
        } else {
            $data['main_class'] = 'active';
            $data['wide_class'] = '';
            $data['price_class'] = '';
            $data['catalog_category_wrapper_class'] = '';
        }


        $data['breadcrumbs'] = array();

        $lang = $this->language->get('code');
        $data['lang'] = $lang;

        if ($lang == 'ru') {
            $data['breadcrumbs'][] = array(
                'text' => 'Главная',
                'href' => $this->url->link('common/home')
            );
        } else {
            $data['breadcrumbs'][] = array(
                'text' => 'Головна',
                'href' => $this->url->link('common/home')
            );
        }


        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
            $this->document->setRobots('noindex,follow');
        } else {
            $page = 1;
        }

        if (isset($this->request->get['limit'])) {
            $limit = (int)$this->request->get['limit'];
            $this->document->setRobots('noindex,follow');
        } else {
            $limit = $this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit');
        }

        $url = '';

        if (isset($this->request->get['limit'])) {
            $url .= '?limit=' . $limit;
        }

        $data['breadcrumbs'][] = array(
            'text' => $data['heading_title'],
            'href' => $this->url->link('product/looked') . $url
        );


        $data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));

        // Set the last category breadcrumb


        $data['compare'] = $this->url->link('product/compare');


        $data['products'] = array();


        $this->load->model("account/wishlist");

        $wishlist = $this->model_account_wishlist->getWishlist();

        $this->load->model('extension/module/p_review');



        if (isset($this->session->data['matrosite']['looked'])) {
            $filter_data = array(
                'list_looked' => implode(",",$this->session->data['matrosite']['looked']),
                'start'              => ($page - 1) * $limit,
                'limit'              => $limit
            );
            $products = $this->model_catalog_product->getProducts($filter_data);
        } else {
            $products = array();
        }

        foreach ($products as $product) {
            $result = $product;
            if ($result['image']) {
                $image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
            }

            if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
            } else {
                $price = false;
            }

            if ((float)$result['special']) {
                $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
            } else {
                $special = false;
            }

            if ($this->config->get('config_tax')) {
                $tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
            } else {
                $tax = false;
            }

            if ($this->config->get('config_review_status')) {
                $rating = (int)$result['rating'];
            } else {
                $rating = false;
            }

            // up
            $attribute_groups = $this->model_catalog_product->getProductAttributes($result['product_id']);
            // end up


            if (isset($this->session->data['compare'])) {
                $compare_count = (in_array($result['product_id'], $this->session->data['compare'])) ? count($this->session->data['compare']) : '';
            } else {
                $compare_count = '';
            }

            $data['products'][] = array(
                'product_id' => $result['product_id'],
                'model' => $result['model'],
                'thumb' => $image,
                'name' => $result['name'],
                'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                'price' => $price,
                'special' => $special,
                'tax' => $tax,
                'class_name' => (in_array($result['product_id'], $wishlist)) ? 'products-wish-btn-active' : 'products-wish-btn',
                'minimum' => $result['minimum'] > 0 ? $result['minimum'] : 1,
                'rating' => $result['rating'],
                'compare_count' => $compare_count,
                // up
                'reviews' => $this->model_extension_module_p_review->getTotalReviews($result['product_id']),
                'metka_id' => $result['metka_id'],
                'attribute_groups' => $attribute_groups,
                // end up
                'href' => $this->url->link('product/product', 'product_id=' . $result['product_id'])
            );
        }
        $pagination = new Pagination();
        $pagination->total = count($products);
        $pagination->page = $page;
        $pagination->limit = $limit;
        $pagination->url = $this->url->link('product/looked', 'page={page}');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), (count($products)) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > (count($products) - $limit)) ? count($products) : ((($page - 1) * $limit) + $limit), count($products), ceil(count($products) / $limit));

        // http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html


        $data['sort'] = $sort;
        $data['order'] = $order;
        $data['limit'] = $limit;

        $data['continue'] = $this->url->link('common/home');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = (isMobile) ? '' : $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');

        $data['header'] = $this->load->controller('common/header');
        $data['column_megamenu'] = $this->load->controller('common/column_megamenu');

        $this->response->setOutput($this->load->view('product/looked', $data));


    }
}
