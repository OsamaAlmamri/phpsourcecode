<?php
class ControllerSettingStore extends Controller {
	private $error = array(); 

	public function index() {
		$this->load_language('setting/store');

		$this->document->setTitle($this->language->get('heading_title_store_list'));
		 
		$this->load->model('setting/store');

		$this->getList();
	}
	      
  	public function insert() {
    	$this->load_language('setting/store');

    	$this->document->setTitle($this->language->get('heading_title_store_setting')); 
		
		$this->load->model('setting/store');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$store_id = $this->model_setting_store->addStore($this->request->post);
	  		
			$this->load->model('setting/setting');
			
			$this->model_setting_setting->editSetting('config', $this->request->post, $store_id);
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL'));
    	}
	
    	$this->getForm();
  	}

  	public function update() {
    	$this->load_language('setting/store');

    	$this->document->setTitle($this->language->get('heading_title_store_setting'));
		
		$this->load->model('setting/store');
	
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_setting_store->editStore($this->request->get['store_id'], $this->request->post);
			
			$this->load->model('setting/setting');
			
			$this->model_setting_setting->editSetting('config', $this->request->post, $this->request->get['store_id']);
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->link('setting/store', 'token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id'], 'SSL'));
		}

    	$this->getForm();
  	}

  	public function delete() {
    	$this->load_language('setting/store');

    	$this->document->setTitle($this->language->get('heading_title_store_list'));
		
		$this->load->model('setting/store');
		
		$this->load->model('setting/setting');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $store_id) {
				$this->model_setting_store->deleteStore($store_id);
				
				$this->model_setting_setting->deleteSetting('config', $store_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL'));
		}

    	$this->getList();
  	}
	
	private function getList() {
		$url = '';
			
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);

   		$this->data['breadcrumbs'][] = array(
   		     'text'      => $this->language->get('heading_title_store_list'),
   			 'href'      => $this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL'),
   		     'separator' => $this->language->get('text_breadcrumb_separator')
   		);
   		
   		$this->document->setBreadcrumbs($this->data['breadcrumbs']);
   		
		$this->data['insert'] = $this->url->link('setting/store/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['delete'] = $this->url->link('setting/store/delete', 'token=' . $this->session->data['token'], 'SSL');	

		$this->data['stores'] = array();
		
		$action = array();
					
		$action[] = array(
			'text' => $this->language->get('text_edit'),
			'href' => $this->url->link('setting/setting', 'token=' . $this->session->data['token'], 'SSL')
		);
					
		$this->data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->config->get('config_name') . $this->language->get('text_default'),
			'url'      => HTTP_CATALOG,
			'selected' => isset($this->request->post['selected']) && in_array(0, $this->request->post['selected']),
			'action'   => $action
		);
					
		$store_total = $this->model_setting_store->getTotalStores();
	
		$results = $this->model_setting_store->getStores();
 
    	foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('setting/store/update', 'token=' . $this->session->data['token'] . '&store_id=' . $result['store_id'], 'SSL')
			);
						
			$this->data['stores'][] = array(
				'store_id' => $result['store_id'],
				'name'     => $result['name'],
				'url'      => $result['url'],
				'selected' => isset($this->request->post['selected']) && in_array($result['store_id'], $this->request->post['selected']),
				'action'   => $action
			);
		}	
	
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->template = 'setting/store_list.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
	}
	 
	public function getForm() { 
		$this->data['token'] = $this->session->data['token'];
		
		$error_keys=array(
			'warning' => 'error_warning',
			'url' => 'error_url',
			'name' => 'error_name',
			'owner' => 'error_owner',
			'address' => 'error_address',
			'email' => 'error_email',
			'telephone' => 'error_telephone',
			'title' => 'error_title',
			'image_thumb' => 'error_image_thumb',
			'image_popup' => 'error_image_popup',
			'image_product' => 'error_image_product',
			'image_category' => 'error_image_category',
			'image_manufacturer' => 'error_image_manufacturer',
			'image_additional' => 'error_image_additional',
			'image_related' => 'error_image_related',
			'image_compare' => 'error_image_compare',
			'image_wishlist' => 'error_image_wishlist',
			'image_cart' => 'error_image_cart',
			'catalog_limit' => 'error_catalog_limit'
				
		);

		foreach ($error_keys as $key => $value) {
			if (isset($this->error[$key])) {
				$this->data[$value] = $this->error[$key];
			} else {
				$this->data[$value] = '';
			}
		}
		
		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->language->get('text_breadcrumb_separator')
   		);
   		if (!isset($this->request->get['store_id'])) {
	   		$this->data['breadcrumbs'][] = array(
	   		      'text'      => $this->language->get('heading_title_store_setting'),
	   			   'href'      => $this->url->link('setting/store/insert', 'token=' . $this->session->data['token'], 'SSL'),
	   		       'separator' => $this->language->get('text_breadcrumb_separator')
	   		);
   		}else{
   			$this->data['breadcrumbs'][] = array(
   				   'text'      => $this->language->get('heading_title_store_setting'),
   				   'href'      => $this->url->link('setting/store/update', 'token=' . $this->session->data['token']. '&store_id=' . $this->request->get['store_id'], 'SSL'),
   				   'separator' => $this->language->get('text_breadcrumb_separator')
   			);
   		}
   		$this->document->setBreadcrumbs($this->data['breadcrumbs']);
   		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		if (!isset($this->request->get['store_id'])) {
			$this->data['action'] = $this->url->link('setting/store/insert', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$this->data['action'] = $this->url->link('setting/store/update', 'token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id'], 'SSL');
		}
				
		$this->data['cancel'] = $this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL');
	
		if (isset($this->request->get['store_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$this->load->model('setting/setting');
			
      		$store_info = $this->model_setting_setting->getSetting('config', $this->request->get['store_id']);
    	}
		
		if (isset($this->request->post['config_url'])) {
			$this->data['config_url'] = $this->request->post['config_url'];
		} elseif (isset($store_info['config_url'])) {
			$this->data['config_url'] = $store_info['config_url'];
		} else {
			$this->data['config_url'] = '';
		}
		
		if (isset($this->request->post['config_ssl'])) {
			$this->data['config_ssl'] = $this->request->post['config_ssl'];
		} elseif (isset($store_info['config_ssl'])) {
			$this->data['config_ssl'] = $store_info['config_ssl'];
		} else {
			$this->data['config_ssl'] = '';
		}
		
		if (isset($this->request->post['config_meta_keyword'])) {
			$this->data['config_meta_keyword'] = $this->request->post['config_meta_keyword'];
		} elseif (isset($store_info['config_meta_keyword'])) {
			$this->data['config_meta_keyword'] = $store_info['config_meta_keyword'];
		} else {
			$this->data['config_meta_keyword'] = '';
		}
				
		if (isset($this->request->post['config_name'])) {
			$this->data['config_name'] = $this->request->post['config_name'];
		} elseif (isset($store_info['config_name'])) {
			$this->data['config_name'] = $store_info['config_name'];
		} else {
			$this->data['config_name'] = '';
		}
	
		if (isset($this->request->post['config_owner'])) {
			$this->data['config_owner'] = $this->request->post['config_owner'];
		} elseif (isset($store_info['config_owner'])) {
			$this->data['config_owner'] = $store_info['config_owner'];		
		} else {
			$this->data['config_owner'] = '';
		}

		if (isset($this->request->post['config_address'])) {
			$this->data['config_address'] = $this->request->post['config_address'];
		} elseif (isset($store_info['config_address'])) {
			$this->data['config_address'] = $store_info['config_address'];		
		} else {
			$this->data['config_address'] = '';
		}
		
		if (isset($this->request->post['config_email'])) {
			$this->data['config_email'] = $this->request->post['config_email'];
		} elseif (isset($store_info['config_email'])) {
			$this->data['config_email'] = $store_info['config_email'];		
		} else {
			$this->data['config_email'] = '';
		}
		
		if (isset($this->request->post['config_telephone'])) {
			$this->data['config_telephone'] = $this->request->post['config_telephone'];
		} elseif (isset($store_info['config_telephone'])) {
			$this->data['config_telephone'] = $store_info['config_telephone'];		
		} else {
			$this->data['config_telephone'] = '';
		}

		if (isset($this->request->post['config_fax'])) {
			$this->data['config_fax'] = $this->request->post['config_fax'];
		} elseif (isset($store_info['config_fax'])) {
			$this->data['config_fax'] = $store_info['config_fax'];		
		} else {
			$this->data['config_fax'] = '';
		}
		
		if (isset($this->request->post['config_title'])) {
			$this->data['config_title'] = $this->request->post['config_title'];
		} elseif (isset($store_info['config_title'])) {
			$this->data['config_title'] = $store_info['config_title'];
		} else {
			$this->data['config_title'] = '';
		}
		
		if (isset($this->request->post['config_meta_description'])) {
			$this->data['config_meta_description'] = $this->request->post['config_meta_description'];
		} elseif (isset($store_info['config_meta_description'])) {
			$this->data['config_meta_description'] = $store_info['config_meta_description'];		
		} else {
			$this->data['config_meta_description'] = '';
		}

		if (isset($this->request->post['config_layout_id'])) {
			$this->data['config_layout_id'] = $this->request->post['config_layout_id'];
		} elseif (isset($store_info['config_layout_id'])) {
			$this->data['config_layout_id'] = $store_info['config_layout_id'];
		} else {
			$this->data['config_layout_id'] = '';
		}
				
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		if (isset($this->request->post['config_template'])) {
			$this->data['config_template'] = $this->request->post['config_template'];
		} elseif (isset($store_info['config_template'])) {
			$this->data['config_template'] = $store_info['config_template'];
		} else {
			$this->data['config_template'] = '';
		}
		
		$this->data['templates'] = array();

		$directories = glob(DIR_CATALOG . 'view/theme/*', GLOB_ONLYDIR);
		
		foreach ($directories as $directory) {
			$this->data['templates'][] = basename($directory);
		}	
								
		if (isset($this->request->post['config_country_id'])) {
			$this->data['config_country_id'] = $this->request->post['config_country_id'];
		} elseif (isset($store_info['config_country_id'])) {
			$this->data['config_country_id'] = $store_info['config_country_id'];		
		} else {
			$this->data['config_country_id'] = $this->config->get('config_country_id');
		}
		
		$this->load->model('localisation/country');
		
		$this->data['countries'] = $this->model_localisation_country->getCountries();

		if (isset($this->request->post['config_zone_id'])) {
			$this->data['config_zone_id'] = $this->request->post['config_zone_id'];
		} elseif (isset($store_info['config_zone_id'])) {
			$this->data['config_zone_id'] = $store_info['config_zone_id'];				
		} else {
			$this->data['config_zone_id'] = $this->config->get('config_zone_id');
		}

		if (isset($this->request->post['config_language'])) {
			$this->data['config_language'] = $this->request->post['config_language'];
		} elseif (isset($store_info['config_language'])) {
			$this->data['config_language'] = $store_info['config_language'];			
		} else {
			$this->data['config_language'] = $this->config->get('config_language');
		}
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['config_currency'])) {
			$this->data['config_currency'] = $this->request->post['config_currency'];
		} elseif (isset($store_info['config_currency'])) {
			$this->data['config_currency'] = $store_info['config_currency'];			
		} else {
			$this->data['config_currency'] = $this->config->get('config_currency');
		}
		
		$this->load->model('localisation/currency');
		
		$this->data['currencies'] = $this->model_localisation_currency->getCurrencies();
		
		if (isset($this->request->post['config_catalog_limit'])) {
			$this->data['config_catalog_limit'] = $this->request->post['config_catalog_limit'];
		} elseif (isset($store_info['config_catalog_limit'])) {
			$this->data['config_catalog_limit'] = $store_info['config_catalog_limit'];	
		} else {
			$this->data['config_catalog_limit'] = '12';
		}		
		
		if (isset($this->request->post['config_seat'])) {
			$this->data['config_seat'] = $this->request->post['config_seat'];
		} elseif (isset($store_info['config_seat'])) {
			$this->data['config_seat'] = $store_info['config_seat'];	
		} else {
			$this->data['config_seat'] = '10';
		}		
		
		if (isset($this->request->post['config_latlng'])) {
			$this->data['config_latlng'] = $this->request->post['config_latlng'];
		} elseif (isset($store_info['config_latlng'])) {
			$this->data['config_latlng'] = $store_info['config_latlng'];	
		} else {
			$this->data['config_latlng'] = '';
		}		
		
		if (isset($this->request->post['config_hoursfrom'])) {
			$this->data['config_hoursfrom'] = $this->request->post['config_hoursfrom'];
		} elseif (isset($store_info['config_hoursfrom'])) {
			$this->data['config_hoursfrom'] = $store_info['config_hoursfrom'];	
		} else {
			$this->data['config_hoursfrom'] = '9';
		}		
		

		
		if (isset($this->request->post['config_hoursto'])) {
			$this->data['config_hoursto'] = $this->request->post['config_hoursto'];
		} elseif (isset($store_info['config_hoursto'])) {
			$this->data['config_hoursto'] = $store_info['config_hoursto'];	
		} else {
			$this->data['config_hoursto'] = '22';
		}	
			
		
		if (isset($this->request->post['config_sms_notice'])) {
			$this->data['config_sms_notice'] = $this->request->post['config_sms_notice'];
		} elseif (isset($store_info['config_sms_notice'])) {
			$this->data['config_sms_notice'] = $store_info['config_sms_notice'];	
		} else {
			$this->data['config_sms_notice'] = '';
		}		
		
		if (isset($this->request->post['config_sms_notice_mobile'])) {
			$this->data['config_sms_notice_mobile'] = $this->request->post['config_sms_notice_mobile'];
		} elseif (isset($store_info['config_sms_notice_mobile'])) {
			$this->data['config_sms_notice_mobile'] = $store_info['config_sms_notice_mobile'];	
		} else {
			$this->data['config_sms_notice_mobile'] = '';
		}		
		
		if (isset($this->request->post['config_print_notice'])) {
			$this->data['config_print_notice'] = $this->request->post['config_print_notice'];
		} elseif (isset($store_info['config_print_notice'])) {
			$this->data['config_print_notice'] = $store_info['config_print_notice'];	
		} else {
			$this->data['config_print_notice'] = '';
		}		
		
		
		if (isset($this->request->post['config_tax'])) {
			$this->data['config_tax'] = $this->request->post['config_tax'];
		} elseif (isset($store_info['config_tax'])) {
			$this->data['config_tax'] = $store_info['config_tax'];			
		} else {
			$this->data['config_tax'] = '';
		}

		$this->load->model('sale/customer_group');
		
		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		
		if (isset($this->request->post['config_customer_group_id'])) {
			$this->data['config_customer_group_id'] = $this->request->post['config_customer_group_id'];
		} elseif (isset($store_info['config_customer_group_id'])) {
			$this->data['config_customer_group_id'] = $store_info['config_customer_group_id'];			
		} else {
			$this->data['config_customer_group_id'] = '';
		}
		
		if (isset($this->request->post['config_customer_price'])) {
			$this->data['config_customer_price'] = $this->request->post['config_customer_price'];
		} elseif (isset($store_info['config_customer_price'])) {
			$this->data['config_customer_price'] = $store_info['config_customer_price'];			
		} else {
			$this->data['config_customer_price'] = '';
		}
		
		if (isset($this->request->post['config_customer_approval'])) {
			$this->data['config_customer_approval'] = $this->request->post['config_customer_approval'];
		} elseif (isset($store_info['config_customer_approval'])) {
			$this->data['config_customer_approval'] = $store_info['config_customer_approval'];			
		} else {
			$this->data['config_customer_approval'] = '';
		}
		
		if (isset($this->request->post['config_guest_checkout'])) {
			$this->data['config_guest_checkout'] = $this->request->post['config_guest_checkout'];
		} elseif (isset($store_info['config_guest_checkout'])) {
			$this->data['config_guest_checkout'] = $store_info['config_guest_checkout'];		
		} else {
			$this->data['config_guest_checkout'] = '';
		}
		
		if (isset($this->request->post['config_account_id'])) {
			$this->data['config_account_id'] = $this->request->post['config_account_id'];
		} elseif (isset($store_info['config_account_id'])) {
			$this->data['config_account_id'] = $store_info['config_account_id'];			
		} else {
			$this->data['config_account_id'] = '';
		}
		
		if (isset($this->request->post['config_checkout_id'])) {
			$this->data['config_checkout_id'] = $this->request->post['config_checkout_id'];
		} elseif (isset($store_info['config_checkout_id'])) {
			$this->data['config_checkout_id'] = $store_info['config_checkout_id'];		
		} else {
			$this->data['config_checkout_id'] = '';
		}

		$this->load->model('catalog/information');
		
		$this->data['informations'] = $this->model_catalog_information->getInformations();

		if (isset($this->request->post['config_stock_display'])) {
			$this->data['config_stock_display'] = $this->request->post['config_stock_display'];
		} elseif (isset($store_info['config_stock_display'])) {
			$this->data['config_stock_display'] = $store_info['config_stock_display'];			
		} else {
			$this->data['config_stock_display'] = '';
		}

		if (isset($this->request->post['config_stock_checkout'])) {
			$this->data['config_stock_checkout'] = $this->request->post['config_stock_checkout'];
		} elseif (isset($store_info['config_stock_checkout'])) {
			$this->data['config_stock_checkout'] = $store_info['config_stock_checkout'];		
		} else {
			$this->data['config_stock_checkout'] = '';
		}
		
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['config_order_status_id'])) {
			$this->data['config_order_status_id'] = $this->request->post['config_order_status_id'];
		} elseif (isset($store_info['config_order_status_id'])) {
			$this->data['config_order_status_id'] = $store_info['config_order_status_id'];		
		} else {
			$this->data['config_order_status_id'] = '';
		}
		
		if (isset($this->request->post['config_order_nopay_status_id'])) {
			$this->data['config_order_nopay_status_id'] = $this->request->post['config_order_nopay_status_id'];
		} elseif (isset($store_info['config_order_nopay_status_id'])) {
			$this->data['config_order_nopay_status_id'] = $store_info['config_order_nopay_status_id'];
		} else {
			$this->data['config_order_nopay_status_id'] = '';
		}
		
		if (isset($this->request->post['config_cart_weight'])) {
			$this->data['config_cart_weight'] = $this->request->post['config_cart_weight'];
		} elseif (isset($store_info['config_cart_weight'])) {
			$this->data['config_cart_weight'] = $store_info['config_cart_weight'];	
		} else {
			$this->data['config_cart_weight'] = '';
		}
				
		$this->load->model('tool/image');

		if (isset($this->request->post['config_logo'])) {
			$this->data['config_logo'] = $this->request->post['config_logo'];
		} elseif (isset($store_info['config_logo'])) {
			$this->data['config_logo'] = $store_info['config_logo'];			
		} else {
			$this->data['config_logo'] = '';
		}

		if (isset($store_info['config_logo']) && file_exists(DIR_IMAGE . $store_info['config_logo']) && is_file(DIR_IMAGE . $store_info['config_logo'])) {
			$this->data['logo'] = $this->model_tool_image->resize($store_info['config_logo'], 100, 100);		
		} else {
			$this->data['logo'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}

		if (isset($this->request->post['config_icon'])) {
			$this->data['config_icon'] = $this->request->post['config_icon'];
		} elseif (isset($store_info['config_icon'])) {
			$this->data['config_icon'] = $store_info['config_icon'];			
		} else {
			$this->data['config_icon'] = '';
		}
		
		if (isset($store_info['config_icon']) && file_exists(DIR_IMAGE . $store_info['config_icon']) && is_file(DIR_IMAGE . $store_info['config_icon'])) {
			$this->data['icon'] = $this->model_tool_image->resize($store_info['config_icon'], 100, 100);
		} else {
			$this->data['icon'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		
		if (isset($this->request->post['config_image_thumb_width'])) {
			$this->data['config_image_thumb_width'] = $this->request->post['config_image_thumb_width'];
		} elseif (isset($store_info['config_image_thumb_width'])) {
			$this->data['config_image_thumb_width'] = $store_info['config_image_thumb_width'];			
		} else {
			$this->data['config_image_thumb_width'] = 228;
		}
		
		if (isset($this->request->post['config_image_thumb_height'])) {
			$this->data['config_image_thumb_height'] = $this->request->post['config_image_thumb_height'];
		} elseif (isset($store_info['config_image_thumb_height'])) {
			$this->data['config_image_thumb_height'] = $store_info['config_image_thumb_height'];				
		} else {
			$this->data['config_image_thumb_height'] = 228;
		}
		
		if (isset($this->request->post['config_image_popup_width'])) {
			$this->data['config_image_popup_width'] = $this->request->post['config_image_popup_width'];
		} elseif (isset($store_info['config_image_popup_width'])) {
			$this->data['config_image_popup_width'] = $store_info['config_image_popup_width'];			
		} else {
			$this->data['config_image_popup_width'] = 500;
		}
		
		if (isset($this->request->post['config_image_popup_height'])) {
			$this->data['config_image_popup_height'] = $this->request->post['config_image_popup_height'];
		} elseif (isset($store_info['config_image_popup_height'])) {
			$this->data['config_image_popup_height'] = $store_info['config_image_popup_height'];			
		} else {
			$this->data['config_image_popup_height'] = 500;
		}
		
		if (isset($this->request->post['config_image_product_width'])) {
			$this->data['config_image_product_width'] = $this->request->post['config_image_product_width'];
		} elseif (isset($store_info['config_image_product_width'])) {
			$this->data['config_image_product_width'] = $store_info['config_image_product_width'];		
		} else {
			$this->data['config_image_product_width'] = 80;
		}
		
		if (isset($this->request->post['config_image_product_height'])) {
			$this->data['config_image_product_height'] = $this->request->post['config_image_product_height'];
		} elseif (isset($store_info['config_image_product_height'])) {
			$this->data['config_image_product_height'] = $store_info['config_image_product_height'];		
		} else {
			$this->data['config_image_product_height'] = 80;
		}
		
		if (isset($this->request->post['config_image_category_width'])) {
			$this->data['config_image_category_width'] = $this->request->post['config_image_category_width'];
		} elseif (isset($store_info['config_image_category_width'])) {
			$this->data['config_image_category_width'] = $store_info['config_image_category_width'];			
		} else {
			$this->data['config_image_category_width'] = 80;
		}
						
		if (isset($this->request->post['config_image_category_height'])) {
			$this->data['config_image_category_height'] = $this->request->post['config_image_category_height'];
		} elseif (isset($store_info['config_image_category_height'])) {
			$this->data['config_image_category_height'] = $store_info['config_image_category_height'];			
		} else {
			$this->data['config_image_category_height'] = 80;
		}	
				
		if (isset($this->request->post['config_image_manufacturer_width'])) {
			$this->data['config_image_manufacturer_width'] = $this->request->post['config_image_manufacturer_width'];
		} elseif (isset($store_info['config_image_manufacturer_width'])) {
			$this->data['config_image_manufacturer_width'] = $store_info['config_image_manufacturer_width'];			
		} else {
			$this->data['config_image_manufacturer_width'] = 80;
		}
						
		if (isset($this->request->post['config_image_manufacturer_height'])) {
			$this->data['config_image_manufacturer_height'] = $this->request->post['config_image_manufacturer_height'];
		} elseif (isset($store_info['config_image_manufacturer_height'])) {
			$this->data['config_image_manufacturer_height'] = $store_info['config_image_manufacturer_height'];			
		} else {
			$this->data['config_image_manufacturer_height'] = 80;
		}

		if (isset($this->request->post['config_image_additional_width'])) {
			$this->data['config_image_additional_width'] = $this->request->post['config_image_additional_width'];
		} elseif (isset($store_info['config_image_additional_width'])) {
			$this->data['config_image_additional_width'] = $store_info['config_image_additional_width'];			
		} else {
			$this->data['config_image_additional_width'] = 74;
		}
		
		if (isset($this->request->post['config_image_additional_height'])) {
			$this->data['config_image_additional_height'] = $this->request->post['config_image_additional_height'];
		} elseif (isset($store_info['config_image_additional_height'])) {
			$this->data['config_image_additional_height'] = $store_info['config_image_additional_height'];				
		} else {
			$this->data['config_image_additional_height'] = 74;
		}
		
		if (isset($this->request->post['config_image_related_width'])) {
			$this->data['config_image_related_width'] = $this->request->post['config_image_related_width'];
		} elseif (isset($store_info['config_image_related_width'])) {
			$this->data['config_image_related_width'] = $store_info['config_image_related_width'];		
		} else {
			$this->data['config_image_related_width'] = 80;
		}
		
		if (isset($this->request->post['config_image_related_height'])) {
			$this->data['config_image_related_height'] = $this->request->post['config_image_related_height'];
		} elseif (isset($store_info['config_image_related_height'])) {
			$this->data['config_image_related_height'] = $store_info['config_image_related_height'];			
		} else {
			$this->data['config_image_related_height'] = 80;
		}
		
		if (isset($this->request->post['config_image_compare_width'])) {
			$this->data['config_image_compare_width'] = $this->request->post['config_image_compare_width'];
		} elseif (isset($store_info['config_image_compare_width'])) {
			$this->data['config_image_compare_width'] = $store_info['config_image_compare_width'];			
		} else {
			$this->data['config_image_compare_width'] = 90;
		}
		
		if (isset($this->request->post['config_image_compare_height'])) {
			$this->data['config_image_compare_height'] = $this->request->post['config_image_compare_height'];
		} elseif (isset($store_info['config_image_compare_height'])) {
			$this->data['config_image_compare_height'] = $store_info['config_image_compare_height'];			
		} else {
			$this->data['config_image_compare_height'] = 90;
		}
		
		if (isset($this->request->post['config_image_wishlist_width'])) {
			$this->data['config_image_wishlist_width'] = $this->request->post['config_image_wishlist_width'];
		} elseif (isset($store_info['config_image_wishlist_width'])) {
			$this->data['config_image_wishlist_width'] = $store_info['config_image_wishlist_width'];			
		} else {
			$this->data['config_image_wishlist_width'] = 50;
		}
		
		if (isset($this->request->post['config_image_wishlist_height'])) {
			$this->data['config_image_wishlist_height'] = $this->request->post['config_image_wishlist_height'];
		} elseif (isset($store_info['config_image_wishlist_height'])) {
			$this->data['config_image_wishlist_height'] = $store_info['config_image_wishlist_height'];			
		} else {
			$this->data['config_image_wishlist_height'] = 50;
		}
				
		if (isset($this->request->post['config_image_cart_width'])) {
			$this->data['config_image_cart_width'] = $this->request->post['config_image_cart_width'];
		} elseif (isset($store_info['config_image_cart_width'])) {
			$this->data['config_image_cart_width'] = $store_info['config_image_cart_width'];			
		} else {
			$this->data['config_image_cart_width'] = 80;
		}
		
		if (isset($this->request->post['config_image_cart_height'])) {
			$this->data['config_image_cart_height'] = $this->request->post['config_image_cart_height'];
		} elseif (isset($store_info['config_image_cart_height'])) {
			$this->data['config_image_cart_height'] = $store_info['config_image_cart_height'];			
		} else {
			$this->data['config_image_cart_height'] = 80;
		}

		if (isset($this->request->post['config_use_ssl'])) {
			$this->data['config_use_ssl'] = $this->request->post['config_use_ssl'];
		} elseif (isset($store_info['config_use_ssl'])) {
			$this->data['config_use_ssl'] = $store_info['config_use_ssl'];
		} else {
			$this->data['config_use_ssl'] = '';
		}

		$this->template = 'setting/store_form.tpl';
		$this->id = 'content';
		$this->layout = 'layout/default';
		$this->render();
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'setting/store')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['config_url']) {
			$this->error['url'] = $this->language->get('error_url');
		}
				
		if (!$this->request->post['config_name']) {
			$this->error['name'] = $this->language->get('error_name');
		}	
		
		if ((strlen(utf8_decode($this->request->post['config_owner'])) < 1) || (strlen(utf8_decode($this->request->post['config_owner'])) > 64)) {
			$this->error['owner'] = $this->language->get('error_owner');
		}

		if ((strlen(utf8_decode($this->request->post['config_address'])) < 1) || (strlen(utf8_decode($this->request->post['config_address'])) > 256)) {
			$this->error['address'] = $this->language->get('error_address');
		}
		
    	if ((strlen(utf8_decode($this->request->post['config_email'])) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['config_email'])) {
      		$this->error['email'] = $this->language->get('error_email');
    	}

    	if ((strlen(utf8_decode($this->request->post['config_telephone'])) < 1) || (strlen(utf8_decode($this->request->post['config_telephone'])) > 32)) {
      		$this->error['telephone'] = $this->language->get('error_telephone');
    	}
		
		if (!$this->request->post['config_title']) {
			$this->error['title'] = $this->language->get('error_title');
		}	
		
		if (!$this->request->post['config_image_thumb_width'] || !$this->request->post['config_image_thumb_height']) {
			$this->error['image_thumb'] = $this->language->get('error_image_thumb');
		}	
		
		if (!$this->request->post['config_image_popup_width'] || !$this->request->post['config_image_popup_height']) {
			$this->error['image_popup'] = $this->language->get('error_image_popup');
		}
			
		if (!$this->request->post['config_image_product_width'] || !$this->request->post['config_image_product_height']) {
			$this->error['image_product'] = $this->language->get('error_image_product');
		}
				
		if (!$this->request->post['config_image_category_width'] || !$this->request->post['config_image_category_height']) {
			$this->error['image_category'] = $this->language->get('error_image_category');
		}
		
		if (!$this->request->post['config_image_manufacturer_width'] || !$this->request->post['config_image_manufacturer_height']) {
			$this->error['image_manufacturer'] = $this->language->get('error_image_manufacturer');
		}
		
		if (!$this->request->post['config_image_additional_width'] || !$this->request->post['config_image_additional_height']) {
			$this->error['image_additional'] = $this->language->get('error_image_additional');
		}
		
		if (!$this->request->post['config_image_related_width'] || !$this->request->post['config_image_related_height']) {
			$this->error['image_related'] = $this->language->get('error_image_related');
		}
		
		if (!$this->request->post['config_image_compare_width'] || !$this->request->post['config_image_compare_height']) {
			$this->error['image_compare'] = $this->language->get('error_image_compare');
		}
		
		if (!$this->request->post['config_image_wishlist_width'] || !$this->request->post['config_image_wishlist_height']) {
			$this->error['image_wishlist'] = $this->language->get('error_image_wishlist');
		}
						
		if (!$this->request->post['config_image_cart_width'] || !$this->request->post['config_image_cart_height']) {
			$this->error['image_cart'] = $this->language->get('error_image_cart');
		}
		
		if (!$this->request->post['config_catalog_limit']) {
			$this->error['catalog_limit'] = $this->language->get('error_limit');
		}
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
					
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'setting/store')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$this->load->model('sale/order');
		
		foreach ($this->request->post['selected'] as $store_id) {
			if (!$store_id) {
				$this->error['warning'] = $this->language->get('error_default');
			}
			
			$store_total = $this->model_sale_order->getTotalOrdersByStoreId($store_id);
	
			if ($store_total) {
				$this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
			}	
		}
		
		if (!$this->error) {
			return true; 
		} else {
			return false;
		}
	}
	
	public function template() {
		$template = basename($this->request->get['template']);
		
		if (file_exists(DIR_IMAGE . 'templates/' . $template . '.png')) {
			$image = HTTPS_IMAGE . 'templates/' . $template . '.png';
		} else {
			$image = HTTPS_IMAGE . 'no_image.jpg';
		}
		
		$this->response->setOutput('<img src="' . $image . '" alt="" title="" style="border: 1px solid #EEEEEE;" />');
	}		
	
	public function zone() {
		$output = '';
		
		$this->load->model('localisation/zone');
		
		$results = $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']);
		
		foreach ($results as $result) {
			$output .= '<option value="' . $result['zone_id'] . '"';

			if (isset($this->request->get['zone_id']) && ($this->request->get['zone_id'] == $result['zone_id'])) {
				$output .= ' selected="selected"';
			}

			$output .= '>' . $result['name'] . '</option>';
		}

		if (!$results) {
			$output .= '<option value="0">' . $this->language->get('text_none') . '</option>';
		}

		$this->response->setOutput($output);
	}		
}
?>