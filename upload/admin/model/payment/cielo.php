<?php
class ModelPaymentCielo extends Model {

    public function getTransactions($data = array()) {

        $sql = "SELECT *, oc.status AS cielo_status, CONCAT(c.firstname, ' ', c.lastname) AS name, CASE oc.status WHEN '4' THEN TIMEDIFF(DATE_ADD(oc.autorizacao_data, INTERVAL 5 DAY), NOW()) ELSE 999 END AS prazo_captura FROM " . DB_PREFIX . "order_cielo oc LEFT JOIN " . DB_PREFIX . "order o ON (oc.pedido_numero = o.order_id) INNER JOIN " . DB_PREFIX . "customer c ON (o.customer_id = c.customer_id)";

        if (!empty($data['filter_name'])) {
            $sql .= " AND (c.firstname LIKE '" . $this->db->escape($data['filter_name']) . "%' OR c.lastname LIKE '" . $this->db->escape($data['filter_name']) . "%' OR c.email LIKE '" . $this->db->escape($data['filter_name']) . "%'))";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND oc.status = '" . (int)$data['filter_status'] . "'";
        }

        if (isset($data['filter_order_id']) && !is_null($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
        }

        $sql .= " GROUP BY oc.order_cielo_id";

        $sort_data = array(
            'c.firstname',
            'oc.status',
            'o.order_id',
            'o.total',
            'prazo_captura',
            'oc.autorizacao_data'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY oc.autorizacao_data";
        }

        if (isset($data['order']) && ($data['order'] == 'ASC')) {
            $sql .= " ASC";
        } else {
            $sql .= " DESC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalTransactions($data = array()) {
        $sql = "SELECT COUNT(DISTINCT oc.order_cielo_id) AS total FROM " . DB_PREFIX . "order_cielo oc LEFT JOIN " . DB_PREFIX . "order o ON (oc.pedido_numero = o.order_id) LEFT JOIN " . DB_PREFIX . "customer c ON (o.customer_id = c.customer_id)";

        if (!empty($data['filter_name'])) {
            $sql .= " AND (c.firstname LIKE '" . $this->db->escape($data['filter_name']) . "%' OR c.lastname LIKE '" . $this->db->escape($data['filter_name']) . "%' OR c.email LIKE '" . $this->db->escape($data['filter_name']) . "%'))";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND oc.status = '" . (int)$data['filter_status'] . "'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTransaction($order_cielo_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_cielo WHERE order_cielo_id = '" . (int)$order_cielo_id . "'");

        return $query->row;
    }

    public function updateTransaction($order_cielo_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "order_cielo SET tid = '" . $data['tid'] . "', pan = '" . $data['pan'] . "',
                            status = '" . $data['status'] . "', pedido_numero = '" . $data['pedido_numero'] . "',
                            pedido_valor = '" . $data['pedido_valor'] . "', pedido_moeda = '" . $data['pedido_moeda'] . "',
                            pedido_data = '" . $data['pedido_data'] . "', pedido_idioma = '" . $data['pedido_idioma'] . "',
                            pagamento_bandeira = '" . $data['pagamento_bandeira'] . "',
                            pagamento_produto = '" . $data['pagamento_produto'] . "',
                            pagamento_parcelas = '" . $data['pagamento_parcelas'] . "',
                            autenticacao_codigo = '" . $data['autenticacao_codigo'] . "',
                            autenticacao_mensagem = '" . $data['autenticacao_mensagem'] . "',
                            autenticacao_data = '" . $data['autenticacao_data'] . "',
                            autenticacao_valor = '" . $data['autorizacao_valor'] . "',
                            autenticacao_eci = '" . $data['autenticacao_eci'] . "',
                            autorizacao_codigo = '" . $data['autorizacao_codigo'] . "',
                            autorizacao_mensagem = '" . $data['autorizacao_mensagem'] . "',
                            autorizacao_data = '" . $data['autorizacao_data'] . "',
                            autorizacao_valor = '" . $data['autorizacao_valor'] . "',
                            autorizacao_lr = '" . $data['autorizacao_lr'] . "',
                            autorizacao_arp = '" . $data['autorizacao_arp'] . "',
                            autorizacao_nsu = '" . $data['autorizacao_nsu'] . "',
                            captura_codigo = '" . $data['captura_codigo'] . "',
                            captura_mensagem = '" . $data['captura_mensagem'] . "',
                            captura_data = '" . $data['captura_data'] . "',
                            captura_valor = '" . $data['captura_valor'] . "',
                            cancelamento_codigo = '" . $data['cancelamento_codigo'] . "',
                            cancelamento_mensagem = '" . $data['cancelamento_mensagem'] . "',
                            cancelamento_data = '" . $data['cancelamento_data'] . "',
                            cancelamento_valor = '" . $data['cancelamento_valor'] . "'
                            WHERE order_cielo_id = '". $order_cielo_id ."'");
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