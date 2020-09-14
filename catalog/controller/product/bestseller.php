<?php
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerProductBestseller extends Controller {
	private $error = array();
	public function index() {
        $data['link_all_show'] = $this->url->link("product/manufacturer");


        $data['main_class'] = 'active';
        $data['catalog_category_wrapper_class'] = '';
        $data['wide_class'] = '';
        $data['price_class'] = '';
        $data['button-view-type'] = '';
        if(isset($this->session->data['button-view-type'])){
            $data['button-view-type'] = $this->session->data['button-view-type'];
            if($this->session->data['button-view-type']=='button-price-catalog'){
                $data['main_class'] = '';
                $data['wide_class'] = '';
                $data['price_class'] = 'active';
                $data['catalog_category_wrapper_class'] = 'price-catalog wide-price-catalog';
            }elseif($this->session->data['button-view-type']=='button-wide-catalog'){
                $data['main_class'] = '';
                $data['wide_class'] = 'active';
                $data['price_class'] = '';
                $data['catalog_category_wrapper_class'] = 'wide-catalog wide-price-catalog';
            }elseif($this->session->data['button-view-type']=='button-main-catalog'){
                $data['main_class'] = 'active';
                $data['wide_class'] = '';
                $data['price_class'] = '';
                $data['catalog_category_wrapper_class'] = '';
            }
        }else{
            $data['main_class'] = 'active';
            $data['wide_class'] = '';
            $data['price_class'] = '';
            $data['catalog_category_wrapper_class'] = '';
        }

        $this->load->language('product/bestseller');

        $this->load->model('catalog/category');

        $this->load->model('catalog/product');

        $this->load->model('tool/image');

        ////
        $config_language_id = (int)$this->config->get('config_language_id');
        $this->load->model('setting/module');
        $module_id = 52; // html banner opt buy
        $html_banner_opt_buy= $this->model_setting_module->getModule($module_id);
        $data['html_banner_opt_buy'] = '';
        if ($html_banner_opt_buy && $html_banner_opt_buy['status']) {
            if ($html_banner_opt_buy['module_description'][$config_language_id]['description']) {
                $data['html_banner_opt_buy'] = html_entity_decode($html_banner_opt_buy['module_description'][$config_language_id]['description'], ENT_QUOTES, 'UTF-8');
            }
        }
        ////

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
            $sort = 'p.sort_order';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
            $this->document->setRobots('noindex,follow');
        } else {
            $order = 'ASC';
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


        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('product/bestseller')
        );

        $this->load->model("account/wishlist");



        $this->document->setTitle($this->language->get('heading_title'));

        $data['heading_title'] = $this->language->get('heading_title');


        $data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));

            // Set the last category breadcrumb




            $data['compare'] = $this->url->link('product/compare');

            $url = '';

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . $this->request->get['filter'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }



        if (isset($this->request->cookie['viewed_popular_instrument'])) {
            $products = explode(',', $this->request->cookie['viewed_popular_instrument']);
        }



             $wishlist =  $this->model_account_wishlist->getWishlist();

            $this->load->model('extension/module/p_review');

                foreach ($products as $product_id) {
                    $result = $this->model_catalog_product->getProduct($product_id);
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


                    if(isset($this->session->data['compare'])){
                        $compare_count=(in_array($result['product_id'],$this->session->data['compare'])) ? count($this->session->data['compare']) : '';
                    }else{
                        $compare_count='';
                    }

                    $data['products'][] = array(
                        'product_id'  => $result['product_id'],
                        'model'      => $result['model'],
                        'thumb'       => $image,
                        'name'        => $result['name'],
                        'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                        'price'       => $price,
                        'special'     => $special,
                        'tax'         => $tax,
                        'class_name'         => (in_array($result['product_id'],$wishlist)) ? 'products-wish-btn-active' : 'products-wish-btn',
                        'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
                        'rating'      => $result['rating'],
                        'compare_count'      => $compare_count,
                        // up
                        'reviews'      => $this->model_extension_module_p_review->getTotalReviews($result['product_id']),
                        'metka_id'      => $result['metka_id'],
                        'attribute_groups'    => $attribute_groups,
                        // end up
                        'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'] . $url)
                    );
                }
            $url = '';

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . $this->request->get['filter'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $data['sorts'] = array();

            $data['sorts'][] = array(
                'text'  => $this->language->get('text_default'),
                'value' => 'p.sort_order-ASC',
                'href'  => $this->url->link('product/bestseller', 'sort=p.sort_order&order=ASC' . $url)
            );

            $data['sorts'][] = array(
                'text'  => $this->language->get('text_name_asc'),
                'value' => 'pd.name-ASC',
                'href'  => $this->url->link('product/bestseller', 'sort=pd.name&order=ASC' . $url)
            );

            $data['sorts'][] = array(
                'text'  => $this->language->get('text_name_desc'),
                'value' => 'pd.name-DESC',
                'href'  => $this->url->link('product/bestseller', 'sort=pd.name&order=DESC' . $url)
            );

            $data['sorts'][] = array(
                'text'  => $this->language->get('text_price_asc'),
                'value' => 'p.price-ASC',
                'href'  => $this->url->link('product/bestseller', 'sort=p.price&order=ASC' . $url)
            );

            $data['sorts'][] = array(
                'text'  => $this->language->get('text_price_desc'),
                'value' => 'p.price-DESC',
                'href'  => $this->url->link('product/bestseller', 'sort=p.price&order=DESC' . $url)
            );

            if ($this->config->get('config_review_status')) {
                $data['sorts'][] = array(
                    'text'  => $this->language->get('text_rating_desc'),
                    'value' => 'rating-DESC',
                    'href'  => $this->url->link('product/bestseller', 'sort=rating&order=DESC' . $url)
                );

                $data['sorts'][] = array(
                    'text'  => $this->language->get('text_rating_asc'),
                    'value' => 'rating-ASC',
                    'href'  => $this->url->link('product/bestseller', 'sort=rating&order=ASC' . $url)
                );
            }

            $data['sorts'][] = array(
                'text'  => $this->language->get('text_model_asc'),
                'value' => 'p.model-ASC',
                'href'  => $this->url->link('product/bestseller', 'sort=p.model&order=ASC' . $url)
            );

            $data['sorts'][] = array(
                'text'  => $this->language->get('text_model_desc'),
                'value' => 'p.model-DESC',
                'href'  => $this->url->link('product/bestseller', 'sort=p.model&order=DESC' . $url)
            );

            $url = '';

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . $this->request->get['filter'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            $data['limits'] = array();

            $limits = array_unique(array($this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit'), 25, 50, 75, 100));

            sort($limits);

            foreach($limits as $value) {
                $data['limits'][] = array(
                    'text'  => $value,
                    'value' => $value,
                    'href'  => $this->url->link('product/bestseller',  $url . '&limit=' . $value)
                );
            }

            $url = '';

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . $this->request->get['filter'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }



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
            /**
             * сортування на мобільному
             */
            $this->load->language('product/bestseller');
            if($data['order']=='ASC'){
                if($data['sort']=='p.sort_order'){
                    $data['sort_text'] = $this->language->get('text_default');
                }else if($data['sort']=='pd.name'){
                    $data['sort_text'] = $this->language->get('text_name_asc');
                }else if($data['sort']=='p.price'){
                    $data['sort_text'] = $this->language->get('text_price_asc');
                }else if($data['sort']=='rating'){
                    $data['sort_text'] = $this->language->get('text_rating_asc');
                }else if($data['sort']=='p.model'){
                    $data['sort_text'] = $this->language->get('text_model_asc');
                }
            }else if($data['order']=='DESC'){
                if($data['sort']=='pd.name'){
                    $data['sort_text'] = $this->language->get('text_name_desc');
                }else if($data['sort']=='p.price'){
                    $data['sort_text'] = $this->language->get('text_price_desc');
                }else if($data['sort']=='rating'){
                    $data['sort_text'] = $this->language->get('text_rating_desc');
                }else if($data['sort']=='p.model'){
                    $data['sort_text'] = $this->language->get('text_model_desc');
                }
            }
            $data['sort_text_title'] = $this->language->get('text_sort');
            $data['text_total_filter_title'] = (isset($this->session->data['text_total_filter'])) ? $this->session->data['text_total_filter'] : '';

            /**
             * сортування на мобільному end
             */
            $this->response->setOutput($this->load->view('product/bestseller', $data));
	}
}
