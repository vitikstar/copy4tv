<?php
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerBlogArticleList extends Controller {

    public function __construct($registry)
    {
        parent::__construct($registry);
        require_once(DIR_SYSTEM.'yandexTranslate/Translator.php');
        require_once(DIR_SYSTEM.'yandexTranslate/Exception.php');
    }

    public function perenos_news(){
        $sql = "SELECT * FROM oc_product";
        $result = $this->db->query($sql);
      //  $translator = new \Yandex\Translate\Translator("trnsl.1.1.20200526T195604Z.92e98e7de7630b52.4bef4a876b324772c5b4e7cfe3e9024932d94c87");

        $i=0;

        foreach ($result->rows as $row){
            $sql = "SELECT * FROM oc_product_image WHERE product_id='". $row['product_id'] ."'";
            $result2 = $this->db->query($sql);
            if ($result2->num_rows>1){
                    foreach ($result2->rows as $row2){
                        if(!file_exists(DIR_IMAGE.$row2['image']) OR $row2['image']=='no_image.jpg' OR $row2['image']==''){
                            echo $row2['product_id'];
                            echo"<br>";
                        }
                    }
            }

//            $product_id = $row['product_id'];
//            $sql = "SELECT * FROM oc_seo_url WHERE query LIKE '%product_id=". $product_id ."%' AND language_id='1'";
//            $result2 = $this->db->query($sql);
//            if(!$result2->row){
//                $sql2 = "INSERT INTO `oc_seo_url`  (`language_id`,`query`,`keyword`) VALUES ('1','product_id=". $product_id ."','". $this->translit($row['name']) ."')";
//                //$this->db->query($sql2);
//                echo $sql2."<br>";
//            }
//            echo $sql;
//            echo "<br>";

           // $result2 = $this->db->query($sql);

           // if($result2->rows){
           // $keyword_ua = $this->translit($row['name']);

          //  $sql2 = "INSERT INTO `oc_seo_url`  (`language_id`,`query`,`keyword`) VALUES ('3','product_id=". $product_id ."','". $keyword_ua ."')";
//            $sql2 = "UPDATE  `oc_product_description` SET `description`='". $this->db->escape($description_ua) ."' WHERE language_id=3 AND product_id='". $product_id ."'";
//
//            echo $sql2;
//            echo "<br>";
//            $this->db->query($sql2);
//            continue;
//                $description = $row['description'];
//                if(empty($description)) continue;
//
//
//               $description_ua = $translator->translate($description, 'ru-uk');
//                $sql = "UPDATE  `oc_product_description` SET `description`='". $this->db->escape($description_ua) ."' WHERE language_id=3 AND product_id='". $product_id ."'";
//
//                echo $sql;
//                echo "<br>";
//                $this->db->query($sql);

        }
        exit;
        foreach ($result->rows as $row){
                    $article_id = str_replace("article_id=","",$row['query']);
            $sql = "SELECT * FROM oc_article_description WHERE article_id='". $article_id ."' AND language_id=1";
            $result2 = $this->db->query($sql);
            $name = $result2->row['name'];
            $sql = "SELECT * FROM record_description WHERE name LIKE '%". $name ."%'";
            $result2 = $this->db->query($sql);

            $name_ru = $result2->row['name'];
            $meta_title_ru = $result2->row['meta_title'];
            $meta_h1_ru = $result2->row['meta_h1'];
            $record_id = $result2->row['record_id'];

             $translator = new \Yandex\Translate\Translator("trnsl.1.1.20200526T195128Z.23ffc24c70a3ac16.f35f2b8d4a0949a74493259bc7e1778d26f45a16");
            $name_ua = $translator->translate($name_ru, 'ru-uk');
            $meta_title_ua = $translator->translate($meta_title_ru, 'ru-uk');
            $meta_h1_ua = $translator->translate($meta_h1_ru, 'ru-uk');
            $sql = "UPDATE `oc_article_description` SET `name`='". $this->db->escape($name_ru) ."',`meta_title`='". $this->db->escape($meta_title_ru) ."',`meta_h1`='". $this->db->escape($meta_h1_ru) ."' WHERE article_id='". $article_id ."' AND language_id=1";

            echo $sql;
            echo "<br>";

            $this->db->query($sql);
            $sql = "UPDATE `oc_article_description` SET `name`='". $this->db->escape($name_ua) ."',`meta_title`='". $this->db->escape($meta_title_ua) ."',`meta_h1`='". $this->db->escape($meta_h1_ua) ."' WHERE article_id='". $article_id ."' AND language_id=3";
            echo $sql;
            echo "<br>";
            $this->db->query($sql);

        }
        exit;
        $sql = "SELECT * FROM record as r LEFT JOIN record_description as rd ON(r.record_id=rd.record_id) WHERE rd.language_id=1";
        $result = $this->db->query($sql);
        foreach ($result->rows as $row){
            $query = "record_id=".$row['record_id'];
            $sql = "SELECT * FROM url_alias_blog  WHERE query='". $query ."' AND language_id=1 LIMIT 1";
            $result2 = $this->db->query($sql);
            $keyword_ru = isset($result2->row['keyword']) ? $result2->row['keyword']: '';
            $name_ru = $row['name'];
            $date_added = $row['date_added'];
            $date_available = $row['date_available'];
            $date_modified = $row['date_modified'];
            $description_ru = $row['description'];
            $meta_title_ru = $row['meta_title'];
            $meta_h1_ru = $row['meta_h1'];
            $meta_description_ru = $row['meta_description'];
            $meta_keyword_ru = $row['meta_keyword'];
            $image = $row['image'];

           // $translator = new \Yandex\Translate\Translator("trnsl.1.1.20200526T195128Z.23ffc24c70a3ac16.f35f2b8d4a0949a74493259bc7e1778d26f45a16");
            $translator = new \Yandex\Translate\Translator("trnsl.1.1.20200526T195604Z.92e98e7de7630b52.4bef4a876b324772c5b4e7cfe3e9024932d94c87");
            $name_ua = $translator->translate($name_ru, 'ru-uk');

            $description_ua = $translator->translate($description_ru, 'ru-uk',true);
            $meta_title_ua = $translator->translate($meta_title_ru, 'ru-uk');
            $meta_h1_ua = $translator->translate($meta_h1_ru, 'ru-uk');
            $meta_description_ua = $translator->translate($meta_description_ru, 'ru-uk');
            $meta_keyword_ua = $translator->translate($meta_keyword_ru, 'ru-uk');

            $keyword_ua = $this->translit($name_ua);
            $sql = "INSERT INTO `oc_article`  (`status`,`image`,`date_added`,`date_available`,`date_modified`) VALUES ('1','". $this->db->escape($image) ."','". $date_added ."','". $date_available ."','". $date_modified ."')";

            $this->db->query($sql);

            $article_id = $this->db->getLastId();

            $sql = "INSERT INTO `oc_article_description` (`article_id`,`language_id`,`name`,`description`,`meta_description`,`meta_keyword`,`meta_title`,`meta_h1`) VALUES ('".$article_id."','3','". $this->db->escape($name_ua) ."','". $this->db->escape($description_ua) ."','". $this->db->escape($meta_description_ua) ."','". $this->db->escape($meta_keyword_ua) ."','". $this->db->escape($meta_title_ua) ."','". $this->db->escape($meta_h1_ua) ."')";

             $this->db->query($sql);

            $sql = "INSERT INTO `oc_article_description`  (`article_id`,`language_id`,`name`,`description`,`meta_description`,`meta_keyword`,`meta_title`,`meta_h1`) VALUES ('".$article_id."','1','". $this->db->escape($name_ru) ."','". $this->db->escape($description_ru) ."','". $this->db->escape($meta_description_ru) ."','". $this->db->escape($meta_keyword_ru) ."','". $this->db->escape($meta_title_ru) ."','". $this->db->escape($meta_h1_ru) ."')";
            $this->db->query($sql);

            $sql2 = "INSERT INTO `oc_seo_url`  (`language_id`,`query`,`keyword`) VALUES ('1','article_id=". $article_id ."','". $keyword_ru ."')";
            $this->db->query($sql2);
            $sql2 = "INSERT INTO `oc_seo_url`  (`language_id`,`query`,`keyword`) VALUES ('3','article_id=". $article_id ."','". $keyword_ua ."')";
            $this->db->query($sql2);

        }
    }

    public function translate(){

    }

    public function translit($value)
    {
        $converter = array(
            'а' => 'a',    'б' => 'b',    'в' => 'v',    'г' => 'g',    'д' => 'd',
            'е' => 'e',    'є' => 'e',    'ж' => 'zh',   'з' => 'z',    'и' => 'i',    'і' => 'i',
            'й' => 'y',    'к' => 'k',    'л' => 'l',    'м' => 'm',    'н' => 'n',
            'о' => 'o',    'п' => 'p',    'р' => 'r',    'с' => 's',    'т' => 't',
            'у' => 'u',    'ф' => 'f',    'х' => 'h',    'ц' => 'c',    'ч' => 'ch',
            'ш' => 'sh',   'щ' => 'sch',  'ь' => '',     'ы' => 'y',    'ъ' => '',
            'ю' => 'yu',   'я' => 'ya',
        );

        $value = mb_strtolower($value);
        $value = strtr($value, $converter);
        $value = mb_ereg_replace('[^-0-9a-z]', '-', $value);
        $value = mb_ereg_replace('[-]+', '-', $value);
        $value = trim($value, '-');

        return $value;
    }


	public function index() {
		$this->load->language('blog/article_list');

		$this->load->model('blog/article_list');

		$this->load->model('blog/article');

		$this->load->model('tool/image');


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
        $data['text_catalog'] = $this->language->get('text_catalog');

		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_blog'),
			'href' => $this->url->link('blog/article_list')
		);

        $data['heading_title'] = $this->language->get('text_blog');

		$this->document->setTitle($data['heading_title']);



//			$data['text_refine'] = $this->language->get('text_refine');
//			$data['text_empty'] = $this->language->get('text_empty');
//			$data['text_sort'] = $this->language->get('text_sort');
//			$data['text_limit'] = $this->language->get('text_limit');
//			$data['text_views'] = $this->language->get('text_views');
//
//			$data['button_continue'] = $this->language->get('button_continue');
//			$data['button_list'] = $this->language->get('button_list');
//			$data['button_grid'] = $this->language->get('button_grid');
//			$data['button_more'] = $this->language->get('button_more');


        $article_data = array();


			$data['articles'] = array();

			$article_total = $this->model_blog_article->getTotalArticles($article_data);

			$results = $this->model_blog_article->getArticles($article_data);

        $url = '';

			foreach ($results as $result) {
				if ($result['image']) {
//                    $image = $this->model_tool_image->resize($result['image'], $this->config->get('configblog_image_article_width'), $this->config->get('configblog_image_article_height'));
                    $image = $this->model_tool_image->resize($result['image'], 600, 360);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('configblog_image_article_width'), $this->config->get('configblog_image_article_height'));
				}

//				if ($this->config->get('configblog_review_status')) {
//					$rating = (int)$result['rating'];
//				} else {
//					$rating = false;
//				}

				$data['articles'][] = array(
					'article_id'  => $result['article_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('configblog_article_description_length')) . '..',
					'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'viewed'      => $result['viewed'],
					'rating'      => $result['rating'],
					'href'        => $this->url->link('blog/article', 'article_id=' . $result['article_id'] . $url)
				);
			}



//			$pagination = new Pagination();
//			$pagination->total = $article_total;
//			$pagination->page = $page;
//			$pagination->limit = $limit;
//			$pagination->url = $this->url->link('blog/article_list', 'blog_category_id=' . $this->request->get['blog_category_id'] . $url . '&page={page}');
//
//			$data['pagination'] = $pagination->render();

			//$data['results'] = sprintf($this->language->get('text_pagination'), ($article_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($article_total - $limit)) ? $article_total : ((($page - 1) * $limit) + $limit), $article_total, ceil($article_total / $limit));

			//$data['sort'] = $sort;
			//$data['order'] = $order;
			//$data['limit'] = $limit;

			$data['continue'] = $this->url->link('common/home');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');

$data['header'] = $this->load->controller('common/header');
$data['column_megamenu'] = $this->load->controller('common/column_megamenu');

			$this->response->setOutput($this->load->view('blog/article_list', $data));
	}
}