<?php
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerBlogArticle extends Controller {
	private $error = array(); 
	
	public function index() { 
		$this->load->language('blog/article');
	
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


		
		$this->load->model('blog/category');	
		
		
		if (isset($this->request->get['blog_category_id'])) {
			$blog_category_id = '';
				
			foreach (explode('_', $this->request->get['blog_category_id']) as $path_id) {
				if (!$blog_category_id) {
					$blog_category_id = $path_id;
				} else {
					$blog_category_id .= '_' . $path_id;
				}
				
				$category_info = $this->model_blog_category->getCategory($path_id);
				
				if ($category_info) {
					$data['breadcrumbs'][] = array(
						'text'      => $category_info['name'],
						'href'      => $this->url->link('blog/category', 'blog_category_id=' . $blog_category_id)
					);
				}
			}
		}


        $sorts = $data['sorts'] = array(
            'date_added~DESC'  => $this->language->get('text_option_date_added_desc'),
            'date_added~ASC'  => $this->language->get('text_option_date_added_asc'),
            'rating~DESC'  => $this->language->get('text_option_rating_desc'),
            'date_rating~ASC'  => $this->language->get('text_option_rating_asc'),
        );



	if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_tag'])) {
			$url = '';
			
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
						
			if (isset($this->request->get['filter_tag'])) {
				$url .= '&filter_tag=' . $this->request->get['filter_tag'];
			}
						
			if (isset($this->request->get['filter_description'])) {
				$url .= '&filter_description=' . $this->request->get['filter_description'];
			}
			
			if (isset($this->request->get['filter_news_id'])) {
				$url .= '&filter_news_id=' . $this->request->get['filter_news_id'];
			}	
						
		}
		
		if (isset($this->request->get['article_id'])) {
			$article_id = (int)$this->request->get['article_id'];
		} else {
			$article_id = 0;
		}

		$data['article_id'] = $article_id;

		$this->load->model('blog/article');
		$this->load->model('blog/review');

		$article_info = $this->model_blog_article->getArticle($article_id);
		
		if ($article_info) {
			$url = '';
			
			if (isset($this->request->get['blog_category_id'])) {
				$url .= '&blog_category_id=' . $this->request->get['blog_category_id'];
			}	

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
						
			if (isset($this->request->get['filter_tag'])) {
				$url .= '&filter_tag=' . $this->request->get['filter_tag'];
			}
			
			if (isset($this->request->get['filter_description'])) {
				$url .= '&filter_description=' . $this->request->get['filter_description'];
			}	
						
			if (isset($this->request->get['filter_news_id'])) {
				$url .= '&filter_news_id=' . $this->request->get['filter_news_id'];
			}
			
			$data['breadcrumbs'][] = array(
				'text' => $article_info['name'],
				'href' => $this->url->link('blog/article', 'article_id=' . $this->request->get['article_id'])
			);
			
			if ($article_info['meta_title']) {
				$this->document->setTitle($article_info['meta_title']);
			} else {
				$this->document->setTitle($article_info['name']);
			}
			
			if ($article_info['noindex'] <= 0) {
				$this->document->setRobots('noindex,follow');
			}

			$this->document->setDescription($article_info['meta_description']);
			$this->document->setKeywords($article_info['meta_keyword']);
			$this->document->addLink($this->url->link('blog/article', 'article_id=' . $this->request->get['article_id']), 'canonical');
			$this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
			$this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
			$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment.min.js');
			$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
			$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

			if ($article_info['meta_h1']) {	
				$data['heading_title'] = $article_info['meta_h1'];
				} else {
				$data['heading_title'] = $article_info['name'];
				}
			
			$data['text_select'] = $this->language->get('text_select');
			$data['text_write'] = $this->language->get('text_write');
			$data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', true), $this->url->link('account/register', '', true));
			$data['text_loading'] = $this->language->get('text_loading');
			$data['text_note'] = $this->language->get('text_note');
			$data['text_share'] = $this->language->get('text_share');
			$data['text_wait'] = $this->language->get('text_wait');
			$data['button_cart'] = $this->language->get('button_cart');
			$data['button_wishlist'] = $this->language->get('button_wishlist');
			$data['button_compare'] = $this->language->get('button_compare');
			$data['entry_name'] = $this->language->get('entry_name');
			$data['entry_review'] = $this->language->get('entry_review');
			$data['entry_rating'] = $this->language->get('entry_rating');
			$data['entry_good'] = $this->language->get('entry_good');
			$data['entry_bad'] = $this->language->get('entry_bad');
			$data['entry_captcha'] = $this->language->get('entry_captcha');
			
			$data['button_continue'] = $this->language->get('button_continue');
			$data['text_comment'] = $this->language->get('text_comment');
			$data['text_comment_write'] = $this->language->get('text_comment_write');
			$data['text_model'] = $this->language->get('text_model');
			$data['text_view_all'] = $this->language->get('text_view_all');

			$this->load->model('blog/review');

			$data['text_related'] = $this->language->get('text_related');
			$data['text_related_product'] = $this->language->get('text_related_product');
			
			$data['article_id'] = $this->request->get['article_id'];
			
			$data['review_status'] = $this->config->get('configblog_review_status');
			
			if ($this->config->get('configblog_review_guest') || $this->customer->isLogged()) {
				$data['review_guest'] = true;
			} else {
				$data['review_guest'] = false;
			}

			if ($this->customer->isLogged()) {
				$data['customer_name'] = $this->customer->getFirstName() . '&nbsp;' . $this->customer->getLastName();
			} else {
				$data['customer_name'] = '';
			}
			
			// Captcha
			if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
				$data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'));
			} else {
				$data['captcha'] = '';
			}
            
			$data['article_review'] = $this->model_blog_review->getTotalReviewsByArticleId($article_id);
			$data['text_latest_block_goods'] = $this->language->get('text_latest_block_goods');
			$data['viewed'] = $article_info['viewed'];
            $data['date_added'] = date($this->language->get('date_format_short'), strtotime($article_info['date_added']));
			//$data['reviews'] = sprintf($this->language->get('text_reviews'), (int)$article_info['reviews']);
			$data['rating'] = $article_info['rating'];
			$data['gstatus'] = (int)$article_info['gstatus'];
			$data['description'] = html_entity_decode($article_info['description'], ENT_QUOTES, 'UTF-8');


			$data['articles'] = array();
			
			$data['button_more'] = $this->language->get('button_more');
			$data['text_views'] = $this->language->get('text_views');
			
			$this->load->model('tool/image');
			
			$results = $this->model_blog_article->getArticleRelated($this->request->get['article_id']);
			
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('configblog_image_related_width'), $this->config->get('configblog_image_related_height'));
				} else {
					$image = false;
				}
				
				if ($this->config->get('configblog_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}
							
				$data['articles'][] = array(
					'article_id' => $result['article_id'],
					'thumb'   	 => $image,
					'name'    	 => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('configblog_article_description_length')) . '..',
					'rating'     => $rating,
					'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'viewed'      => $result['viewed'],
					'reviews'    => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
					'href'    	 => $this->url->link('blog/article', 'article_id=' . $result['article_id']),
				);
			}

			$this->load->model('tool/image');
			$data['products'] = array();
			
			$results = $this->model_blog_article->getArticleRelatedProduct($this->request->get['article_id']);
			
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('configblog_image_related_width'), $this->config->get('configblog_image_related_height'));
				} else {
					$image = false;
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
				
				if ($this->config->get('configblog_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}
				
				$data['text_tax'] = $this->language->get('text_tax');
							
				$data['products'][] = array(
					'product_id' => $result['product_id'],
					'thumb'   	 => $image,
					'name'    	 => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('configblog_article_description_length')) . '..',
					'price'   	 => $price,
					'special' 	 => $special,
					'rating'     => $rating,
					'tax'        => $tax,
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'reviews'    => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
					'href'    	 => $this->url->link('product/product', 'product_id=' . $result['product_id']),
				);
			}	
			
			$data['download_status'] = $this->config->get('configblog_article_download');
			
			$data['downloads'] = array();
			
			$results = $this->model_blog_article->getDownloads($this->request->get['article_id']);
 
            foreach ($results as $result) {
                if (file_exists(DIR_DOWNLOAD . $result['filename'])) {
                    $size = filesize(DIR_DOWNLOAD . $result['filename']);
 
                    $i = 0;
 
                    $suffix = array(
                        'B',
                        'KB',
                        'MB',
                        'GB',
                        'TB',
                        'PB',
                        'EB',
                        'ZB',
                        'YB'
                    );
 
                    while (($size / 10024) > 1) {
                        $size = $size / 10024;
                        $i++;
                    }
 
                    $data['downloads'][] = array(
                        'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                        'name'       => $result['name'],
                        'size'       => round(substr($size, 0, strpos($size, '.') + 4), 2) . $suffix[$i],
                        'href'       => $this->url->link('blog/article/download', '&article_id='. $this->request->get['article_id']. '&download_id=' . $result['download_id'])
                    );
                }
            } 

            $data['limit_reviewed_list'] = LIMIT_REVIEW_LIST;

			$this->model_blog_article->updateViewed($this->request->get['article_id']);

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');

$data['header'] = $this->load->controller('common/header');
$data['column_megamenu'] = $this->load->controller('common/column_megamenu');
			
			$this->response->setOutput($this->load->view('blog/article_new', $data));
		} else {
			$url = '';
			
			if (isset($this->request->get['blog_category_id'])) {
				$url .= '&blog_category_id=' . $this->request->get['blog_category_id'];
			}		

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}	
					
			if (isset($this->request->get['filter_tag'])) {
				$url .= '&filter_tag=' . $this->request->get['filter_tag'];
			}
							
			if (isset($this->request->get['filter_description'])) {
				$url .= '&filter_description=' . $this->request->get['filter_description'];
			}
					
			if (isset($this->request->get['filter_news_id'])) {
				$url .= '&filter_news_id=' . $this->request->get['filter_news_id'];
			}
								
				$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('product/product', $url . '&product_id=' . $article_id)
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

  	public function writeRattingReviews(){
	    
    }

	public function download() {

		$this->load->model('blog/article');

		if (isset($this->request->get['download_id'])) {
			$download_id = $this->request->get['download_id'];
		} else {
			$download_id = 0;
		}

		if (isset($this->request->get['article_id'])) {
			$article_id = $this->request->get['article_id'];
		} else {
			$article_id = 0;
		}

		$download_info = $this->model_blog_article->getDownload($article_id, $download_id);
		
		

		if ($download_info) {
			$file = DIR_DOWNLOAD . $download_info['filename'];
			$mask = basename($download_info['mask']);

			if (!headers_sent()) {
				if (file_exists($file)) {
					header('Content-Description: File Transfer');
					header('Content-Type: application/octet-stream');
					header('Content-Disposition: attachment; filename="' . ($mask ? $mask : basename($file)) . '"');
					header('Content-Transfer-Encoding: binary');
					header('Expires: 0');
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header('Pragma: public');
					header('Content-Length: ' . filesize($file));

					readfile($file, 'rb');

					

					exit;
				} else {
					exit('Error: Could not find file ' . $file . '!');
				}
			} else {
				exit('Error: Headers already sent out!');
			}
		} else {
			$this->redirect(HTTP_SERVER . 'index.php?route=account/download');
		}
	}
	
	public function review() {
    	$this->language->load('blog/article');
		$this->load->model('blog/review');

		if (isset($this->request->post['article_id'])) {
			$article_id = $this->request->post['article_id'];
		} else {
            $article_id = 0;
		}

		if (isset($this->request->post['start'])) {
			$start = $this->request->post['start'];
		} else {
			$start = 0;
		}
		if (isset($this->request->post['limit'])) {
			$limit = $this->request->post['limit'];
		} else {
			$limit = LIMIT_REVIEW_LIST;
		}
		if (isset($this->request->post['sort_order'])) {
			list($order,$by) = explode('~',$this->request->post['sort_order']);
		} else {
            $order='date_added';
            $by='DESC';
		}

		$data['reviews'] = array();
		
		$review_total = $this->model_blog_review->getTotalReviewsByArticleId($article_id);
			
		$results = $this->model_blog_review->getReviewsByArticleId($article_id, $start, $limit, $order, $by);
      		
		foreach ($results as $result) {
        	$data['reviews'][] = array(
        		'author'     => $result['author'],
				'text'       => $result['text'],
				'rating'     => (int)$result['rating'],
        		'reviews'    => (int)$review_total,
        		'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
        	);
      	}

		$json['html'] = $this->load->view('blog/review', $data);
		$json['total_reviews'] = $review_total;
		$json['text_show'] = $this->language->get('text_show_limit');

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
	}
	
	public function write() {
		$this->load->language('blog/article');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'GET') {
			if ((utf8_strlen($this->request->get['name']) < 3) || (utf8_strlen($this->request->get['name']) > 25)) {
				$json['error']['name'] = $this->language->get('error_name');
			}

			if ((utf8_strlen($this->request->get['text']) < 25) || (utf8_strlen($this->request->get['text']) > 1000)) {
				$json['error']['text'] = $this->language->get('error_text');
			}

            if ((utf8_strlen($this->request->get['email']) > 96) || !filter_var($this->request->get['email'], FILTER_VALIDATE_EMAIL)) {
                $json['error']['email'] = $this->language->get('error_email');
            }

			// Captcha
			if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
				$captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');

				if ($captcha) {
					$json['error']['captcha'] = $captcha;
				}
			}

			if (!isset($json['error'])) {
				$this->load->model('blog/review');

				$this->model_blog_review->addReview($this->request->get['article_id'], $this->request->get);



				$json['success'] = $this->language->get('text_success');
				$json['text_success_p'] = $this->language->get('text_success_p');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
}
?>