<?php
class ControllerPaymentCielo extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('payment/cielo');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('payment/cielo');

        $this->getList();
    }

    protected function getList() {

        if (isset($this->request->get['filter_order_id'])) {
            $filter_order_id = $this->request->get['filter_order_id'];
        } else {
            $filter_order_id = null;
        }

        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
        }

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'prazo_captura';
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

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

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
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_payment'),
            'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('payment/cielo', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

        $data['edit'] = $this->url->link('payment/cielo/edit', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['captura'] = $this->url->link('payment/cielo/captura', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $data['cancel'] = $this->url->link('payment/cielo/cancela', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['transactions'] = array();

        $filter_data = array(
            'filter_order_id' => $filter_order_id,
            'filter_name'     => $filter_name,
            'filter_status'   => $filter_status,
            'sort'            => $sort,
            'order'           => $order,
            'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit'           => $this->config->get('config_limit_admin')
        );

        $events_total = $this->model_payment_cielo->getTotalTransactions($filter_data);

        $results = $this->model_payment_cielo->getTransactions($filter_data);

        $data['status_options'] = array();

        for($i=0; $i <= 10; $i++) {
            $status = 'cielo_status_' . $i;
            if($this->language->get($status) != $status) {
                $data['status_options'][$i] = $this->language->get($status);
            }
        }

        foreach ($results as $result) {
            $hoje = new \DateTime();

            $prazo_captura = new \DateTime();
            $prazo_captura->setTimestamp(strtotime($result['autorizacao_data']) + (60*60*24*5));  // 5 dias

            $prazo_captura = $hoje->diff($prazo_captura);

            $data['transactions'][] = array(
                'order_cielo_id' => $result['order_cielo_id'],
                'order_id' => $result['order_id'],
                'name'       => $result['name'],
                'total'     => $result['total'],
                'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
                'prazo_captura' => ($result['cielo_status'] == '4' ) ? $prazo_captura->format('%d dias, %Hh e %imin') : '',
                'status_code'     => $result['cielo_status'],
                'status'     => isset($data['status_options'][$result['cielo_status']]) ? $data['status_options'][$result['cielo_status']] : $this->language->get('text_disabled'),
                'view'       => $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
                'customer'     => $this->url->link('sale/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, 'SSL'),
                'cancel'       => $this->url->link('payment/cielo/cancela', 'token=' . $this->session->data['token'] . '&order_cielo_id=' . $result['order_cielo_id'] . $url, 'SSL'),
                'captura'       => $this->url->link('payment/cielo/captura', 'token=' . $this->session->data['token'] . '&order_cielo_id=' . $result['order_cielo_id'] . $url, 'SSL')
            );
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_order_id'] = $this->language->get('column_order_id');
        $data['column_name'] = $this->language->get('column_name');
        $data['column_total'] = $this->language->get('column_total');
        $data['column_date'] = $this->language->get('column_date');
        $data['column_prazo_captura'] = $this->language->get('column_prazo_captura');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_action'] = $this->language->get('column_action');

        $data['entry_order_id'] = $this->language->get('entry_order_id');
        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_status'] = $this->language->get('entry_status');

        $data['button_view'] = $this->language->get('button_view');
        $data['button_customer'] = $this->language->get('button_customer');
        $data['button_captura'] = $this->language->get('button_captura');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_edit'] = $this->language->get('button_edit');

        $data['token'] = $this->session->data['token'];

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

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_order_id'] = $this->url->link('payment/cielo', 'token=' . $this->session->data['token'] . '&sort=o.order_id' . $url, 'SSL');
        $data['sort_total'] = $this->url->link('payment/cielo', 'token=' . $this->session->data['token'] . '&sort=o.total' . $url, 'SSL');
        $data['sort_name'] = $this->url->link('payment/cielo', 'token=' . $this->session->data['token'] . '&sort=c.firstname' . $url, 'SSL');
        $data['sort_date'] = $this->url->link('payment/cielo', 'token=' . $this->session->data['token'] . '&sort=oc.autorizacao_data' . $url, 'SSL');
        $data['sort_prazo_captura'] = $this->url->link('payment/cielo', 'token=' . $this->session->data['token'] . '&sort=prazo_captura' . $url, 'SSL');
        $data['sort_status'] = $this->url->link('payment/cielo', 'token=' . $this->session->data['token'] . '&sort=oc.status' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $events_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('payment/cielo', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($events_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($events_total - $this->config->get('config_limit_admin'))) ? $events_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $events_total, ceil($events_total / $this->config->get('config_limit_admin')));

        $data['filter_order_id'] = $filter_order_id;
        $data['filter_name'] = $filter_name;
        $data['filter_status'] = $filter_status;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('payment/cielo_list.tpl', $data));
    }

    public function edit() {
        $this->load->language('payment/cielo');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('cielo', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_all_zones'] = $this->language->get('text_all_zones');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_loja'] = $this->language->get('text_loja');
        $data['text_administradora'] = $this->language->get('text_administradora');

        $data['entry_total'] = $this->language->get('entry_total');
        $data['help_total'] = $this->language->get('help_total');
        $data['entry_afiliacao'] = $this->language->get('entry_afiliacao');
        $data['help_afiliacao'] = $this->language->get('help_afiliacao');
        $data['entry_chave'] = $this->language->get('entry_chave');
        $data['help_chave'] = $this->language->get('help_chave');
        $data['entry_teste'] = $this->language->get('entry_teste');
        $data['help_teste'] = $this->language->get('help_teste');
        $data['entry_parcelamento'] = $this->language->get('entry_parcelamento');
        $data['entry_cartao_visa'] = $this->language->get('entry_cartao_visa');
        $data['entry_cartao_mastercard'] = $this->language->get('entry_cartao_mastercard');
        $data['entry_cartao_diners'] = $this->language->get('entry_cartao_diners');
        $data['entry_cartao_discover'] = $this->language->get('entry_cartao_discover');
        $data['entry_cartao_elo'] = $this->language->get('entry_cartao_elo');
        $data['entry_cartao_amex'] = $this->language->get('entry_cartao_amex');
        $data['entry_parcela_maximo'] = $this->language->get('entry_parcela_maximo');
        $data['entry_parcela_minimo'] = $this->language->get('entry_parcela_minimo');
        $data['entry_parcela_semjuros'] = $this->language->get('entry_parcela_semjuros');
        $data['entry_parcela_juros'] = $this->language->get('entry_parcela_juros');
        $data['entry_aprovado'] = $this->language->get('entry_aprovado');
        $data['entry_nao_aprovado'] = $this->language->get('entry_nao_aprovado');
        $data['entry_capturado'] = $this->language->get('entry_capturado');
        $data['entry_cancelado'] = $this->language->get('entry_cancelado');
        $data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');

        $data['entry_captura'] = $this->language->get('entry_captura');
        $data['entry_autorizacao'] = $this->language->get('entry_autorizacao');
        $data['text_nao_autorizar'] = $this->language->get('text_nao_autorizar');
        $data['text_somente_autenticada'] = $this->language->get('text_somente_autenticada');
        $data['text_autenticada_nao_autenticada'] = $this->language->get('text_autenticada_nao_autenticada');
        $data['text_sem_autenticacao'] = $this->language->get('text_sem_autenticacao');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['cielo_afiliacao'])) {
            $data['error_afiliacao'] = $this->error['cielo_afiliacao'];
        } else {
            $data['error_afiliacao'] = '';
        }

        if (isset($this->error['cielo_chave'])) {
            $data['error_chave'] = $this->error['cielo_chave'];
        } else {
            $data['error_chave'] = '';
        }

        if (isset($this->error['cielo_parcela_maximo'])) {
            $data['error_parcela_maximo'] = $this->error['cielo_parcela_maximo'];
        } else {
            $data['error_parcela_maximo'] = '';
        }

        if (isset($this->error['cielo_parcela_semjuros'])) {
            $data['error_parcela_semjuros'] = $this->error['cielo_parcela_semjuros'];
        } else {
            $data['error_parcela_semjuros'] = '';
        }

        if (isset($this->error['cielo_parcela_juros'])) {
            $data['error_parcela_juros'] = $this->error['cielo_parcela_juros'];
        } else {
            $data['error_parcela_juros'] = '';
        }

        if (isset($this->error['cielo_parcela_minimo'])) {
            $data['error_parcela_minimo'] = $this->error['cielo_parcela_minimo'];
        } else {
            $data['error_parcela_minimo'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_payment'),
            'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('payment/cielo', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['action'] = $this->url->link('payment/cielo/edit', 'token=' . $this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->post['cielo_total'])) {
            $data['cielo_total'] = $this->request->post['cielo_total'];
        } else {
            $data['cielo_total'] = $this->config->get('cielo_total');
        }

        if (isset($this->request->post['cielo_afiliacao'])) {
            $data['cielo_afiliacao'] = $this->request->post['cielo_afiliacao'];
        } else {
            $data['cielo_afiliacao'] = $this->config->get('cielo_afiliacao');
        }

        if (isset($this->request->post['cielo_chave'])) {
            $data['cielo_chave'] = $this->request->post['cielo_chave'];
        } else {
            $data['cielo_chave'] = $this->config->get('cielo_chave');
        }

        if (isset($this->request->post['cielo_teste'])) {
            $data['cielo_teste'] = $this->request->post['cielo_teste'];
        } else {
            $data['cielo_teste'] = $this->config->get('cielo_teste');
        }

        if (isset($this->request->post['cielo_parcelamento'])) {
            $data['cielo_parcelamento'] = $this->request->post['cielo_parcelamento'];
        } else {
            $data['cielo_parcelamento'] = $this->config->get('cielo_parcelamento');
        }

        if (isset($this->request->post['cielo_cartao_visa'])) {
            $data['cielo_cartao_visa'] = $this->request->post['cielo_cartao_visa'];
        } else {
            $data['cielo_cartao_visa'] =  $this->config->get('cielo_cartao_visa');
        }

        if (isset($this->request->post['cielo_cartao_mastercard'])) {
            $data['cielo_cartao_mastercard'] = $this->request->post['cielo_cartao_mastercard'];
        } else {
            $data['cielo_cartao_mastercard'] =  $this->config->get('cielo_cartao_mastercard');
        }

        if (isset($this->request->post['cielo_cartao_diners'])) {
            $data['cielo_cartao_diners'] = $this->request->post['cielo_cartao_diners'];
        } else {
            $data['cielo_cartao_diners'] =  $this->config->get('cielo_cartao_diners');
        }

        if (isset($this->request->post['cielo_cartao_discover'])) {
            $data['cielo_cartao_discover'] = $this->request->post['cielo_cartao_discover'];
        } else {
            $data['cielo_cartao_discover'] =  $this->config->get('cielo_cartao_discover');
        }

        if (isset($this->request->post['cielo_cartao_elo'])) {
            $data['cielo_cartao_elo'] = $this->request->post['cielo_cartao_elo'];
        } else {
            $data['cielo_cartao_elo'] =  $this->config->get('cielo_cartao_elo');
        }

        if (isset($this->request->post['cielo_cartao_amex'])) {
            $data['cielo_cartao_amex'] = $this->request->post['cielo_cartao_amex'];
        } else {
            $data['cielo_cartao_amex'] =  $this->config->get('cielo_cartao_amex');
        }

        if (isset($this->request->post['cielo_parcela_maximo'])) {
            $data['cielo_parcela_maximo'] = $this->request->post['cielo_parcela_maximo'];
        } else {
            $data['cielo_parcela_maximo'] =  $this->config->get('cielo_parcela_maximo');
        }

        if (isset($this->request->post['cielo_parcela_semjuros'])) {
            $data['cielo_parcela_semjuros'] = $this->request->post['cielo_parcela_semjuros'];
        } else {
            $data['cielo_parcela_semjuros'] = $this->config->get('cielo_parcela_semjuros');
        }

        if (isset($this->request->post['cielo_parcela_minimo'])) {
            $data['cielo_parcela_minimo'] = $this->request->post['cielo_parcela_minimo'];
        } else {
            $data['cielo_parcela_minimo'] = $this->config->get('cielo_parcela_minimo');
        }

        if (isset($this->request->post['cielo_parcela_juros'])) {
            $data['cielo_parcela_juros'] = $this->request->post['cielo_parcela_juros'];
        } else {
            $data['cielo_parcela_juros'] = $this->config->get('cielo_parcela_juros');
        }

        if (isset($this->request->post['cielo_autorizacao'])) {
            $data['cielo_autorizacao'] = $this->request->post['cielo_autorizacao'];
        } else {
            $data['cielo_autorizacao'] = $this->config->get('cielo_autorizacao');
        }

        if (isset($this->request->post['cielo_captura'])) {
            $data['cielo_captura'] = $this->request->post['cielo_captura'];
        } else {
            $data['cielo_captura'] = $this->config->get('cielo_captura');
        }

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        if (isset($this->request->post['cielo_aprovado_id'])) {
            $data['cielo_aprovado_id'] = $this->request->post['cielo_aprovado_id'];
        } else {
            $data['cielo_aprovado_id'] = $this->config->get('cielo_aprovado_id');
        }

        if (isset($this->request->post['cielo_nao_aprovado_id'])) {
            $data['cielo_nao_aprovado_id'] = $this->request->post['cielo_nao_aprovado_id'];
        } else {
            $data['cielo_nao_aprovado_id'] = $this->config->get('cielo_nao_aprovado_id');
        }

        if (isset($this->request->post['cielo_capturado_id'])) {
            $data['cielo_capturado_id'] = $this->request->post['cielo_capturado_id'];
        } else {
            $data['cielo_capturado_id'] = $this->config->get('cielo_capturado_id');
        }

        if (isset($this->request->post['cielo_cancelado_id'])) {
            $data['cielo_cancelado_id'] = $this->request->post['cielo_cancelado_id'];
        } else {
            $data['cielo_cancelado_id'] = $this->config->get('cielo_cancelado_id');
        }

        $this->load->model('localisation/geo_zone');

        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        if (isset($this->request->post['cielo_geo_zone_id'])) {
            $data['cielo_geo_zone_id'] = $this->request->post['cielo_geo_zone_id'];
        } else {
            $data['cielo_geo_zone_id'] = $this->config->get('cielo_geo_zone_id');
        }

        if (isset($this->request->post['cielo_status'])) {
            $data['cielo_status'] = $this->request->post['cielo_status'];
        } else {
            $data['cielo_status'] = $this->config->get('cielo_status');
        }

        if (isset($this->request->post['cielo_sort_order'])) {
            $data['cielo_sort_order'] = $this->request->post['cielo_sort_order'];
        } else {
            $data['cielo_sort_order'] = $this->config->get('cielo_sort_order');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('payment/cielo_form.tpl',$data));
    }

    private function validate() {
        if (!$this->user->hasPermission('modify', 'payment/cielo')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['cielo_afiliacao']) {
            $this->error['cielo_afiliacao'] = $this->language->get('error_afiliacao');
        }

        if (!$this->request->post['cielo_chave']) {
            $this->error['cielo_chave'] = $this->language->get('error_chave');
        }

        if (!$this->request->post['cielo_parcela_semjuros']) {
            $this->error['cielo_parcela_semjuros'] = $this->language->get('error_parcela_semjuros');
        }

        if (!$this->request->post['cielo_parcela_juros']) {
            $this->error['cielo_parcela_juros'] = $this->language->get('error_parcela_juros');
        }

        if (!isset($this->request->post['cielo_parcela_minimo']) || $this->request->post['cielo_parcela_minimo'] < 5) {
            $this->error['cielo_parcela_minimo'] = $this->language->get('error_parcela_minimo');
        }

        return !$this->error;
    }

    private function validateCancelamento() {
        if (!$this->user->hasPermission('modify', 'payment/cielo')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!isset($this->request->post['selected']) && !isset($this->request->get['order_cielo_id'])) {
            $this->error['warning'] = $this->language->get('error_order_cielo_id');
        }

        return !$this->error;
    }

    private function validateCaptura() {
        if (!$this->user->hasPermission('modify', 'payment/cielo')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!isset($this->request->post['selected']) && !isset($this->request->get['order_cielo_id'])) {
            $this->error['warning'] = $this->language->get('error_order_cielo_id');
        }

        return !$this->error;
    }



    public function captura() {
        $this->load->library('cielo');

        $this->load->language('payment/cielo');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('payment/cielo');

        if ($this->validateCaptura()) {

            $order_cielo_ids = array();

            if(isset($this->request->post['selected'])) {
                $order_cielo_ids = $this->request->post['selected'];
            } else if(isset($this->request->get['order_cielo_id'])) {
                $order_cielo_ids = array($this->request->get['order_cielo_id']);
            }

            foreach($order_cielo_ids as $id) {
                $transacao_info = $this->model_payment_cielo->getTransaction($id);

                $transacao = new \Tritoq\Payment\Cielo\Transacao();
                $transacao->setTid($transacao_info['tid']);

                $loja = new \Tritoq\Payment\Cielo\Loja();
                $loja->setAmbiente(\Tritoq\Payment\Cielo\Loja::AMBIENTE_PRODUCAO)
                     ->setChave($this->config->get('cielo_chave'))
                     ->setNumeroLoja($this->config->get('cielo_afiliacao'))
                     ->setSslCertificado(DIR_SYSTEM . 'library/Tritoq/Payment/Cielo/ssl/ecommerce.cielo.com.br.cer');

                if($this->config->get('cielo_teste') == '1') {

                    $loja->setAmbiente(\Tritoq\Payment\Cielo\Loja::AMBIENTE_TESTE)
                         ->setChave(\Tritoq\Payment\Cielo\Loja::LOJA_CHAVE_AMBIENTE_TESTE)
                         ->setNumeroLoja(\Tritoq\Payment\Cielo\Loja::LOJA_NUMERO_AMBIENTE_TESTE);
                }

                $service = new \Tritoq\Payment\Cielo\CieloService(array(
                                                                      'loja' => $loja,
                                                                      'transacao' => $transacao,
                                                                  ));

                // Setando o tipo de versão de conexão SSL
                $service->setSslVersion(4);

                $service->doCaptura();

                if($transacao->getStatus() == \Tritoq\Payment\Cielo\Transacao::STATUS_CAPTURADA) {

                    $requisicoes = $transacao->getRequisicoes(\Tritoq\Payment\Cielo\Transacao::REQUISICAO_TIPO_CAPTURA);

                    foreach($requisicoes as $requisicao) {

                        $xmlRetorno = $requisicao->getXmlRetorno();
                        $data = $this->model_payment_cielo->parseData($xmlRetorno);

                        $this->model_payment_cielo->updateTransaction($id, $data);
                        $this->addOrderHistory($transacao_info['pedido_numero'], $this->config->get('cielo_capturado_id'), $data['captura_mensagem'], true);
                    }


                    $this->session->data['success'] = $this->language->get('text_success');
                } else {

                    $requisicoes = $transacao->getRequisicoes(\Tritoq\Payment\Cielo\Transacao::REQUISICAO_TIPO_CAPTURA);

                    foreach($requisicoes as $requisicao) {

                        $errors = $requisicao->getErrors();

                        if(!empty($errors)) {
                            $this->error = array_merge($this->error, $errors);
                        }
                    }
                }
            }

            $url = '';

            if (isset($this->request->get['filter_order_id'])) {
                $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
            }

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('payment/cielo', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    public function cancela() {

        $this->load->library('cielo');

        $this->load->language('payment/cielo');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('payment/cielo');

        if ($this->validateCancelamento()) {

            $order_cielo_ids = array();

            if(isset($this->request->post['selected'])) {
                $order_cielo_ids = $this->request->post['selected'];
            } else if(isset($this->request->get['order_cielo_id'])) {
                $order_cielo_ids = array($this->request->get['order_cielo_id']);
            }

            foreach($order_cielo_ids as $id) {
                $transacao_info = $this->model_payment_cielo->getTransaction($id);

                $transacao = new \Tritoq\Payment\Cielo\Transacao();
                $transacao->setTid($transacao_info['tid']);

                $loja = new \Tritoq\Payment\Cielo\Loja();
                $loja->setAmbiente(\Tritoq\Payment\Cielo\Loja::AMBIENTE_PRODUCAO)
                     ->setChave($this->config->get('cielo_chave'))
                     ->setNumeroLoja($this->config->get('cielo_afiliacao'))
                     ->setSslCertificado(DIR_SYSTEM . 'library/Tritoq/Payment/Cielo/ssl/ecommerce.cielo.com.br.cer');

                if($this->config->get('cielo_teste') == '1') {

                    $loja->setAmbiente(\Tritoq\Payment\Cielo\Loja::AMBIENTE_TESTE)
                         ->setChave(\Tritoq\Payment\Cielo\Loja::LOJA_CHAVE_AMBIENTE_TESTE)
                         ->setNumeroLoja(\Tritoq\Payment\Cielo\Loja::LOJA_NUMERO_AMBIENTE_TESTE);
                }

                $service = new \Tritoq\Payment\Cielo\CieloService(array(
                                                                      'loja' => $loja,
                                                                      'transacao' => $transacao,
                                                                  ));

                // Setando o tipo de versão de conexão SSL
                $service->setSslVersion(4);

                $service->doCancela();

                if($transacao->getStatus() == \Tritoq\Payment\Cielo\Transacao::STATUS_CANCELADA) {

                    $requisicoes = $transacao->getRequisicoes(\Tritoq\Payment\Cielo\Transacao::REQUISICAO_TIPO_CANCELA);

                    foreach($requisicoes as $requisicao) {

                        $xmlRetorno = $requisicao->getXmlRetorno();
                        $data = $this->model_payment_cielo->parseData($xmlRetorno);

                        $this->model_payment_cielo->updateTransaction($id, $data);
                        $this->addOrderHistory($transacao_info['pedido_numero'], $this->config->get('cielo_cancelado_id'), $data['cancelamento_mensagem'], true);
                    }

                    $this->session->data['success'] = $this->language->get('text_success');
                } else {

                    $requisicoes = $transacao->getRequisicoes(\Tritoq\Payment\Cielo\Transacao::REQUISICAO_TIPO_CANCELA);

                    foreach($requisicoes as $requisicao) {

                        $errors = $requisicao->getErrors();

                        if(!empty($errors)) {
                            $this->error = array_merge($this->error, $errors);
                        }
                    }
                }
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_order_id'])) {
                $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
            }

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('payment/cielo', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    private function addOrderHistory($order_id, $order_status_id, $comment, $notify = true) {
        // API
        $this->load->model('user/api');

        $api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));


        if ($api_info) {
            $curl = curl_init();

            // Set SSL if required
            if (substr(HTTPS_CATALOG, 0, 5) == 'https') {
                curl_setopt($curl, CURLOPT_PORT, 443);
            }

            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLINFO_HEADER_OUT, true);
            curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_URL, HTTPS_CATALOG . 'index.php?route=api/login');
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($api_info));

            $json = curl_exec($curl);

            if (!$json) {
                $this->error['warning'] = sprintf($this->language->get('error_curl'), curl_error($curl), curl_errno($curl));
            } else {
                $response = json_decode($json, true);

                if (isset($response['cookie'])) {
                    $this->session->data['cookie'] = $response['cookie'];
                }

                curl_close($curl);
            }
        }

        if (isset($this->session->data['cookie'])) {
            $curl = curl_init();

            // Set SSL if required
            if (substr(HTTPS_CATALOG, 0, 5) == 'https') {
                curl_setopt($curl, CURLOPT_PORT, 443);
            }

            $data = array(
                'order_status_id' => $order_status_id,
                'notify' => $notify,
                'append' => 0,
                'comment' => $comment,
            );

            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLINFO_HEADER_OUT, true);
            curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_URL, HTTPS_CATALOG . 'index.php?route=api/order/history&order_id=' . $order_id);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($curl, CURLOPT_COOKIE, session_name() . '=' . $this->session->data['cookie'] . ';');

            $json = curl_exec($curl);

            if (!$json) {
                $this->error['warning'] = sprintf($this->language->get('error_curl'), curl_error($curl), curl_errno($curl));
            } else {
                $response = json_decode($json, true);

                curl_close($curl);

                if (isset($response['error'])) {
                    $this->error['warning'] = $response['error'];
                }
            }
        }
    }

    public function install() {
        $this->db->query("
            CREATE TABLE " . DB_PREFIX . "order_cielo (
              order_cielo_id int(11) NOT NULL AUTO_INCREMENT,
              tid varchar(40),
              pan varchar(255),
              status varchar(12),
              pedido_numero varchar(20),
              pedido_valor int(11),
              pedido_moeda int(3),
              pedido_data varchar(19),
              pedido_idioma char(2),
              pagamento_bandeira varchar(20),
              pagamento_produto char(1),
              pagamento_parcelas char(2),
              autenticacao_codigo char(2),
              autenticacao_mensagem varchar(255),
              autenticacao_data varchar(19),
              autenticacao_valor int(11),
              autenticacao_eci char(2),
              autorizacao_codigo char(2),
              autorizacao_mensagem varchar(255),
              autorizacao_data varchar(19),
              autorizacao_valor int(11),
              autorizacao_lr char(2),
              autorizacao_arp varchar(20),
              autorizacao_nsu varchar(20),
              captura_codigo char(2),
              captura_mensagem varchar(255),
              captura_data varchar(19),
              captura_valor int(11),
              cancelamento_codigo char(2),
              cancelamento_mensagem varchar(255),
              cancelamento_data varchar(19),
              cancelamento_valor int(11),
              PRIMARY KEY (order_cielo_id),
              KEY tid (tid)
            ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");
    }

    public function uninstall() {
        $this->db->query('DROP TABLE IF EXISTS ' . DB_PREFIX . 'order_cielo');
    }
}