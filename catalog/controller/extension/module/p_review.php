<?php 
class ControllerExtensionModulePReview extends Controller {
	public function index() {
		$this->load->language('extension/module/p_review');
		
		$this->load->model('extension/module/p_review');
		
		$data['tab_p_review'] = $this->language->get('tab_p_review');
		
		$data['text_total'] = $this->language->get('text_total');
		$data['text_no_p_review'] = $this->language->get('text_no_p_review');
		
		$data['text_sort'] = $this->language->get('text_sort');
		$data['text_filter_rating'] = $this->language->get('text_filter_rating');
		$data['text_limit'] = $this->language->get('text_limit');
		$data['text_write'] = $this->language->get('text_write');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', true), $this->url->link('account/register', '', true));
		
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_city'] = $this->language->get('entry_city');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_text'] = $this->language->get('entry_text');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_captcha'] = $this->language->get('entry_captcha');
		$data['entry_rating'] = $this->language->get('entry_rating');
		$data['entry_good'] = $this->language->get('entry_good');
		$data['entry_bad'] = $this->language->get('entry_bad');
		$data['entry_avatar'] = $this->language->get('entry_avatar');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_comment'] = $this->language->get('entry_comment');
		
		$data['button_upload'] = $this->language->get('button_upload');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_clear'] = $this->language->get('button_clear');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['button_write'] = $this->language->get('button_write');
		$data['button_submit'] = $this->language->get('button_submit');
		$data['button_readmore'] = $this->language->get('button_readmore');

		$data['reply_count'] = $this->language->get('reply_count');

		if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
		} else {
			$product_id = 0;
		}

		$data['position'] = $this->getPosition();
		$data['total'] = $this->model_extension_module_p_review->getTotalReviews($product_id);
		$data['rating'] = $this->model_extension_module_p_review->getRating($product_id);
		$data['filter'] = $this->getFilter();
		$data['p_reviews'] = $this->getList($product_id, $data['total']);
		$data['form'] = $this->getForm();
		return $data;
	}
	public function showMore(){
        $this->load->language('extension/module/p_review');
        $data['p_review']['tab_p_review'] = $this->language->get('tab_p_review');

        $data['p_review']['text_total'] = $this->language->get('text_total');
        $data['p_review']['text_no_p_review'] = $this->language->get('text_no_p_review');

        $data['p_review']['text_sort'] = $this->language->get('text_sort');
        $data['p_review']['text_filter_rating'] = $this->language->get('text_filter_rating');
        $data['p_review']['text_limit'] = $this->language->get('text_limit');
        $data['p_review']['text_write'] = $this->language->get('text_write');
        $data['p_review']['text_loading'] = $this->language->get('text_loading');
        $data['p_review']['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', true), $this->url->link('account/register', '', true));

        $data['p_review']['entry_title'] = $this->language->get('entry_title');
        $data['p_review']['entry_city'] = $this->language->get('entry_city');
        $data['p_review']['entry_name'] = $this->language->get('entry_name');
        $data['p_review']['entry_text'] = $this->language->get('entry_text');
        $data['p_review']['entry_email'] = $this->language->get('entry_email');
        $data['p_review']['entry_captcha'] = $this->language->get('entry_captcha');
        $data['p_review']['entry_rating'] = $this->language->get('entry_rating');
        $data['p_review']['entry_good'] = $this->language->get('entry_good');
        $data['p_review']['entry_bad'] = $this->language->get('entry_bad');
        $data['p_review']['entry_avatar'] = $this->language->get('entry_avatar');
        $data['p_review']['entry_image'] = $this->language->get('entry_image');
        $data['p_review']['entry_comment'] = $this->language->get('entry_comment');

        $data['p_review']['button_upload'] = $this->language->get('button_upload');
        $data['p_review']['button_add'] = $this->language->get('button_add');
        $data['p_review']['button_clear'] = $this->language->get('button_clear');
        $data['p_review']['button_remove'] = $this->language->get('button_remove');
        $data['p_review']['button_write'] = $this->language->get('button_write');
        $data['p_review']['button_submit'] = $this->language->get('button_submit');
        $data['p_review']['button_readmore'] = $this->language->get('button_readmore');

        $data['p_review']['reply_count'] = $this->language->get('reply_count');
        $product_id = (int)$this->request->post['product_id'];


        if (isset($this->request->post['sort_order'])) {
            $array = explode('~',$this->request->post['sort_order']);
            $sort = $array[0];
            $order = $array[1];
//            echo $this->request->post['sort_order'];
//            exit;
        } else {
            $sort = 'pr.date_added';
            $order = 'DESC';
        }

        if (isset($this->request->post['rating'])) {
            $rating = $this->request->post['rating'];
        } else {
            if ($this->config->get('module_p_review_filter_rating')) {
                $rating = 'all';
            } else {
                $rating = 0;
            }
        }


        $start = (int)$this->request->post['start'];
        $limit = (int)$this->request->post['limit'];
        $rendering = $this->request->post['rendering'];

        if($rendering==1){
            $limit = (int)$this->request->post['start'];
            $start=0;
        }  //костиль (нз чого але так працює коли йде сортування)

        $data['p_reviews'] = $this->getListAjax($product_id, $start,$limit,$sort,$order,$rating);
        $this->load->model('extension/module/p_review');
        $total_reviews =  $this->model_extension_module_p_review->getTotalReviews($product_id);

        $diff =   $total_reviews-($start+$this->config->get('module_p_review_limit'));

        $count = ($diff>$this->config->get('module_p_review_limit')) ? $this->config->get('module_p_review_limit') : $diff;

        $json['reviews'] = $this->load->view('extension/module/p_review/ajax/item', $data);
        $json['more_button'] = ($count>=1) ? '<div class="product-more-button-text" onclick="showMoreReview('.$count.','.$product_id.')">Посмотреть еще '.$count.' комментариев.</div>
	<div class="product-more-button-icon">
		<img src="catalog/view/theme/4tv/image/icons-bg/product-more-icon.svg" alt="" class="img-responsive">
	</div>' : '';
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
	public function p_review() {
		$this->load->language('extension/module/p_review');
		
		$this->load->model('extension/module/p_review');

		if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
		} else {
			$product_id = 0;
		}
		
		if (isset($this->request->get['rating'])) {
			$rating = $this->request->get['rating'];
		} else {
			if ($this->config->get('module_p_review_filter_rating')) {
				$rating = 'all';
			} else {
				$rating = 0;
			}
		}
		
		$filter_data = array(
			'rating' => $rating
		);
		
		$data['text_no_p_review'] = $this->language->get('text_no_p_review');
		
		$data['entry_text'] = $this->language->get('entry_text');
		$data['entry_good'] = $this->language->get('entry_good');
		$data['entry_bad'] = $this->language->get('entry_bad');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_comment'] = $this->language->get('entry_comment');
		
		$data['button_readmore'] = $this->language->get('button_readmore');
		
		$data['field_image'] = $this->config->get('module_p_review_image');
		
		$data['p_reviews'] = $this->getList($product_id, $this->model_extension_module_p_review->getTotalReviews($product_id, $filter_data));

		$this->response->setOutput($this->load->view('extension/module/p_review/ajax/list', $data));
	}
	
	public function total() {
		$this->load->language('extension/module/p_review');
		
		$this->load->model('extension/module/p_review');
		
		$data['text_total'] = $this->language->get('text_total');
		
		$data['field_rating'] = $this->config->get('module_p_review_rating');
		
		if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
		} else {
			$product_id = 0;
		}
		
		$data['total'] = $this->model_extension_module_p_review->getTotalReviews($product_id, $data);
		$data['rating'] = $this->model_extension_module_p_review->getRating($product_id);
		
		$this->response->setOutput($this->load->view('extension/module/p_review/ajax/total', $data));
	}
	
	public function all() {
		$this->load->language('extension/module/p_review');

		$this->load->model('extension/module/p_review');
		$this->load->model('tool/image');
		
		$data['field_image'] = $this->config->get('module_p_review_image');
		$data['field_rating'] = $this->config->get('module_p_review_rating');
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pr.date_added';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}
		
		if (isset($this->request->get['rating'])) {
			$rating = $this->request->get['rating'];
		} else {
			if ($this->config->get('module_p_review_filter_rating')) {
				$rating = 'all';
			} else {
				$rating = 0;
			}
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limits = array_unique(explode(',', $this->config->get('module_p_review_limit')));
			sort($limits);
			$limit = $limits[0];
		}

		$description = $this->config->get('module_p_review_description');
		$language_id = $this->config->get('config_language_id');
		
		if ($description[$language_id]['title']) {
			$heading_title = $description[$language_id]['title'];
		} else {
			$heading_title = $this->language->get('heading_title');
		}
		
		$this->document->setTitle($heading_title);
		
		$this->document->setDescription($description[$language_id]['meta_description']);
		$this->document->setKeywords($description[$language_id]['meta_keyword']);
		
		$data['description'] = html_entity_decode($description[$language_id]['description'], ENT_QUOTES, 'UTF-8');
		
		if ($this->config->get('module_p_review_image')) {
			$this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
			$this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
		}
		
		$this->document->addStyle('catalog/view/javascript/p_review/p_review.css');

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
	       	'text'      => $heading_title,
			'href'      => $this->url->link('extension/module/p_review/all')
	   	);
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['rating'])) {
			$url .= '&rating=' . $this->request->get['rating'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}
		
		$data['heading_title'] = $heading_title;
		
		$data['text_sort'] = $this->language->get('text_sort');
		$data['text_filter_rating'] = $this->language->get('text_filter_rating');
		$data['text_limit'] = $this->language->get('text_limit');
		$data['text_no_p_review'] = $this->language->get('text_no_p_review');
		
		$data['entry_text'] = $this->language->get('entry_text');
		$data['entry_bad'] = $this->language->get('entry_bad');
		$data['entry_good'] = $this->language->get('entry_good');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_comment'] = $this->language->get('entry_comment');
		
		$data['button_product'] = $this->language->get('button_product');
		$data['button_readmore'] = $this->language->get('button_readmore');
		
		$filter_data = array(
			'sort'       	=> $sort,
			'order'      	=> $order,
			'rating' 		=> $rating,
			'filter_rating' => $this->config->get('module_p_review_filter_rating'),
			'start'      	=> ($page - 1) * $limit,
			'limit'      	=> $limit
		);
		
		$data['p_reviews'] = array();
		
		$p_review_total = $this->model_extension_module_p_review->getTotalReviews(0);
			
		$results = $this->model_extension_module_p_review->getReviews(0, $filter_data);

		foreach ($results as $result) {
			if ($result['product_image']) {
				$product_image = $this->model_tool_image->resize($result['product_image'], $this->config->get('module_p_review_product_image_width'), $this->config->get('module_p_review_product_image_height'));
			} else {
				$product_image = $this->model_tool_image->resize('placeholder.png', $this->config->get('module_p_review_product_image_width'), $this->config->get('module_p_review_product_image_height'));
			}
				
			$avatar = '';
			
			if ($this->config->get('module_p_review_avatar')) {
				
				if ($result['avatar']) {
					$avatar = $this->model_tool_image->resize($result['avatar'], $this->config->get('module_p_review_avatar_width'), $this->config->get('module_p_review_avatar_height'));
				} else {
					$avatar = $this->model_tool_image->resize('catalog/p_review/avatar/no_avatar.png', $this->config->get('module_p_review_avatar_width'), $this->config->get('module_p_review_avatar_height'));
				}
			}
			
			$image = array();
			
			if ($this->config->get('module_p_review_image')) {
				
				if ($result['image']) {
					
					foreach (explode('|', $result['image']) as $img) {
						$image[] = array(
							'thumbnail' => $this->model_tool_image->resize($img, $this->config->get('module_p_review_thumbnail_width'), $this->config->get('module_p_review_thumbnail_height')),
							'thumb'     => $this->model_tool_image->resize($img, $this->config->get('module_p_review_thumb_width'), $this->config->get('module_p_review_thumb_height'))
						);
					}
				}
			}
			
			$text_limit = $this->config->get('module_p_review_text_limit');
			
			$text = '';
			$readmore = false;
			
			if ($this->config->get('module_p_review_text')) {
				
				if ($this->config->get('module_p_review_editor')) {
					$text = $this->model_extension_module_p_review->replaceBBCode($result['text']);
				} else {
					$text = $result['text'];
				}
			
				if ($this->config->get('module_p_review_cut') && mb_strlen(strip_tags($text), 'UTF-8') > $text_limit) {
					$text = utf8_substr(strip_tags($text), 0, $text_limit) . '...';
					$readmore = true;
				}
			}
			
			$good = '';
			
			if ($this->config->get('module_p_review_good')) {
				
				if ($this->config->get('module_p_review_editor')) {
					$good = $this->model_extension_module_p_review->replaceBBCode($result['good']);
				} else {
					$good = $result['good'];
				}
			
				if ($this->config->get('module_p_review_cut') && mb_strlen(strip_tags($good), 'UTF-8') > $text_limit) {
					$good = utf8_substr(strip_tags($good), 0, $text_limit) . '...';
					$readmore = true;
				}
			}
			
			$bad = '';
			
			if ($this->config->get('module_p_review_bad')) {
				
				if ($this->config->get('module_p_review_editor')) {
					$bad = $this->model_extension_module_p_review->replaceBBCode($result['bad']);
				} else {
					$bad = $result['bad'];
				}
			
				if ($this->config->get('module_p_review_cut') && mb_strlen(strip_tags($bad), 'UTF-8') > $text_limit) {
					$bad = utf8_substr(strip_tags($bad), 0, $text_limit) . '...';
					$readmore = true;
				}
			}
				
			$data['p_reviews'][] = array(
				'p_review_id' 	 => $result['p_review_id'],
				'title'    	 	 => $this->config->get('module_p_review_title') ? $result['title'] : '',
				'city'		     => $this->config->get('module_p_review_city') ? $result['city'] : '',
				'name'		     => $this->config->get('module_p_review_name') ? $result['name'] : '',
				'text'		     => $text,
				'good'		     => $good,
				'bad'		     => $bad,
				'rating'	     => $this->config->get('module_p_review_rating') ? $result['rating'] : '',
				'avatar'	     => $avatar,
				'image'	 	     => $image,
				'comment'	     => trim(strip_tags(html_entity_decode($result['comment'], ENT_QUOTES, 'UTF-8'))) ? html_entity_decode($result['comment'], ENT_QUOTES, 'UTF-8') : '',
				'date_added'     => $this->config->get('module_p_review_date_added') ? date($this->language->get('date_format_short'), strtotime($result['date_added'])) : '',
				'href' 			 => $this->url->link('extension/module/p_review/info', 'p_review_id=' . $result['p_review_id']),
				'readmore'  	 => $readmore,
				'product_image'	 => $product_image,
				'product_href'   => $this->url->link('product/product', 'product_id=' . $result['product_id']),
				'product'        => $result['product']
			);
		}
		
		$url = '';
		
		if (isset($this->request->get['rating'])) {
			$url .= '&rating=' . $this->request->get['rating'];
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		$data['sorts'] = array();

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_date_added_desc'),
			'value' => 'pr.date_added-DESC',
			'href'  => $this->url->link('extension/module/p_review/all', 'sort=pr.date_added&order=DESC' . $url)
		);
		
		$data['sorts'][] = array(
			'text'  => $this->language->get('text_date_added_asc'),
			'value' => 'pr.date_added-ASC',
			'href'  => $this->url->link('extension/module/p_review/all', 'sort=pr.date_added&order=ASC' . $url)
		);
		
		if ($this->config->get('module_p_review_rating')) {
			$data['sorts'][] = array(
				'text'  => $this->language->get('text_rating_desc'),
				'value' => 'pr.rating-DESC',
				'href'  => $this->url->link('extension/module/p_review/all', 'sort=pr.rating&order=DESC' . $url)
			);
			
			$data['sorts'][] = array(
				'text'  => $this->language->get('text_rating_asc'),
				'value' => 'pr.rating-ASC',
				'href'  => $this->url->link('extension/module/p_review/all', 'sort=pr.rating&order=ASC' . $url)
			);
		}
		
		if ($this->config->get('module_p_review_rating')) {
			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
			
			$data['ratings'] = array();
			
			if ($this->config->get('module_p_review_filter_rating')) {
				$data['ratings'][] = array(
					'text'  => $this->language->get('text_select_rating_all'),
					'value' => 'all',
					'href'  => $this->url->link('extension/module/p_review/all', 'rating=all' . $url)
				);
				
				for ($i = 5; $i >= 0; $i--) {
					$data['ratings'][] = array(
						'text'  => $this->language->get('text_select_rating_' . $i),
						'value' => $i,
						'href'  => $this->url->link('extension/module/p_review/all', 'rating=' . $i . $url)
					);
				}
			} else {
				for ($i = 0; $i<=5; $i++) {
					$data['ratings'][] = array(
						'text'  => $this->language->get('text_min_rating_' . $i),
						'value' => $i,
						'href'  => $this->url->link('extension/module/p_review/all', 'rating=' . $i . $url)
					);
				}
			}
		}
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['rating'])) {
			$url .= '&rating=' . $this->request->get['rating'];
		}

		$data['limits'] = array();

		$limits = array();

		$limits = array_unique(explode(',', $this->config->get('module_p_review_limit')));

		sort($limits);

		foreach($limits as $value) {
			$data['limits'][] = array(
				'text'  => $value,
				'value' => $value,
				'href'  => $this->url->link('extension/module/p_review/all', $url . '&limit=' . $value)
			);
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['rating'])) {
			$url .= '&rating=' . $this->request->get['rating'];
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		$pagination = new Pagination();
		$pagination->total = $p_review_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('extension/module/p_review/all', $url . '&page={page}');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($p_review_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($p_review_total - $limit)) ? $p_review_total : ((($page - 1) * $limit) + $limit), $p_review_total, ceil($p_review_total / $limit));

		// http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
		if ($page == 1) {
		    $this->document->addLink($this->url->link('extension/module/p_review/all', '', true), 'canonical');
		} elseif ($page == 2) {
		    $this->document->addLink($this->url->link('extension/module/p_review/all', '', true), 'prev');
		} else {
		    $this->document->addLink($this->url->link('extension/module/p_review/all', 'page='. ($page - 1), true), 'prev');
		}

		if ($limit && ceil($p_review_total / $limit) > $page) {
		    $this->document->addLink($this->url->link('extension/module/p_review/all', 'page='. ($page + 1), true), 'next');
		}

		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['rating'] = $rating;
		$data['limit'] = $limit;

		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');

$data['header'] = $this->load->controller('common/header');
$data['column_megamenu'] = $this->load->controller('common/column_megamenu');

		$this->response->setOutput($this->load->view('extension/module/p_review/all', $data));
	}
	
	public function info() {
		$this->language->load('extension/module/p_review');
		
		$this->load->model('extension/module/p_review');
		
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
		
		$description = $this->config->get('module_p_review_description');
		$language_id = $this->config->get('config_language_id');
		
		if ($description[$language_id]['title']) {
			$heading_title = $description[$language_id]['title'];
		} else {
			$heading_title = $this->language->get('heading_title');
		}
		
		$data['breadcrumbs'][] = array(
	       	'text'      => $heading_title,
			'href'      => $this->url->link('extension/module/p_review/all')
	   	);

		if (isset($this->request->get['p_review_id'])) {
			$p_review_id = (int)$this->request->get['p_review_id'];
		} else {
			$p_review_id = 0;
		}
		
		$p_review_info = $this->model_extension_module_p_review->getReview($p_review_id);
		
		if ($p_review_info) {
			$this->document->addStyle('catalog/view/javascript/p_review/p_review.css');
			
			$this->load->model('tool/image');
			
			if ($this->config->get('module_p_review_image')) {
				$this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
				$this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
			}
			
			$data['entry_name'] = $this->language->get('entry_name');
			$data['entry_city'] = $this->language->get('entry_city');
			$data['entry_rating'] = $this->language->get('entry_rating');
			$data['entry_date_added'] = $this->language->get('entry_date_added');
			$data['entry_text'] = $this->language->get('entry_text');
			$data['entry_bad'] = $this->language->get('entry_bad');
			$data['entry_good'] = $this->language->get('entry_good');
			$data['entry_image'] = $this->language->get('entry_image');
			$data['entry_comment'] = $this->language->get('entry_comment');
			
			$data['button_product'] = $this->language->get('button_product');
			
			if ($p_review_info['product_image']) {
				$data['product_image'] = $this->model_tool_image->resize($p_review_info['product_image'], $this->config->get('module_p_review_product_image_info_width'), $this->config->get('module_p_review_product_image_info_height'));
			} else {
				$data['product_image'] = $this->model_tool_image->resize('placeholder.png', $this->config->get('module_p_review_product_image_info_width'), $this->config->get('module_p_review_product_image_info_height'));
			}
			
			$data['product_href'] = $this->url->link('product/product', 'product_id=' . $p_review_info['product_id']);
			$data['product'] = $p_review_info['product'];

			$data['avatar'] = '';
			
			if ($this->config->get('module_p_review_avatar')) {
				if ($p_review_info['avatar']) {
					$data['avatar'] = $this->model_tool_image->resize($p_review_info['avatar'], $this->config->get('module_p_review_avatar_info_width'), $this->config->get('module_p_review_avatar_info_height'));
				} else {
					$data['avatar'] = $this->model_tool_image->resize('catalog/p_review/avatar/no_avatar.png', $this->config->get('module_p_review_avatar_info_width'), $this->config->get('module_p_review_avatar_info_height'));
				}
			}
			
			$data['image'] = array();
			
			if ($this->config->get('module_p_review_image')) {
				
				if ($p_review_info['image']) {
					
					foreach (explode('|', $p_review_info['image']) as $value) {
						$data['image'][] = array(
							'thumbnail' => $this->model_tool_image->resize($value, $this->config->get('module_p_review_thumbnail_info_width'), $this->config->get('module_p_review_thumbnail_info_height')),
							'thumb'     => $this->model_tool_image->resize($value, $this->config->get('module_p_review_thumb_width'), $this->config->get('module_p_review_thumb_height'))
						);
					}
				}
			}
				
			$data['title'] = $this->config->get('module_p_review_title') ? $p_review_info['title'] : '';
			$data['city'] = $this->config->get('module_p_review_city') ? $p_review_info['city'] : '';
			$data['name'] = $this->config->get('module_p_review_name') ? $p_review_info['name'] : '';
			$data['text'] = $this->config->get('module_p_review_text') ? ($this->config->get('module_p_review_editor') ? $this->model_extension_module_p_review->replaceBBCode($p_review_info['text']) : $p_review_info['text']) : '';
			$data['good'] = $this->config->get('module_p_review_good') ? ($this->config->get('module_p_review_editor') ? $this->model_extension_module_p_review->replaceBBCode($p_review_info['good']) : $p_review_info['good']) : '';
			$data['bad'] = $this->config->get('module_p_review_bad') ? ($this->config->get('module_p_review_editor') ? $this->model_extension_module_p_review->replaceBBCode($p_review_info['bad']) : $p_review_info['bad']) : '';
			$data['comment'] = trim(strip_tags(html_entity_decode($p_review_info['comment'], ENT_QUOTES, 'UTF-8'))) ? html_entity_decode($p_review_info['comment'], ENT_QUOTES, 'UTF-8') : '';
			$data['rating'] = $this->config->get('module_p_review_rating') ? $p_review_info['rating'] : '';
			$data['date_added'] = $this->config->get('module_p_review_date_added') ? date($this->language->get('date_format_short'), strtotime($p_review_info['date_added'])) : '';
			
			if ($data['title']) {
				$title = $data['title'];
			} else {
				$title = $this->language->get('text_title');
			}
			
			$data['breadcrumbs'][] = array(
				'text' => $title,
				'href' => $this->url->link('extension/module/p_review/info', '&p_review_id=' . $this->request->get['p_review_id'])
			);
			
			$this->document->setTitle($title);
			
			$data['text_title'] = $title;
			
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');

$data['header'] = $this->load->controller('common/header');
$data['column_megamenu'] = $this->load->controller('common/column_megamenu');

			$this->response->setOutput($this->load->view('extension/module/p_review/info', $data));
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('p_review/Review/info', '&p_review_id=' . $p_review_id)
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

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}
	
	public function write() {
		//if ($this->config->get('module_p_review_status') && ($this->config->get('module_p_review_guest') || $this->customer->isLogged())) {

			$this->load->language('extension/module/p_review');

			$json = array();
			$data = array();

			if ($this->request->server['REQUEST_METHOD'] == 'POST') {
				
				$data = $this->request->post;
				
				if (isset($this->request->get['product_id'])) {
					$data['product_id'] = (int)$this->request->get['product_id'];
				} else {
					$data['product_id'] = 0;
				}
				
				$store_id = $this->config->get('config_store_id');
				$data['store_name'] = $this->config->get('config_name');

				if ($store_id) {
					$data['store_url'] = $this->config->get('config_url');
				} else {
					if ($this->request->server['HTTPS']) {
						$data['store_url'] = HTTPS_SERVER;
					} else {
						$data['store_url'] = HTTP_SERVER;
					}
				}

				if ($this->config->get('module_p_review_title') == 2) {
					if ((utf8_strlen($this->request->post['title']) < 1) || (utf8_strlen($this->request->post['title']) > 255)) {
						$json['error'] = $this->language->get('error_title');
					}
				}
				
				if ($this->config->get('module_p_review_city') == 2) {
					if ((utf8_strlen($this->request->post['city']) < 1) || (utf8_strlen($this->request->post['city']) > 255)) {
						$json['error'] = $this->language->get('error_city');
					}
				}
				
				if ($this->config->get('module_p_review_name') == 2) {
					if ((utf8_strlen($this->request->post['name']) < 1) || (utf8_strlen($this->request->post['name']) > 255)) {
						$json['error'] = $this->language->get('error_name');
					}
				}
				
				if ($this->config->get('module_p_review_email') == 2) {
					if (utf8_strlen($this->request->post['email']) > 96 || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
						$json['error'] = $this->language->get('error_email');
					}
				}
				
				if ($this->config->get('module_p_review_text') == 2) {
					if (utf8_strlen($this->request->post['text']) < 1) {
						$json['error'] = $this->language->get('error_text');
					}
				}
					
				if ($this->config->get('module_p_review_good') == 2) {
					if (utf8_strlen($this->request->post['good']) < 1) {
						$json['error'] = $this->language->get('error_good');
					}
				}
					
				if ($this->config->get('module_p_review_bad') == 2) {
					if (utf8_strlen($this->request->post['bad']) < 1) {
						$json['error'] = $this->language->get('error_bad');
					}
				}
				
				if ($this->config->get('module_p_review_rating') == 2) {
					if (empty($this->request->post['rating']) || $this->request->post['rating'] < 0 || $this->request->post['rating'] > 5) {
						$json['error'] = $this->language->get('error_rating');
					}
				}
				
				// Captcha
				if ($this->config->get($this->config->get('config_captcha') . '_status') && $this->config->get('module_p_review_captcha')) {
					$captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');

					if ($captcha) {
						$json['error'] = $captcha;
					}
				}
				
				if (($this->config->get('module_p_review_avatar') == 3) && (empty($this->request->files['avatar']['name']) || !is_file($this->request->files['avatar']['tmp_name']))) {	
					$json['error'] = $this->language->get('error_avatar');
				} elseif (($this->config->get('module_p_review_avatar') > 1) && !empty($this->request->files['avatar']['name']) && is_file($this->request->files['avatar']['tmp_name'])) {
					$avatar_error = $this->checkFile('avatar', $this->request->files['avatar']);
					
					if ($avatar_error) {
						$json['error'] = $avatar_error;
					} else {
						$avatar = $this->request->files['avatar'];
					}
				}
				
				if (($this->config->get('module_p_review_image') > 1) && isset($this->request->files['image']) && $this->request->files['image']) {
					
					$image = array();
					
					foreach($this->request->files['image']['name'] as $k => $value) {
						
						if (!empty($this->request->files['image']['name'][$k]) && is_file($this->request->files['image']['tmp_name'][$k])) {
							
							$image[$k] = array(
								'name' 	   => $this->request->files['image']['name'][$k],
								'type' 	   => $this->request->files['image']['type'][$k],
								'tmp_name' => $this->request->files['image']['tmp_name'][$k],
								'error'    => $this->request->files['image']['error'][$k],
								'size'     => $this->request->files['image']['size'][$k],
							);
							
							$image_error = $this->checkFile('image', $image[$k]);
					
							if ($image_error) {
								$json['error'] = $image_error;
								break;
							}
						}
					}
				}
				
				if (!isset($json['error'])) {
					$this->load->model('extension/module/p_review');
					
					if ($this->config->get('module_p_review_moderation')) {
						$data['status'] = 0;
						$success = $this->language->get('text_moderation');
					} else {
						$data['status'] = 1;
						$success = $this->language->get('text_success');
					}
					
					if (isset($avatar) && $avatar) {
						$data['avatar'] = $this->model_extension_module_p_review->uploadFile('avatar', $avatar);
					}
					
					if (isset($image) && $image) {
						foreach ($image as $value) {
							$data['image'][] = $this->model_extension_module_p_review->uploadFile('image', $value);
						}
						
						$data['image'] = implode('|', $data['image']);
					}

					$this->model_extension_module_p_review->addReview($data);
					$json['success'] =  $success;
				}
			}

			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
	//	}
	}
	public function writeRattingReviews() {

			$this->load->language('extension/module/p_review');

			$json = array();
        $json['dislike'] = false;
        $json['like'] = false;
			if ($this->request->server['REQUEST_METHOD'] == 'POST') {
				$p_review_id = (int)$this->request->post['p_review_id'];


                $like_or_dislike = 'like_review';
                $like_or_dislike =  ($this->request->post['like_or_dislike']=='like') ? 'like_review' : 'dislike_review';

                $result = $this->db->query("SELECT * FROM `oc_p_review_like_or_dislike`  WHERE `p_review_id`='".$p_review_id."' AND `like_or_dislike_ip`='".$_SERVER['REMOTE_ADDR']."'");

                if($result->row){
                    if($like_or_dislike=='like_review' and $result->row['like_review']==0 and $result->row['dislike_review']==1){
                        $query = $this->db->query("UPDATE `oc_p_review_like_or_dislike` SET `like_review`=`like_review`+1,`dislike_review`=`dislike_review`-1 WHERE `p_review_id_like_or_dislike`=".$result->row['p_review_id_like_or_dislike']);
                       if($query) $json['like'] = true;
                    }
                    if($like_or_dislike=='dislike_review' and $result->row['like_review']==1 and $result->row['dislike_review']==0){
                        $query = $this->db->query("UPDATE `oc_p_review_like_or_dislike` SET `like_review`=`like_review`-1,`dislike_review`=`dislike_review`+1 WHERE `p_review_id_like_or_dislike`=".$result->row['p_review_id_like_or_dislike']);
                        if($query) $json['dislike'] = true;
                    }
                }else{
                    $query =  $this->db->query("INSERT INTO `oc_p_review_like_or_dislike` SET `".$like_or_dislike."`=`".$like_or_dislike."`+1, `like_or_dislike_ip`= '".$_SERVER['REMOTE_ADDR']."', `p_review_id`=".$p_review_id);
                    if($query){
                        if($like_or_dislike=='like_review') $json['like'] = true;
                        if($like_or_dislike=='dislike_review') $json['dislike'] = true;
                    }
                }
			}


			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));

	}
	
	private function getPosition() {
		$tab = (int)$this->config->get('module_p_review_position_tab');
		$content = (int)$this->config->get('module_p_review_position_content');
		$total = (int)$this->config->get('module_p_review_position_total');
		
		$position = array();
		$position[$tab] = $this->checkThemeFile('tab.twig');
		$position[$total] = $this->checkThemeFile('total.twig');
		$position[$content] = $this->checkThemeFile('content.twig');
		
		return $position;
	}
	
	private function getFilter() {
		$url = '';
		
		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}
		
		if ($this->config->get('module_p_review_rating') && isset($this->request->get['rating'])) {
			$url .= '&rating=' . $this->request->get['rating'];
		}
		
		$data['sorts'] = array();

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_date_added_desc'),
			'href'  => '&sort=pr.date_added&order=DESC' . $url
		);
		
		$data['sorts'][] = array(
			'text'  => $this->language->get('text_date_added_asc'),
			'href'  => '&sort=pr.date_added&order=ASC' . $url
		);
		
		if ($this->config->get('module_p_review_rating')) {
			$data['sorts'][] = array(
				'text'  => $this->language->get('text_rating_desc'),
				'href'  => '&sort=pr.rating&order=DESC' . $url
			);
			
			$data['sorts'][] = array(
				'text'  => $this->language->get('text_rating_asc'),
				'href'  => '&sort=pr.rating&order=ASC' . $url
			);
		}
		
		if ($this->config->get('module_p_review_rating')) {
			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
			
			$data['ratings'] = array();
			
			if ($this->config->get('module_p_review_filter_rating')) {
				$data['ratings'][] = array(
					'text'  => $this->language->get('text_select_rating_all'),
					'href'  => '&rating=all' . $url
				);
				
				for ($i = 5; $i >= 0; $i--) {
					$data['ratings'][] = array(
						'text'  => $this->language->get('text_select_rating_' . $i),
						'href'  => '&rating=' . $i . $url
					);
				}
			} else {
				for ($i = 0; $i<=5; $i++) {
					$data['ratings'][] = array(
						'text'  => $this->language->get('text_min_rating_' . $i),
						'href'  => '&rating=' . $i . $url
					);
				}
			}
		}
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if ($this->config->get('module_p_review_rating') && isset($this->request->get['rating'])) {
			$url .= '&rating=' . $this->request->get['rating'];
		}

		$data['limits'] = array();
		$limits = array();

		$limits = array_unique(explode(',', $this->config->get('module_p_review_limit')));

		sort($limits);

		foreach($limits as $value) {
			$data['limits'][] = array(
				'text'  => $value,
				'value' => $value,
				'href'  => $url . '&limit=' . $value
			);
		}
		
		return $data;
	}
	
	private function getList($product_id, $total) {
		$this->load->model('tool/image');
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pr.date_added';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}
		
		if (isset($this->request->get['rating'])) {
			$rating = $this->request->get['rating'];
		} else {
			if ($this->config->get('module_p_review_filter_rating')) {
				$rating = 'all';
			} else {
				$rating = 0;
			}
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limits = array_unique(explode(',', $this->config->get('module_p_review_limit')));
			sort($limits);
			$limit = $limits[0];
		}
		
		$data['p_reviews'] = array();
		
		$filter_data = array(
		    'sort'  	 	=> $sort,
			'order'      	=> $order,
			'rating' 		=> $rating,
			'filter_rating' => $this->config->get('module_p_review_filter_rating'),
			'start'      	=> ($page - 1) * $limit,
			'limit'      	=> $limit
		);
			
		$results = $this->model_extension_module_p_review->getReviews($product_id, $filter_data);
		
		foreach ($results as $result) {
			$avatar = '';
			
			if ($this->config->get('module_p_review_avatar')) {
				
				if ($result['avatar']) {
					$avatar = $this->model_tool_image->resize($result['avatar'], $this->config->get('module_p_review_avatar_width'), $this->config->get('module_p_review_avatar_height'));
				} else {
					$avatar = $this->model_tool_image->resize('catalog/p_review/avatar/no_avatar.png', $this->config->get('module_p_review_avatar_width'), $this->config->get('module_p_review_avatar_height'));
				}
			}
			
			$image = array();
			
			if ($this->config->get('module_p_review_image')) {
				
				if ($result['image']) {
					
					foreach (explode('|', $result['image']) as $img) {
						$image[] = array(
							'thumbnail' => $this->model_tool_image->resize($img, $this->config->get('module_p_review_thumbnail_width'), $this->config->get('module_p_review_thumbnail_height')),
							'thumb'     => $this->model_tool_image->resize($img, $this->config->get('module_p_review_thumb_width'), $this->config->get('module_p_review_thumb_height'))
						);
					}
				}
			}
			
			$text_limit = $this->config->get('module_p_review_text_limit');
			
			$text = '';
			$readmore = false;
			
			if ($this->config->get('module_p_review_text')) {
				
				if ($this->config->get('module_p_review_editor')) {
					$text = $this->model_extension_module_p_review->replaceBBCode($result['text']);
				} else {
					$text = $result['text'];
				}
			
				if ($this->config->get('module_p_review_cut') && mb_strlen(strip_tags($text), 'UTF-8') > $text_limit) {
					$text = utf8_substr(strip_tags($text), 0, $text_limit) . '...';
					$readmore = true;
				}
			}
			
			$good = '';
			
			if ($this->config->get('module_p_review_good')) {
				
				if ($this->config->get('module_p_review_editor')) {
					$good = $this->model_extension_module_p_review->replaceBBCode($result['good']);
				} else {
					$good = $result['good'];
				}
			
				if ($this->config->get('module_p_review_cut') && mb_strlen(strip_tags($good), 'UTF-8') > $text_limit) {
					$good = utf8_substr(strip_tags($good), 0, $text_limit) . '...';
					$readmore = true;
				}
			}
			
			$bad = '';
			
			if ($this->config->get('module_p_review_bad')) {
				
				if ($this->config->get('module_p_review_editor')) {
					$bad = $this->model_extension_module_p_review->replaceBBCode($result['bad']);
				} else {
					$bad = $result['bad'];
				}
			
				if ($this->config->get('module_p_review_cut') && mb_strlen(strip_tags($bad), 'UTF-8') > $text_limit) {
					$bad = utf8_substr(strip_tags($bad), 0, $text_limit) . '...';
					$readmore = true;
				}
			}
				
			$data['p_reviews'][] = array(
				'p_review_id' 	 => $result['p_review_id'],
				'title'    	 	 => $this->config->get('module_p_review_title') ? $result['title'] : '',
				'city'		     => $this->config->get('module_p_review_city') ? $result['city'] : '',
				'name'		     => $this->config->get('module_p_review_name') ? $result['name'] : '',
				'text'		     => $text,
				'good'		     => $good,
				'bad'		     => $bad,
				'rating'	     => $this->config->get('module_p_review_rating') ? $result['rating'] : '',
				'avatar'	     => $avatar,
				'image'	 	     => $image,
				'comment'	     => trim(strip_tags(html_entity_decode($result['comment'], ENT_QUOTES, 'UTF-8'))) ? html_entity_decode($result['comment'], ENT_QUOTES, 'UTF-8') : '',
				'date_added'     => $this->config->get('module_p_review_date_added') ? date($this->language->get('date_format_short'), strtotime($result['date_added'])) : '',
				'href' 			 => $this->url->link('extension/module/p_review/info', 'p_review_id=' . $result['p_review_id']),
				'readmore'  	 => $readmore
			);
		}
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['rating'])) {
			$url .= '&rating=' . $this->request->get['rating'];
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('extension/module/p_review/p_review', 'product_id=' . $product_id . $url . '&page={page}');

		$data['pagination'] = $pagination->render();
		
		return $data;
	}

	private function getListAjax($product_id, $start , $limit,$sort,$order,$rating) {
		$this->load->model('tool/image');
		$this->load->model('extension/module/p_review');

		$data['p_reviews'] = array();

		$filter_data = array(
		    'sort'  	 	=> $sort,
			'order'      	=> $order,
			'rating' 		=> $rating,
			'filter_rating' => $this->config->get('module_p_review_filter_rating'),
			'start'      	=> $start,
			'limit'      	=> $limit
		);



		$results = $this->model_extension_module_p_review->getReviews($product_id, $filter_data,true);

		foreach ($results as $result) {
			$avatar = '';

			if ($this->config->get('module_p_review_avatar')) {

				if ($result['avatar']) {
					$avatar = $this->model_tool_image->resize($result['avatar'], $this->config->get('module_p_review_avatar_width'), $this->config->get('module_p_review_avatar_height'));
				} else {
					$avatar = $this->model_tool_image->resize('catalog/p_review/avatar/no_avatar.png', $this->config->get('module_p_review_avatar_width'), $this->config->get('module_p_review_avatar_height'));
				}
			}

			$image = array();

			if ($this->config->get('module_p_review_image')) {

				if ($result['image']) {

					foreach (explode('|', $result['image']) as $img) {
						$image[] = array(
							'thumbnail' => $this->model_tool_image->resize($img, $this->config->get('module_p_review_thumbnail_width'), $this->config->get('module_p_review_thumbnail_height')),
							'thumb'     => $this->model_tool_image->resize($img, $this->config->get('module_p_review_thumb_width'), $this->config->get('module_p_review_thumb_height'))
						);
					}
				}
			}

			$text_limit = $this->config->get('module_p_review_text_limit');

			$text = '';
			$readmore = false;

			if ($this->config->get('module_p_review_text')) {

				if ($this->config->get('module_p_review_editor')) {
					$text = $this->model_extension_module_p_review->replaceBBCode($result['text']);
				} else {
					$text = $result['text'];
				}

				if ($this->config->get('module_p_review_cut') && mb_strlen(strip_tags($text), 'UTF-8') > $text_limit) {
					$text = utf8_substr(strip_tags($text), 0, $text_limit) . '...';
					$readmore = true;
				}
			}

			$good = '';

			if ($this->config->get('module_p_review_good')) {

				if ($this->config->get('module_p_review_editor')) {
					$good = $this->model_extension_module_p_review->replaceBBCode($result['good']);
				} else {
					$good = $result['good'];
				}

				if ($this->config->get('module_p_review_cut') && mb_strlen(strip_tags($good), 'UTF-8') > $text_limit) {
					$good = utf8_substr(strip_tags($good), 0, $text_limit) . '...';
					$readmore = true;
				}
			}

			$bad = '';

			if ($this->config->get('module_p_review_bad')) {

				if ($this->config->get('module_p_review_editor')) {
					$bad = $this->model_extension_module_p_review->replaceBBCode($result['bad']);
				} else {
					$bad = $result['bad'];
				}

				if ($this->config->get('module_p_review_cut') && mb_strlen(strip_tags($bad), 'UTF-8') > $text_limit) {
					$bad = utf8_substr(strip_tags($bad), 0, $text_limit) . '...';
					$readmore = true;
				}
			}
            $arr_ru = [
                'январь',
                'февраль',
                'март',
                'апрель',
                'май',
                'июнь',
                'июль',
                'август',
                'сентябрь',
                'октябрь',
                'ноябрь',
                'декабрь'
            ];
            $arr_ua = [
                'січень',
                'лютий',
                'березень',
                'квітень',
                'травень',
                'червень',
                'липень',
                'серпень',
                'вересень',
                'жовтень',
                'листопад',
                'грудень'
            ];
            $lang = $this->language->get('code');
            $arr =  ($lang == 'ru') ? $arr_ru : $arr_ua;
            $month = date('n',strtotime($result['date_added']))-1;
           $date_added_string = date('d',strtotime($result['date_added'])).' '.$arr[$month].' '.date('Y',strtotime($result['date_added']));
// Поскольку от 1 до 12, а в массиве, как мы знаем, отсчет идет от нуля (0 до 11),
// то вычитаем 1 чтоб правильно выбрать уже из нашего массива.

            $result_like = $this->db->query("SELECT * FROM `oc_p_review_like_or_dislike`  WHERE `p_review_id`='".$result['p_review_id']."' AND like_review=1");
            $result_dislike = $this->db->query("SELECT * FROM `oc_p_review_like_or_dislike`  WHERE `p_review_id`='".$result['p_review_id']."' AND dislike_review=1");


			$data['p_reviews'][] = array(
				'p_review_id' 	 => $result['p_review_id'],
				'title'    	 	 => $this->config->get('module_p_review_title') ? $result['title'] : '',
				'city'		     => $this->config->get('module_p_review_city') ? $result['city'] : '',
				'name'		     => $this->config->get('module_p_review_name') ? $result['name'] : '',
				'text'		     => $text,
				'good'		     => $good,
				'bad'		     => $bad,
				'rating'	     => $this->config->get('module_p_review_rating') ? $result['rating'] : '',
				'avatar'	     => $avatar,
				'image'	 	     => $image,
				'like'	 	     => $result_like->num_rows,
				'dislike'	 	     => $result_dislike->num_rows,
				'comment'	     => trim(strip_tags(html_entity_decode($result['comment'], ENT_QUOTES, 'UTF-8'))) ? html_entity_decode($result['comment'], ENT_QUOTES, 'UTF-8') : '',
				'date_added'     => $this->config->get('module_p_review_date_added') ? date($this->language->get('date_format_short'), strtotime($result['date_added'])) : '',
				'date_added_string'     => $date_added_string,
				'href' 			 => $this->url->link('extension/module/p_review/info', 'p_review_id=' . $result['p_review_id']),
				'readmore'  	 => $readmore
			);
		}

		return $data;
	}

	private function getForm() {
		if ($this->config->get('module_p_review_guest') || $this->customer->isLogged()) {
			$data['guest'] = true;
		} else {
			$data['guest'] = false;
		}
		
		if ($this->customer->isLogged()) {
			$data['email'] = $this->customer->getEmail();
			$data['name'] = $this->customer->getFirstname();
		} else {
			$data['email'] = '';
			$data['name'] = '';
		}
		
		if ($this->config->get($this->config->get('config_captcha') . '_status') && $this->config->get('module_p_review_captcha')) {
			$data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'));
		} else {
			$data['captcha'] = '';
		}
		
		$data['wysibb_language'] = substr($this->config->get('config_language'), 0, 2);
		$data['editor'] = $this->config->get('module_p_review_editor');
		$data['date_added'] = $this->config->get('module_p_review_date_added');
		$data['moderation'] = $this->config->get('module_p_review_moderation');
		$data['form'] = $this->config->get('module_p_review_form');
		$data['field_title'] = $this->config->get('module_p_review_title');
		$data['field_city'] = $this->config->get('module_p_review_city');
		$data['field_email'] = $this->config->get('module_p_review_email');
		$data['field_name'] = $this->config->get('module_p_review_name');
		$data['field_text'] = $this->config->get('module_p_review_text');
		$data['field_good'] = $this->config->get('module_p_review_good');
		$data['field_bad'] = $this->config->get('module_p_review_bad');
		$data['field_rating'] = $this->config->get('module_p_review_rating');
		$data['field_avatar'] = $this->config->get('module_p_review_avatar');
		$data['field_image'] = $this->config->get('module_p_review_image');
		$data['image_limit'] = $this->config->get('module_p_review_image_limit');
		$data['max_avatar'] = sprintf($this->language->get('text_max_avatar'), $this->config->get('module_p_review_upload_avatar_width'), $this->config->get('module_p_review_upload_avatar_height'));
		$data['max_image'] = sprintf($this->language->get('text_max_image'), $this->config->get('module_p_review_upload_image_width'), $this->config->get('module_p_review_upload_image_height'));
		$data['smiles'] = $this->model_extension_module_p_review->getSmiles();

		return $data;
	}
	
	private function checkThemeFile($filename) {
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/module/p_review/' . $filename)) {
			return $this->config->get('config_template') . '/template/extension/module/p_review/' . $filename;
		} else {
			return 'default/template/extension/module/p_review/' . $filename;
		}
	}
	
	private function checkFile($folder, $file) {
		$this->load->language('tool/upload');
		$this->load->language('extension/module/p_review');

		$error = array();
		
		// Sanitize the filename
		$filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($file['name'], ENT_QUOTES, 'UTF-8')));

		// Validate the filename length
		if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 64)) {
			$error = $this->language->get('error_filename');
		}

		//model_extension_module_p_reviewowed file extension types
		$allowed = array();

		$allowed = array('png', 'jpe', 'jpeg', 'jpg', 'gif');

		if (!in_array(strtolower(substr(strrchr($filename, '.'), 1)), $allowed)) {
			$error = $this->language->get('error_filetype');
		}

		//model_extension_module_p_reviewowed file mime types
		$allowed = array();
		
		$allowed = array('image/png', 'image/jpeg', 'image/gif');

		if (!in_array($file['type'], $allowed)) {
			$error = $this->language->get('error_filetype');
		}
		
		$allowed = array();
		
		$allowed = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
		
		$imageinfo = getimagesize($file['tmp_name']);
		
		if (!in_array($imageinfo[2], $allowed)) {
			$error = $this->language->get('error_filetype');
		}
		
		if ($folder == 'avatar') {
			$width = $this->config->get('module_p_review_upload_avatar_width');
			$height = $this->config->get('module_p_review_upload_avatar_height');
		} else {
			$width = $this->config->get('module_p_review_upload_image_width');
			$height = $this->config->get('module_p_review_upload_image_height');
		}
		
		if ($imageinfo[0] > (int)$width || $imageinfo[1] > (int)$height) {
			$error = $this->language->get('error_imagesize');
		}

		// Check to see if any PHP files are trying to be uploaded
		$content = file_get_contents($file['tmp_name']);

		if (preg_match('/\<\?php/i', $content)) {
			$error = $this->language->get('error_filetype');
		}

		// Return any upload error
		if ($file['error'] != UPLOAD_ERR_OK) {
			$error = $this->language->get('error_upload_' . $file['error']);
		}

		if ($error) {
			return $filename . ' - ' . $error;
		} else {
			return false;
		}
	}
}