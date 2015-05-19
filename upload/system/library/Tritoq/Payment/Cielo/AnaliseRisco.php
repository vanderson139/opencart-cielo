<?php

namespace Tritoq\Payment\Cielo;

use Tritoq\Payment\Cielo\AnaliseRisco\ClienteAnaliseRiscoInterface;
use Tritoq\Payment\Cielo\AnaliseRisco\PedidoAnaliseRisco;
use Tritoq\Payment\Exception\InvalidArgumentException;

/**
 *
 * Responsável por montar e organizar as informações que serão enviadas para Análise de Risco
 *
 *
 * Class AnaliseRisco
 *
 * @category Library
 * @copyright Artur Magalhães <nezkal@gmail.com>
 * @package Tritoq\Payment\Cielo
 * @license GPL-3.0+
 */
class AnaliseRisco
{
    /**
     *
     * Valor da Ação Desfazer da Análise
     *
     * @const string
     */
    const ACAO_DESFAZER = 'desfazer';

    /**
     *
     * Valor da Ação para decisão manual posterior
     *
     * @const string
     */
    const ACAO_MANUAL_POSTERIOR = 'amp';

    /**
     *
     * Valor da Ação para capturar (baixo risco)
     *
     * @const string
     */
    const ACAO_CAPTURAR = 'capturar';

    /**
     *
     * Objeto Cliente
     *
     * @var ClienteAnaliseRiscoInterface
     */
    private $cliente;

    /**
     *
     * Objeto do Pedido
     *
     * @var PedidoAnaliseRisco
     */
    private $pedido;

    // configurações da análise de risco

    /**
     *
     * Ação automática caso o valor seja de alto risco
     *
     * @var string
     */
    private $altoRisco;

    /**
     *
     * Açao automática caso o valor seja de medio risco
     *
     * @var string
     */
    private $medioRisco;

    /**
     *
     * Ação automática caso o valor seja de baixo risco
     *
     * @var string
     */
    private $baixoRisco;

    /**
     *
     * Ação automática caso haja erro nos dados
     *
     * @var string
     */
    private $erroDados;

    /**
     *
     * Ação automática caso haja indisponibilidade no serviço
     *
     * @var string
     */
    private $erroIndisponibilidade;

    /**
     *
     * Indicador de verifição
     *
     * @var string
     */
    private $afsServiceRun;

    /**
     *
     * Tags adicionais ao XML
     *
     * @var array
     */
    private $tagsAdicionais;

    /**
     *
     * Opções adicionais ao XML
     *
     * @var array
     */
    private $tagsOpcionais;

    /**
     *
     * Valor do Device Finger Print ID (consultar manual)
     *
     * @var string
     */
    private $deviceFingerPrintID;

    /**
     * @param null $options
     * @throws \Tritoq\Payment\Exception\InvalidArgumentException
     */
    public function __construct($options = null)
    {
        if (isset($options)) {
            foreach ($options as $key => $item) {
                if (isset($this->$key)) {
                    $method = 'set' . ucfirst($key);
                    $this->$method($item);
                } else {
                    throw new InvalidArgumentException('A opção ' . $key . ' não existe na classe');
                }
            }
        }

        $this->prencherTagsElementosVaziosRequeridos();
        $this->preencherOpcionaisElementosVaziosRequeridos();
    }

    /**
     * Método que preenche algumas tags já requeridas
     *
     */
    private function prencherTagsElementosVaziosRequeridos()
    {
        $dataProvider = array();

        foreach ($dataProvider as $item) {
            $this->addTagAdicional($item, 'NULL');
        }
    }

    /**
     * Método que preenche valores opcionais requeridos
     */
    private function preencherOpcionaisElementosVaziosRequeridos()
    {
        $dataProvider = array(
            'merchantDefinedData_mddField_13',
            'merchantDefinedData_mddField_14',
            'merchantDefinedData_mddField_26',
        );

        foreach ($dataProvider as $item) {
            $this->addTagOpcional($item, 'NULL');
        }
    }

    /**
     *
     * Método que adiciona ao XML os valores opcionais
     *
     * @param \SimpleXMLElement $xml
     */
    private function addXmlOpcionais(\SimpleXMLElement $xml)
    {
        if (sizeof($this->tagsOpcionais) > 0) {
            foreach ($this->tagsOpcionais as $key => $item) {
                $xml->addChild($key, $item);
            }
        }

    }

    /**
     *
     * Método que adiciona ao XML os valores adicionais
     *
     * @param \SimpleXMLElement $xml
     */
    private function addXmlAdicionais(\SimpleXMLElement $xml)
    {
        if (sizeof($this->tagsAdicionais) > 0) {
            foreach ($this->tagsAdicionais as $key => $item) {
                $xml->addChild($key, $item);
            }
        }

    }

    /**
     *
     * Seta as tags opcionais
     *
     * @param array $tagsOpcionais
     * @return $this
     */
    public function setTagsOpcionais($tagsOpcionais)
    {
        $this->tagsOpcionais = $tagsOpcionais;
        return $this;
    }

    /**
     *
     * Retorna as tags Opcionais
     *
     * @return array
     */
    public function getTagsOpcionais()
    {
        return $this->tagsOpcionais;
    }

    /**
     *
     * Adiciona uma tag opcional
     *
     * @param $tag
     * @param $valor
     * @return $this
     */
    public function addTagOpcional($tag, $valor)
    {
        $this->tagsOpcionais[$tag] = $valor;
        return $this;
    }


    /**
     *
     * Adciona uma tag Adicional
     *
     * @param $tag
     * @param $valor
     * @return $this
     */
    public function addTagAdicional($tag, $valor)
    {
        $this->tagsAdicionais[$tag] = $valor;
        return $this;
    }

    /**
     *
     * Retorna uma tag adicional
     *
     * @return array
     */
    public function getTagsAdicionais()
    {
        return $this->tagsAdicionais;
    }

    /**
     *
     * Seta a ação automática caso retorne a transação como auto risco
     *
     * desfazer     ACAO_DESFAZER
     * amp          ACAO_MANUAL_POSTERIOR
     *
     * @param string $altoRisco
     * @throws \Tritoq\Payment\Exception\InvalidArgumentException
     * @return $this
     */
    public function setAltoRisco($altoRisco)
    {
        switch ($altoRisco) {
            case self::ACAO_DESFAZER:
            case self::ACAO_MANUAL_POSTERIOR:
                $this->altoRisco = $altoRisco;
                return $this;
            default:
                throw new InvalidArgumentException('Opção para Alto Risco inválida');

        }

    }

    /**
     *
     * Retorna o valor do indicador de alto risco
     *
     * @return string
     */
    public function getAltoRisco()
    {
        return $this->altoRisco;
    }

    /**
     *
     * Seta a ação automática caso retorno da análise de risco seja como baixo risco
     *
     * capturar      ACAO_CAPTURAR
     * amp           ACAO_MANUAL_POSTERIOR
     *
     * @param string $baixoRisco
     * @throws \Tritoq\Payment\Exception\InvalidArgumentException
     * @return $this
     */
    public function setBaixoRisco($baixoRisco)
    {
        switch ($baixoRisco) {
            case self::ACAO_CAPTURAR:
            case self::ACAO_MANUAL_POSTERIOR:
                $this->baixoRisco = $baixoRisco;
                return $this;
            default:
                throw new InvalidArgumentException('Opção para Baixo Risco inválida');
        }

    }

    /**
     *
     * Retorna o valor do indicador de baixo risco
     *
     * @return string
     */
    public function getBaixoRisco()
    {
        return $this->baixoRisco;
    }

    /**
     *
     * Seta a ação automática caso retorno da análise de risco seja como médio risco
     *
     * capturar         ACAO_CAPTURAR
     * amp              ACAO_MANUAL_POSTERIOR
     * desfazer         ACAO_DESFAZER
     *
     *
     * @param string $medioRisco
     * @throws \Tritoq\Payment\Exception\InvalidArgumentException
     * @return $this
     */
    public function setMedioRisco($medioRisco)
    {
        switch ($medioRisco) {
            case self::ACAO_DESFAZER:
            case self::ACAO_CAPTURAR:
            case self::ACAO_MANUAL_POSTERIOR:
                $this->medioRisco = $medioRisco;
                return $this;
            default:
                throw new InvalidArgumentException('Opção para Médio Risco inválida');

        }

    }

    /**
     *
     * Retorna o valor do indicador para médio risco
     *
     * @return string
     */
    public function getMedioRisco()
    {
        return $this->medioRisco;
    }

    /**
     *
     * Seta o indicador para caso houver erro de dados na análise
     *
     * desfazer         ACAO_DESFAZER
     * capturar         ACAO_CAPTURAR
     * amp              ACAO_MANUAL_POSTERIOR
     *
     * @param string $erroDados
     * @throws \Tritoq\Payment\Exception\InvalidArgumentException
     * @return $this
     */
    public function setErroDados($erroDados)
    {
        switch ($erroDados) {
            case self::ACAO_DESFAZER:
            case self::ACAO_CAPTURAR:
            case self::ACAO_MANUAL_POSTERIOR:
                $this->erroDados = $erroDados;
                return $this;
            default:
                throw new InvalidArgumentException('Opção para Erro Dados inválida');

        }
    }

    /**
     * @return string
     */
    public function getErroDados()
    {
        return $this->erroDados;
    }

    /**
     *
     * Seta o indicador caso houver indisponibildade
     *
     * desfazer         ACAO_DESFAZER
     * capturar         ACAO_CAPTURAR
     * amp              ACAO_MANUAL_POSTERIOR
     *
     *
     * @param string $erroIndisponibilidade
     * @throws \Tritoq\Payment\Exception\InvalidArgumentException
     * @return $this
     */
    public function setErroIndisponibilidade($erroIndisponibilidade)
    {
        switch ($erroIndisponibilidade) {
            case self::ACAO_DESFAZER:
            case self::ACAO_CAPTURAR:
            case self::ACAO_MANUAL_POSTERIOR:
                $this->erroIndisponibilidade = $erroIndisponibilidade;
                return $this;
            default:
                throw new InvalidArgumentException('Opção para Médio Risco inválida');

        }
    }

    /**
     * @return string
     */
    public function getErroIndisponibilidade()
    {
        return $this->erroIndisponibilidade;
    }

    /**
     *
     * Seta o indicador de Afs Service Run
     *
     * @param string $afsServiceRun
     * @throws \Tritoq\Payment\Exception\InvalidArgumentException
     * @return $this
     */
    public function setAfsServiceRun($afsServiceRun)
    {
        switch ($afsServiceRun) {
            case 'true':
            case 'false':
            case true:
            case false:
                $this->afsServiceRun = $afsServiceRun;
                return $this;
            default:
                throw new InvalidArgumentException('Opção para AfsServiceRun inválida');
        }

    }

    /**
     *
     * Retorna o indicador de Afs Service Run
     *
     * @return string
     */
    public function getAfsServiceRun()
    {
        return $this->afsServiceRun;
    }

    /**
     *
     * Seta o cliente
     *
     * @param \Tritoq\Payment\Cielo\AnaliseRisco\ClienteAnaliseRiscoInterface $cliente
     * @return $this
     */
    public function setCliente($cliente)
    {
        $this->cliente = $cliente;
        return $this;
    }

    /**
     *
     * Retorna o Cliente
     *
     * @return \Tritoq\Payment\Cielo\AnaliseRisco\ClienteAnaliseRiscoInterface
     */
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     *
     * Seta o pedido da compra
     *
     * @param \Tritoq\Payment\Cielo\AnaliseRisco\PedidoAnaliseRisco $pedido
     * @return $this
     */
    public function setPedido($pedido)
    {
        $this->pedido = $pedido;

        return $this;
    }

    /**
     *
     * Retorna o pedido da compra
     *
     * @return \Tritoq\Payment\Cielo\AnaliseRisco\PedidoAnaliseRisco
     */
    public function getPedido()
    {
        return $this->pedido;
    }

    /**
     *
     * Seta o valor de Device Finger Print ID - Ver documentação da Cielo
     *
     *
     * @param string $deviceFingerPrintID
     * @return $this
     */
    public function setDeviceFingerPrintID($deviceFingerPrintID)
    {
        $this->deviceFingerPrintID = $deviceFingerPrintID;
        return $this;
    }

    /**
     *
     * Retorna o valor de Device Finger Print ID
     *
     * @return string
     */
    public function getDeviceFingerPrintID()
    {
        return $this->deviceFingerPrintID;
    }


    /**
     *
     * Método que cria o XML com as informações
     *
     * @param \SimpleXMLElement $analise
     * @return \SimpleXMLElement
     */
    public function criarXml($analise = null)
    {
        if (!isset($analise)) {
            $root = new \SimpleXMLElement('<root></root>');
            $analise = $root->addChild('analise-fraude');
        }

        // configuracao

        $configuracao = $analise->addChild('configuracao');
        $configuracao->addChild('analisar-fraude', 'true');
        $configuracao->addChild('alto-risco', $this->altoRisco);
        $configuracao->addChild('medio-risco', $this->medioRisco);
        $configuracao->addChild('baixo-risco', $this->baixoRisco);
        $configuracao->addChild('erro-dados', $this->erroDados);
        $configuracao->addChild('erro-indisponibilidade', $this->erroIndisponibilidade);
        $analise->addChild('afsService_run', $this->getAfsServiceRun() ? 'true' : 'false');

        // ID do pedido
        $analise->addChild('merchantReferenceCode', $this->pedido->getId());

        // Informações de cobranca
        $analise->addChild('billTo_street1', $this->pedido->getEndereco());
        $analise->addChild('billTo_street2', $this->pedido->getComplemento());
        $analise->addChild('billTo_city', $this->pedido->getCidade());
        $analise->addChild('billTo_state', $this->cliente->getEstado());
        $analise->addChild('billTo_country', $this->pedido->getPais());
        $analise->addChild('billTo_postalCode', $this->pedido->getCep());
        $analise->addChild('billTo_customerID', $this->cliente->getId());
        $analise->addChild('billTo_customerPassword', $this->cliente->getSenha());
        $analise->addChild('billTo_personalID', $this->cliente->getDocumento());
        $analise->addChild('billTo_email', $this->cliente->getEmail());
        $analise->addChild('billTo_firstName', $this->cliente->getNome());
        $analise->addChild('billTo_lastName', $this->cliente->getSobrenome());
        $analise->addChild('billTo_phoneNumber', $this->cliente->getTelefone());
        $analise->addChild('billTo_ipAddress', $this->cliente->getIp());

        // Informações de entrega do pedido
        $analise->addChild('shipto_street1', $this->cliente->getEndereco());
        $analise->addChild('shipto_street2', $this->cliente->getComplemento());
        $analise->addChild('shipto_city', $this->cliente->getCidade());
        $analise->addChild('shipto_state', $this->cliente->getEstado());
        $analise->addChild('shipto_country', $this->cliente->getPais());
        $analise->addChild('shipto_postalCode', $this->cliente->getCep());
        $analise->addChild('shipTo_phoneNumber', $this->cliente->getTelefone());

        // Device Finger Print ID
        $analise->addChild('deviceFingerprintID', 'null');

        // Informações caso a venda seja de passagem área são obrigatórios, porém nulos
        $analise->addChild('decisionManager_travelData_completeRoute', 'NULL');
        $analise->addChild('decisionManager_travelData_departureDateTime', 'NULL');
        $analise->addChild('decisionManager_travelData_journeyType', 'NULL');
        $analise->addChild('decisionManager_travelData_leg_origin', '');
        $analise->addChild('decisionManager_travelData_leg_destination', '');

        // informações de venda
        $analise->addChild('purchaseTotals_currency', $this->pedido->getMoeda());
        $analise->addChild('purchaseTotals_grandTotalAmount', number_format($this->pedido->getPrecoTotal(), 2));
        $analise->addChild('item_unitPrice', number_format($this->pedido->getPrecoUnitario(), 2));

        // Informações do passageiro caso seja uma passagem área
        $analise->addChild('item_passengerFirstName', 'NULL');
        $analise->addChild('item_passengerLastName', 'NULL');
        $analise->addChild('item_passengerEmail', 'NULL');
        $analise->addChild('item_passengerID', 'NULL');

        // Adiciona tags adicionais
        $this->addXmlAdicionais($analise);

        // Adiciona Tags Opcionais, porém algumas requeridas pela Cielo são obrigatórias
        $mdd = $analise->addChild('mdd');
        $this->addXmlOpcionais($mdd);

        return $analise;
    }
} 