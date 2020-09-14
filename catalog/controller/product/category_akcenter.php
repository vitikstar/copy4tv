<?php
class ControllerProductCategory extends Controller {
    public function index() {
        $this->document->addScript('catalog/view/javascript/ocfilter/ocfilter.js');
        $this->document->addScript('catalog/view/javascript/ocfilter/trackbar.js');
        include_once($_SERVER['DOCUMENT_ROOT']."/catalog/model/tool/location.php");
        $this->document->addStyle('catalog/view/theme/akcenter/stylesheet/catalog.css');
        $this->document->addScript('catalog/view/javascript/jquery/owl-carousel/owl.carousel.js');
        $this->document->addScript('catalog/view/theme/akcenter/javascript/bootstrap-star-rating/js/star-rating.js');
        $data = $this->load->language('product/category');

        $o=new ModelToolLocation($this->registry);
        $o->getURL();

        /**
        vitik edit
         */



        /******************************************************
         */
        //$this->load->language('product/category');

        $this->load->model('catalog/category');

        $this->load->model('catalog/product');



        /**
         * ГРУПИРОВКА
         */

        $data['config_block_limit_goods']        = 0;
        $data['config_group_goods']              = 0;
        $data['config_sort_order_goods']         = 0;
        $data['config_block_hide_goods_no_foto'] = 0;
        $data['config_grid_button_goods_show']   = 0;


        $data['config_block_limit_goods']        = $this->config->get('config_block_limit_goods');
        $data['config_group_goods']              = $this->config->get('config_group_goods');
        $data['config_sort_order_goods']         = $this->config->get('config_sort_order_goods');
        $data['config_block_hide_goods_no_foto'] = $this->config->get('config_block_hide_goods_no_foto');
        $data['config_grid_button_goods_show']   = $this->config->get('config_grid_button_goods_show');


        /**
         * ГРУПИРОВКА
         */



        /* OCFilter - start */
        $this->load->model('catalog/manufacturer');
        if (isset($this->request->get['path_ocfilter'])) {
            $ocfilter_path = $this->request->get['path_ocfilter'];
        } else {
            $ocfilter_path = null;
        }

        if (isset($this->request->get['filter_ocfilter'])) {

            $filter_ocfilter = $this->request->get['filter_ocfilter'];
        } else {
            $filter_ocfilter = null;
        }
        /* OCFilter - end */
        $this->load->model('tool/image');

        if (isset($this->request->get['filter'])) {
            $filter = $this->request->get['filter'];
        } else {
            $filter = '';
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'pd.name';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }
        if (isset($this->request->get['limit']) and empty($this->request->session['limitPage'])) {
            $limit = (int)$this->request->get['limit'];
            // $_SESSION['limitPage']=$limit;
        } elseif(isset($_SESSION['limitPage']) and !empty($_SESSION['limitPage'])){
            $limit=$_SESSION['limitPage'];
        }else{
            $limit = $this->config->get('config_product_limit');
        }

        if (isset($this->request->get['type'])) {
            $type = $this->request->get['type'];
        } else {
            $type = 'all';
        }

        $data['type'] = $type;

        $data['breadcrumbs'] = array();



        /* SOFORP QuickOrder - begin */
        $this->language->load("module/soforp_quickorder");
        $data['button_quickorder'] = $this->language->get('button_quickorder');

        /* SOFORP QuickOrder - end */



        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        if (isset($this->request->get['path'])) {
            $url = '';
            /* OCFilter - start */
            if (isset($this->request->get['filter_ocfilter'])) {
                $url .= "&filter_ocfilter=" . $this->request->get['filter_ocfilter'];
            }
            /* OCFilter - end */

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['limit'])) {
                $this->request->session['limitPage']=$this->request->get['limit'];
                $url .= '&limit=' . $this->request->get['limit'];
            }
            if (isset($this->request->get['type'])) {
                $url .= '&type=' . $this->request->get['type'];
            }
            $path = '';

            $parts = explode('_', (string)$this->request->get['path']);

            $category_id = (int)array_pop($parts);

            foreach ($parts as $path_id) {
                if (!$path) {
                    $path = (int)$path_id;
                } else {
                    $path .= '_' . (int)$path_id;
                }

                $category_info = $this->model_catalog_category->getCategory($path_id);

                if ($category_info) {
                    $data['breadcrumbs'][] = array(
                        'text' => $category_info['name'],
                        'href' => $this->url->link('product/category', 'path=' . $path )
                        // 'href' => $this->url->link('product/category', 'path=' . $path .$url)
                    );
                }
            }
        } else {
            $category_id = 0;
        }


        $data['path']=(string)$this->request->get['path'];
        $data['filter_ocfilter']=$this->request->get['filter_ocfilter'];


        $category_info = $this->model_catalog_category->getCategory($category_id);

        /* SOFORP Product Link - begin */
        $this->user = new User($this->registry);
        if ( $this->user->isLogged() ) {
            $data['edit_link'] = '/admin/index.php?route=catalog/category/edit&token=' . $this->session->data['token'] . '&category_id=' . $category_id;
        }
        /* SOFORP Product Link - end */


        if ($category_info) {

            if ($category_info['meta_title']) {
                $this->document->setTitle($category_info['meta_title']);
            } else {
                $this->document->setTitle($category_info['name']);
            }

            $this->document->setDescription($category_info['meta_description']);
            $this->document->setKeywords($category_info['meta_keyword']);

            $this->load->model("design/banner");

            $data['slides'] = array();
            $banners = $this->model_design_banner->getBanner($category_info['banner1_id']);
            foreach ($banners as $banner) {
                if (is_file(DIR_IMAGE . $banner['image'])) {
                    $data['slides'][] = array(
                        'title' => $banner['title'],
                        'link'  => $banner['link'],
                        'image' => $this->model_tool_image->resize($banner['image'], 1075, 286)
                    );
                }
            }

            $data['banners'] = array();
            $banners = $this->model_design_banner->getBanner($category_info['banner2_id']);
            foreach ($banners as $banner) {
                if (is_file(DIR_IMAGE . $banner['image'])) {
                    $data['banners'][] = array(
                        'title' => $banner['title'],
                        'link'  => $banner['link'],
                        'image' => $this->model_tool_image->resize($banner['image'], 422, 138)
                    );
                }
            }

            if ($category_info['meta_h1']) {
                $data['heading_title'] = $category_info['meta_h1'];
            } else {
                $data['heading_title'] = $category_info['name'];
            }

            $data['text_refine'] = $this->language->get('text_refine');
            $data['text_empty'] = $this->language->get('text_empty');
            $data['reset_filter'] = $this->language->get('reset_filter');
            $data['text_quantity'] = $this->language->get('text_quantity');
            $data['text_manufacturer'] = $this->language->get('text_manufacturer');
            $data['text_model'] = $this->language->get('text_model');
            $data['text_price'] = $this->language->get('text_price');
            $data['text_tax'] = $this->language->get('text_tax');
            $data['text_points'] = $this->language->get('text_points');
            $data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
            $data['text_sort'] = $this->language->get('text_sort');
            $data['text_limit'] = $this->language->get('text_limit');

            $data['button_cart'] = $this->language->get('button_cart');
            $data['button_wishlist'] = $this->language->get('button_wishlist');
            $data['button_compare'] = $this->language->get('button_compare');
            $data['button_product_review'] = $this->language->get('button_product_review');
            $data['button_continue'] = $this->language->get('button_continue');
            $data['button_list'] = $this->language->get('button_list');
            $data['button_grid'] = $this->language->get('button_grid');

            // Set the last category breadcrumb
            $data['breadcrumbs'][] = array(
                'text' => $category_info['name'],
                'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'])
            );




            if ($category_info['image']) {
                $data['thumb'] = $this->model_tool_image->resize($category_info['image'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
                $this->document->setOgImage($data['thumb']);
            } else {
                $data['thumb'] = '';
            }

            $data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');
            $data['compare'] = $this->url->link('product/compare');

            $url = '';
            /* OCFilter - start */
            if (isset($this->request->get['filter_ocfilter'])) {
                $url .= "&filter_ocfilter=" . $this->request->get['filter_ocfilter'];
            }
            /* OCFilter - end */

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
                $this->request->session['limitPage']=$this->request->get['limit'];
                $url .= '&limit=' . $this->request->get['limit'];
            }
            if (isset($this->request->get['type'])) {
                $url .= '&type=' . $this->request->get['type'];
            }
            $image_filter=false;
            if (isset($this->request->get['image'])) {
                $url .= '&image=1';
                $image_filter=$this->request->get['image'];
            }
            $data['categories'] = array();


            /*
            $results = $this->model_catalog_category->getCategories($category_id);

            foreach ($results as $result) {
                $filter_data = array(
                    'filter_category_id'  => $result['category_id'],
                    'filter_sub_category' => true
                );

                $data['categories'][] = array(
                    'name' => $result['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
                    'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '_' . $result['category_id'] . $url)
                );
            }*/

            $data['products'] = array();

            $filter_data = array(
                'filter_category_id' => $category_id,
                'filter_sub_category' => false,
                /* OCFilter - start */
                'filter_ocfilter' => $filter_ocfilter,
                /* OCFilter - end */
                'filter_filter'      => $filter,
                'sort'               => $sort,
                'order'              => $order,
                'type'               => $type,
                'start'              => ($page - 1) * $limit,
                'limit'              => $limit,
                'image'              => $image_filter
            );


            $product_total = $this->model_catalog_product->getTotalProducts($filter_data);


            $results = $this->model_catalog_product->getProducts($filter_data);
            if( count($results) < $limit) {
                $product_total = ( $page - 1 ) * $limit + count($results);
            }
            $imageFolder = DIR_IMAGE . 'catalog/product/'; // папка для загрузки картинок, звідси будуть братись картинки для виводу

            foreach ($results as $result) {

                $images = array();
                if($result['image_p']) $image = $this->model_tool_image->resize($result['image_p'],  $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
                else                   $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')     );

                $arr=$this->model_catalog_product->getProductImagesVitya($result['product_id']);
                if($arr){
                    $images=array_map(function ($item){
                        return $this->model_tool_image->resize($item['image'],  $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));;
                    },$arr);
                }





                if($result['hide_photo']==1){
                    $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')     );
                    $images=array();
                }


                /*
*
* даєм приорітет на відображення спарсеним картинкам (завантаженим через адмінку)(новим)
*/


                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $price = false;
                }

                if ((float)$result['special']) {
                    $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $special = false;
                }

                if ($this->config->get('config_tax')) {
                    $tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
                } else {
                    $tax = false;
                }

                if ($this->config->get('config_review_status')) {
                    $rating = (int)$result['rating'];
                } else {
                    $rating = false;
                }

                $description_text="";

                if(!empty($result['description']) and strlen($result['description'])>10) $description_text=utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..';
//$popup=$this->model_tool_image->resize($result['sku'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'),true);

                $data['products'][] = array(
                    'product_id'  => $result['product_id'],
                    'hide_opt_price'  => $result['hide_opt_price'],
                    'quantity'  => $result['quantity'],
                    'stock' => ( $this->config->get('config_stock_display') ? $this->language->get('text_stock') . " " . $result['quantity'] : '' ),
                    'thumb'       => $image,
                    //'popup'       => $popup,

                    /* akcenter - begin */
                    'sku' => isset($result['sku']) ?  $result['sku'] : '',
                    'images' => $images,
                    'sticker' => isset($result['sticker']) ?  $result['sticker'] : '',
                    'video' => isset($result['video']) ?  $result['video'] : '',
                    'customer_price' => $this->currency->format((float)$result['customer_price']),
                    'opt_price' => isset($result['opt_price']) ?  $this->currency->format((float)$result['opt_price']) : '',
                    'opt_quantity' => isset($result['opt_quantity']) ?  floatval($result['opt_quantity']) : '',
                    'date_added' => $result['date_added'],
                    /* akcenter - end */
                    'name'        => $result['name'],
                    'text_price_retail'        => $this->language->get('text_price_retail'),
                    'description' => $description_text,
                    'price'       => $price,
                    'special'     => $special,
                    'tax'         => $tax,
                    'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
                    'rating'      => $result['rating'],
                    'retail_price'      => $this->currency->format((float)$result['price']),
                    'href'        => $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'] . $url),
                    'stock_only' => sprintf($this->language->get('stock_only'),$result['quantity']),
                    'text_from' => sprintf($this->language->get('text_from'),$result['opt_quantity']),
                    'config_image_product_width'=> $this->config->get('config_image_product_width'),
                    'config_image_product_height'=> $this->config->get('config_image_product_height'),
                    // 'text_price_you' => $this->language->get('text_price_you'),

                    /**
                     * ГРУПИРОВКА
                     */
                    'percentage_markup' => $result['percentage_markup']
                    /**
                     * ГРУПИРОВКА
                     */
                );


            }
            $data['logged']=$this->customer->isLogged();

            $data['group_id']=$this->customer->getGroupId();
            $url = '';
            /* OCFilter - start */
            if (isset($this->request->get['filter_ocfilter'])) {
                $url .= "&filter_ocfilter=" . $this->request->get['filter_ocfilter'];
            }
            /* OCFilter - end */

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . $this->request->get['filter'];
            }

            if (isset($this->request->get['limit'])) {
                $this->request->session['limitPage']=$this->request->get['limit'];

                $url .= '&limit=' . $this->request->get['limit'];
            }
            if (isset($this->request->get['type'])) {
                $url .= '&type=' . $this->request->get['type'];
            }
            if (isset($this->request->get['image'])) {
                $url .= '&image=1';
            }
            $data['sorts'] = array();

//            $data['sorts'][] = array(
//                //				'text'  => $this->language->get('text_default'), По уполчанию
//                'text'  => $this->language->get('text_name_asc'),
//                'value' => 'p.sort_order-ASC',
//                'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.sort_order&order=ASC' . $url)
//            );

            $data['sorts'][] = array(
                'text'  => $this->language->get('text_default'),
                'value' => 'p.image-DESC',
                'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.image&order=DESC' . $url)
            );
            $data['sorts'][] = array(
                'text'  => $this->language->get('text_name_asc'),
                'value' => 'pd.name-ASC',
                'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=ASC' . $url)
            );

            $data['sorts'][] = array(
                'text'  => $this->language->get('text_name_desc'),
                'value' => 'pd.name-DESC',
                'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=DESC' . $url)
            );

            $data['sorts'][] = array(
                'text'  => $this->language->get('text_price_asc'),
                'value' => 'p.price-ASC',
                'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=ASC' . $url)
            );

            $data['sorts'][] = array(
                'text'  => $this->language->get('text_price_desc'),
                'value' => 'p.price-DESC',
                'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=DESC' . $url)
            );

            if ($this->config->get('config_review_status')) {
                $data['sorts'][] = array(
                    'text'  => $this->language->get('text_rating_desc'),
                    'value' => 'rating-DESC',
                    'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=DESC' . $url)
                );

                $data['sorts'][] = array(
                    'text'  => $this->language->get('text_rating_asc'),
                    'value' => 'rating-ASC',
                    'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=ASC' . $url)
                );
            }

            $data['sorts'][] = array(
                'text'  => $this->language->get('text_model_asc'),
                'value' => 'p.model-ASC',
                'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=ASC' . $url)
            );

            $data['sorts'][] = array(
                'text'  => $this->language->get('text_model_desc'),
                'value' => 'p.model-DESC',
                'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=DESC' . $url)
            );







            $url = '';
            /* OCFilter - start */
            if (isset($this->request->get['filter_ocfilter'])) {
                $url .= "&filter_ocfilter=" . $this->request->get['filter_ocfilter'];
            }
            /* OCFilter - end */

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . $this->request->get['filter'];
            }

            if (isset($this->request->session['sort'])){
                $url .= '&sort=' . $this->request->session['sort'];

            }elseif (isset($this->request->get['sort'])) {
                $this->request->session['sort']=$this->request->get['sort'];
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->session['order'])){
                $url .= '&order=' . $this->request->session['order'];

            }elseif (isset($this->request->get['order'])) {
                $this->request->session['order']=$this->request->get['order'];
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['limit'])) {
                $this->request->session['limitPage']=$this->request->get['limit'];

                $url .= '&limit=' . $this->request->get['limit'];
            }

            if (isset($this->request->get['image'])) {
                $url .= '&image=1';
            }

            if (isset($this->request->get['type'])) {
                $url .= '&type=' . $this->request->get['type'];
            }

            $data['limits'] = array();

            $limits = array_unique(array($this->config->get('config_product_limit'), 50, 75, 100));

            sort($limits);

            foreach($limits as $value) {
                $data['limits'][] = array(
                    'text'  => $value,
                    'value' => $value,
                    'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=' . $value)
                );
            }

            $data['filter_only_foto_array_text']=$this->language->get("filter_only_foto_array_text");
            if (isset($this->request->get['image'])) {
                $url =str_replace('&image=1','',$url);
                $data['filter_only_foto_array'][]=
                    array(
                        'href'=>$this->url->link('product/category', 'path=' . $this->request->get['path'] . '&image=1' . $url),
                        'text'=>$this->language->get("filter_only_foto_array_yes")
                    );
                $data['filter_only_foto_array'][]=
                    array(
                        'href'=>$this->url->link('product/category', 'path=' . $this->request->get['path'] .  $url),
                        'text'=>$this->language->get("filter_only_foto_array_no")
                    );
            }else{

                $data['filter_only_foto_array'][]=
                    array(
                        'href'=>$this->url->link('product/category', 'path=' . $this->request->get['path'] .  $url),
                        'text'=>$this->language->get("filter_only_foto_array_no")
                    );
                $data['filter_only_foto_array'][]=
                    array(
                        'href'=>$this->url->link('product/category', 'path=' . $this->request->get['path'] . '&image=1' . $url),
                        'text'=>$this->language->get("filter_only_foto_array_yes")
                    );
            }

            if (isset($this->request->get['image']))  $url .= '&image=' . $this->request->get['image'];

            $data['text_type'] = $this->language->get("text_type");
            $data['types'] = array();

            $data['types'][] = array(
                'text' => $this->language->get('text_type_all'),
                'value' => 0,
                'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url)
            );


            /**
             * ГРУПИРОВКА
             */

            $data['text_type'] = $this->language->get("text_type");
            $data['types'] = array();

            $data['types']['all'] = array(
                'text' => $this->language->get('text_type_all'),
                'value' => 'all',
                'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&type=all')
            );

            foreach( array('hit','novelty','interesting_things') as $type_id ) {
                $data['types'][$type_id] = array(
                    'text' => $this->language->get('text_type_' . $type_id),
                    'value' => $type_id,
                    'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&type=' . $type_id)
                );
            }


            /**
             * ГРУПИРОВКА
             */


            $pagination = new Pagination();
            $pagination->total = $product_total;
            $pagination->page = $page;
            $pagination->limit = $limit;
            $pagination->url = $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&page={page}');


            $data['pagination'] = $pagination->render();
            //  $data['count_page'] = ceil($product_total / $limit);

            $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

            // http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
            if ($page == 1) {
                /* OCFilter - start */
                if(!isset($this->request->get['filter_ocfilter'])){
                    // $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'], 'SSL'), 'canonical');
                }
                /* OCFilter - end */
            } elseif ($page == 2) {
                $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'], 'SSL'), 'prev');
            } else {
                $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'] . '&page='. ($page - 1), 'SSL'), 'prev');
            }

            if ($limit && ceil($product_total / $limit) > $page) {
                $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'] . '&page='. ($page + 1), 'SSL'), 'next');
            }

            $data['sort'] = $sort;
            $data['order'] = $order;
            $data['limit'] = $limit;







            //$data['show_more']= sprintf($this->language->get('show_more'),$limit_show_more);


            /* OCFilter - start */
            $filter_name = '';

            if (!empty($filter_ocfilter)) {
                $this->document->addLink($this->url->link('product/category',  'path=' . $category_info['category_id'] . '&filter_ocfilter=' . $filter_ocfilter, 'SSL'), 'canonical');
                $params        = $this->model_catalog_ocfilter->decodeParamsFromString($filter_ocfilter);
                $options_count = 0;

                foreach ($params as $option_id => $values) {
                    if ($filter_name) {
                        $filter_name .= ', ';
                    }

                    // Add price
                    if ($option_id == 'p') {
                        $price_range = explode('-', array_shift($values));

                        if (isset($price_range[0]) && isset($price_range[1])) {
                            $filter_name .= 'от ' . $price_range[0] . ' до ' . $price_range[1] . $this->currency->getSymbolLeft() . $this->currency->getSymbolRight();
                        }

                        $options_count++;
                        continue;
                    }

                    // Add stock status
                    if ($option_id == 's') {
                        $stock_status = array_shift($values);

                        if ($stock_status == 'in') {
                            $filter_name .= 'в наличии';
                        } elseif ($stock_status == 'out') {
                            $filter_name .= 'нет в наличии';
                        }

                        $options_count++;
                        continue;
                    }

                    // Add manufacturers
                    if ($option_id == 'm') {
                        $values_name  = '';
                        $values_count = 0;

                        foreach ($values as $value_id) {
                            $values_count++;
                            $value_info = $this->model_catalog_manufacturer->getManufacturer($value_id);

                            if ($value_info) {
                                if ($values_name) {
                                    $values_name .= ', ';
                                }

                                $values_name .= $value_info['name'];
                            }
                        }

                        if ($values_count > 1) {
                            $this->document->setNoindex(true);
                        }

                        $filter_name .= $values_name;

                        $options_count++;
                        continue;
                    }

                    // Add other filters
                    $option_info = $this->model_catalog_ocfilter->getOption($option_id);
                    if ($option_info) {
                        $filter_name .= ' ' . $option_info['name'];
                        $values_name  = '';
                        $values_count = 0;

                        foreach ($values as $value_id) {
                            $values_count++;
                            $value_info = $this->model_catalog_ocfilter->getOptionValue($value_id);

                            if ($value_info) {
                                if ($values_name) {
                                    $values_name .= ', ';
                                }
                                $values_name .= $value_info['name'] . $option_info['postfix'];
                            } else {
                                $values_name .= $value_id . $option_info['postfix'];
                            }
                        }

                        if ($values_count > 1) {
                            $this->document->setNoindex(true);
                        }

                        $filter_name .= ' - ' . $values_name;

                        $options_count++;
                    }
                }

                if ($filter_name) {
                    $filter_name = strip_tags(html_entity_decode($filter_name, ENT_QUOTES, 'UTF-8'));

                    $title = $category_info['meta_title'] ? $category_info['meta_title'] : $category_info['name'];

                    $this->document->setTitle($title . ' ' . $filter_name);
                    $this->document->setDescription($category_info['meta_description'] . ' ' . $filter_name);
                    $this->document->setKeywords($category_info['meta_keyword'] . ' ' . $filter_name);

                    $data['heading_title'] = $data['heading_title'] . ': ' . $filter_name;

                    if ($ocfilter_path) {
                        $ocfilter_page_info = $this->model_catalog_ocfilter->getPage($category_id, $ocfilter_path);
                    } else {
                        $params_href = $this->load->controller('module/ocfilter/getParamsHref', $filter_ocfilter);
                        $ocfilter_page_info = $this->model_catalog_ocfilter->getPage($category_id, trim($params_href, '/'));
                    }

                    if ($ocfilter_page_info) {

                        $this->document->setNoindex(false);

                        $config_language_id = $this->config->get('config_language_id');

                        $meta_title=unserialize($ocfilter_page_info['meta_title']);
                        $meta_description=unserialize($ocfilter_page_info['meta_description']);
                        $meta_keyword=unserialize($ocfilter_page_info['meta_keyword']);
                        $title = unserialize($ocfilter_page_info['title']);

                        $this->document->setTitle($meta_title[$config_language_id]);
                        $this->document->setDescription($meta_description[$config_language_id]);
                        $this->document->setKeywords($meta_keyword[$config_language_id]);
                        $data['heading_title'] = $title[$config_language_id];

                        $description=unserialize($ocfilter_page_info['description']);
                        $data['description'] = html_entity_decode($description[$config_language_id], ENT_QUOTES, 'UTF-8');
                        //$this->document->addLink($this->url->link('product/category',  'path=' . $category_info['category_id'] . '&filter_ocfilter=' . $filter_ocfilter, 'SSL'), 'canonical');
                    } else{

                        // TODO дублі anonical
                        $data['description'] = '';
                        //$this->document->addLink($this->url->link('product/category',  'path=' . $category_info['category_id'], 'SSL'), 'canonical');
                    }
                }
            }else{
                $this->document->addLink($this->url->link('product/category',  'path=' . $category_info['category_id'], 'SSL'), 'canonical');
            }
            /* OCFilter - end */
            //$this->document->addLink(HTTP_SERVER ,$_SERVER['REQUEST_URI'], 'canonical');
            $data['continue'] = $this->url->link('common/home');

            $this->registry->set('data',$data);
            $data['registry'] = $this->registry;

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');

$data['header'] = $this->load->controller('common/header');
$data['column_megamenu'] = $this->load->controller('common/column_megamenu');

            // if($_SERVER['HTTP_X_REAL_IP']=='93.75.87.151') $this->model_catalog_product->getProducts();


            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/category.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/category.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/product/category.tpl', $data));
            }
        } else {
            $url = '';
            /* OCFilter - start */
            if (isset($this->request->get['filter_ocfilter'])) {
                $url .= "&filter_ocfilter=" . $this->request->get['filter_ocfilter'];
            }
            /* OCFilter - end */

            if (isset($this->request->get['path'])) {
                $url .= '&path=' . $this->request->get['path'];
            }

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . $this->request->get['filter'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            if (isset($this->request->get['limit'])) {
                $this->request->session['limitPage']=$this->request->get['limit'];

                $url .= '&limit=' . $this->request->get['limit'];
            }
            if (isset($this->request->get['type'])) {
                $url .= '&type=' . $this->request->get['type'];
            }

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_error'),
                'href' => $this->url->link('product/category', $url)
            );


            $this->document->setTitle($this->language->get('text_error'));

            $data['heading_title'] = $this->language->get('text_error');

            $data['text_error'] = $this->language->get('text_error');

            $data['button_continue'] = $this->language->get('button_continue');

            $data['continue'] = $this->url->link('common/home');

            $this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');

$data['header'] = $this->load->controller('common/header');
$data['column_megamenu'] = $this->load->controller('common/column_megamenu');

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
            }
        }
    }



    public function loadImageLazy(){
        $this->load->model("tool/image");
        $imageFolder = DIR_IMAGE . 'catalog/product/'; // папка для загрузки картинок, звідси будуть братись картинки для виводу

        /*
       *
       *
       */

        $arrayUrl=array();
        foreach (json_decode($_POST['data']) as $sku){
            $object = new SplitImageSubFolder($cache = FALSE, $imageFolder, $sku);

            $filename = $object->implode();

            if         ((is_file($filename) and !strpos($filename,'placeholder')))     $image = $this->model_tool_image->resize($sku,    $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')    ,true); //якщо картинки завантажувались через форму то поле  $result['parseImg'] буде рівне 1
            //elseif     ($result['image'])        $image = $this->model_tool_image->resize($result['image'],  $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')     );
            else                                 $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')     );

            $arrayUrl[$sku]=$image;
        }

        echo json_encode($arrayUrl);
        // echo $image;
        // $this->response->addHeader('Content-Type: application/json');
        //$this->response->setOutput($_POST);
    }
}
