<?php  
class ControllerExtensionModulePMReview extends Controller {
	public function index($setting) {
		static $module = 0;
		
		$this->language->load('extension/module/pm_review');
		
		$this->load->model('extension/module/p_review');
		$this->load->model('tool/image');

		$language_id = $this->config->get('config_language_id');
		$heading_title = $setting['title_p_review'][$language_id];

		$data['heading_title'] = $heading_title ? $heading_title : $this->language->get('heading_title');

		$data['entry_text'] = $this->language->get('entry_text');
		$data['entry_bad'] = $this->language->get('entry_bad');
		$data['entry_good'] = $this->language->get('entry_good');
		$data['entry_image'] = $this->language->get('entry_image');
		
		$data['button_readmore'] = $this->language->get('button_readmore');
		$data['button_show_all'] = $this->language->get('button_show_all');

		$data['readmore'] = $setting['readmore'];
		$data['show_all'] = $setting['show_all'];
				
		$data['show_all_url'] = $this->url->link('extension/module/p_review/all');
		
		$this->document->addStyle('catalog/view/javascript/p_review/pm_review.css');
		
		$data['image'] = $setting['image'];
		
		if ($setting['image']) {
			$this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
			$this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
		}
		
		if ($setting['carousel']) {
			$data['carousel_item'] = (int)$setting['carousel_item'];
			$this->document->addStyle('catalog/view/javascript/p_review/owl-carousel/owl.carousel.css');
			$this->document->addScript('catalog/view/javascript/p_review/owl-carousel/owl.carousel.min.js');
		}
		
		if ($this->config->get('module_p_review_font_awesome')) {
			$this->document->addStyle('catalog/view/javascript/font-awesome/css/font-awesome.min.css');
		}

		$data['p_reviews'] = array();
		
		$filter_data = array(
			'sort'       	=> $setting['sort'],
			'order'      	=> 'DESC',
			'rating' 		=> $setting['select_rating'],
			'filter_rating' => $setting['filter_rating'],
			'start'      	=> 0,
			'limit' 	 	=> $setting['limit']
		);
		
		$results = $this->model_extension_module_p_review->getReviews('0', $filter_data);

		foreach ($results as $result) {
			
			$product_image = '';
			
			if ($setting['product_image']) {
				if ($result['product_image']) {
					$product_image = $this->model_tool_image->resize($result['product_image'], $setting['product_image_width'], $setting['product_image_height']);
				} else {
					$product_image = $this->model_tool_image->resize('placeholder.png', $setting['product_image_width'], $setting['product_image_height']);
				}
			}
			
			$avatar = '';
			
			if ($setting['avatar']) {
				if ($result['avatar']) {
					$avatar = $this->model_tool_image->resize($result['avatar'], $setting['avatar_width'], $setting['avatar_height']);
				} else {
					$avatar = $this->model_tool_image->resize('catalog/p_review/avatar/no_avatar.png', $setting['avatar_width'], $setting['avatar_height']);
				}
			}
			
			$image = array();
			
			if ($setting['image']) {
				
				if ($result['image']) {
					
					$image_limit = 0;
					
					foreach (explode('|', $result['image']) as $value) {
						if ($image_limit < $setting['image_limit']) {
							$image[] = array(
								'thumbnail' => $this->model_tool_image->resize($value, $setting['thumbnail_width'], $setting['thumbnail_width']),
								'thumb'     => $this->model_tool_image->resize($value, $this->config->get('module_p_review_thumb_width'), $this->config->get('module_p_review_thumb_height'))
							);
						} else {
							break;
						}
						
						$image_limit++;
					}
				}
			}
			
			$text = '';
			
			if ($setting['text']) {
				$text = $this->config->get('module_p_review_editor') ? $this->model_extension_module_p_review->clearBBCode($result['text']) : $result['text'];
			
				if (mb_strlen($text,'UTF-8') > 100) {
					$text = utf8_substr($text, 0, 100) . '...';
				}
			}
			
			$good = '';
			
			if ($setting['good']) {
				$good = $this->config->get('module_p_review_editor') ? $this->model_extension_module_p_review->clearBBCode($result['good']) : $result['good'];
			
				if (mb_strlen($good,'UTF-8') > 100) {
					$good = utf8_substr($good, 0, 100) . '...';
				}
			}
			
			$bad = '';
			
			if ($setting['bad']) {
				$bad = $this->config->get('module_p_review_editor') ? $this->model_extension_module_p_review->clearBBCode($result['bad']) : $result['bad'];
			
				if (mb_strlen($bad,'UTF-8') > 100) {
					$bad = utf8_substr($bad, 0, 100) . '...';
				}
			}

			$data['p_reviews'][] = array(
				'p_review_id' 	 => $result['p_review_id'],
				'title'		 	 => $setting['title'] ? $result['title'] : '',
				'city'		     => $setting['city'] ? $result['city'] : '',
				'name'		 	 => $setting['name_customer'] ? $result['name'] : '',
				'text'		     => $text,
				'good'		     => $good,
				'bad'		     => $bad,
				'rating'	     => $setting['rating'] ? $result['rating'] : '',
				'date_added'     => $setting['date_added'] ? date($this->language->get('date_format_short'), strtotime($result['date_added'])) : '',
				'product_image'	 => $product_image,
				'avatar'	     => $avatar,
				'image'	 	 	 => $image,
				'href'           => $this->url->link('extension/module/p_review/info', 'p_review_id=' . $result['p_review_id']),
				'product'        => $result['product'],
				'product_href'   => $this->url->link('product/product', 'product_id=' . $result['product_id'])
			);
		}
		
		$data['module'] = $module++;
		
		if ($setting['carousel']) {
			$template = 'pm_review_carousel';
		} else {
			$template = 'pm_review';
		}
		
		if ($data['p_reviews']) {
			return $this->load->view('extension/module/pm_review/' . $template, $data);
		}
	}
}