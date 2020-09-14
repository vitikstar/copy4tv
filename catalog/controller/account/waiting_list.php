<?php
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerAccountWaitingList extends Controller {
    public function index() {
        $customer_id = 0;
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/waiting_list', '', true);
            
			$this->response->redirect($this->url->link('account/login', '', true));
		}else{
            $customer_id = $this->customer->getId();
        }


        $data = $this->load->language('account/waiting_list');

        $this->document->setTitle($this->language->get('heading_title'));
        $this->document->setRobots('noindex,follow');

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
            'text' => $data['text_account'],
            'href' => $this->url->link('account/account', '', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $data['heading_title'],
            'href' => $this->url->link('account/waiting_list')
        );

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

        $this->load->model('account/waiting_list');

        $data['list_available'] = $this->model_account_waiting_list->getListAvailable($customer_id);
        $data['list_not_available'] = $this->model_account_waiting_list->getListNotAvailable($customer_id);
        $data['list_expected_delivery'] = $this->model_account_waiting_list->getListExpectedDelivery($customer_id);


        $data['continue'] = $this->url->link('account/account', '', true);

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');

$data['header'] = $this->load->controller('common/header');
$data['column_megamenu'] = $this->load->controller('common/column_megamenu');

        $this->response->setOutput($this->load->view('account/waiting_list', $data));
    }

    public function setSession(){
        $this->session->data['button-view-type'] = $_POST['key'];
    }

    public function add() {
        $initial_page = $this->request->post['initial_page'];
        $this->load->language('account/wishlist');

        $json = array();

        if (isset($this->request->post['product_id'])) {
            $product_id = $this->request->post['product_id'];
        } else {
            $product_id = 0;
        }
        $json['add_wishlist_text'] ='';
        if($initial_page=='product'){

            $this->load->language('product/product');

            $json['add_wishlist_text'] =  sprintf($this->language->get('add_wishlist_text'), $this->url->link('account/wishlist', '', true));

        }



        $this->load->model('catalog/product');

        $product_info = $this->model_catalog_product->getProduct($product_id);

        if ($product_info) {
            //	if ($this->customer->isLogged()) {
            // Edit customers cart
            $this->load->model('account/wishlist');

            $this->model_account_wishlist->addWishlist($this->request->post['product_id']);

            $json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . (int)$this->request->post['product_id']), $product_info['name'], $this->url->link('account/wishlist'));

            $json['total'] = sprintf($this->language->get('text_wishlist'), $this->model_account_wishlist->getTotalWishlist());
//			} else {
//				if (!isset($this->session->data['wishlist'])) {
//					$this->session->data['wishlist'] = array();
//				}
//
//				$this->session->data['wishlist'][] = $this->request->post['product_id'];
//
//				$this->session->data['wishlist'] = array_unique($this->session->data['wishlist']);
//
//				$json['success'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', true), $this->url->link('account/register', '', true), $this->url->link('product/product', 'product_id=' . (int)$this->request->post['product_id']), $product_info['name'], $this->url->link('account/wishlist'));
//
//				$json['total'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
//			}
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
