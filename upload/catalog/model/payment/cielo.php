<?php
class ModelPaymentCielo extends Model {

    public function getMethod($address, $total) {
		$this->load->language('payment/cielo');
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('cielo_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
		if ($this->config->get('cielo_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('cielo_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}	

		$currencies = array(
			'AUD',
			'CAD',
			'EUR',
			'GBP',
			'JPY',
			'USD',
			'NZD',
			'CHF',
			'HKD',
			'SGD',
			'SEK',
			'DKK',
			'PLN',
			'NOK',
			'HUF',
			'CZK',
			'ILS',
			'MXN',
			'MYR',
			'BRL',
			'PHP',
			'TWD',
			'THB',
			'TRY'
		);
		
		if (!in_array(strtoupper($this->currency->getCode()), $currencies)) {
			$status = false;
		}			

		$method_data = array();

		if ($status) {  
      		$method_data = array( 
        		'code'       => 'cielo',
        		'title'      => $this->language->get('text_title'),
        		'terms'      => '',
				'sort_order' => $this->config->get('cielo_sort_order')
      		);
    	}
   
    	return $method_data;
  	}

    public function getCountryCodeById($country_id) {
        $query = $this->db->query('SELECT iso_code_2 FROM '. DB_PREFIX . 'country WHERE country_id = ' . $country_id);
        $country_code = !empty($query->row['iso_code_2']) ? $query->row['iso_code_2'] : null;

        return $country_code;
    }

    public function getZoneCodeById($zoney_id) {
        $query = $this->db->query('SELECT code FROM '. DB_PREFIX . 'zone WHERE zone_id = ' . $zoney_id);
        $zone_code = !empty($query->row['code']) ? $query->row['code'] : null;

        return $zone_code;
    }

    public function getTransactionByOrderId($order_id) {

        $query = $this->db->query('SELECT * FROM '. DB_PREFIX . 'order_cielo WHERE pedido_numero = '. $order_id . ' ORDER BY order_cielo_id DESC LIMIT 0,1');

        return $query->row;
    }

    public function addTransaction($data) {

        $this->db->query("INSERT INTO " . DB_PREFIX . "order_cielo (tid,pan,status,pedido_numero,pedido_valor,pedido_moeda,pedido_data,
                          pedido_idioma,pagamento_bandeira,pagamento_produto,pagamento_parcelas,autenticacao_codigo,
                          autenticacao_mensagem,autenticacao_data,autenticacao_valor,autenticacao_eci,autorizacao_codigo,
                          autorizacao_mensagem,autorizacao_data,autorizacao_valor,autorizacao_lr,autorizacao_arp,
                          autorizacao_nsu,captura_codigo,captura_mensagem,captura_data,captura_valor)
                          VALUES ('" . $data['tid'] . "','" . $data['pan'] . "','" . $data['status'] . "',
                          '" . $data['pedido_numero'] . "','" . $data['pedido_valor'] . "','" . $data['pedido_moeda'] . "',
                          '" . $data['pedido_data'] . "','" . $data['pedido_idioma'] . "','" . $data['pagamento_bandeira'] . "',
                          '" . $data['pagamento_produto'] . "','" . $data['pagamento_parcelas'] . "',
                          '" . $data['autenticacao_codigo'] . "','" . $data['autenticacao_mensagem'] . "',
                          '" . $data['autenticacao_data'] . "','" . $data['autorizacao_valor'] . "',
                          '" . $data['autenticacao_eci'] . "','" . $data['autorizacao_codigo'] . "',
                          '" . $data['autorizacao_mensagem'] . "','" . $data['autorizacao_data'] . "',
                          '" . $data['autorizacao_valor'] . "','" . $data['autorizacao_lr'] . "',
                          '" . $data['autorizacao_arp'] . "','" . $data['autorizacao_nsu'] . "',
                          '" . $data['captura_codigo'] . "','" . $data['captura_mensagem'] . "',
                          '" . $data['captura_data'] . "','" . $data['captura_valor'] . "')");
    }

    public function parseData($xmlResposta) {

        if (!$xmlResposta instanceof \SimpleXMLElement) {
            throw new \Exception('XML de requisição inválido');
        }

        $data['tid'] = (string)$xmlResposta->tid;
        $data['pan'] = (string)$xmlResposta->pan;
        $data['status'] = (string)$xmlResposta->status;
        $data['pedido_numero'] = isset($xmlResposta->{'dados-pedido'}->numero) ? (string)$xmlResposta->{'dados-pedido'}->numero : '';
        $data['pedido_valor'] = isset($xmlResposta->{'dados-pedido'}->valor) ? (string)$xmlResposta->{'dados-pedido'}->valor : '';
        $data['pedido_moeda'] = isset($xmlResposta->{'dados-pedido'}->moeda) ? (string)$xmlResposta->{'dados-pedido'}->moeda : '';
        $data['pedido_data'] = isset($xmlResposta->{'dados-pedido'}->{'data-hora'}) ? (string)$xmlResposta->{'dados-pedido'}->{'data-hora'} : '';
        $data['pedido_idioma'] = isset($xmlResposta->{'dados-pedido'}->idioma) ? (string)$xmlResposta->{'dados-pedido'}->idioma : '';
        $data['pagamento_bandeira'] = isset($xmlResposta->{'forma-pagamento'}->bandeira) ? (string)$xmlResposta->{'forma-pagamento'}->bandeira : '';
        $data['pagamento_produto'] = isset($xmlResposta->{'forma-pagamento'}->produto) ? (string)$xmlResposta->{'forma-pagamento'}->produto : '';
        $data['pagamento_parcelas'] = isset($xmlResposta->{'forma-pagamento'}->parcelas) ? (string)$xmlResposta->{'forma-pagamento'}->parcelas : '';
        $data['autenticacao_codigo'] = isset($xmlResposta->autenticacao->codigo) ? (string)$xmlResposta->autenticacao->codigo : '';
        $data['autenticacao_mensagem'] = isset($xmlResposta->autenticacao->mensagem) ? (string)$xmlResposta->autenticacao->mensagem : '';
        $data['autenticacao_data'] = isset($xmlResposta->autenticacao->{'data-hora'}) ? (string)$xmlResposta->autenticacao->{'data-hora'} : '';
        $data['autenticacao_valor'] = isset($xmlResposta->autenticacao->valor) ? (string)$xmlResposta->autenticacao->valor : '';
        $data['autenticacao_eci'] = isset($xmlResposta->autenticacao->eci) ? (string)$xmlResposta->autenticacao->eci : '';
        $data['autorizacao_codigo'] = isset($xmlResposta->autorizacao->codigo) ? (string)$xmlResposta->autorizacao->codigo : '';
        $data['autorizacao_mensagem'] = isset($xmlResposta->autorizacao->mensagem) ? (string)$xmlResposta->autorizacao->mensagem : '';
        $data['autorizacao_data'] = isset($xmlResposta->autorizacao->{'data-hora'}) ? (string)$xmlResposta->autorizacao->{'data-hora'} : '';
        $data['autorizacao_valor'] = isset($xmlResposta->autorizacao->valor) ? (string)$xmlResposta->autorizacao->valor : '';
        $data['autorizacao_lr'] = isset($xmlResposta->autorizacao->lr) ? (string)$xmlResposta->autorizacao->lr : '';
        $data['autorizacao_arp'] = isset($xmlResposta->autorizacao->arp) ? (string)$xmlResposta->autorizacao->arp : '';
        $data['autorizacao_nsu'] = isset($xmlResposta->autorizacao->nsu) ? (string)$xmlResposta->autorizacao->nsu : '';
        $data['captura_codigo'] = isset($xmlResposta->captura->codigo) ? (string)$xmlResposta->captura->codigo : '';
        $data['captura_mensagem'] = isset($xmlResposta->captura->mensagem) ? (string)$xmlResposta->captura->mensagem : '';
        $data['captura_data'] = isset($xmlResposta->captura->{'data-hora'}) ? (string)$xmlResposta->captura->{'data-hora'} : '';
        $data['captura_valor'] = isset($xmlResposta->captura->valor) ? (string)$xmlResposta->captura->valor : '';
        $data['cancelamento_codigo'] = isset($xmlResposta->cancelamentos->cancelamento->codigo) ? (string)$xmlResposta->cancelamentos->cancelamento->codigo : '';
        $data['cancelamento_mensagem'] = isset($xmlResposta->cancelamentos->{0}->cancelamento->mensagem) ? (string)$xmlResposta->cancelamentos->{0}->cancelamento->mensagem : '';
        $data['cancelamento_data'] = isset($xmlResposta->cancelamentos->{0}->cancelamento->{'data-hora'}) ? (string)$xmlResposta->cancelamentos->{0}->cancelamento->{'data-hora'} : '';
        $data['cancelamento_valor'] = isset($xmlResposta->cancelamentos->{0}->cancelamento->valor) ? (string)$xmlResposta->cancelamentos->{0}->cancelamento->valor : '';

        return $data;
    }
}