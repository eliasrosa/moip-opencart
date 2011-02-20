<?php 
class ControllerPaymentMoip extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('payment/moip');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->load->model('setting/setting');
			
			$this->model_setting_setting->editSetting('moip', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect(HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token']);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		
		
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_test'] = $this->language->get('entry_test');
		$this->data['entry_encryption'] = $this->language->get('entry_encryption');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');	
		$this->data['entry_aguardando'] = $this->language->get('entry_aguardando');	
		$this->data['entry_cancelado'] = $this->language->get('entry_cancelado');	
		$this->data['entry_aprovado'] = $this->language->get('entry_aprovado');	
		$this->data['entry_analize'] = $this->language->get('entry_analize');	
		$this->data['entry_completo'] = $this->language->get('entry_completo');	

		$this->data['entry_order_status'] = $this->language->get('entry_order_status');		
		
		$this->data['help_encryption'] = $this->language->get('help_encryption');
		$this->data['help_aguardando'] = $this->language->get('help_aguardando');
		$this->data['help_cancelado'] = $this->language->get('help_cancelado');
		$this->data['help_aprovado'] = $this->language->get('help_aprovado');
		$this->data['help_analize'] = $this->language->get('help_analize');
		$this->data['help_completo'] = $this->language->get('help_completo');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');
		
if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$this->data['error_email'] = $this->language->get('error_email');
		$this->data['error_encryption'] = $this->language->get('error_encryption');
			
  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_payment'),
      		'separator' => ' :: '
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=payment/moip&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=payment/moip&token=' . $this->session->data['token'];

		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'];

if (isset($this->request->post['moip_status'])) {
			$this->data['moip_status'] = $this->request->post['moip_status'];
		} else {
			$this->data['moip_status'] = $this->config->get('moip_status'); 
		} 


		if (isset($this->request->post['moip_email'])) {
			$this->data['moip_email'] = $this->request->post['moip_email'];
		} else {
			$this->data['moip_email'] = $this->config->get('moip_email'); 
		} 
		
			if (isset($this->request->post['moip_test'])) {
			$this->data['moip_test'] = $this->request->post['moip_test'];
		} else {
			$this->data['moip_test'] = $this->config->get('moip_test'); 
		} 
		
			if (isset($this->request->post['moip_encryption'])) {
			$this->data['moip_encryption'] = $this->request->post['moip_encryption'];
		} else {
			$this->data['moip_encryption'] = $this->config->get('moip_encryption');
		}
		
		
		$this->load->model('localisation/geo_zone');
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['moip_geo_zone_id'])) {
			$this->data['moip_geo_zone_id'] = $this->request->post['moip_geo_zone_id'];
		} else {
			$this->data['moip_geo_zone_id'] = $this->config->get('moip_geo_zone_id'); 
		} 

          if (isset($this->request->post['moip_order_status_id'])) {
			$this->data['moip_order_status_id'] = $this->request->post['moip_order_status_id'];
		} else {
			$this->data['moip_order_status_id'] = $this->config->get('moip_order_status_id'); 
		} 
		
		//status do pedido quando estive aguardando pagamento pelo moip
		  if (isset($this->request->post['moip_aguardando'])) {
			$this->data['moip_aguardando'] = $this->request->post['moip_aguardando'];
		} else {
			$this->data['moip_aguardando'] = $this->config->get('moip_aguardando'); 
		} 
		//estatos do pedido quando for cancelado pelo moip
		  if (isset($this->request->post['moip_cancelado'])) {
			$this->data['moip_cancelado'] = $this->request->post['moip_cancelado'];
		} else {
			$this->data['moip_cancelado'] = $this->config->get('moip_cancelado'); 
		} 
		//status do pedido quando for aprovando pelo moip
		  if (isset($this->request->post['moip_aprovado'])) {
			$this->data['moip_aprovado'] = $this->request->post['moip_aprovado'];
		} else {
			$this->data['moip_aprovado'] = $this->config->get('moip_aprovado'); 
		} 
		//status do pedido quando for Analize pelo moip
		  if (isset($this->request->post['moip_analize'])) {
			$this->data['moip_analize'] = $this->request->post['moip_analize'];
		} else {
			$this->data['moip_analize'] = $this->config->get('moip_analize'); 
		} 
		//status do pedido quando for Completo pelo moip
		  if (isset($this->request->post['moip_completo'])) {
			$this->data['moip_completo'] = $this->request->post['moip_completo'];
		} else {
			$this->data['moip_completo'] = $this->config->get('moip_completo'); 
		} 
		
				$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['moip_status'])) {
			$this->data['moip_status'] = $this->request->post['moip_status'];
		} else {
			$this->data['moip_status'] = $this->config->get('moip_status');
		}
		
		if (isset($this->request->post['moip_sort_order'])) {
			$this->data['moip_sort_order'] = $this->request->post['moip_sort_order'];
		} else {
			$this->data['moip_sort_order'] = $this->config->get('moip_sort_order');
		}

		$this->id       = 'content';
		$this->template = 'payment/moip.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/moip')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}


		if (!@$this->request->post['moip_email']) {
			$this->error['email'] = $this->language->get('error_email');
		}
		
		if (!@$this->request->post['moip_encryption']) {
			$this->error['encryption'] = $this->language->get('error_encryption');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>