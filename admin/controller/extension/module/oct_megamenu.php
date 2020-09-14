<?php
class ControllerExtensionModuleOctMegamenu extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/oct_megamenu');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/oct_megamenu');

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('oct_megamenu', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		$this->model_extension_module_oct_megamenu->makeDB();
		$this->cache->delete('octemplates');
		$this->getList();
	}

	public function add() {
		$this->load->language('extension/module/oct_megamenu');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/oct_megamenu');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_module_oct_megamenu->addMegamenu($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/module/oct_megamenu', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('extension/module/oct_megamenu');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/oct_megamenu');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_module_oct_megamenu->editMegamenu($this->request->get['megamenu_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/module/oct_megamenu', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('extension/module/oct_megamenu');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/oct_megamenu');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $megamenu_id) {
				$this->model_extension_module_oct_megamenu->deleteMegamenu($megamenu_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/module/oct_megamenu', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'ocmm.sort_order';
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

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->document->addScript('view/javascript/oct_megamenu/jquery.minicolors.min.js');
		$this->document->addStyle('view/javascript/oct_megamenu/jquery.minicolors.css');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/oct_megamenu', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['action'] = $this->url->link('extension/module/oct_megamenu', 'user_token=' . $this->session->data['user_token'], true);
		$data['add'] = $this->url->link('extension/module/oct_megamenu/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete'] = $this->url->link('extension/module/oct_megamenu/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		$data['oct_megamenus'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$oct_megamenu_total = $this->model_extension_module_oct_megamenu->getTotalMegamenus();

		$results = $this->model_extension_module_oct_megamenu->getMegamenus($filter_data);

		foreach ($results as $result) {
			$data['oct_megamenus'][] = array(
				'megamenu_id' => $result['megamenu_id'],
				'title'       => $result['title'],
				'link'        => $result['link'],
				'sort_order'  => $result['sort_order'],
				'edit'        => $this->url->link('extension/module/oct_megamenu/edit', 'user_token=' . $this->session->data['user_token'] . '&megamenu_id=' . $result['megamenu_id'] . $url, true)
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_status'] = $this->language->get('entry_status');

		$data['column_title'] = $this->language->get('column_title');
		$data['column_link'] = $this->language->get('column_link');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');

		if (isset($this->request->post['oct_megamenu_data'])) {
			$data['oct_megamenu_data'] = $this->request->post['oct_megamenu_data'];
		} else {
			$data['oct_megamenu_data'] = $this->config->get('oct_megamenu_data');
		}

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

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_title'] = $this->url->link('extension/module/oct_megamenu', 'user_token=' . $this->session->data['user_token'] . '&sort=otmmd.title' . $url, true);
		$data['sort_link'] = $this->url->link('extension/module/oct_megamenu', 'user_token=' . $this->session->data['user_token'] . '&sort=otmm.link' . $url, true);
		$data['sort_sort_order'] = $this->url->link('extension/module/oct_megamenu', 'user_token=' . $this->session->data['user_token'] . '&sort=otmm.sort_order' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $oct_megamenu_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/module/oct_megamenu', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($oct_megamenu_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($oct_megamenu_total - $this->config->get('config_limit_admin'))) ? $oct_megamenu_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $oct_megamenu_total, ceil($oct_megamenu_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('extension/module/oct_megamenu', $data));
	}

	protected function getForm() {
		$this->cache->delete('octemplates');
		$data['heading_title'] = $this->language->get('heading_title');

		//CKEditor
    if ($this->config->get('config_editor_default')) {
        $this->document->addScript('view/javascript/ckeditor/ckeditor.js');
        $this->document->addScript('view/javascript/ckeditor/ckeditor_init.js');
    } else {
        $this->document->addScript('view/javascript/summernote/summernote.js');
        $this->document->addScript('view/javascript/summernote/lang/summernote-' . $this->language->get('lang') . '.js');
        $this->document->addScript('view/javascript/summernote/opencart.js');
        $this->document->addStyle('view/javascript/summernote/summernote.css');
    }
		
		$data['ckeditor'] = $this->config->get('config_editor_default');
		
		$data['text_form'] = !isset($this->request->get['megamenu_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_custom_html'] = $this->language->get('entry_custom_html');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_customer_group_id'] = $this->language->get('entry_customer_group_id');
		$data['entry_link'] = $this->language->get('entry_link');
		$data['entry_info_text'] = $this->language->get('entry_info_text');
		$data['entry_simple_link'] = $this->language->get('entry_simple_link');
		$data['entry_sub_categories'] = $this->language->get('entry_sub_categories');
		$data['entry_open_link_type'] = $this->language->get('entry_open_link_type');
		$data['entry_display_type'] = $this->language->get('entry_display_type');
		$data['entry_show_img'] = $this->language->get('entry_show_img');
		$data['entry_img_width'] = $this->language->get('entry_img_width');
		$data['entry_img_height'] = $this->language->get('entry_img_height');
		$data['entry_limit_item'] = $this->language->get('entry_limit_item');

		$data['text_enter_category'] = $this->language->get('text_enter_category');
		$data['text_enter_manufacturer'] = $this->language->get('text_enter_manufacturer');
		$data['text_enter_information'] = $this->language->get('text_enter_information');
		$data['text_enter_product'] = $this->language->get('text_enter_product');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_type'] = $this->language->get('text_type');
		$data['text_type_1'] = $this->language->get('text_type_1');
		$data['text_type_2'] = $this->language->get('text_type_2');
		$data['text_type_3'] = $this->language->get('text_type_3');
		$data['text_type_4'] = $this->language->get('text_type_4');
		$data['text_type_5'] = $this->language->get('text_type_5');
		$data['text_type_6'] = $this->language->get('text_type_6');
		$data['text_type_7'] = $this->language->get('text_type_7');
		$data['text_display_type_1'] = $this->language->get('text_display_type_1');
		$data['text_display_type_2'] = $this->language->get('text_display_type_2');
		$data['text_display_type_3'] = $this->language->get('text_display_type_3');
		$data['text_display_type_4'] = $this->language->get('text_display_type_4');
		$data['text_display_type_5'] = $this->language->get('text_display_type_5');
		$data['text_select_all']	= $this->language->get('text_select_all');
		$data['text_unselect_all']	= $this->language->get('text_unselect_all');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_language'] = $this->language->get('tab_language');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['link'])) {
			$data['error_link'] = $this->error['link'];
		} else {
			$data['error_link'] = '';
		}

		if (isset($this->error['title'])) {
			$data['error_title'] = $this->error['title'];
		} else {
			$data['error_title'] = array();
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
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
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
        );


		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/oct_megamenu', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		if (isset($this->request->get['megamenu_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('extension/module/oct_megamenu/edit', 'user_token=' . $this->session->data['user_token'] . '&megamenu_id=' . $this->request->get['megamenu_id'] . $url, true)
			);
		}

		if (!isset($this->request->get['megamenu_id'])) {
			$data['action'] = $this->url->link('extension/module/oct_megamenu/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('extension/module/oct_megamenu/edit', 'user_token=' . $this->session->data['user_token'] . '&megamenu_id=' . $this->request->get['megamenu_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('extension/module/oct_megamenu', 'user_token=' . $this->session->data['user_token'] . $url, true);

		if (isset($this->request->get['megamenu_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$oct_megamenu_info = $this->model_extension_module_oct_megamenu->getMegamenu($this->request->get['megamenu_id']);
		}

		$data['user_token'] = $this->session->data['user_token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['oct_megamenu_description'])) {
			$data['oct_megamenu_description'] = $this->request->post['oct_megamenu_description'];
		} elseif (isset($this->request->get['megamenu_id'])) {
			$data['oct_megamenu_description'] = $this->model_extension_module_oct_megamenu->getMegamenuDescriptions($this->request->get['megamenu_id']);
		} else {
			$data['oct_megamenu_description'] = array();
		}

		$this->load->model('setting/store');

		$data['stores'] = $this->model_setting_store->getStores();

		if (isset($this->request->post['store'])) {
			$data['store'] = $this->request->post['store'];
		} elseif (isset($this->request->get['megamenu_id'])) {
			$data['store'] = $this->model_extension_module_oct_megamenu->getMegamenuStores($this->request->get['megamenu_id']);
		} else {
			$data['store'] = array(0);
		}

		$this->load->model('customer/customer_group');

		$data['customer_groups'] = array();

    foreach ($this->model_customer_customer_group->getCustomerGroups() as $customer_group) {
      $data['customer_groups'][] = array(
        'customer_group_id' => $customer_group['customer_group_id'],
        'name'              => $customer_group['name']
      );
    }

    if (isset($this->request->post['customer_group'])) {
			$data['menu_customer_groups'] = $this->request->post['customer_group'];
		} elseif (isset($this->request->get['megamenu_id'])) {
			$data['menu_customer_groups'] = $this->model_extension_module_oct_megamenu->getMegamenuCustomerGroups($this->request->get['megamenu_id']);
		} else {
			$data['menu_customer_groups'] = array(0);
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($oct_megamenu_info)) {
			$data['status'] = $oct_megamenu_info['status'];
		} else {
			$data['status'] = true;
		}

		if (isset($this->request->post['info_text'])) {
			$data['info_text'] = $this->request->post['info_text'];
		} elseif (!empty($oct_megamenu_info)) {
			$data['info_text'] = $oct_megamenu_info['info_text'];
		} else {
			$data['info_text'] = false;
		}

		if (isset($this->request->post['sub_categories'])) {
			$data['sub_categories'] = $this->request->post['sub_categories'];
		} elseif (!empty($oct_megamenu_info)) {
			$data['sub_categories'] = $oct_megamenu_info['sub_categories'];
		} else {
			$data['sub_categories'] = false;
		}

		if (isset($this->request->post['open_link_type'])) {
			$data['open_link_type'] = $this->request->post['open_link_type'];
		} elseif (!empty($oct_megamenu_info)) {
			$data['open_link_type'] = $oct_megamenu_info['open_link_type'];
		} else {
			$data['open_link_type'] = false;
		}

		if (isset($this->request->post['show_img'])) {
			$data['show_img'] = $this->request->post['show_img'];
		} elseif (!empty($oct_megamenu_info)) {
			$data['show_img'] = $oct_megamenu_info['show_img'];
		} else {
			$data['show_img'] = true;
		}

		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($oct_megamenu_info)) {
			$data['image'] = $oct_megamenu_info['image'];
		} else {
			$data['image'] = '';
		}

		if (isset($this->request->post['item_type'])) {
			$data['item_type'] = $this->request->post['item_type'];
		} elseif (!empty($oct_megamenu_info)) {
			$data['item_type'] = $oct_megamenu_info['item_type'];
		} else {
			$data['item_type'] = 0;
		}

		if (isset($this->request->post['display_type'])) {
			$data['display_type'] = $this->request->post['display_type'];
		} elseif (!empty($oct_megamenu_info)) {
			$data['display_type'] = $oct_megamenu_info['display_type'];
		} else {
			$data['display_type'] = '';
		}

		if (isset($this->request->post['img_width'])) {
			$data['img_width'] = $this->request->post['img_width'];
		} elseif (!empty($oct_megamenu_info)) {
			$data['img_width'] = $oct_megamenu_info['img_width'];
		} else {
			$data['img_width'] = 100;
		}

		if (isset($this->request->post['img_height'])) {
			$data['img_height'] = $this->request->post['img_height'];
		} elseif (!empty($oct_megamenu_info)) {
			$data['img_height'] = $oct_megamenu_info['img_height'];
		} else {
			$data['img_height'] = 100;
		}

		if (isset($this->request->post['limit_item'])) {
			$data['limit_item'] = $this->request->post['limit_item'];
		} elseif (!empty($oct_megamenu_info)) {
			$data['limit_item'] = $oct_megamenu_info['limit_item'];
		} else {
			$data['limit_item'] = 5;
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($oct_megamenu_info) && is_file(DIR_IMAGE . $oct_megamenu_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($oct_megamenu_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($oct_megamenu_info)) {
			$data['sort_order'] = $oct_megamenu_info['sort_order'];
		} else {
			$data['sort_order'] = '';
		}

		$this->load->model('catalog/category');
		$this->load->model('catalog/manufacturer');
		$this->load->model('catalog/product');

    $data['products'] = array();

    if (isset($this->request->post['oct_megamenu_products'])) {
      $products = $this->request->post['oct_megamenu_products'];
    } elseif (isset($this->request->get['megamenu_id'])) {
			$products = $this->model_extension_module_oct_megamenu->getMegamenuProduct($this->request->get['megamenu_id']);
    } else {
      $products = array();
    }

    if ($products) {
      foreach ($products as $product_id) {
        $product_info = $this->model_catalog_product->getProduct($product_id);

        if ($product_info) {
          $data['products'][] = array(
            'product_id' => $product_info['product_id'],
            'name'       => $product_info['name']
          );
        }
      }
    }

    $data['categories'] = $this->model_extension_module_oct_megamenu->getCategories(array('sort' => 'name', 'order' => 'ASC'));

		if (isset($this->request->post['oct_megamenu_categories'])) {
			$data['category_id'] = $this->request->post['oct_megamenu_categories'];
		} elseif (isset($this->request->get['megamenu_id'])) {
			$data['category_id'] = $this->model_extension_module_oct_megamenu->getMegamenuCategory($this->request->get['megamenu_id']);
		} else {
			$data['category_id'] = array();
		}

    $data['manufacturers'] = $this->model_extension_module_oct_megamenu->getManufacturers(array('sort' => 'name', 'order' => 'ASC'));

		if (isset($this->request->post['oct_megamenu_manufacturers'])) {
			$data['manufacturer_id'] = $this->request->post['oct_megamenu_manufacturers'];
		} elseif (isset($this->request->get['megamenu_id'])) {
			$data['manufacturer_id'] = $this->model_extension_module_oct_megamenu->getMegamenuManufacturer($this->request->get['megamenu_id']);
		} else {
			$data['manufacturer_id'] = array();
		}

		$data['informations'] = $this->model_extension_module_oct_megamenu->getInformations(array('sort' => 'id.title', 'order' => 'ASC'));

		if (isset($this->request->post['oct_megamenu_informations'])) {
			$data['information_id'] = $this->request->post['oct_megamenu_informations'];
		} elseif (isset($this->request->get['megamenu_id'])) {
			$data['information_id'] = $this->model_extension_module_oct_megamenu->getMegamenuInformation($this->request->get['megamenu_id']);
		} else {
			$data['information_id'] = array();
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/oct_megamenu_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'extension/module/oct_megamenu')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['oct_megamenu_description'] as $language_id => $value) {
			if ((utf8_strlen($value['title']) < 3) || (utf8_strlen($value['title']) > 64)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}
			if (empty($value['link'])) {
				$this->error['link'][$language_id] = $this->language->get('error_link');
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	public function uninstall() {
    $this->load->model('extension/extension');
    $this->load->model('setting/setting');
    $this->load->model('extension/module/oct_megamenu');

    $this->model_extension_module_oct_megamenu->removeDB();
    $this->model_extension_extension->uninstall('module', 'oct_megamenu');
    $this->model_setting_setting->deleteSetting('oct_megamenu_data');
  }

  
	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'extension/module/oct_megamenu')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/oct_megamenu')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function install() {   
		$this->load->language('extension/module/oct_megamenu');

    $this->load->model('extension/extension');
    $this->load->model('setting/setting');
    $this->load->model('user/user_group');

    $this->model_user_user_group->addPermission($this->user->getId(), 'access', 'extension/module/oct_megamenu');
    $this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'extension/module/oct_megamenu');

    $this->model_setting_setting->editSetting('oct_megamenu', array(
        'oct_megamenu_data' => array(
          'status'  => '1'
        )
      )
		);        

    if (!in_array('oct_megamenu', $this->model_extension_extension->getInstalled('module'))) {
        $this->model_extension_extension->install('module', 'oct_megamenu');
    }
    
    $this->session->data['success'] = $this->language->get('text_success_install');
  }
}