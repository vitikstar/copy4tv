<?php
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerCatalogWaitingList extends Controller {
    private $error = array();
    public function index() {

        $data = $this->load->language('catalog/waiting_list');

        $this->document->setTitle($this->language->get('heading_title'));



        $data['user_token'] = $this->session->data['user_token'];
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->get['filter_product_name'])) {
            $filter_product_name = $this->request->get['filter_product_name'];
        } else {
            $filter_product_name = '';
        }
        if (isset($this->request->get['filter_customer_name'])) {
            $filter_customer_name = $this->request->get['filter_customer_name'];
        } else {
            $filter_customer_name = '';
        }
        if (isset($this->request->get['filter_customer_telephone'])) {
            $filter_customer_telephone = $this->request->get['filter_customer_telephone'];
        } else {
            $filter_customer_telephone = '';
        }
        if (isset($this->request->get['filter_date_report_create'])) {
            $filter_date_report_create = $this->request->get['filter_date_report_create'];
        } else {
            $filter_date_report_create = '';
        }

        if (isset($this->request->get['filter_model'])) {
            $filter_model = $this->request->get['filter_model'];
        } else {
            $filter_model = '';
        }


        if (isset($this->request->get['filter_price_min'])) {
            $filter_price_min = $this->request->get['filter_price_min'];
        } else {
            $filter_price_min = null;
        }

        if (isset($this->request->get['filter_price_max'])) {
            $filter_price_max = $this->request->get['filter_price_max'];
        } else {
            $filter_price_max = null;
        }


        if (isset($this->request->get['filter_quantity_min'])) {
            $filter_quantity_min = $this->request->get['filter_quantity_min'];
        } else {
            $filter_quantity_min = null;
        }

        if (isset($this->request->get['filter_quantity_max'])) {
            $filter_quantity_max = $this->request->get['filter_quantity_max'];
        } else {
            $filter_quantity_max = null;
        }

        if (isset($this->request->get['filter_telephone'])) {
            $filter_telephone = $this->request->get['filter_telephone'];
        } else {
            $filter_telephone = null;
        }



        $filter_sub_category = null;
        if (isset($this->request->get['filter_category'])) {
            $filter_category = $this->request->get['filter_category'];
            if (!empty($filter_category) && isset($this->request->get['filter_sub_category'])) {
                $filter_sub_category = true;
            } elseif (isset($this->request->get['filter_sub_category'])) {
                unset($this->request->get['filter_sub_category']);
            }
        } else {
            $filter_category = null;
            if (isset($this->request->get['filter_sub_category'])) {
                unset($this->request->get['filter_sub_category']);
            }
        }

        $filter_category_name = null;
        if (isset($filter_category)) {
            if ($filter_category>0) {
                $this->load->model('catalog/category');



                $category = $this->model_catalog_category->getCategory($filter_category);
                if ($category) {
                    $filter_category_name = ($category['path']) ? $category['path'] . ' &gt; ' . $category['name'] : $category['name'];
                } else {
                    $filter_category = null;
                    unset($this->request->get['filter_category']);
                    $filter_sub_category = null;
                    if (isset($this->request->get['filter_sub_category'])) {
                        unset($this->request->get['filter_sub_category']);
                    }
                }
            } else {
                $filter_category_name = $this->language->get('text_none_category');
            }
        }

        $filter_manufacturer_id = null;
        $filter_manufacturer_name = '';
        if (isset($this->request->get['filter_manufacturer_id'])) {
            $filter_manufacturer_id = (int)$this->request->get['filter_manufacturer_id'];
            if($filter_manufacturer_id > 0) {
                $this->load->model('catalog/manufacturer');
                $manufacturer = $this->model_catalog_manufacturer->getManufacturer($filter_manufacturer_id);
                if ($manufacturer) {
                    $filter_manufacturer_name = $manufacturer['name'];
                } else {
                    $filter_manufacturer_name = null;
                    unset($this->request->get['filter_manufacturer_id']);
                }
            } else {
                $filter_manufacturer_id = 0;
                $filter_manufacturer_name =  $this->language->get('text_none_manufacturer');
            }
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


        $url = '';

        if (isset($this->request->get['filter_product_name'])) {
            $url .= '&filter_product_name=' . urlencode(html_entity_decode($this->request->get['filter_product_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_customer_name'])) {
            $url .= '&filter_customer_name=' . urlencode(html_entity_decode($this->request->get['filter_customer_name'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['filter_customer_telephone'])) {
            $url .= '&filter_customer_telephone=' . urlencode(html_entity_decode($this->request->get['filter_customer_telephone'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['filter_date_report_create'])) {
            $url .= '&filter_date_report_create=' . urlencode(html_entity_decode($this->request->get['filter_date_report_create'], ENT_QUOTES, 'UTF-8'));
        }


        if (isset($this->request->get['filter_product_name'])) {
            $url .= '&filter_product_name=' . urlencode(html_entity_decode($this->request->get['filter_product_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
        }


        if (isset($this->request->get['filter_price_min'])) {
            $url .= '&filter_price_min=' . $this->request->get['filter_price_min'];
        }

        if (isset($this->request->get['filter_price_max'])) {
            $url .= '&filter_price_max=' . $this->request->get['filter_price_max'];
        }


        if (isset($this->request->get['filter_quantity_min'])) {
            $url .= '&filter_quantity_min=' . $this->request->get['filter_quantity_min'];
        }

        if (isset($this->request->get['filter_quantity_max'])) {
            $url .= '&filter_quantity_max=' . $this->request->get['filter_quantity_max'];
        }


        if (isset($this->request->get['filter_category'])) {
            $url .= '&filter_category=' . $this->request->get['filter_category'];
            if (isset($this->request->get['filter_sub_category'])) {
                $url .= '&filter_sub_category';
            }
        }

        if (isset($this->request->get['filter_manufacturer_id'])) {
            $url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
        }


        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_product_name'] = $this->url->link('catalog/waiting_list', 'user_token=' . $this->session->data['user_token'] . '&sort=pd.name' . $url, true);
        $data['sort_customer_name'] = $this->url->link('catalog/waiting_list', 'user_token=' . $this->session->data['user_token'] . '&sort=ra.report_appearance_name' . $url, true);
        $data['sort_customer_telephone'] = $this->url->link('catalog/waiting_list', 'user_token=' . $this->session->data['user_token'] . '&sort=ra.telephone' . $url, true);
        $data['sort_model'] = $this->url->link('catalog/waiting_list', 'user_token=' . $this->session->data['user_token'] . '&sort=p.model' . $url, true);
        $data['sort_price'] = $this->url->link('catalog/waiting_list', 'user_token=' . $this->session->data['user_token'] . '&sort=p.price' . $url, true);
        $data['sort_quantity'] = $this->url->link('catalog/waiting_list', 'user_token=' . $this->session->data['user_token'] . '&sort=p.quantity' . $url, true);
        $data['sort_date_report_create'] = $this->url->link('catalog/waiting_list', 'user_token=' . $this->session->data['user_token'] . '&sort=ra.date_report_create' . $url, true);


        $url = '';

        if (isset($this->request->get['filter_product_name'])) {
            $url .= '&filter_product_name=' . urlencode(html_entity_decode($this->request->get['filter_product_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_customer_name'])) {
            $url .= '&filter_customer_name=' . urlencode(html_entity_decode($this->request->get['filter_customer_name'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['filter_customer_telephone'])) {
            $url .= '&filter_customer_telephone=' . urlencode(html_entity_decode($this->request->get['filter_customer_telephone'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['filter_date_report_create'])) {
            $url .= '&filter_date_report_create=' . urlencode(html_entity_decode($this->request->get['filter_date_report_create'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_price'])) {
            $url .= '&filter_price=' . $this->request->get['filter_price'];
        }

        if (isset($this->request->get['filter_price_min'])) {
            $url .= '&filter_price_min=' . $this->request->get['filter_price_min'];
        }

        if (isset($this->request->get['filter_price_max'])) {
            $url .= '&filter_price_max=' . $this->request->get['filter_price_max'];
        }

        if (isset($this->request->get['filter_quantity'])) {
            $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
        }

        if (isset($this->request->get['filter_quantity_min'])) {
            $url .= '&filter_quantity_min=' . $this->request->get['filter_quantity_min'];
        }

        if (isset($this->request->get['filter_quantity_max'])) {
            $url .= '&filter_quantity_max=' . $this->request->get['filter_quantity_max'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_category'])) {
            $url .= '&filter_category=' . $this->request->get['filter_category'];
            if (isset($this->request->get['filter_sub_category'])) {
                $url .= '&filter_sub_category';
            }
        }


        if (isset($this->request->get['filter_manufacturer_id'])) {
            $url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
        }



        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }



        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('catalog/waiting_list', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        $filter_data = array(
            'filter_customer_name'	  => $filter_customer_name,
            'filter_customer_telephone'	  => $filter_customer_telephone,
            'filter_date_report_create'	  => $filter_date_report_create,
            'filter_product_name'	  => $filter_product_name,
            'filter_model'	  => $filter_model,
            'filter_price_min'=> $filter_price_min,
            'filter_price_max'=> $filter_price_max,
            'filter_quantity_min' 	=> $filter_quantity_min,
            'filter_quantity_max' 	=> $filter_quantity_max,
            'filter_category'		=> $filter_category,
            'filter_category_name' => $filter_category_name,
            'filter_sub_category'	=> $filter_sub_category,
            'filter_manufacturer_id'=> $filter_manufacturer_id,
            'filter_manufacturer_name' => $filter_manufacturer_name,
            'filter_telephone' => $filter_telephone,
            'sort'            		=> $sort,
            'order'           		=> $order,
            'start'           		=> ($page - 1) * $this->config->get('config_limit_admin'),
            'limit'           		=> $this->config->get('config_limit_admin')
        );

        $this->load->model('catalog/waiting_list');

        $this->load->model('tool/image');


        $results = $this->model_catalog_waiting_list->getProductsRequest($filter_data);
        $request_total = $this->model_catalog_waiting_list->getTotalProductsRequest($filter_data);


        foreach ($results as $result) {
            if (is_file(DIR_IMAGE . $result['image'])) {
                $image = $this->model_tool_image->resize($result['image'], 40, 40);
            } else {
                $image = $this->model_tool_image->resize('no_image.png', 40, 40);
            }


            $data['request_result_list'][] = array(
                'product_id' => $result['product_id'],
                'image'      => $image,
                'report_appearance_name'       => $result['report_appearance_name'],
                'report_appearance_id'       => $result['report_appearance_id'],
                'telephone'       => $result['telephone'],
                'product_name'       => $result['name'],
                'date_report_create'      => $result['date_report_create'],
                'date_report_availability'      => $result['date_report_availability'],
                'model'      => $result['model'],
                'price'      => $this->currency->format($result['price'], $this->config->get('config_currency')),
                'quantity'   => $result['quantity']
            );
        }


        $data['results'] = sprintf($this->language->get('text_pagination'), ($request_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($request_total - $this->config->get('config_limit_admin'))) ? $request_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $request_total, ceil($request_total / $this->config->get('config_limit_admin')));

        $pagination = new Pagination();
        $pagination->total = $request_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('catalog/waiting_list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();

        $data['filter_product_name'] = $filter_product_name;
        $data['filter_customer_name'] = $filter_customer_name;
        $data['filter_customer_telephone'] = $filter_customer_telephone;
        $data['filter_date_report_create'] = $filter_date_report_create;
        $data['filter_model'] = $filter_model;
        $data['filter_quantity_min'] = $filter_quantity_min;
        $data['filter_quantity_max'] = $filter_quantity_max;
        $data['filter_category'] = $filter_category;
        $data['filter_sub_category'] = $filter_sub_category;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');


        $this->response->setOutput($this->load->view('catalog/waiting_list', $data));
    }

    public function autocomplete() {
        $json = array();

        if(isset($this->request->get['filter_customer_name'])) {


            if (isset($this->request->get['filter_customer_name'])) {
                $filter_customer_name = $this->request->get['filter_customer_name'];
            } else {
                $filter_customer_name = '';
            }

            if (isset($this->request->get['limit'])) {
                $limit = $this->request->get['limit'];
            } else {
                $limit = 5;
            }

            $filter_data = array(
                'filter_customer_name'  => $filter_customer_name,
                'start'        => 0,
                'limit'        => $limit
            );
            $this->load->model('catalog/waiting_list');

            $results = $this->model_catalog_waiting_list->getProductsRequest($filter_data);

            foreach ($results as $result) {
                $json[] = array(
                    'report_appearance_id' => $result['report_appearance_id'],
                    'report_appearance_name'       => strip_tags(html_entity_decode($result['report_appearance_name'], ENT_QUOTES, 'UTF-8')),
                    'model'      => $result['model'],
                    'price'      => $result['price']
                );
            }
        }
        if(isset($this->request->get['filter_customer_telephone'])) {


            if (isset($this->request->get['filter_customer_telephone'])) {
                $filter_customer_telephone = $this->request->get['filter_customer_telephone'];
            } else {
                $filter_customer_telephone = '';
            }

            if (isset($this->request->get['limit'])) {
                $limit = $this->request->get['limit'];
            } else {
                $limit = 5;
            }

            $filter_data = array(
                'filter_customer_telephone'  => $filter_customer_telephone,
                'start'        => 0,
                'limit'        => $limit
            );
            $this->load->model('catalog/waiting_list');

            $results = $this->model_catalog_waiting_list->getProductsRequest($filter_data);

            foreach ($results as $result) {

                $report_appearance_name = strip_tags(html_entity_decode($result['report_appearance_name'], ENT_QUOTES, 'UTF-8'));
                $json[] = array(
                    'report_appearance_id' => $result['report_appearance_id'],
                    'telephone' => $result['telephone'],
                    'report_appearance_name'       => $report_appearance_name
                );
            }
        }
        if(isset($this->request->get['filter_product_name'])) {


            if (isset($this->request->get['filter_product_name'])) {
                $filter_product_name = $this->request->get['filter_product_name'];
            } else {
                $filter_product_name = '';
            }

            if (isset($this->request->get['limit'])) {
                $limit = $this->request->get['limit'];
            } else {
                $limit = 5;
            }

            $filter_data = array(
                'filter_product_name'  => $filter_product_name,
                'start'        => 0,
                'limit'        => $limit
            );
            $this->load->model('catalog/waiting_list');

            $results = $this->model_catalog_waiting_list->getProductsRequest($filter_data);

            foreach ($results as $result) {
                $json[] = array(
                    'product_id' => $result['product_id'],
                    'product_name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
                    'model'      => $result['model'],
                    'price'      => $result['price']
                );
            }
        }
        if (isset($this->request->get['filter_model'])) {

            if (isset($this->request->get['filter_model'])) {
                $filter_model = $this->request->get['filter_model'];
            } else {
                $filter_model = '';
            }

            if (isset($this->request->get['limit'])) {
                $limit = $this->request->get['limit'];
            } else {
                $limit = 5;
            }

            $filter_data = array(
                'filter_model' => $filter_model,
                'start'        => 0,
                'limit'        => $limit
            );
            $this->load->model('catalog/waiting_list');

            $results = $this->model_catalog_waiting_list->getProductsRequest($filter_data);

            foreach ($results as $result) {
                $json[] = array(
                    'report_appearance_id' => $result['report_appearance_id'],
                    'report_appearance_name'       => strip_tags(html_entity_decode($result['report_appearance_name'], ENT_QUOTES, 'UTF-8')),
                    'model'      => $result['model'],
                    'price'      => $result['price']
                );
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    public function status() {
        $json = array();

        if(isset($this->request->post['radio']) and isset($this->request->post['report_appearance_id'])) {
            $status = $this->request->post['radio'];
            $report_appearance_id = (int)$this->request->post['report_appearance_id'];
            if($status){
                $sql = "UPDATE " . DB_PREFIX . "report_appearance SET date_report_availability=NOW() WHERE report_appearance_id = '" . $report_appearance_id . "'";
            }else{
                $sql = "UPDATE " . DB_PREFIX . "report_appearance SET date_report_availability='' WHERE report_appearance_id = '" . $report_appearance_id . "'";
            }
            if($report_appearance_id){
                $this->db->query($sql);
            }
            $sql = "SELECT date_report_availability FROM " . DB_PREFIX . "report_appearance  WHERE report_appearance_id = '" . $report_appearance_id . "'";

            $result = $this->db->query($sql);
            $json['date_report_availability'] = $result->row['date_report_availability'];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
