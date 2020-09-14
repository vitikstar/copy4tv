<?php
class ControllerExtensionModuleBanner extends Controller {
	public function index($setting) {
	    if(!$setting) return;
		static $module = 0;

		$this->load->model('design/banner');
		$this->load->model('tool/image');

		$this->document->addStyle('catalog/view/javascript/jquery/swiper/css/swiper.min.css');
		$this->document->addStyle('catalog/view/javascript/jquery/swiper/css/opencart.css');
		$this->document->addScript('catalog/view/javascript/jquery/swiper/js/swiper.jquery.js');

		$data['banners'] = array();

		$results = $this->model_design_banner->getBanner($setting['banner_id']);


		$data['banners_top'] = array();
		if ($setting['banner_id'] == 16) {
            $data['banners_top']['slider'] = array();
            $data['banners_top']['banner_1'] = array();
            $data['banners_top']['banner_2'] = array();
            $count = count($results);
            $i = 0;

            foreach ($results as $result) {
                $i++;
                if (is_file(DIR_IMAGE . $result['image'])) {
                    $title = $result['title'];
                    $link = $result['link'];
                    $image = '/image/'.$result['image'];
                    if ($i <= $count - 2) {
                        $data['banners_top']['slider'][] = array(
                            'title' => $title,
                            'link' => $link,
                            'image' => $image
                        );
                    } elseif ($i == $count - 1) {
                        $data['banners_top']['banner_1'] = array(
                            'title' => $title,
                            'link' => $link,
                            'image' => $image
                        );
                    } elseif ($i == $count) {
                        $data['banners_top']['banner_2'] = array(
                            'title' => $title,
                            'link' => $link,
                            'image' => $image
                        );
                    }
                }
            }
        }



        if ($setting['banner_id'] != 16) {
            foreach ($results as $result) {
                if (is_file(DIR_IMAGE . $result['image'])) {
                    $data['banners'][] = array(
                        'title' => $result['title'],
                        'link' => $result['link'],
                        'image' => $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height'])
                    );
                }
            }
        }

		$data['module'] = $module++;

		return $this->load->view('extension/module/banner', $data);
	}
}