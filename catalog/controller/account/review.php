<?php
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerAccountReview extends Controller {
    public $show_limit_review=5;
    public function index() {


        $this->load->language('account/account');
        $data['text_catalog'] = $this->language->get('heading_title');
        $data['open_content'] = $this->language->get('open_content');
        $data['close_content'] = $this->language->get('close_content');

        $this->load->language('account/review');
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/review', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}


        $this->load->model('account/review');

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
        $customer_id = 0;
        //$customer_id = $this->customer->getId();
        $sorts = $data['sorts'] = array(
            'date_added~DESC'  => $this->language->get('text_option_date_added_desc'),
            'date_added~ASC'  => $this->language->get('text_option_date_added_asc'),
            'rating~DESC'  => $this->language->get('text_option_rating_desc'),
            'date_rating~ASC'  => $this->language->get('text_option_rating_asc'),
        );

        $data['review_total'] = $this->model_account_review->getTotalReviews($customer_id);

        $data['show_limit_review'] = $this->show_limit_review;

        $data['continue'] = $this->url->link('account/account', '', true);

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');

$data['header'] = $this->load->controller('common/header');
$data['column_megamenu'] = $this->load->controller('common/column_megamenu');

        $this->response->setOutput($this->load->view('account/review', $data));
    }

    public function loadReview()
    {

        $this->load->language('account/review');
        $this->load->model('account/review');

        if ($this->request->get['limit']) {
            $limit=  $this->request->get['limit'];
        } else {
            $limit = $this->show_limit_review;
        }
        if ($this->request->get['start']) {
            $start = $start = $this->request->get['start'];
        } else {
            $start = 0;
        }

        $show_item_display = $start;

        $customer_id = $this->customer->getId();

        $reviews = array();

        $result = $this->model_account_review->getReviews($start,$limit,$customer_id); //з групуваням по товару, щоб відгуки не повторювались



        foreach ($result as $item){

            $this->load->model('catalog/product');
            $product_info = $this->model_catalog_product->getProduct($item['product_id']);
            if ($product_info['image']) {
                $image = $this->model_tool_image->resize($product_info['image'], 60, 60);
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', 60, 60);
            }

            $review_id = $item['review_id'];

            $reviews[$review_id]['product_id'] = $product_info['product_id'];
            $reviews[$review_id]['name'] = $product_info['name'];
            $reviews[$review_id]['href'] = $this->url->link('product/product', 'product_id=' . $product_info['product_id']);
            $reviews[$review_id]['image'] = $image;
            $items = $this->model_account_review->getItems($item['product_id'],$customer_id);
            $coments = array();


            foreach ($items as $item) {
                $coments[] = array(
                    'product_id' => $product_info['product_id'],
                    'review_id' => $item['review_id'],
                   // 'author' => $this->customer->getFirstName() . " " . $this->customer->getLastName(),
                    'author' => $this->customer->getFirstName() . " " . $this->customer->getLastName(),
                    'text_review' => $item['text'],
                    'rating' => array_pad(array(),$item['rating'],1),
                    'like_review' => ($item['like_review']) ? $item['like_review'] : 0,
                    'dislike_review' => ($item['dislike_review']) ? $item['dislike_review'] : 0,
                );
            }
            $reviews[$review_id]['coments'] = $coments;

        }

        $data['reviews'] = $reviews;



        $this->response->setOutput($this->load->view('account/load_review', $data));
    }
    public function countShowItemReview(){
        $show_item_display = $this->request->post['show_item_display'];
        $this->load->language('account/review');
        $this->load->model('account/review');
        $customer_id = $this->customer->getId();
        $review_total = $this->model_account_review->getTotalReviews($customer_id);
        $show_limit_review = $this->show_limit_review;
        $merge_count = $review_total - ($show_limit_review+$show_item_display);
        if($show_item_display>=$review_total){
            $count_item_review = 0;
        } else{
            $count_item_review = ($merge_count<0) ? $review_total-$show_item_display : $show_limit_review;
        }

        $json['text_show_limit_review']  = ($count_item_review) ? $this->language->get('text_show_limit_review')." ".$this->config->num_decline($count_item_review , $this->language->get('text_show_limit_review_1')) : 0;

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
