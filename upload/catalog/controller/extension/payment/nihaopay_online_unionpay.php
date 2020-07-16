<?php
class ControllerExtensionPaymentNihaoPayOnlineUnionPay extends Controller {
	public function index() {
		$this->load->language('extension/payment/nihaopay_online_unionpay');
		
		return $this->load->view('extension/payment/nihaopay_online_unionpay', '');
	}

	public function send() {
	    $url = 'https://api.nihaopay.com/v1.2/transactions/securepay';
	    if ($this->config->get('payment_nihaopay_online_unionpay_server') == 'test') {
	        $url = 'https://apitest.nihaopay.com/v1.2/transactions/securepay';
	    }

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$data = array();
		
		$token = $this->config->get('payment_nihaopay_online_unionpay_token');
		
		$data['client_ip'] = $this->request->server['REMOTE_ADDR'];
		$data['description'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');
		$amount=$this->currency->format($order_info['total'], $order_info['currency_code'], 1.00000 , false);
		if($this->config->get('config_currency')=='JPY'){
		    $data['amount']=$amount;
		} else {
		    $data['amount'] = 100 * $amount;
		}
		$data['currency'] = $this->config->get('config_currency');
		$data['vendor'] = 'unionpay';
		$data['reference'] = $this->session->data['order_id'];
		$data['callback_url'] = $this->url->link('extension/payment/nihaopay_online_unionpay/callback','',true);
		$data['ipn_url'] = $this->url->link('extension/payment/nihaopay_online_unionpay/ipn','',true);
		
		
		$curl = curl_init($url);

		curl_setopt_array($curl, array(
// 		    CURLOPT_URL => $url,
		    CURLOPT_RETURNTRANSFER => true,
		    CURLOPT_SSL_VERIFYPEER => false,
		    CURLOPT_ENCODING => "",
		    CURLOPT_MAXREDIRS => 10,
		    CURLOPT_TIMEOUT => 30,
		    CURLOPT_CUSTOMREQUEST => "POST",
		    CURLOPT_POSTFIELDS => http_build_query($data, '', '&'),
		    CURLOPT_HTTPHEADER => array(
		        "authorization: Bearer " . $token,
		        "cache-control: no-cache",
		        "content-type: application/x-www-form-urlencoded",
		        "postman-token: 873bb649-79d1-a03a-c0d0-42cbac4824cf"
		    ),
		));

		$response = curl_exec($curl);
    
		$httpCode = curl_getinfo($curl,CURLINFO_HTTP_CODE);
		
		$json = array();

		if (curl_error($curl)) {
			$json['error'] = 'CURL ERROR: ' . curl_errno($curl) . '::' . curl_error($curl);

			$this->log->write('AUTHNET AIM CURL ERROR: ' . curl_errno($curl) . '::' . curl_error($curl));
		} elseif ($response) {
		    
		    $results = json_decode($response,true);
		   
		    if ($httpCode == '200') {
		        
		       $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('config_order_status_id'));
		        
		       $json['html'] = $response;
		        
		    } else {
		        $json['error'] = $response;
		    }
		} else {
			$json['error'] = 'Empty Gateway Response';

			$this->log->write('AUTHNET AIM CURL ERROR: Empty Gateway Response');
		}

		curl_close($curl);
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function callback() {
	    $this->load->language('extension/payment/nihaopay_online_unionpay');
	    
	    $this->load->model('extension/payment/nihaopay_online_unionpay');
	    
	    $this->load->model('checkout/order');
	    
	    $data = $this->request->get;
	    
	    $token = $this->config->get('payment_nihaopay_online_unionpay_token');
	    
	    $verify_sign=$data['verify_sign'];
	    
	    foreach ($data as $key => $row){
	        $volume[$key]  = $key;
	    }
	    array_multisort($volume, SORT_ASC, $data);
	    $build='';
	    foreach($data as $key=>$value){
	        if($value!='null' && $key!='verify_sign' && $key!='route'){
	            $build = $build.$key.'='.$value.'&';
	        }
	    }
	    
	    if($verify_sign == md5($build.md5($token))){
	        
	        if (isset($data['status']) && $data['status'] == 'success') {
	            
	            $order_id = $data['reference'];
	            
	            $order_info = $this->model_checkout_order->getOrder($order_id);
	            
	            $this->load->model('extension/payment/nihaopay_online_unionpay');
	            
	            $message = "NihaoPay Payment accepted\n";
	            
	            $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('payment_nihaopay_online_unionpay_order_status_id'), $message);
	            
	            $this->response->redirect($this->url->link('checkout/success', '', true));
	            
	        }else{
	            
	            $this->response->redirect($this->url->link('checkout/failure', '', true));
	            
	            $this->log->write(print_r($data,true));
	            
	        }
	    }else{
	        
	        $this->response->redirect($this->url->link('checkout/failure', '', true));
	        $this->log->write('Callback Signature authentication failed.');
	        $this->log->write(print_r($data,true));
	    }
	}
	
	public function ipn() {
	    
	    $data = $this->request->get;
	    
	    $token = $this->config->get('payment_nihaopay_online_unionpay_token');
	    
	    $verify_sign=$data['verify_sign'];
	    
	    foreach ($data as $key => $row){
	        $volume[$key]  = $key;
	    }
	    array_multisort($volume, SORT_ASC, $data);
	    $build='';
	    foreach($data as $key=>$value){
	        if($value!='null' && $key!='verify_sign' && $key!='route'){
	            $build = $build.$key.'='.$value.'&';
	        }
	    }
	    
	    if($verify_sign == md5($build.md5($token))){
	        
	        if (isset($data['status']) && $data['status'] == 'success') {
	            
	            $order_id = $data['reference'];
	            
	            $this->load->model('checkout/order');
	            
	            $order_info = $this->model_checkout_order->getOrder($order_id);
	            
	            $message = "NihaoPay Payment accepted\n";
	            
	            $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('payment_nihaopay_online_unionpay_order_status_id'), $message);
	            
	        }else{
	            
	            $this->log->write(print_r($data,true));
	        }
	        
	    }else{
	        $this->log->write('IPN Signature authentication failed.');
	        $this->log->write(print_r($data,true));
	    }
	    return 'ok';
	}
}