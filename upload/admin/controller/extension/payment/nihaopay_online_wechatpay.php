<?php
class ControllerExtensionPaymentNihaoPayOnlineWechatPay extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/nihaopay_online_wechatpay');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_nihaopay_online_wechatpay', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
		}
		
		$data['callback_url'] = HTTPS_CATALOG . 'index.php?route=extension/payment/nihaopay_online_wechatpay/callback';

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
			'href' => $this->url->link('extension/payment/nihaopay_online_wechatpay', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/nihaopay_online_wechatpay', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);


		if (isset($this->request->post['payment_nihaopay_online_wechatpay_token'])) {
			$data['payment_nihaopay_online_wechatpay_token'] = $this->request->post['payment_nihaopay_online_wechatpay_token'];
		} else {
			$data['payment_nihaopay_online_wechatpay_token'] = $this->config->get('payment_nihaopay_online_wechatpay_token');
		}


		if (isset($this->request->post['payment_nihaopay_online_wechatpay_server'])) {
			$data['payment_nihaopay_online_wechatpay_server'] = $this->request->post['payment_nihaopay_online_wechatpay_server'];
		} else {
			$data['payment_nihaopay_online_wechatpay_server'] = $this->config->get('payment_nihaopay_online_wechatpay_server');
		}

		if (isset($this->request->post['payment_nihaopay_online_wechatpay_total'])) {
			$data['payment_nihaopay_online_wechatpay_total'] = $this->request->post['payment_nihaopay_online_wechatpay_total'];
		} else {
			$data['payment_nihaopay_online_wechatpay_total'] = $this->config->get('payment_nihaopay_online_wechatpay_total');
		}

		if (isset($this->request->post['payment_nihaopay_online_wechatpay_order_status_id'])) {
			$data['payment_nihaopay_online_wechatpay_order_status_id'] = $this->request->post['payment_nihaopay_online_wechatpay_order_status_id'];
		} else {
			$data['payment_nihaopay_online_wechatpay_order_status_id'] = $this->config->get('payment_nihaopay_online_wechatpay_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_nihaopay_online_wechatpay_geo_zone_id'])) {
			$data['payment_nihaopay_online_wechatpay_geo_zone_id'] = $this->request->post['payment_nihaopay_online_wechatpay_geo_zone_id'];
		} else {
			$data['payment_nihaopay_online_wechatpay_geo_zone_id'] = $this->config->get('payment_nihaopay_online_wechatpay_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_nihaopay_online_wechatpay_status'])) {
			$data['payment_nihaopay_online_wechatpay_status'] = $this->request->post['payment_nihaopay_online_wechatpay_status'];
		} else {
			$data['payment_nihaopay_online_wechatpay_status'] = $this->config->get('payment_nihaopay_online_wechatpay_status');
		}

		if (isset($this->request->post['payment_nihaopay_online_wechatpay_sort_order'])) {
			$data['payment_nihaopay_online_wechatpay_sort_order'] = $this->request->post['payment_nihaopay_online_wechatpay_sort_order'];
		} else {
			$data['payment_nihaopay_online_wechatpay_sort_order'] = $this->config->get('payment_nihaopay_online_wechatpay_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/nihaopay_online_wechatpay', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/nihaopay_online_wechatpay')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['payment_nihaopay_online_wechatpay_token']) {
			$this->error['token'] = $this->language->get('error_token');
		}

		return !$this->error;
	}
}
