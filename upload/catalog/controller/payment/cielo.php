<?php
class ControllerPaymentCielo extends Controller {

    private $error;

    private function juroComposto($capital, $tempo, $juros, $tipo = 0) {
        $m = $capital * pow((1 + ($juros / 100)), $tempo);

        if ($tipo == 0) {
            return $m;
        } else {
            return ($m / $tempo);
        }
    }

    public function index() {

        $this->language->load('payment/cielo');

        $data['text_barra'] = $this->language->get('text_barra');
        $data['text_teste'] = $this->language->get('text_teste');
        $data['text_pagamento'] = $this->language->get('text_pagamento');
        $data['text_info'] = $this->language->get('text_info');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['teste'] = $this->config->get('cielo_teste');

        $data['button_confirm'] = $this->language->get('button_confirm');

        $data['action'] = $this->url->link('payment/cielo/processar', '', 'SSL');

        $order_info  = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        $valor_total = number_format($order_info['total'],2);
        $data['valor_total'] = str_replace(".","",$valor_total);

        $data['cartoes'] = array();

        if ($this->config->get('cielo_cartao_visa') == 1) {
            $data['cartoes']['visa']['nome'] = 'Visa';
            $data['cartoes']['visa']['parcelas'] = $this->config->get('cielo_visa_parcelas');
        }

        if ($this->config->get('cielo_cartao_mastercard') == 1) {
            $data['cartoes']['mastercard']['nome'] = 'Mastercard';
            $data['cartoes']['mastercard']['parcelas'] = $this->config->get('cielo_mastercard_parcelas');
        }

        if ($this->config->get('cielo_cartao_diners') == 1) {
            $data['cartoes']['diners']['nome'] = 'Diners';
            $data['cartoes']['diners']['parcelas'] = $this->config->get('cielo_diners_parcelas');
        }

        if ($this->config->get('cielo_cartao_discover') == 1) {
            $data['cartoes']['discover']['nome'] = 'Discover';
            $data['cartoes']['discover']['parcelas'] = $this->config->get('cielo_discover_parcelas');
        }

        if ($this->config->get('cielo_cartao_elo') == 1) {
            $data['cartoes']['elo']['nome'] = 'Elo';
            $data['cartoes']['elo']['parcelas'] = $this->config->get('cielo_elo_parcelas');
        }

        if ($this->config->get('cielo_cartao_amex') == 1) {
            $data['cartoes']['amex']['nome'] = 'Amex';
            $data['cartoes']['amex']['parcelas'] = $this->config->get('cielo_amex_parcelas');
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/cielo.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/payment/cielo.tpl', $data);
        } else {
            return $this->load->view('default/template/payment/cielo.tpl', $data);
        }
    }

    protected function validar() {
        if (!isset($this->request->post['creditcard_cctype']) || utf8_strlen(trim($this->request->post['creditcard_cctype'])) < 1) {
            $this->error['creditcard_cctype'] = $this->language->get('error_bandeira');
        }

        if (!isset($this->request->post['creditcard_name']) || utf8_strlen(trim($this->request->post['creditcard_name'])) < 1) {
            $this->error['creditcard_name'] = $this->language->get('error_nome');
        }

        if (!isset($this->request->post['creditcard_ccno']) || trim($this->request->post['creditcard_ccno']) < 16) {
            $this->error['creditcard_ccno'] = $this->language->get('error_numero');
        }

        if (!isset($this->request->post['validade']) || trim($this->request->post['validade']) < 6) {
            $this->error['creditcard_ccexpy'] = $this->language->get('error_validade');
        }

        if (!isset($this->request->post['creditcard_cccvd']) || trim($this->request->post['creditcard_cccvd']) < 3) {
            $this->error['creditcard_cccvd'] = $this->language->get('error_cod_seg');
        }

        return !$this->error;
    }

    public function processar() {

        $this->load->library('cielo');

        $this->language->load('payment/cielo');

        $this->load->model('checkout/order');
        $this->load->model('payment/cielo');

        $json = array();

        if($this->validar()) {
            $order_info  = $this->model_checkout_order->getOrder($this->session->data['order_id']);
            $valor_total = $order_info['total'];

            $loja = new \Tritoq\Payment\Cielo\Loja();
            $loja
                ->setNomeLoja(substr($order_info['store_name'],0,13))
                ->setAmbiente(\Tritoq\Payment\Cielo\Loja::AMBIENTE_PRODUCAO)
                ->setUrlRetorno($this->url->link('payment/cielo/callback'))
                ->setChave($this->config->get('cielo_chave'))
                ->setNumeroLoja($this->config->get('cielo_afiliacao'))
                ->setSslCertificado(DIR_SYSTEM . 'library/Tritoq/Payment/Cielo/ssl/ecommerce.cielo.com.br.cer');

            if($this->config->get('cielo_teste') == '1') {

                $loja->setAmbiente(\Tritoq\Payment\Cielo\Loja::AMBIENTE_TESTE)
                     ->setChave(\Tritoq\Payment\Cielo\Loja::LOJA_CHAVE_AMBIENTE_TESTE)
                     ->setNumeroLoja(\Tritoq\Payment\Cielo\Loja::LOJA_NUMERO_AMBIENTE_TESTE);
            }

            $cartao = new \Tritoq\Payment\Cielo\Cartao();
            $cartao
                ->setNumero($this->request->post['creditcard_ccno'])
                ->setCodigoSegurancaCartao($this->request->post['creditcard_cccvd'])
                ->setBandeira($this->request->post['creditcard_cctype'])
                ->setNomePortador($this->request->post['creditcard_name'])
                ->setValidade($this->request->post['validade']);

            $transacao = new \Tritoq\Payment\Cielo\Transacao();
            $transacao
                ->setAutorizar($this->config->get('cielo_autorizacao'))
                ->setCapturar(\Tritoq\Payment\Cielo\Transacao::CAPTURA_SIM)
                ->setParcelas(1)
                ->setProduto(\Tritoq\Payment\Cielo\Transacao::PRODUTO_CREDITO_AVISTA);

            if(!$this->config->get('cielo_captura')) {

                $transacao->setCapturar(\Tritoq\Payment\Cielo\Transacao::CAPTURA_NAO);
            }

            if($this->request->post["formaPagamento"] == 'A') {
                $transacao->setProduto(\Tritoq\Payment\Cielo\Transacao::PRODUTO_DEBITO);

            } else if($this->request->post["formaPagamento"] != '1') {

                $transacao->setProduto($this->config->get('cielo_parcelamento'));
                $transacao->setParcelas($this->request->post["formaPagamento"]);

                if($this->config->get('cielo_parcelamento') == \Tritoq\Payment\Cielo\Transacao::PRODUTO_PARCELADO_LOJA) {

                    $valor_total = $this->juroComposto($valor_total, $this->request->post["formaPagamento"], $this->config->get('cielo_cartao_juros'));
                }
            }

            $pedido = new \Tritoq\Payment\Cielo\Pedido();
            $pedido
                ->setDataHora(new \DateTime())
                ->setDescricao('Compra na loja ' . $order_info['store_name'])
                ->setIdioma(\Tritoq\Payment\Cielo\Pedido::IDIOMA_PORTUGUES)
                ->setNumero($this->session->data['order_id'])
                ->setValor(preg_replace('/[^0-9]/','',number_format($valor_total,2)));

            if($this->config->get('cielo_teste') == '1') {
                $pedido->setValor(preg_replace('/[^0-9]/','',ceil($valor_total) . '00'));
            }

            $portador = new \Tritoq\Payment\Cielo\Portador();
            $portador
                ->setBairro($order_info['payment_address_2'])
                ->setCep($order_info['payment_postcode'])
                ->setEndereco($order_info['payment_address_1']);

            if($this->config->get('cielo_analise') == '1') {

                $country_code = $this->model_payment_cielo->getCountryCodeById($order_info['payment_country_id']);
                $zone_code = $this->model_payment_cielo->getZoneCodeById($order_info['payment_zone_id']);

                $pedidoAnalise = new \Tritoq\Payment\Cielo\AnaliseRisco\PedidoAnaliseRisco();
                $pedidoAnalise
                    ->setEstado($zone_code)
                    ->setCep($order_info['payment_postcode'])
                    ->setCidade($order_info['payment_city'])
                    ->setEndereco($order_info['payment_address_1'])
                    ->setId($this->request->post['pedido'])
                    ->setPais($country_code)
                    ->setPrecoTotal(str_replace(',','.',$valor_total));

                if($order_info['shipping_country_id'] != $order_info['payment_country_id']) {

                    $country_code = $this->model_payment_cielo->getCountryCodeById($order_info['shipping_country_id']);
                }

                if($order_info['shipping_zone_id'] != $order_info['payment_zone_id']) {

                    $zone_code = $this->model_payment_cielo->getZoneCodeById($order_info['shipping_zone_id']);
                }

                $cliente = new \Tritoq\Payment\Cielo\AnaliseRisco\ClienteAnaliseRisco();
                $cliente->nome = $order_info['firstname'];
                $cliente->sobrenome = $order_info['lastname'];
                $cliente->endereco = $order_info['shuipping_address_1'];
                $cliente->complemento = '';
                $cliente->cep = $order_info['shipping_postcode'];
                $cliente->documento = '';
                $cliente->email = $order_info['email'];
                $cliente->estado = $zone_code;
                $cliente->cidade = $order_info['shipping_city'];
                $cliente->id = $this->customer->getId();
                $cliente->ip = $order_info['ip'];
                $cliente->pais = $country_code;
                $cliente->telefone = $this->customer->getTelephone();

                /*
                *
                * Usando a Análise de Risco
                *
                */

                // Para qualquer ação será revista com ação manual posterior, caso seja de baixo risco, a transação será capturada automaticamente

                $analise = new \Tritoq\Payment\Cielo\AnaliseRisco();
                $analise
                    ->setCliente($cliente)
                    ->setPedido($pedidoAnalise)
                    ->setAfsServiceRun(true)
                    ->setAltoRisco(\Tritoq\Payment\Cielo\AnaliseRisco::ACAO_MANUAL_POSTERIOR)
                    ->setMedioRisco(\Tritoq\Payment\Cielo\AnaliseRisco::ACAO_MANUAL_POSTERIOR)
                    ->setBaixoRisco(\Tritoq\Payment\Cielo\AnaliseRisco::ACAO_CAPTURAR)
                    ->setErroDados(\Tritoq\Payment\Cielo\AnaliseRisco::ACAO_MANUAL_POSTERIOR)
                    ->setErroIndisponibilidade(\Tritoq\Payment\Cielo\AnaliseRisco::ACAO_MANUAL_POSTERIOR)
                    ->setDeviceFingerPrintID(md5($this->config->get('config_name')));

                $service = new \Tritoq\Payment\Cielo\CieloService(array(
                                                                      'portador' => $portador,
                                                                      'loja' => $loja,
                                                                      'cartao' => $cartao,
                                                                      'transacao' => $transacao,
                                                                      'pedido' => $pedido,
                                                                      'analise' => $analise
                                                                  ));

                // Setando o tipo de versão de conexão SSL
                $service->setSslVersion(4);

                // Desabilitando a analise de risco
                $service->setHabilitarAnaliseRisco(true);

                $gerarToken = false;
                $checkAvs = true;

            } else {

                $service = new \Tritoq\Payment\Cielo\CieloService(array(
                                                                      'portador' => $portador,
                                                                      'loja' => $loja,
                                                                      'cartao' => $cartao,
                                                                      'transacao' => $transacao,
                                                                      'pedido' => $pedido,
                                                                  ));

                // Setando o tipo de versão de conexão SSL
                $service->setSslVersion(4);

                // Desabilitando a analise de risco
                $service->setHabilitarAnaliseRisco(false);

                $gerarToken = false;
                $checkAvs = false;
            }

            $service->doTransacao($gerarToken, $checkAvs);

            $urlAutenticacao = (string)$transacao->getUrlAutenticacao();

            if($transacao->getStatus() == \Tritoq\Payment\Cielo\Transacao::STATUS_AUTORIZADA
                || $transacao->getStatus() == \Tritoq\Payment\Cielo\Transacao::STATUS_CAPTURADA) {

                $finalizacao = 'Aprovado';

                $comentario = "Situação: ". $finalizacao ."<br />";
                $comentario .= " Pedido: ". (string)$pedido->getNumero() ."<br />";
                $comentario .= " TID: ". (string)$transacao->getTid() ."<br />";
                $comentario .= " Cartão: ". strtoupper((string)$cartao->getBandeira()) ."<br />";
                $comentario .= " Parcelado em: ". (string)$transacao->getParcelas() ."x";

                $this->model_checkout_order->addOrderHistory((string)$pedido->getNumero(), $this->config->get('cielo_aprovado_id'), $comentario, true);

                $requisicoes = $transacao->getRequisicoes(\Tritoq\Payment\Cielo\Transacao::REQUISICAO_TIPO_TRANSACAO);

                foreach($requisicoes as $requisicao) {

                    $xmlRetorno = $requisicao->getXmlRetorno();
                    $data = $this->model_payment_cielo->parseData($xmlRetorno);

                    $this->model_payment_cielo->addTransaction($data);
                }

                $json['redirect'] = $this->url->link('checkout/success');

            } else if(!empty($urlAutenticacao)) {

                $requisicoes = $transacao->getRequisicoes(\Tritoq\Payment\Cielo\Transacao::REQUISICAO_TIPO_TRANSACAO);

                foreach($requisicoes as $requisicao) {

                    $xmlRetorno = $requisicao->getXmlRetorno();
                    $data = $this->model_payment_cielo->parseData($xmlRetorno);

                    $this->model_payment_cielo->addTransaction($data);
                }

                $json['redirect'] = $urlAutenticacao;
            } else {
                $requisicoes = $transacao->getRequisicoes(\Tritoq\Payment\Cielo\Transacao::REQUISICAO_TIPO_TRANSACAO);

                foreach($requisicoes as $requisicao) {

                    $errors = $requisicao->getErrors();

                    if(!empty($errors)) {
                        $this->error = array_merge((array)$this->error, $errors);
                    }
                }
            }
        }

        if(!empty($this->error)) {
            $json['error'] = $this->error;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function parcelamento() {
        if (isset($this->request->get['bandeira'])) {
            $bandeira = $this->request->get['bandeira'];
        } else {
            $bandeira = null;
        }

        if (isset($this->request->get['parcelas'])) {
            $maximo_parcelas = $this->request->get['parcelas'];
        } else {
            $maximo_parcelas = 0;
        }

        $this->load->model('checkout/order');
        $order_info  = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        $valor = str_replace(',','',number_format($order_info['total'],2));

        $parcelas_sem_juros = $this->config->get('cielo_cartao_semjuros');

        $juros = $this->config->get('cielo_cartao_juros');

        $parcela_minima = $this->config->get('cielo_cartao_minimo');

        $parcelamento = '';
        $info = '';

        if (!empty($bandeira)) {
            if ($this->config->get('cielo_parcelamento') == "2") {
                $parcelamento .= '<div class="form-group">
                                <label for="formaPagamento" class="col-sm-3 control-label">Valor</label>
                                <div class="col-sm-9">
                                    <select name="formaPagamento" class="form-control">
                                        <option value="1">1x de '. number_format($valor, 2, ',', '.') .' sem juros</option>';

                for ($p = 2; $p <= $maximo_parcelas; $p++) {
                    $valor_parcela = 0;

                    if ($p <= $parcelas_sem_juros) {
                        $valor_parcela = $valor / $p;
                    }

                    if ($p > $parcelas_sem_juros) {
                        $valor_parcela = $this->juroComposto($valor, $p, $juros, 1);
                    }

                    if ($valor_parcela >= $parcela_minima) {
                        if ($p <= $parcelas_sem_juros) {
                            $parcelamento .= '<option value="'. $p .'"> '. $p .'x de '. number_format($valor_parcela, 2, ',', '.') .' sem juros</option>';
                        } else {
                            $parcelamento .= '<option value="' . $p . '"> ' . $p . 'x de ' . number_format($valor_parcela, 2,',','.') . ' com juros</option>';
                        }
                    } else {
                        $info .= '<span class="help-inline fixed-help">Parcela mínima de '. number_format($parcela_minima, 2, ',', '.') .'</span>';
                        break;
                    }
                }
                if ($parcelas_sem_juros < $maximo_parcelas) {
                    $juros = number_format($juros, 2, ',', '.');
                    $info .= '<span class="help-inline fixed-help">Juros de '. $juros .'% ao mês</span>';
                }
            } else if ($this->config->get('cielo_parcelamento') == "3") {
                $parcelamento .= '<option value="1"> 1x de '. number_format($valor, 2, ',', '.') .' sem juros</option>';

                for ($p = 2; $p <= $maximo_parcelas; $p++) {
                    $parcelamento .= '<option value="' . $p . '"> '. $p .'x (o valor da parcela será consultado no próximo passo)</option>';
                }
            }

            if($bandeira == 'visa' || $bandeira == 'mastercard') {
                $parcelamento .= '<optgroup label="Débito"><option value="A">1x Débito à vista</option></optgroup>';
            }

            $parcelamento .= '</select>' . $info . '</div></div>';
        }

        $this->response->setOutput($parcelamento);
    }

    public function callback() {

        if(!isset($this->session->data['order_id'])) {
            return $this->response->redirect($this->url->link('common/home'));
        }

        $this->load->library('cielo');

        $this->language->load('payment/cielo');

        $this->load->model('checkout/order');
        $this->load->model('payment/cielo');

        $order_id = $this->session->data['order_id'];

        $transaction = $this->model_payment_cielo->getTransactionByOrderId($order_id);

        if(!empty($transaction['tid'])) {

            $loja = new \Tritoq\Payment\Cielo\Loja();
            $loja->setAmbiente(\Tritoq\Payment\Cielo\Loja::AMBIENTE_PRODUCAO)
                 ->setUrlRetorno($this->url->link('payment/cielo/callback'))
                 ->setChave($this->config->get('cielo_chave'))
                 ->setNumeroLoja($this->config->get('cielo_afiliacao'))
                 ->setSslCertificado(DIR_SYSTEM . 'library/Tritoq/Payment/Cielo/ssl/ecommerce.cielo.com.br.cer');

            if($this->config->get('cielo_teste') == '1') {

                $loja->setAmbiente(\Tritoq\Payment\Cielo\Loja::AMBIENTE_TESTE)
                     ->setChave(\Tritoq\Payment\Cielo\Loja::LOJA_CHAVE_AMBIENTE_TESTE)
                     ->setNumeroLoja(\Tritoq\Payment\Cielo\Loja::LOJA_NUMERO_AMBIENTE_TESTE);
            }

            $transacao = new \Tritoq\Payment\Cielo\Transacao();
            $transacao->setTid($transaction['tid']);

            $service = new \Tritoq\Payment\Cielo\CieloService(array(
                                                                  'loja' => $loja,
                                                                  'transacao' => $transacao,
                                                              ));

            // Setando o tipo de versão de conexão SSL
            $service->setSslVersion(4);

            $service->doConsulta();

            $situacao = 'Autenticada';

            if($this->config->get('cielo_autorizacao') != \Tritoq\Payment\Cielo\Transacao::AUTORIZAR_NAO_AUTORIZAR) {

                $service->doAutorizacao();
            }

            if($transacao->getStatus() == \Tritoq\Payment\Cielo\Transacao::STATUS_AUTORIZADA) {
                $situacao = 'Autorizada';
                $comentario = "Situação: ". $situacao ."<br />";
                $comentario .= " Pedido: ". $order_id ."<br />";
                $comentario .= " TID: ". (string)$transacao->getTid() ."<br />";
                $comentario .= " Parcelado em: ". (string)$transacao->getParcelas() ."x";

                $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('cielo_aprovado_id'), $comentario, true);
            } else {
                $situacao = 'Não Autorizada';

                $comentario = "Situação: ". $situacao ."<br />";
                $comentario .= " Pedido: ". $order_id ."<br />";
                $comentario .= " TID: ". (string)$transacao->getTid() ."<br />";
                $comentario .= " Parcelado em: ". (string)$transacao->getParcelas() ."x";

                $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('cielo_nao_capturado_id'), $comentario, true);
            }

            if($this->config->get('cielo_captura') && $transacao->getStatus() == \Tritoq\Payment\Cielo\Transacao::STATUS_AUTORIZADA) {
                $service->doCaptura();
            }

            if($transacao->getStatus() == \Tritoq\Payment\Cielo\Transacao::STATUS_CAPTURADA) {
                $situacao = 'Capturada';

                $comentario = "Situação: ". $situacao ."<br />";
                $comentario .= " Pedido: ". $order_id ."<br />";
                $comentario .= " TID: ". (string)$transacao->getTid() ."<br />";
                $comentario .= " Parcelado em: ". (string)$transacao->getParcelas() ."x";

                $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('cielo_capturado_id'), $comentario, true);
            }

            $requisicoes = $transacao->getRequisicoes(\Tritoq\Payment\Cielo\Transacao::REQUISICAO_TIPO_TRANSACAO);

            if(empty($requisicoes)) {
                $requisicoes = $transacao->getRequisicoes(\Tritoq\Payment\Cielo\Transacao::REQUISICAO_TIPO_CAPTURA);
            }

            if(empty($requisicoes)) {
                $requisicoes = $transacao->getRequisicoes(\Tritoq\Payment\Cielo\Transacao::REQUISICAO_TIPO_AUTORIZACAO);
            }

            if(empty($requisicoes)) {
                $requisicoes = $transacao->getRequisicoes(\Tritoq\Payment\Cielo\Transacao::REQUISICAO_TIPO_CONSULTA);
            }

            $requisicao = current($requisicoes);

            if(is_array($requisicao)) {
                $requisicao = current($requisicao);
            }

            $xmlRetorno = $requisicao->getXmlRetorno();
            $data = $this->model_payment_cielo->parseData($xmlRetorno);

            $this->model_payment_cielo->addTransaction($data);

            return $this->response->redirect($this->url->link('checkout/success'));
        }

        return $this->response->redirect($this->url->link('checkout/failure'));
    }
}