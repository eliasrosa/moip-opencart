<?php
class ControllerPaymentMoip extends Controller {
	protected function index() {
    	$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_back'] = $this->language->get('button_back');

		if (!$this->config->get('moip_test')) {
    		$this->data['action'] = 'https://www.moip.com.br/PagamentoMoIP.do';
  		} else {
			$this->data['action'] ='https://desenvolvedor.moip.com.br/sandbox/PagamentoMoIP.do';
		}		
		
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		$this->data['nometranzacao'] = $this->config->get('moip_encryption');
		$this->data['business'] = $this->config->get('moip_email');
		$this->data['item_name'] = $this->config->get('config_store');				
		$this->data['currency_code'] = $order_info['currency'];
		$this->data['amount'] = $this->currency->format($order_info['total'], $order_info['currency'], $order_info['value'], FALSE);
		$this->data['first_name'] = htmlentities($order_info['payment_firstname'], ENT_COMPAT, 'UTF-8');
		$this->data['last_name'] = htmlentities($order_info['payment_lastname'], ENT_COMPAT, 'UTF-8');
		$this->data['address1'] = htmlentities($order_info['payment_address_1'], ENT_COMPAT, 'UTF-8');
		$this->data['address2'] = htmlentities($order_info['payment_address_2'], ENT_COMPAT, 'UTF-8');
		if(isset($order_info['payment_numero'])){
			$this->data['numero'] = $order_info['payment_numero'];
		}
		if(isset($order_info['payment_bairro'])){
			$this->data['bairro'] = $order_info['payment_bairro'];
		}
		$this->data['city'] = htmlentities($order_info['payment_city'], ENT_COMPAT, 'UTF-8');
		$this->data['zip'] = $order_info['payment_postcode'];
		$this->data['country'] = $order_info['payment_country'];
		$this->data['notify_url'] = HTTPS_SERVER . 'payment/moip/callback&order_id';
		$this->session->data['order_id'];
		$this->data['codipedido'] = $this->session->data['order_id'];
		$this->data['email'] = $order_info['email'];
		$this->data['invoice'] = $this->session->data['order_id'] . ' - ' . $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'];
		
		/* Pega o id do país */		
		$this->load->model('localisation/country');
    	$paises = $this->model_localisation_country->getCountries();		
		foreach ($paises as $country) {
			if($country['name']==$order_info['payment_country']){
				$codigodopais = $country['country_id'];
			}
		}

		/* Com id do país pega o code da cidade */
		$this->load->model('localisation/zone');
    	$results = $this->model_localisation_zone->getZonesByCountryId($codigodopais);

		foreach ($results as $result) {
        	if($result['name']==$order_info['payment_zone']){
				$this->data['estado'] =$result['code'];
			}
    	} 

		if(isset($order_info['ddd'])){
			$this->data['ddd'] = $order_info['ddd'];
		} else {
			$ntelefone = preg_replace("/[^0-9]/", "", $order_info['telephone']);
			if(strlen($ntelefone) >= 10){	
				$ntelefone = ltrim($ntelefone, "0");
				$this->data['ddd'] = substr($ntelefone, 0, 2);
				$this->data['telephone'] = substr($ntelefone, 2,11);
			} else {
				$this->data['telephone'] = substr($ntelefone, 2,11);
			}
		}
		$this->data['return'] = HTTPS_SERVER . 'checkout/success';
		$this->data['cancel_return'] = HTTPS_SERVER . 'checkout/payment';
		$this->data['back'] = HTTPS_SERVER . 'checkout/payment';
		$this->data['mailpg'] = $this->config->get('moip_email');
		$this->data['products'] = array();
	
		foreach ($this->cart->getProducts() as $product) {
			$option_data = array();		
			foreach ($product['option'] as $option) {
        		$option_data[] = array(
          			'name'  => $option['name'],
          			'value' => $option['value']
        		);
      		}
			
      		$this->data['products'][] = array(
				'descricao'   => htmlentities($product['name'], ENT_COMPAT, 'UTF-8'),
				'valor'       => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))),
				//'disconto'  => ($product['discount'] ? $this->currency->format($this->tax->calculate($product['price'] - $product['discount'], $product['tax_class_id'], $this->config->get('config_tax'))) : NULL),
				'quantidade'  => $product['quantity'],
				'option'      => $option_data,
				'id'          => $product['product_id'],	
				'peso'        => $this->weight->convert($product['weight'], $product['weight_class'], $this->config->get('config_weight_class')),
				//'discontos' => ($product['discount'] ? $this->currency->format($product['price'] - $product['discount']) : NULL)
      		); 
    	} 
		
		if (isset($this->session->data['coupon'])) {
			$this->load->model('checkout/coupon');
			$coupon = $this->model_checkout_coupon->getCoupon($this->session->data['coupon']);
			if ($coupon) {
				$desconto = preg_replace("/[^0-9]/", "", $this->currency->format($coupon['discount'])); //valor do desconto
				$valototal = preg_replace("/[^0-9]/", "", $this->currency->format($this->cart->getTotal())); //total da compra
				$desctotalcompra = preg_replace("/[^0-9]/", "", $this->currency->format($coupon['total'])); //valo da compra que e aceito o desconto
				if($valototal>=$desctotalcompra){
					$this->data['cupomnome'] = $coupon['name'];
					if($coupon['type']=='P' and $coupon['shipping']==0){
						$valorddescon = $this->currency->format(($coupon['discount']/100)*$this->cart->getTotal());
						$this->data['cupondedesconto'] = str_replace("[^0-9]", "", $valorddescon); 
					} else if ($coupon['type']=='F' and $coupon['shipping']==0){
						$this->data['cupondedesconto'] = $desconto;
					} else if ($coupon['shipping']==1){
						$this->data['fretegratis'] = true;
					}
				} 
			}
		}		
		
		$this->data['continue'] = HTTPS_SERVER . 'checkout/success';
		
		if ($this->request->get['route'] != 'checkout/guest_step_3') {
			$this->data['back'] = HTTPS_SERVER . 'checkout/payment';
		} else {
			$this->data['back'] = HTTPS_SERVER . 'checkout/guest_step_2';
		}
		
		$this->id = 'payment';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/moip.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/moip.tpl';
		} else {
			$this->template = 'default/template/payment/moip.tpl';
		}	
		
		$this->render();
					
	}
	
	public function confirm() {
		$this->load->language('payment/moip');
		$this->load->model('checkout/order');	
		$comment  = $this->language->get('text_instruction') . "\n\n";
		$comment .= $this->language->get('text_payment');
		$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('config_order_status_id'), $comment);
		if (isset($this->session->data['order_id'])) {
			$this->cart->clear();
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);	
			unset($this->session->data['coupon']);
		}
	}
	
	public function callback() {
		if (isset($this->request->post['retornoalto']) AND isset($this->request->post['id_transacao'])){
			$this->load->language('payment/moip');
			$reference = $this->request->post['id_transacao'];
			$this->load->model('checkout/order');
			$order_info = $this->model_checkout_order->getOrder($reference);
			if ($this->request->post['retornoalto'] == "sim") {
				$valorpago ='R$ '.number_format($this->request->post['valor'], 2, ',', '.');
				if ($order_info) {
					$this->cart->clear();
					/* Iniciado */
					if ($this->request->post['status_pagamento'] == '2') {
						/* Recupera o código do status aguardando pagto */
						$aguardando = $this->config->get('moip_aguardando');
						$comentaraio=sprintf($this->language->get('text_aguardandopg'), $this->request->post['tipo_pagamento']).$this->language->get('text_auterastatus');
						/* Atualiza o status do pedido */
						/* Atualiza o histórico do pedido */
						$this->model_checkout_order->update((int)$reference, (int)$aguardando, $comentaraio,true);
						$this->model_checkout_order->confirm((int)$reference,(int)$aguardando, $comentaraio);
					/* Cancelado */
					} elseif ($this->request->post['status_pagamento'] == '5') {
						/* recupera o código do status em cancelado */
						$cancelado = $this->config->get('moip_cancelado');
						$comentaraio=sprintf($this->language->get('text_canceladouol'), $this->request->post['tipo_pagamento']).$this->language->get('text_auterastatus');
						/* Atualiza o status do pedido */
						/* Atualiza o histórico do pedido */
						$this->model_checkout_order->update((int)$reference, (int)$cancelado, $comentaraio,true);
						$this->model_checkout_order->confirm((int)$reference,(int)$cancelado, $comentaraio);
					/* Autorizado */																			 
					} elseif ($this->request->post['status_pagamento'] == '1') {
						/* Recupera o código do status aprovado */
						$aprovado = $this->config->get('moip_aprovado');
						$comentaraio=sprintf($this->language->get('text_aprovandouol'), $this->request->post['tipo_pagamento'],$valorpago).$this->language->get('text_auterastatus');
						/* Atualiza o status do pedido */
						/* Atualiza o histórico do pedido */
						$this->model_checkout_order->update((int)$reference, (int)$aprovado, $comentaraio,true);
						$this->model_checkout_order->confirm((int)$reference,(int)$aprovado, $comentaraio);
					/* Em análise */
					} elseif ($this->request->post['status_pagamento'] == '6') {
						/* Recupera o código do status aprovado */
						$aguardando = $this->config->get('moip_aguardando');
						$comentaraio=sprintf($this->language->get('text_aprovemanalize'), $this->request->post['tipo_pagamento'],$valorpago).$this->language->get('text_auterastatus');
						$this->model_checkout_order->update((int)$reference, (int)$aguardando, $comentaraio);       
					/* Concluido */
					} elseif ($this->request->post['status_pagamento'] == '4') {
						/* Recupera o código do status aprovado */
						$aguardando = $this->config->get('moip_aguardando');
						$comentaraio=sprintf($this->language->get('text_pagcompleto'), $this->request->post['tipo_pagamento'],$valorpago).$this->language->get('text_auterastatus');
						$this->model_checkout_order->update((int)$reference, (int)$aguardando, $comentaraio);  
					}	  
				}
			}
		}
	}
}
?>