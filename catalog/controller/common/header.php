<?php
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerCommonHeader extends Controller {
	public function index()
    {

//        $data = array(
//            'region_name_en' => 'kiev',
//            'region_name' => 'киев'
//        );
//        $url = 'https://ukrposhta.ua/address-classifier-ws/get_regions_by_region_ua?'.http_build_query($data);
//        echo $url;
//        exit;
//        if( $curl = curl_init() ) {
//            curl_setopt($curl, CURLOPT_URL, urlencode('https://ukrposhta.ua/address-classifier-ws/get_postoffices_by_postindex?pi=01001&pdRegionId=10'));
//            $headr = array();
//            $headr[] = 'Content-Type: application/json';
//            $headr[] = 'Authorization: Bearer 3057712f-107c-3ed0-a3bb-286699972c47';
//            $headr[] = 'Accept: application/json';
//
//            curl_setopt($curl, CURLOPT_HTTPHEADER,$headr);
//            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
//            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
//            curl_setopt($curl, CURLOPT_HEADER, TRUE);
//            $out = curl_exec($curl);
//curl_close($curl);
//            print_r(json_encode($out));
//        }

       // $xml = simplexml_load_string($data);
        //exit;
        //$this->log->write($this->session->data['language']);
        // Analytics
        $this->load->model('setting/extension');

        $data['analytics'] = array();

        $data['route'] = $this->request->get['route'];

        $analytics = $this->model_setting_extension->getExtensions('analytics');

        foreach ($analytics as $analytic) {
            if ($this->config->get('analytics_' . $analytic['code'] . '_status')) {
                $data['analytics'][] = $this->load->controller('extension/analytics/' . $analytic['code'], $this->config->get('analytics_' . $analytic['code'] . '_status'));
            }
        }

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
            $this->document->addLink($server . 'image/' . $this->config->get('config_icon'), 'icon');
        }

        $data['title'] = $this->document->getTitle();

        $data['base'] = $server;
        $data['description'] = $this->document->getDescription();
        $data['keywords'] = $this->document->getKeywords();
        $data['links'] = $this->document->getLinks();
        $data['robots'] = $this->document->getRobots();
        $data['lang'] = $this->language->get('code');
        $data['direction'] = $this->language->get('direction');

        $data['name'] = $this->config->get('config_name');

        if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
            $data['logo'] = $server . 'image/' . $this->config->get('config_logo');
        } else {
            $data['logo'] = '';
        }

        $this->load->language('common/header');

        $data['text_contact_telephones'] = $this->language->get('text_contact_telephones');
        $data['text_consult_for_telephones'] = $this->language->get('text_consult_for_telephones');
        $data['text_operator_kievstar'] = $this->language->get('text_operator_kievstar');
        $data['text_type_telephone'] = $this->language->get('text_type_telephone');
        $data['text_service_centre'] = $this->language->get('text_service_centre');
        $data['text_grafik_call_centre'] = $this->language->get('text_grafik_call_centre');
        $data['text_weekdays'] = $this->language->get('text_weekdays');
        $data['text_hello_user'] = $this->language->get('text_hello_user');
        $data['text_add_photo_menu_user'] = $this->language->get('text_add_photo_menu_user');
        $data['text_data_menu_user'] = $this->language->get('text_data_menu_user');
        $data['text_data_menu_wishlist'] = $this->language->get('text_data_menu_wishlist');
        $data['text_waiting_list'] = $this->language->get('text_waiting_list');
        $data['text_order_menu'] = $this->language->get('text_order_menu');
        $data['text_feedback'] = $this->language->get('text_feedback');
        $data['text_viewed_products'] = $this->language->get('text_viewed_products');
        $data['text_newsletters'] = $this->language->get('text_newsletters');
        $data['text_select_category_compare'] = $this->language->get('text_select_category_compare');

        //up
        $data['logged_first_name'] = $this->customer->getFirstName();
        ////


        $data['banner_top_cookie'] = false;
        if (isset($_COOKIE['banner_top_cookie'])) {
            $data['banner_top_cookie'] = true;
        }

        $data['banner_top'] = array();
        $data['banner_top_is_image'] = false;
        $banner = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_image AS bi LEFT JOIN " . DB_PREFIX . "banner AS b ON (bi.banner_id = b.banner_id) WHERE b.status = '1' AND b.banner_id = '17' AND bi.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY sort_order ASC");
        if ($banner->rows) {
            if ($banner->row['image'] || $banner->row['link']) {
                if ($banner->row['image']) {
                    $data['banner_top_is_image'] = true;
                }
                $data['banner_top'] = array(
                    'image' => '/image/' . $banner->row['image'],
                    'link' => $banner->row['link'],
                    'title' => $banner->row['title'],
//                    'description' => html_entity_decode($banner->row['description'], ENT_QUOTES, 'UTF-8')
                );
            }
        }
        //

        ////
        $config_language_id = (int)$this->config->get('config_language_id');

        $this->load->model('setting/module');
        $module_id = 36; // html модуль телефона и время работы
        $telephone_block = $this->model_setting_module->getModule($module_id);
        $data['telephone_block'] = '';
        if ($telephone_block && $telephone_block['status']) {
            if ($telephone_block['module_description'][$config_language_id]['description']) {
                $data['telephone_block'] = html_entity_decode($telephone_block['module_description'][$config_language_id]['description'], ENT_QUOTES, 'UTF-8');
            }
        }
        ////

        //up


        $this->load->language('extension/module/blog_menu');

        $data['top_menu'][] = array(
            'name'=> $this->language->get('text_about'),
            'href'=> $this->url->link('catalog/information','information_id=4')
        );
        $data['top_menu'][] = array(
            'name'=> $this->language->get('text_news_shop'),
            'href'=> $this->url->link('blog/category','blog_category_id=1')
        );
        $data['top_menu'][] = array(
            'name'=> $this->language->get('text_contact'),
            'href'=> $this->url->link('information/contact', '', true)
        );
        $data['top_menu'][] = array(
            'name'=> $this->language->get('text_delivery_and_payment'),
            'href'=> $this->url->link('catalog/information','information_id=10')
        );
        $data['top_menu'][] = array(
            'name'=> $this->language->get('text_garantie'),
            'href'=> $this->url->link('catalog/information','information_id=11')
        );
        $data['top_menu'][] = array(
            'name'=> $this->language->get('text_opt_pay'),
            'href'=> $this->url->link('catalog/information','information_id=7')
        );
        $data['top_menu'][] = array(
            'name'=> $this->language->get('text_dropshiping'),
            'href'=> $this->url->link('catalog/information','information_id=8')
        );

        $data['top_menu'][] = array(
            'name'=> $this->language->get('text_answer'),
            'href'=> $this->url->link('catalog/information','information_id=15')
        );
            // end up


        $host = isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1')) ? HTTPS_SERVER : HTTP_SERVER;
        if ($this->request->server['REQUEST_URI'] == '/') {
            $data['og_url'] = $this->url->link('common/home');
        } else {
            $data['og_url'] = $host . substr($this->request->server['REQUEST_URI'], 1, (strlen($this->request->server['REQUEST_URI']) - 1));
        }

        $data['og_image'] = $this->document->getOgImage();


        $this->load->model('account/wishlist');
        // Wishlist
//		if ($this->customer->isLogged()) {
//
//
//			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), $this->model_account_wishlist->getTotalWishlist());
//		} else {
//			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
//		}
        $data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), $this->model_account_wishlist->getTotalWishlist());
        $compare_product_count = 0;
        $this->load->model('catalog/category');
        if(isset($this->session->data['compare'])){
            foreach($this->session->data['compare'] as $item) $compare_product_count += count($item);
        }
        $data['text_compare'] = sprintf($this->language->get('text_compare'), $compare_product_count);

		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', true), $this->customer->getFirstName(), $this->url->link('account/logout', '', true));
		
		$data['home'] = $this->url->link('common/home');
		$data['wishlist'] = $this->url->link('account/wishlist', '', true);
		$data['logged'] = $this->customer->isLogged();
		$data['account'] = $this->url->link('account/account', '', true);
		$data['register'] = $this->url->link('account/register', '', true);
		$data['login'] = $this->url->link('account/login', '', true);
		$data['order'] = $this->url->link('account/order', '', true);
		$data['transaction'] = $this->url->link('account/transaction', '', true);
		$data['download'] = $this->url->link('account/download', '', true);
		$data['logout'] = $this->url->link('account/logout', '', true);
		$data['shopping_cart'] = $this->url->link('checkout/cart');
		$data['checkout'] = $this->url->link('checkout/checkout', '', true);
		$data['contact'] = $this->url->link('information/contact');
		$data['telephone'] = $this->config->get('config_telephone');
		
		$data['language'] = $this->load->controller('common/language');

		$data['currency'] = $this->load->controller('common/currency');
		if ($this->config->get('configblog_blog_menu')) {
			$data['blog_menu'] = $this->load->controller('blog/menu');
		} else {
			$data['blog_menu'] = '';
		}
		$data['search'] = $this->load->controller('common/search');
		$data['cart'] = $this->load->controller('common/cart');
		$data['menu'] = $this->load->controller('common/menu');


//		$template = ($this->request->get['route']=='checkout/checkout') ? 'common/checkout_header' : 'common/header';

        $template = ($this->request->get['route']=='checkout/checkout') ? 'common/header' : 'common/header';

		return $this->load->view($template, $data);
	}
}
