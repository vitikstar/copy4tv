<?php
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerAccountWishList extends Controller {
	public function index() {
        
//		if (!$this->customer->isLogged()) {
//			$this->session->data['redirect'] = $this->url->link('account/wishlist', '', true);
//
//			$this->response->redirect($this->url->link('account/login', '', true));
//		}

		$this->load->language('account/wishlist');

		$data['text_model'] = $this->language->get('column_model');

		$this->load->model('account/wishlist');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		if (isset($this->request->get['remove'])) {
			// Remove Wishlist
			$this->model_account_wishlist->deleteWishlist($this->request->get['remove']);

			$this->session->data['success'] = $this->language->get('text_remove');

			$this->response->redirect($this->url->link('account/wishlist'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		$this->document->setTitle($data['heading_title']);
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
//		$data['breadcrumbs'][] = array(
//			'text' => $this->language->get('text_home'),
//			'href' => $this->url->link('common/home')
//		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $data['heading_title'],
			'href' => $this->url->link('account/wishlist')
		);

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
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
		$data['products'] = array();


        $product_total = $this->model_account_wishlist->getTotalWishlist();
        $list_wishlist = ($this->model_account_wishlist->getWishlist()) ? implode(",",$this->model_account_wishlist->getWishlist()) : '';
        $list_wishlist = trim($list_wishlist,",");
        $filter_data = array(
            'list_wishlist' => $list_wishlist,
            'filter_filter'      => $filter,
            'sort'               => $sort,
            'order'              => $order,
            'start'              => ($page - 1) * $limit,
            'limit'              => $limit
        );
		$results = ($list_wishlist) ? $this->model_catalog_product->getProducts($filter_data) : array();


		foreach ($results as $product_info) {

			if ($product_info) {
				if ($product_info['image']) {
					$image = $this->model_tool_image->resize($product_info['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_wishlist_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_wishlist_height'));
				} else {
					$image = false;
				}

				if ($product_info['quantity'] <= 0) {
					$stock = $product_info['stock_status'];
				} elseif ($this->config->get('config_stock_display')) {
					$stock = $product_info['quantity'];
				} else {
					$stock = $this->language->get('text_instock');
				}

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if ((float)$product_info['special']) {
					$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = false;
				}
                if ($this->customer->isLogged() || !$this->config->get('config_customer_price') and $product_info['metka_id']=='super_price') {
                    $super_price = $this->currency->format($this->tax->calculate($product_info['super_price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                } else {
                    $super_price = false;
                }
                // up
                $attribute_groups = $this->model_catalog_product->getProductAttributes($product_info['product_id']);
                // end up
				$data['products'][] = array(
					'product_id' => $product_info['product_id'],
					'thumb'      => $image,
					'name'       => $product_info['name'],
					'model'      => $product_info['model'],
					'stock'      => $stock,
					'price'      => $price,
					'super_price'      => $super_price,
					'special'    => $special,

                    // up
                    'reviews'      => $product_info['reviews'],
                    'metka_id'      => $product_info['metka_id'],
                    'attribute_groups'    => $attribute_groups,
                    // end up
					'href'       => $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
					'remove'     => $this->url->link('account/wishlist', 'remove=' . $product_info['product_id'])
				);
			} else {
				$this->model_account_wishlist->deleteWishlist($product_info['product_id']);
			}
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
            'href'  => $this->url->link('account/wishlist', 'sort=p.sort_order&order=ASC' . $url)
        );

        $data['sorts'][] = array(
            'text'  => $this->language->get('text_name_asc'),
            'value' => 'pd.name-ASC',
            'href'  => $this->url->link('account/wishlist', 'sort=pd.name&order=ASC' . $url)
        );

        $data['sorts'][] = array(
            'text'  => $this->language->get('text_name_desc'),
            'value' => 'pd.name-DESC',
            'href'  => $this->url->link('account/wishlist', 'sort=pd.name&order=DESC' . $url)
        );

        $data['sorts'][] = array(
            'text'  => $this->language->get('text_price_asc'),
            'value' => 'p.price-ASC',
            'href'  => $this->url->link('account/wishlist', 'sort=p.price&order=ASC' . $url)
        );

        $data['sorts'][] = array(
            'text'  => $this->language->get('text_price_desc'),
            'value' => 'p.price-DESC',
            'href'  => $this->url->link('account/wishlist', 'sort=p.price&order=DESC' . $url)
        );

        if ($this->config->get('config_review_status')) {
            $data['sorts'][] = array(
                'text'  => $this->language->get('text_rating_desc'),
                'value' => 'rating-DESC',
                'href'  => $this->url->link('account/wishlist', 'sort=rating&order=DESC' . $url)
            );

            $data['sorts'][] = array(
                'text'  => $this->language->get('text_rating_asc'),
                'value' => 'rating-ASC',
                'href'  => $this->url->link('account/wishlist', 'sort=rating&order=ASC' . $url)
            );
        }

        $data['sorts'][] = array(
            'text'  => $this->language->get('text_model_asc'),
            'value' => 'p.model-ASC',
            'href'  => $this->url->link('account/wishlist', 'sort=p.model&order=ASC' . $url)
        );

        $data['sorts'][] = array(
            'text'  => $this->language->get('text_model_desc'),
            'value' => 'p.model-DESC',
            'href'  => $this->url->link('account/wishlist', 'sort=p.model&order=DESC' . $url)
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
                'href'  => $this->url->link('account/wishlist', $url . 'limit=' . $value)
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

        $pagination = new Pagination();
        $pagination->total = $product_total;
        $pagination->page = $page;
        $pagination->limit = $limit;
        $pagination->url = $this->url->link('account/wishlist',  $url . '&page={page}');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

        if ($page == 1) {
            $this->document->addLink($this->url->link('account/wishlist'), 'canonical');
        } else {
            $this->document->addLink($this->url->link('account/wishlist', 'page='. $page), 'canonical');
        }

        if ($page > 1) {
            $this->document->addLink($this->url->link('account/wishlist',  (($page - 2) ? '&page='. ($page - 1) : '')), 'prev');
        }

        if ($limit && ceil($product_total / $limit) > $page) {
            $this->document->addLink($this->url->link('account/wishlist', 'page='. ($page + 1)), 'next');
        }

        $data['sort'] = $sort;
        $data['order'] = $order;
        $data['limit'] = $limit;

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');

$data['header'] = $this->load->controller('common/header');
$data['column_megamenu'] = $this->load->controller('common/column_megamenu');

		$this->response->setOutput($this->load->view('account/wishlist', $data));
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
        $this->load->model('account/wishlist');
		$product_info = $this->model_catalog_product->getProduct($product_id);
        $wishlist = $this->model_account_wishlist->getWishlist();

		if ($product_info) {
			if ($this->customer->isLogged()) {
				// Edit customers cart

                if((in_array($this->request->post['product_id'],$wishlist))){
                    $this->model_account_wishlist->deleteWishlist($this->request->post['product_id']);
                    $json['count'] = $this->model_account_wishlist->getTotalWishlist() - 1;
                }else{
                    $this->model_account_wishlist->addWishlist($this->request->post['product_id']);
                    $json['count'] = $this->model_account_wishlist->getTotalWishlist() + 1;
                }

				$json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . (int)$this->request->post['product_id']), $product_info['name'], $this->url->link('account/wishlist'));

				$json['total'] = sprintf($this->language->get('text_wishlist'), $this->model_account_wishlist->getTotalWishlist());
				$json['count'] = $this->model_account_wishlist->getTotalWishlist();
			} else {


                if ((in_array($this->request->post['product_id'], $wishlist))) {
                    $this->model_account_wishlist->deleteWishlist($this->request->post['product_id']);
                    $json['count'] = $this->model_account_wishlist->getTotalWishlist() - 1;
                } else {
                    $this->model_account_wishlist->addWishlist($this->request->post['product_id']);
                    $json['count'] = $this->model_account_wishlist->getTotalWishlist() + 1;
                }

				//$json['success'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', true), $this->url->link('account/register', '', true), $this->url->link('product/product', 'product_id=' . (int)$this->request->post['product_id']), $product_info['name'], $this->url->link('account/wishlist'));
                $json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . (int)$this->request->post['product_id']), $product_info['name'], $this->url->link('account/wishlist'));

                $json['total'] = sprintf($this->language->get('text_wishlist'), (isset($this->request->cookie['wishlist']) ? count($this->request->cookie['wishlist']) : 0));
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function remove(){
        $this->load->model('account/wishlist');

        $json = array();
        $json['count'] = $this->model_account_wishlist->getTotalWishlist()-1;
        $this->load->model('account/wishlist');
        $this->model_account_wishlist->deleteWishlist($this->request->post['remove']);
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
