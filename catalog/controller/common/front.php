<?php
/* All rights reserved belong to the module, the module developers http://opencartadmin.com */
// http://opencartadmin.com © 2011-2017 All Rights Reserved
// Distribution, without the author's consent is prohibited
// Commercial license
class ControllerCommonFront extends Controller
{
	public function install() {
		$sc_ver = VERSION;
		if (!defined('SC_VERSION'))
			define('SC_VERSION', (int) substr(str_replace('.', '', $sc_ver), 0, 2));
		if ($this->config->get('ascp_settings') != '') {
			$settings_general = $this->config->get('ascp_settings');
		} else {
			$settings_general = Array();
		}

		if (!$this->registry->get('admin_work')) {

			if (!class_exists('agooUrl')) {
				loadlibrary('agoo/url');
			}
			$Url_old = $this->registry->get('url');
			$this->registry->set('url_old', $Url_old);
			$agooUrl = new agooUrl($this->registry);
			$this->registry->set('url', $agooUrl);
   		}

		if (isset($settings_general['langmark_widget_status']) && $settings_general['langmark_widget_status'] && !$this->registry->get('admin_work')) {

		    if (isset($settings_general['langmark_widget_status']) && $settings_general['langmark_widget_status'] && !$this->registry->get('admin_work')) {
				if (!class_exists('agooMultilang')) {
					loadlibrary('agoo/multilang');
				}
				$multilang = new agooMultilang($this->registry);
			}

		}

		if (isset($settings_general['seocms_url_alter']) && $settings_general['seocms_url_alter']) {
			$this->registry->set('seocms_url_alter', true);
		} else {
			$this->registry->set('seocms_url_alter', false);
		}

		if (!class_exists('ModelToolImage')) {
			$this->load->model('tool/image');
		}
		if (!class_exists('ModelDesignLayout')) {
			$this->load->model('design/layout');
		}

		if (!class_exists('User')) {
			loadlibrary('user');
		}
		if (SC_VERSION > 21) {
			$user_str = 'Cart\User';
		} else {
			$user_str = 'User';
		}
		$this->user = new $user_str($this->registry);

		if ($this->user->isLogged() || $this->registry->get('admin_work')) {
			$this->registry->set('sc_isLogged', true);
			$is_admin = true;
		} else {
			$this->registry->set('sc_isLogged', false);
			$is_admin = false;
		}
		if ($this->config->get('config_theme') == 'theme_default') {
			$theme_directory = $this->config->get('theme_default_directory');
		} else {
			$theme_directory = $this->config->get('config_theme');
		}
		$this->registry->set('theme_directory', $theme_directory);

		if (!$this->config->get('config_maintenance') || $is_admin) {

			$loader_old = $this->registry->get('load');
			$this->registry->set('load_old', $loader_old);
			loadlibrary('agoo/loader');
			$agooloader = new agooLoader($this->registry);
			$this->registry->set('load', $agooloader);

			$Document = $this->registry->get('document');
			$this->registry->set('document_old', $Document);
			loadlibrary('agoo/document');
			$agooDocument = new agooDocument($this->registry);
			$this->registry->set('document', $agooDocument);

			$Cache = $this->registry->get('cache');
			$this->registry->set('cache_old', $Cache);
			loadlibrary('agoo/cache');
			$agooCache = new agooCache($this->registry);
			$agooCache->agooconstruct($settings_general);
			$this->registry->set('cache', $agooCache);

            /*
			loadlibrary('agoo/request');
			$Request = $this->registry->get('request');
			$this->registry->set('request_old', $Request);
			$agooRequest = new agooRequest($this->registry);
			$this->registry->set('request', $agooRequest);
            */
			/*
            if (SC_VERSION > 21) {
				loadlibrary('agoo/language');
				$Language = $this->registry->get('language');
				$this->registry->set('language_old', $Language);
				$agooLanguage = new agooLanguage($this->registry);
				$this->registry->set('language', $agooLanguage);
			}
            */

			$this->registry->set('config_ascp_settings', $settings_general);
			if (!$this->registry->get('loader_loading')) {
				loadlibrary('agoo/config');
				$Config = $this->registry->get('config');
				$this->registry->set('config_old', $Config);
				$agooConfig = new agooConfig($this->registry);
				$this->registry->set('config', $agooConfig);

				loadlibrary('agoo/response');
				$Response = $this->registry->get('response');
				$this->registry->set('response_old', $Response);
				$agooResponse = new agooResponse($this->registry);
				$this->registry->set('response', $agooResponse);
			}
			$this->registry->set('loader_loading', true);

	        //Load Cache

			if (!$this->registry->get('admin_work') && ((
				(isset($settings_general['jetcache_widget_status'])) && $settings_general['jetcache_widget_status']) ||
				(isset($settings_general['cache_pages'])) && $settings_general['cache_pages'])
			) {
	        	$this->registry->set('seocms_cache_status', true);

			}

	        if ($this->registry->get('seocms_cache_status') && !$this->registry->get('cont_jetcache_loading')) {
				agoo_cont('jetcache/jetcache', $this->registry);

		        if (isset($settings_general['seocms_jetcache_alter']) && $settings_general['seocms_jetcache_alter'] && is_callable(array('Response', 'seocms_setRegistry'))) {
	               	$this->registry->set('seocms_jetcache_alter', true);
		        } else {
		        	$this->registry->set('seocms_jetcache_alter', false);
		        }

		        $this->registry->set('cont_jetcache_loading', $this->controller_jetcache_jetcache->jetcache_construct());
            }

		} else {
			if (SC_VERSION > 15) {
				return $this->load->controller('common/maintenance');
			}
		}
	}
}