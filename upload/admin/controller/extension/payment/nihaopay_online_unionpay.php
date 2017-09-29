<?php
class ControllerExtensionPaymentNihaoPayOnlineUnionPay extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/nihaopay_online_unionpay');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_nihaopay_online_unionpay', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
		}
		
		$data['callback_url'] = HTTPS_CATALOG . 'index.php?route=extension/payment/nihaopay_online_unionpay/callback';

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['token'])) {
			$data['error_token'] = $this->error['token'];
		} else {
			$data['error_token'] = '';
		}
		
		if (isset($this->error['callback_url'])) {
		    $data['error_callback_url'] = $this->error['callback_url'];
		} else {
		    $data['error_callback_url'] = '';
		}
		
		if (isset($this->error['ipn_url'])) {
		    $data['error_ipn_url'] = $this->error['ipn_url'];
		} else {
		    $data['error_ipn_url'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/nihaopay_online_unionpay', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/nihaopay_online_unionpay', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);

		if (isset($this->request->post['payment_nihaopay_online_unionpay_callback_url'])) {
			$data['payment_nihaopay_online_unionpay_callback_url'] = $this->request->post['payment_nihaopay_online_unionpay_callback_url'];
		} else {
			$data['payment_nihaopay_online_unionpay_callback_url'] = $this->config->get('payment_nihaopay_online_unionpay_callback_url');
		}
		
		if (isset($this->request->post['payment_nihaopay_online_unionpay_ipn_url'])) {
		    $data['payment_nihaopay_online_unionpay_ipn_url'] = $this->request->post['payment_nihaopay_online_unionpay_ipn_url'];
		} else {
		    $data['payment_nihaopay_online_unionpay_ipn_url'] = $this->config->get('payment_nihaopay_online_unionpay_ipn_url');
		}

		if (isset($this->request->post['payment_nihaopay_online_unionpay_token'])) {
			$data['payment_nihaopay_online_unionpay_token'] = $this->request->post['payment_nihaopay_online_unionpay_token'];
		} else {
			$data['payment_nihaopay_online_unionpay_token'] = $this->config->get('payment_nihaopay_online_unionpay_token');
		}


		if (isset($this->request->post['payment_nihaopay_online_unionpay_server'])) {
			$data['payment_nihaopay_online_unionpay_server'] = $this->request->post['payment_nihaopay_online_unionpay_server'];
		} else {
			$data['payment_nihaopay_online_unionpay_server'] = $this->config->get('payment_nihaopay_online_unionpay_server');
		}

		if (isset($this->request->post['payment_nihaopay_online_unionpay_total'])) {
			$data['payment_nihaopay_online_unionpay_total'] = $this->request->post['payment_nihaopay_online_unionpay_total'];
		} else {
			$data['payment_nihaopay_online_unionpay_total'] = $this->config->get('payment_nihaopay_online_unionpay_total');
		}

		if (isset($this->request->post['payment_nihaopay_online_unionpay_order_status_id'])) {
			$data['payment_nihaopay_online_unionpay_order_status_id'] = $this->request->post['payment_nihaopay_online_unionpay_order_status_id'];
		} else {
			$data['payment_nihaopay_online_unionpay_order_status_id'] = $this->config->get('payment_nihaopay_online_unionpay_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_nihaopay_online_unionpay_geo_zone_id'])) {
			$data['payment_nihaopay_online_unionpay_geo_zone_id'] = $this->request->post['payment_nihaopay_online_unionpay_geo_zone_id'];
		} else {
			$data['payment_nihaopay_online_unionpay_geo_zone_id'] = $this->config->get('payment_nihaopay_online_unionpay_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_nihaopay_online_unionpay_status'])) {
			$data['payment_nihaopay_online_unionpay_status'] = $this->request->post['payment_nihaopay_online_unionpay_status'];
		} else {
			$data['payment_nihaopay_online_unionpay_status'] = $this->config->get('payment_nihaopay_online_unionpay_status');
		}

		if (isset($this->request->post['payment_nihaopay_online_unionpay_sort_order'])) {
			$data['payment_nihaopay_online_unionpay_sort_order'] = $this->request->post['payment_nihaopay_online_unionpay_sort_order'];
		} else {
			$data['payment_nihaopay_online_unionpay_sort_order'] = $this->config->get('payment_nihaopay_online_unionpay_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/nihaopay_online_unionpay', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/nihaopay_online_unionpay')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['payment_nihaopay_online_unionpay_callback_url']) {
			$this->error['callback_url'] = $this->language->get('error_callback_url');
		}
		
		if (!$this->request->post['payment_nihaopay_online_unionpay_ipn_url']) {
		    $this->error['ipn_url'] = $this->language->get('error_ipn_url');
		}

		if (!$this->request->post['payment_nihaopay_online_unionpay_token']) {
			$this->error['token'] = $this->language->get('error_token');
		}

		return !$this->error;
	}
}