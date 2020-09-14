<?php
/* All rights reserved belong to the module, the module developers http://opencartadmin.com */
// https://opencartadmin.com © 2011-2019 All Rights Reserved
// Distribution, without the author's consent is prohibited
// Commercial license
class ControllerAgooHtmlHtml extends Controller
{
	private $error = array();
	protected  $data;

	public function index($data) {
		$this->data = $data;
		$this->config->set("blog_work", true);

		if (SC_VERSION > 23) {
			$this->data['html_template'] = 'agoo/html/html';
		} else {
			$this->data['html_template'] = 'agoo/html/html.tpl';
		}

       	$this->language->load('agoo/html/html');
        $this->data['categories'] = array();
        $this->load->model('catalog/category');
        $this->load->model('catalog/blog');
	    if ($this->table_exists(DB_PREFIX . "blog")) {
			$r = $this->db->query("DESCRIBE  `" . DB_PREFIX . "blog` `design`");
			if ($r->num_rows == 1) {
	              	$this->data['categories'] = $this->model_catalog_blog->getCategories(0);
			}
		}

		$this->data['cat'] = $this->model_catalog_category->getCategories(0);

		$this->load->model('catalog/manufacturer');
		$this->data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();


		if (isset($this->data['id'])) {
     		$this->load->model('catalog/product');
			$this->data['products'] = array();
			if (isset($this->data['ascp_widgets'][$this->data['id']]['products']) && !empty($this->data['ascp_widgets'][$this->data['id']]['products'])) {
				foreach ($this->data['ascp_widgets'][$this->data['id']]['products'] as $product_id) {
					$this->data['products'][] = $this->model_catalog_product->getProduct($product_id);
				}
			}
		}

        if (!isset($this->data['id'])) {
         $this->data['id'] = false;
        }
		if (isset($this->data['id']) && !isset($this->data['ascp_widgets'][$this->data['id']]['anchor'])) {
			$this->data['ascp_widgets'][$this->data['id']]['anchor'] = '';
		}

		if (isset($this->data['id']) && !isset($this->data['ascp_widgets'][$this->data['id']]['modal_a_class'])) {
			$this->data['ascp_widgets'][$this->data['id']]['modal_a_class'] = 'button_html_modal button btn btn-primary';
		}

		if (isset($this->data['id']) && !isset($this->data['ascp_widgets'][$this->data['id']]['modal_cbmobile_width_browser'])) {
			$this->data['ascp_widgets'][$this->data['id']]['modal_cbmobile_width_browser'] = '800';
		}

		if (isset($this->data['ascp_widgets'][$this->data['id']]['modal_cbmobile_width_browser'])) {
        	$this->data['ascp_widgets'][$this->data['id']]['modal_cbmobile_width_browser'] = str_replace('px' , '', $this->data['ascp_widgets'][$this->data['id']]['modal_cbmobile_width_browser']);
        	$this->data['ascp_widgets'][$this->data['id']]['modal_cbmobile_width_browser'] = str_replace('%' , '', $this->data['ascp_widgets'][$this->data['id']]['modal_cbmobile_width_browser']);
        }

		if (isset($this->data['id']) && !isset($this->data['ascp_widgets'][$this->data['id']]['modal_cb_width'])) {
			$this->data['ascp_widgets'][$this->data['id']]['modal_cb_width'] = '50%';
		}

		if (isset($this->data['id']) && !isset($this->data['ascp_widgets'][$this->data['id']]['modal_cb_height'])) {
			$this->data['ascp_widgets'][$this->data['id']]['modal_cb_height'] = '50%';
		}

		if (isset($this->data['id']) && !isset($this->data['ascp_widgets'][$this->data['id']]['modal_cb_maxwidth'])) {
			$this->data['ascp_widgets'][$this->data['id']]['modal_cb_maxwidth'] = '100%';
		}
		if (isset($this->data['id']) && !isset($this->data['ascp_widgets'][$this->data['id']]['modal_cb_maxheight'])) {
			$this->data['ascp_widgets'][$this->data['id']]['modal_cb_maxheight'] = '100%';
		}

		if (isset($this->data['id']) && !isset($this->data['ascp_widgets'][$this->data['id']]['modal_cbmobile_width'])) {
			$this->data['ascp_widgets'][$this->data['id']]['modal_cbmobile_width'] = '90%';
		}

		if (isset($this->data['id']) && !isset($this->data['ascp_widgets'][$this->data['id']]['modal_cbmobile_height'])) {
			$this->data['ascp_widgets'][$this->data['id']]['modal_cbmobile_height'] = '90%';
		}

		if (isset($this->data['id']) && !isset($this->data['ascp_widgets'][$this->data['id']]['modal_cbmobile_maxwidth'])) {
			$this->data['ascp_widgets'][$this->data['id']]['modal_cbmobile_maxwidth'] = '100%';
		}

		if (isset($this->data['id']) && !isset($this->data['ascp_widgets'][$this->data['id']]['modal_cbmobile_maxheight'])) {
			$this->data['ascp_widgets'][$this->data['id']]['modal_cbmobile_maxheight'] = '100%';
		}

		if (isset($this->data['id']) && !isset($this->data['ascp_widgets'][$this->data['id']]['modal_cb_fixed'])) {
			$this->data['ascp_widgets'][$this->data['id']]['modal_cb_fixed'] = '0';
		}
		if (isset($this->data['id']) && !isset($this->data['ascp_widgets'][$this->data['id']]['modal_cb_reposition'])) {
			$this->data['ascp_widgets'][$this->data['id']]['modal_cb_reposition'] = '1';
		}
		if (isset($this->data['id']) && !isset($this->data['ascp_widgets'][$this->data['id']]['modal_cb_scrolling'])) {
			$this->data['ascp_widgets'][$this->data['id']]['modal_cb_scrolling'] = '1';
		}

		if (isset($this->data['id']) && !isset($this->data['ascp_widgets'][$this->data['id']]['modal_cb_opacity'])) {
			$this->data['ascp_widgets'][$this->data['id']]['modal_cb_opacity'] = '0.3';
		}

if (SC_VERSION > 15) {
$this->data['ascp_widgets'][$this->data['id']]['anchor_templates'] = array(

$this->language->get('entry_anchor_templates_tab') =><<<EOF
$('#cmswidget-'+cmswidget).remove();
$('.nav-tabs').append ('<li><a data-toggle=\'tab\' href=\'#tab-html-'+cmswidget+'\'>'+heading_title+'</a></li>');
$('.tab-content:first').append($(data).html());
EOF
,

$this->language->get('entry_anchor_templates_html') => "$('#cmswidget-'+cmswidget).remove();
$('".$this->language->get('text_anchor_templates_selector')."').html(data);",

$this->language->get('entry_anchor_templates_prepend') => "$('#cmswidget-'+cmswidget).remove();
$('".$this->language->get('text_anchor_templates_selector')."').prepend(data);",

$this->language->get('entry_anchor_templates_append') => "$('#cmswidget-'+cmswidget).remove();
$('".$this->language->get('text_anchor_templates_selector')."').append(data);",

$this->language->get('entry_anchor_templates_before') => "$('#cmswidget-'+cmswidget).remove();
$('".$this->language->get('text_anchor_templates_selector')."').before(data);",

$this->language->get('entry_anchor_templates_after') => "$('#cmswidget-'+cmswidget).remove();
$('".$this->language->get('text_anchor_templates_selector')."').after(data);",

$this->language->get('entry_anchor_templates_clear') => ""
);
} else {

$this->data['ascp_widgets'][$this->data['id']]['anchor_templates'] = array(
$this->language->get('entry_anchor_templates_tab') =><<<EOF
$('#cmswidget-'+cmswidget).remove();
$('#tabs').append ('<a href=\'#tab-html-'+cmswidget+'\'>'+heading_title+'</a>');
$('#tab-description').after(data);
$('#tabs a').each(function() {
   var obj = $(this);
   $(obj.attr('href')).hide();
   $(obj).unbind( 'click' );
});
$('#tabs a').tabs();
EOF
,

$this->language->get('entry_anchor_templates_html') => "$('#cmswidget-'+cmswidget).remove();
$('".$this->language->get('text_anchor_templates_selector')."').html(data);",

$this->language->get('entry_anchor_templates_prepend') => "$('#cmswidget-'+cmswidget).remove();
$('".$this->language->get('text_anchor_templates_selector')."').prepend(data);",

$this->language->get('entry_anchor_templates_append') => "$('#cmswidget-'+cmswidget).remove();
$('".$this->language->get('text_anchor_templates_selector')."').append(data);",

$this->language->get('entry_anchor_templates_before') => "$('#cmswidget-'+cmswidget).remove();
$('".$this->language->get('text_anchor_templates_selector')."').before(data);",

$this->language->get('entry_anchor_templates_after') => "$('#cmswidget-'+cmswidget).remove();
$('".$this->language->get('text_anchor_templates_selector')."').after(data);",

$this->language->get('entry_anchor_templates_clear') => ""
);


}


if (SC_VERSION > 15) {
$this->data['ascp_widgets'][$this->data['id']]['box_begin_templates'] = array(

$this->language->get('entry_box_begin_templates_tab') => '<div id="tab-html-{CMSWIDGET}" class="tab-pane">
	<div class="box" style="display: block">
		<div class="box-content bordernone">
',
$this->language->get('entry_box_begin_templates_empty') => '<div>',
$this->language->get('entry_anchor_templates_clear') => ""
);
} else {

$this->data['ascp_widgets'][$this->data['id']]['box_begin_templates'] = array(
$this->language->get('entry_box_begin_templates_tab') => '<div id="tab-html-{CMSWIDGET}" class="tab-content">
	<div class="box" style="display: block">
		<div class="box-content bordernone">
',
$this->language->get('entry_box_begin_templates_empty') => '<div>',
$this->language->get('entry_anchor_templates_clear') => ""
);

}

if (SC_VERSION > 15) {
$this->data['ascp_widgets'][$this->data['id']]['box_end_templates'] = array(
$this->language->get('entry_box_end_templates_tab') => '		</div>
	</div>
</div>',
$this->language->get('entry_box_end_templates_empty') => '</div>',
$this->language->get('entry_anchor_templates_clear') => ""
);
} else {

$this->data['ascp_widgets'][$this->data['id']]['box_end_templates'] = array(
$this->language->get('entry_box_end_templates_tab') => '		</div>
	</div>
</div>',
$this->language->get('entry_box_end_templates_empty') => '</div>',
$this->language->get('entry_anchor_templates_clear') => ""
);

}
        return $this->data;
	}


	public function widget_services($data) {
		$this->data = $data;
		$this->config->set("blog_work", true);
        if ((isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == '1')) || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https') || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && strtolower($_SERVER['HTTP_X_FORWARDED_SSL']) == 'on'))) {
        	$url_link_ssl = true;
        } else {
        	if (SC_VERSION < 20) {
        		$url_link_ssl = 'NONSSL';
        	} else {
        		$url_link_ssl = false;
        	}
        }
        if (SC_VERSION > 23) {
        	$this->data['token_name'] = 'user_token';
        } else {
        	$this->data['token_name'] = $this->data['token_name'];
        }
        $this->data['token'] = $this->session->data[$this->data['token_name']];

       	$this->language->load('agoo/html/html');

		if (SC_VERSION > 23) {
			$this->template = 'agoo/html/widget_services_html';
		} else {
			$this->template = 'agoo/html/widget_services_html.tpl';
		}


        if (version_compare(VERSION, '2.0', '<')) {
	        $mod_str = 'module/httpsfix/cacheremove';
	        $mod_str_value = 'mod=1&';
        } else {
	        $mod_str = 'extension/modification/refresh';
	        $mod_str_value = '';
        }

        $this->data['url_ocmod_refresh'] = $this->url->link($mod_str, $mod_str_value.$this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);
        $this->data['url_cache_remove'] = $this->url->link('module/blog/cacheremove', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);
        $this->data['url_cache_image_remove'] = $this->url->link('module/blog/cacheremove', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']].'&image=1', $url_link_ssl);

		$this->data['header'] 	= '';
		$this->data['menu'] 	= '';
		$this->data['footer'] 	= '';
		$this->data['column_left'] 	= '';
        $this->data['language'] = $this->language;

		if (SC_VERSION > 23) {
			$this->template = 'agoo/html/widget_services_html';
		} else {
			$this->template = 'agoo/html/widget_services_html.tpl';
		}

        if (SC_VERSION < 20) {
			$html = $this->render();
		} else {
			$html = $this->load->view($this->template, $this->data);
		}
        $this->data['widget_services']['html'] = $html;

	    return $this->data;

	}


	private function table_exists($tableName) {
		$found= false;
		$like   = addcslashes($tableName, '%_\\');
		$result = $this->db->query("SHOW TABLES LIKE '" . $this->db->escape($like) . "';");
		$found  = $result->num_rows > 0;
		return $found;
	}

}
