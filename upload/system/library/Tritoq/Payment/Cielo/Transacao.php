<?php

namespace Tritoq\Payment\Cielo;

use Tritoq\Payment\Cielo\AnaliseRisco\AnaliseResultado;
use Tritoq\Payment\Exception\InvalidArgumentException;

/**
 *
 * Representação da Transação do Cartão
 *
 *
 * Class Transacao
 *
 * @category  Library
 * @copyright Artur Magalhães <nezkal@gmail.com>
 * @package   Tritoq\Payment\Cielo
 * @license   GPL-3.0+
 */
class Transacao
{
    /**
     *
     * Constante que informa o produto da transação é crédito a vista
     *
     * @const integer
     */
    const PRODUTO_CREDITO_AVISTA = 1;
    /**
     *
     * Constante que informa o produto da transação é parcelado pela Loja
     *
     * @const integer
     */
    const PRODUTO_PARCELADO_LOJA = 2;
    /**
     *
     * Constante que informa o produto da transação é parcelado pela administradora
     *
     * @const integer
     */
    const PRODUTO_PARCELADO_ADMINISTRADORA = 3;
    /**
     *
     * Constante que informa o produto da transação é débito
     *
     * @const string
     */
    const PRODUTO_DEBITO = 'A';

    /**
     *
     * Status quando a transação é criada
     *
     * @const integer
     */
    const STATUS_CRIADA = 0;

    /**
     *
     * Status quando a transação está em andamento
     *
     * @const integer
     */
    const STATUS_ANDAMENTO = 1;

    /**
     *
     * Status quando a transação foi autenticada
     *
     * @const integer
     */
    const STATUS_AUTENTICADA = 2;

    /**
     *
     * Status quando a transação não foi autenticada
     *
     * @const integer
     */
    const STATUS_NAO_AUTENTICADA = 3;

    /**
     *
     * Status quando a transação foi autorizada
     *
     * @const integer
     */
    const STATUS_AUTORIZADA = 4;


    /**
     *
     * Status quando a transação não foi autorizada
     *
     * @const integer
     */
    const STATUS_NAO_AUTORIZADA = 5;


    /**
     *
     * Status quando a transação foi capturada
     *
     * @const integer
     */
    const STATUS_CAPTURADA = 6;

    /**
     *
     * Status quando a transação foi cancelada
     *
     * @const integer
     */

    const STATUS_CANCELADA = 9;

    /**
     *
     * Status quando a transação está em autenticação (cartão de débito)
     *
     * @const integer
     */
    const STATUS_EM_AUTENTICACAO = 10;

    /**
     *
     * Status quando a transação está em cancelamento
     *
     * @const integer
     */
    const STATUS_EM_CANCELAMENTO = 12;

    /**
     *
     * Status quando houve algum erro de requisição
     *
     * @const integer
     */
    const STATUS_ERRO = 99;

    /**
     *
     * Indicador de autorização - Não autorizar (somente autenticar)
     *
     * @const integer
     */
    const AUTORIZAR_NAO_AUTORIZAR = 0;

    /**
     *
     * Indicador de autorização - Autorizar somente se for autenticada
     *
     * @const integer
     */
    const AUTORIZAR_SOMENTE_AUTENTICADA = 1;

    /**
     *
     * Indicador de autorização - Autorizar autenticada e não autenticada
     *
     * @const integer
     */
    const AUTORIZAR_AUTENTICADA_NAO_AUTENTICADA = 2;

    /**
     *
     * Indicador de autorização - Autorizar sem passar por autentição (usada somente para cartões de crédito)
     *
     *
     * Para bandeiras: Diners, Discover, Elo, Amex, Aura e JCB será usada sempre este valor(3)
     *
     * @const integer
     */
    const AUTORIZAR_SEM_AUTENTICACAO = 3;

    /**
     *
     * Flag para captura automática
     *
     * @const string
     */
    const CAPTURA_SIM = 'true';

    /**
     *
     * Flag para não captura automática
     *
     * @const string
     */
    const CAPTURA_NAO = 'false';

    /**
     *
     * Requisição tipo Transação
     *
     * @const string
     */
    const REQUISICAO_TIPO_TRANSACAO = 'transacao';
    /**
     *
     * Requisição tipo Captura
     *
     * @const string
     */
    const REQUISICAO_TIPO_CAPTURA = 'captura';
    /**
     *
     * Requisição tipo autorização
     *
     * @const string
     */
    const REQUISICAO_TIPO_AUTORIZACAO = 'autorizacao';
    /**
     *
     * Requisição tipo cancela
     *
     * @const string
     */
    const REQUISICAO_TIPO_CANCELA = 'cancela';

    /**
     *
     * Requisição tipo consulta
     *
     * @const string
     */
    const REQUISICAO_TIPO_CONSULTA = 'consulta';


    /**
     *
     * Resultado da Análise de Risco
     *
     * @var AnaliseResultado
     */
    private $analiseResultado;


    /**
     *
     * TID - Transaçao ID informada pela Cielo
     *
     * Armazenar esse número, pois através dele que são realizadas as consultas posteriores
     *
     * @var integer
     */
    private $_tid;

    /**
     *
     * Tipo de produto
     *
     * 1 - Crédito a Vista                  PRODUTO_CREDITO_AVISTA
     * 2 - Parcelado Loja                   PRODUTO_PARCELADO_LOJA
     * 3 - Parcelado Administradora         PRODUTO_PARCELADO_ADMINISTRADORA
     * A - Débito                           PRODUTO_DEBITO
     *
     * @var integer
     */
    private $_produto;

    /**
     *
     * Número de parcelas da compra
     *
     * @var integer
     */
    private $_parcelas;

    /**
     *
     * Flag de captura automática
     *
     * @var string
     */
    private $_capturar = 'false';

    /**
     *
     * Indicador de autorização
     *
     * 0 - Não autorizar                    AUTORIZAR_NAO_AUTORIZAR
     * 1 - Somente Autenticada              AUTORIZAR_SOMENTE_AUTENTICADA
     * 2 - Autenticada e Não Autenticada    AUTORIZAR_AUTENTICADA_NAO_AUTENTICADA
     * 3 - Autorizar sem autenticação       AUTORIZAR_SEM_AUTENTICACAO
     *
     * @var integer
     */
    private $_autorizar = 2;

    /**
     *
     * Campo livre disponível para o Estabelecimento / Loja
     *
     * @var string
     */
    private $_campoLivre;

    /**
     *
     * Status da transação
     *
     * @var integer
     */
    private $_status;

    /**
     * @var string
     */
    private $_statusAnalise;

    /**
     *
     * Token da transação
     *
     * @var string
     */
    private $_token = '';

    /**
     *
     * URL de redirecionamento à Cielo
     *
     * @var string
     */
    private $_urlAutenticacao = '';

    /**
     *
     * Array onde são guardadas as requisições
     *
     * @var array
     */
    private $_requisicoes = array();


    /**
     *
     * Adiciona uma requisição e separa por seu tipo
     *
     * @param Requisicao $requisicao
     * @param string     $type
     *
     * @throws \Tritoq\Payment\Exception\InvalidArgumentException
     * @return $this
     */
    public function addRequisicao(Requisicao $requisicao, $type = '')
    {
        switch ($type) {
            case self::REQUISICAO_TIPO_AUTORIZACAO:
            case self::REQUISICAO_TIPO_CANCELA:
            case self::REQUISICAO_TIPO_CAPTURA:
            case self::REQUISICAO_TIPO_CONSULTA:
            case self::REQUISICAO_TIPO_TRANSACAO:
                $this->_requisicoes[$type][] = $requisicao;
                return $this;
            default:
                throw new InvalidArgumentException('Tipo de requisiçao inválida');
        }
    }

    /**
     *
     * Retorna a requisição, se passado o tipo retorna por categoria, caso contrário retorna o array inteiro
     *
     * @param null $type
     *
     * @throws \Tritoq\Payment\Exception\InvalidArgumentException
     * @return array
     */
    public function getRequisicoes($type = null)
    {
        if (isset($type)) {
            switch ($type) {
                case self::REQUISICAO_TIPO_AUTORIZACAO:
                case self::REQUISICAO_TIPO_CANCELA:
                case self::REQUISICAO_TIPO_CAPTURA:
                case self::REQUISICAO_TIPO_TRANSACAO:
                case self::REQUISICAO_TIPO_CONSULTA:
                    return $this->_requisicoes[$type];
                default:
                    throw new InvalidArgumentException('Tipo de requisiçao inválida');
            }
        } else {
            return $this->_requisicoes;
        }
    }

    /**
     *
     * Retorna o Token
     *
     * @param string $token
     *
     * @return $this
     */
    public function setToken($token)
    {
        $this->_token = $token;
        return $this;
    }

    /**
     *
     * Seta o Token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->_token;
    }


    /**
     *
     * Seta o Status da Transação
     *
     * @param int $status
     *
     * @throws \Tritoq\Payment\Exception\InvalidArgumentException
     * @return $this
     */
    public function setStatus($status)
    {
        switch ((integer)$status) {
            case self::STATUS_CRIADA:
            case self::STATUS_ANDAMENTO:
            case self::STATUS_AUTENTICADA:
            case self::STATUS_NAO_AUTENTICADA:
            case self::STATUS_AUTORIZADA:
            case self::STATUS_NAO_AUTORIZADA:
            case self::STATUS_CAPTURADA:
            case self::STATUS_CANCELADA:
            case self::STATUS_EM_AUTENTICACAO:
            case self::STATUS_EM_CANCELAMENTO:
            case self::STATUS_ERRO:
                $this->_status = $status;
                return $this;
            default:
                throw new InvalidArgumentException('Status de Transação Inexistente');
        }

    }

    /**
     *
     * Retorna o status da transação
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->_status;
    }


    /**
     *
     * Seta o TID da transação
     *
     * @param int $tid
     *
     * @return $this
     */
    public function setTid($tid)
    {
        $this->_tid = $tid;

        return $this;
    }

    /**
     *
     * Retorna o TID da transação
     *
     * @return int
     */
    public function getTid()
    {
        return $this->_tid;
    }

    /**
     *
     * Seta o Indicador de Produto
     *
     * 1 - Crédito a Vista                  PRODUTO_CREDITO_AVISTA
     * 2 - Parcelado Loja                   PRODUTO_PARCELADO_LOJA
     * 3 - Parcelado Administradora         PRODUTO_PARCELADO_ADMINISTRADORA
     * A - Débito                           PRODUTO_DEBITO
     *
     *
     * @param int $produto
     *
     * @throws InvalidArgumentException
     * @return $this
     */
    public function setProduto($produto)
    {
        switch ($produto) {
            case self::PRODUTO_CREDITO_AVISTA:
            case self::PRODUTO_PARCELADO_LOJA:
            case self::PRODUTO_PARCELADO_ADMINISTRADORA:
            case self::PRODUTO_DEBITO:
                $this->_produto = $produto;
                return $this;
            default:
                throw new InvalidArgumentException("Indicador de Produto inválido");
        }
    }

    /**
     *
     * Retorna o indicador de Produto
     *
     * @return int
     */
    public function getProduto()
    {
        return $this->_produto;
    }

    /**
     *
     * Seta o número de parcelas da compra / transação
     *
     * @param int $parcelas
     *
     * @throws InvalidArgumentException
     * @return $this
     */
    public function setParcelas($parcelas)
    {
        if ((integer)$parcelas > 12 || (integer)$parcelas < 1) {
            throw new InvalidArgumentException('Número de parcelas inválidas');
        }


        $this->_parcelas = (integer)$parcelas;

        // se caso a parcela for maior que é setado o produto para PARCELADO_LOJA automaticamente

        if ($this->_parcelas > 1 && $this->_produto = 1) {
            $this->setProduto(self::PRODUTO_PARCELADO_LOJA);
        }

        return $this;
    }

    /**
     *
     * Retorna o número de parcelas
     *
     * @return int
     */
    public function getParcelas()
    {
        return $this->_parcelas;
    }

    /**
     *
     * Seta se a transação vai ser capturada automaticamente (não recomendado)
     *
     * @param string $capturar
     *
     * @throws InvalidArgumentException
     * @return $this
     */
    public function setCapturar($capturar)
    {
        switch ($capturar) {
            case 'true':
            case 'false':
                $this->_capturar = $capturar;
                return $this;
            default:
                throw new InvalidArgumentException('O indicador capturar deve ser "true" ou "false"');
        }

    }

    /**
     *
     * Retorna se a captura será realizada automaticamente
     *
     * @return string
     */
    public function getCapturar()
    {
        return $this->_capturar;
    }

    /**
     *
     * Seta o indicador de autorização
     *
     * 0 - Não autorizar                    AUTORIZAR_NAO_AUTORIZAR
     * 1 - Somente Autenticada              AUTORIZAR_SOMENTE_AUTENTICADA
     * 2 - Autenticada e Não Autenticada    AUTORIZAR_AUTENTICADA_NAO_AUTENTICADA
     * 3 - Autorizar sem autenticação       AUTORIZAR_SEM_AUTENTICACAO
     *
     * @param int $autorizar
     *
     * @throws InvalidArgumentException
     * @return $this
     */
    public function setAutorizar($autorizar)
    {
        switch ($autorizar) {
            case self::AUTORIZAR_NAO_AUTORIZAR:
            case self::AUTORIZAR_SOMENTE_AUTENTICADA:
            case self::AUTORIZAR_AUTENTICADA_NAO_AUTENTICADA:
            case self::AUTORIZAR_SEM_AUTENTICACAO:
                $this->_autorizar = $autorizar;
                return $this;
            default:
                throw new InvalidArgumentException("Indicador autorizar {$autorizar} inválido");

        }

    }

    /**
     *
     * Retorna o indicador de autorização
     *
     * @return int
     */
    public function getAutorizar()
    {
        return $this->_autorizar;
    }

    /**
     *
     * Seta o campo livre disponível para o estabelecimento
     *
     * @param string $campoLivre
     *
     * @return $this
     */
    public function setCampoLivre($campoLivre)
    {
        $this->_campoLivre = $campoLivre;
        return $this;
    }

    /**
     *
     * Retorna o campo livre do estabelecimento
     *
     * @return string
     */
    public function getCampoLivre()
    {
        return $this->_campoLivre;
    }

    /**
     * @param \Tritoq\Payment\Cielo\AnaliseRisco\AnaliseResultado $analiseResultado
     *
     * @return $this
     */
    public function setAnaliseResultado($analiseResultado)
    {
        $this->analiseResultado = $analiseResultado;
        return $this;
    }

    /**
     * @return \Tritoq\Payment\Cielo\AnaliseRisco\AnaliseResultado
     */
    public function getAnaliseResultado()
    {
        return $this->analiseResultado;
    }

    /**
     * @param string $statusAnalise
     *
     * @return $this
     */
    public function setStatusAnalise($statusAnalise)
    {
        $this->_statusAnalise = $statusAnalise;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatusAnalise()
    {
        return $this->_statusAnalise;
    }

    /**
     * @param string $urlAutenticacao
     *
     * @return $this
     */
    public function setUrlAutenticacao($urlAutenticacao)
    {
        $this->_urlAutenticacao = $urlAutenticacao;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrlAutenticacao()
    {
        return $this->_urlAutenticacao;
    }

    /**
     *
     * Retorna uma print sobre a Trasanção
     *
     * @return string
     */
    public function __toString()
    {
        $output = "TID: " . $this->getTid() . "\n"
            . "Status: " . $this->getStatus() . "\n";

        return $output;
    }
} 