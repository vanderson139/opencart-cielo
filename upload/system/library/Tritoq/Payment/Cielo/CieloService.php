<?php

namespace Tritoq\Payment\Cielo;

use Tritoq\Payment\Cielo\AnaliseRisco\AnaliseResultado;
use Tritoq\Payment\Exception\InvalidArgumentException;
use Tritoq\Payment\Exception\ResourceNotFoundException;
use Tritoq\Payment\PortadorInterface;

/**
 *
 * Classe de abstração do Serviço de Integração
 *
 * Ela é responsável pela comunicação entre o seu site e o Webservice da Cielo
 *
 *
 * Class CieloService
 *
 * @category  Library
 * @copyright Artur Magalhães <nezkal@gmail.com>
 * @package   Tritoq\Payment\Cielo
 * @license   GPL-3.0+
 */
class CieloService
{
    /**
     *
     * Constante indicando a versão do XML/Api usada
     *
     * @const string
     */
    const VERSAO = '1.4.0';


    /**
     * @const string
     */
    const URL_TESTE = 'https://qasecommerce.cielo.com.br/servicos/ecommwsec.do';

    /**
     * @const string
     */
    const URL_PRODUCAO = 'https://ecommerce.cielo.com.br/servicos/ecommwsec.do';

    /**
     *
     * ID de chamada da Transação
     *
     * @const integer
     */
    const TRANSACAO_ID = 1;

    /**
     *
     * Cabeçalho XML para requisição de transação
     *
     * @const string
     */
    const TRANSACAO_HEADER = 'requisicao-transacao';

    /**
     *
     * ID de chamada da Autorização
     *
     * @const integer
     */
    const AUTORIZACAO_ID = 2;

    /**
     *
     * Cabeçalho XML para requisição de autorização
     *
     * @const string
     */
    const AUTORIZACAO_HEADER = 'requisicao-autorizacao-tid';

    /**
     *
     * ID de chamada de Captura de Transação
     *
     * @const integer
     */
    const CAPTURA_ID = 3;

    /**
     *
     * Cabeçalho XML para requisição de captura
     *
     * @const string
     */
    const CAPTURA_HEADER = 'requisicao-captura';

    /**
     *
     * ID de chamada de Cancelamento de Transação
     *
     * @const integer
     */
    const CANCELAMENTO_ID = 4;

    /**
     *
     * Cabeçalho XML de requisição de cancelamento
     *
     * @const string
     */
    const CANCELAMENTO_HEADER = 'requisicao-cancelamento';

    /**
     *
     * ID de chamada para consulta da Transação
     *
     * @const integer
     */
    const CONSULTA_ID = 5;

    /**
     *
     * Cabeçalho XML de Consulta da Transação
     *
     * @const string
     */
    const CONSULTA_HEADER = 'requisicao-consulta';

    /**
     *
     * Loja Credenciada a Cielo
     *
     * @var Loja
     */
    private $loja;

    /**
     *
     * Transação
     *
     * @var Transacao
     */
    private $transacao;

    /**
     *
     * Pedido do Cliente/Portador
     *
     * @var Pedido
     */
    private $pedido;

    /**
     *
     * Portador do Cartão
     *
     * @var PortadorInterface
     */
    private $portador;

    /**
     *
     * Cartão de Crédito
     *
     * @var Cartao
     */
    private $cartao;

    /**
     *
     * Objeto que armazena os dados para a Análise de Risco
     *
     * @var AnaliseRisco
     */
    private $analiseRisco;

    /**
     *
     * Flag de Indicação se está ativo a análise de Risco
     *
     * @var bool
     */
    private $habilitarAnaliseRisco = false;

    /**
     *
     * Número da versão de conexão do SSL
     * 1 - SSLV1
     * 2 - SSLV2
     * 3 - SSLV3
     * 4 - SSLV4
     *
     * @var int
     */

    private $sslVersion = 4;

    /**
     * @var string
     */
    private $ssl = null;

    /**
     * @param string $ssl
     *
     * @return $this
     */
    public function setSsl($ssl)
    {
        $this->ssl = $ssl;
        return $this;
    }

    /**
     * @return string
     */
    public function getSsl()
    {
        return $this->ssl;
    }

    /**
     * @return int
     */
    public function getSslVersion()
    {
        return $this->sslVersion;
    }

    /**
     * @param int $sslVersion
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setSslVersion($sslVersion)
    {
        $sslVersion = intval($sslVersion);

        if ($sslVersion < 1 || $sslVersion > 4) {
            throw new InvalidArgumentException('O valor experado para a versão do SSL é de 1 a 4');
        }

        $this->sslVersion = $sslVersion;
        return $this;
    }


    /**
     *
     * Construtor onde já implementa padrão para setar os objetos por array
     *
     * @param null|array $options
     *
     * @throws \Tritoq\Payment\Exception\InvalidArgumentException
     */
    function __construct($options = null)
    {
        if (isset($options) && !is_array($options)) {
            throw new InvalidArgumentException('Experado um array, ' . gettype($options));
        }

        if (isset($options['loja']) && $options['loja'] instanceof Loja) {
            $this->loja = $options['loja'];
            $this->ssl = $options['loja']->getSslCertificado();
        }

        if (isset($options['transacao']) && $options['transacao'] instanceof Transacao) {
            $this->transacao = $options['transacao'];
        }

        if (isset($options['pedido']) && $options['pedido'] instanceof Pedido) {
            $this->pedido = $options['pedido'];
        }

        if (isset($options['portador']) && $options['portador'] instanceof Portador) {
            $this->portador = $options['portador'];
        }

        if (isset($options['cartao']) && $options['cartao'] instanceof Cartao) {
            $this->cartao = $options['cartao'];
        }

        if (isset($options['analise']) && $options['analise'] instanceof AnaliseRisco) {
            $this->analiseRisco = $options['analise'];
            $this->habilitarAnaliseRisco = true;
        }
    }

    /**
     *
     * Seta o objeto de análise de Risco
     *
     * @param \Tritoq\Payment\Cielo\AnaliseRisco $analiseRisco
     *
     * @return $this
     */
    public function setAnaliseRisco($analiseRisco)
    {
        $this->analiseRisco = $analiseRisco;
        return $this;
    }

    /**
     *
     * Retorna o objeto de Análise de Risco
     *
     * @return \Tritoq\Payment\Cielo\AnaliseRisco
     */
    public function getAnaliseRisco()
    {
        return $this->analiseRisco;
    }

    /**
     *
     * Seta a flag de análise de risco
     *
     * @param boolean $habilitarAnaliseRisco
     *
     * @throws \Tritoq\Payment\Exception\InvalidArgumentException
     * @return $this
     */
    public function setHabilitarAnaliseRisco($habilitarAnaliseRisco)
    {
        if (!is_bool($habilitarAnaliseRisco)) {
            throw new InvalidArgumentException('Opçao inválida para habilitar Análise de Risco');
        }

        $this->habilitarAnaliseRisco = $habilitarAnaliseRisco;
        return $this;
    }

    /**
     *
     * Retorna o valor da flag de analise de risco
     *
     * @return boolean
     */
    public function getHabilitarAnaliseRisco()
    {
        return $this->habilitarAnaliseRisco;
    }

    /**
     *
     * Retorna o valor da flag se está ativo a análise de risco
     *
     * @return bool
     */
    public function isAnaliseRisco()
    {
        return $this->habilitarAnaliseRisco;
    }


    /**
     *
     * Adiciona os valores de credenciamento da loja no XML
     *
     * @param \SimpleXMLElement $xml
     *
     * @return \SimpleXMLElement
     */
    private function addNodeDadosEc(\SimpleXMLElement $xml)
    {
        $ec = $xml->addChild('dados-ec', '');
        $ec->addChild('numero', $this->loja->getNumeroLoja());
        $ec->addChild('chave', $this->loja->getChave());

        return $ec;
    }

    /**
     *
     * Adiciona as informações de dados do portador ao XML de Requisição
     *
     *
     * @param \SimpleXMLElement $xml
     *
     * @return \SimpleXMLElement
     */
    private function addNodeDadosPortador(\SimpleXMLElement $xml)
    {
        $dp = $xml->addChild('dados-portador');
        $dp->addChild('numero', $this->cartao->getNumero());
        $dp->addChild('validade', $this->cartao->getValidade());
        $dp->addChild('indicador', $this->cartao->getIndicador());
        $dp->addChild('codigo-seguranca', $this->cartao->getCodigoSegurancaCartao());
        $dp->addChild('nome-portador', $this->cartao->getNomePortador());
        $dp->addChild('token', $this->transacao->getToken());

        return $dp;
    }

    /**
     *
     * Adiciona informações sobre o pagamento ao XML de Requisição
     *
     * @param \SimpleXMLElement $xml
     *
     * @return \SimpleXMLElement
     */
    private function addNodeFormaPagamento(\SimpleXMLElement $xml)
    {
        $fp = $xml->addChild('forma-pagamento');
        $fp->addChild('bandeira', $this->cartao->getBandeira());
        $fp->addChild('produto', $this->transacao->getProduto());
        $fp->addChild('parcelas', $this->transacao->getParcelas());

        return $fp;
    }


    /**
     *
     * Adiciona ao XML informações do Portador para o AVS da Cielo
     *
     * AVS = Address Verification Secured
     *
     *
     * @param \SimpleXMLElement $xml
     */
    private function addNodeAvs(\SimpleXMLElement $xml)
    {
        $avs = $xml->addChild('avs');

        $node = dom_import_simplexml($avs);
        $no = $node->ownerDocument;


        $root = new \SimpleXMLElement('<root></root>');

        $obj = $root->addChild('dados-avs');
        $obj->addChild('endereco', $this->portador->getEndereco());
        $obj->addChild('complemento', $this->portador->getComplemento());
        $obj->addChild('numero', $this->portador->getNumero());
        $obj->addChild('bairro', $this->portador->getBairro());
        $obj->addChild('cep', $this->portador->getCep());

        $node->appendChild($no->createCDATASection($obj->asXML()));
    }

    /**
     *
     * Adiciona informações do Pedido ao XML de Requisição
     *
     * @param \SimpleXMLElement $xml
     *
     * @return \SimpleXMLElement
     */
    private function addNodeDadosPedido(\SimpleXMLElement $xml)
    {
        $dp = $xml->addChild('dados-pedido');
        $dp->addChild('numero', $this->pedido->getNumero());
        $dp->addChild('valor', $this->pedido->getValor());
        $dp->addChild('moeda', $this->pedido->getMoeda());
        $dp->addChild('data-hora', $this->pedido->getDataHora());
        $dp->addChild('descricao', $this->pedido->getDescricao());
        $dp->addChild('idioma', $this->pedido->getIdioma());
        $dp->addChild('soft-descriptor', $this->loja->getNomeLoja());

        if (strlen($this->pedido->getTaxaEmbarque()) > 0) {
            $dp->addChild('taxa-embarque', $this->pedido->getTaxaEmbarque());
        }

        return $dp;

    }

    /**
     *
     * Metódo responsável por montar a requisição e enviar ao servidor da Cielo
     *
     * @param \SimpleXMLElement $xml
     *
     * @return \Tritoq\Payment\Cielo\Requisicao
     */
    private function enviaRequisicao(\SimpleXMLElement $xml)
    {

        // URL para o ambiente de produção
        $url = self::URL_PRODUCAO;

        // URL para o ambiente de teste
        if ($this->loja->getAmbiente() === Loja::AMBIENTE_TESTE) {
            $url = self::URL_TESTE;
        }

        $requisicao = new Requisicao(
            array(
                'sslVersion' => $this->getSslVersion()
            )
        );

        $requisicao
            ->setUrl($url)
            ->setXmlRequisicao($xml)
            ->send(isset($this->ssl) ? $this->ssl : null);

        return $requisicao;
    }

    /**
     *
     * Atualiza informações na Transação
     *
     * @param Requisicao $requisicao
     * @param            $requisicaoTipo
     */
    private function updateTransacao(Requisicao $requisicao, $requisicaoTipo)
    {
        // Atualiza informações na Transação de acordo com a resposta da Cielo
        $this->transacao->addRequisicao($requisicao, $requisicaoTipo);

        if (!$requisicao->containsError()) {
            // Pega o retorno XML da Requisição
            $xmlRetorno = $requisicao->getXmlRetorno();

            $this->transacao->setStatus($xmlRetorno->status);

            if ($this->habilitarAnaliseRisco) {

                $var = 'analise-fraude-retorno';
                $analise = $xmlRetorno->$var;

                $analiseResultado = new AnaliseResultado();
                $analiseResultado->setUp($analise);

                $this->transacao->setAnaliseResultado($analiseResultado);
                $this->transacao->setStatusAnalise($analiseResultado->getStatus());
            }

            // Pega a URL de redirecionamento à Cielo
            if(!empty($xmlRetorno->{'url-autenticacao'})) {
                $this->transacao->setUrlAutenticacao($xmlRetorno->{'url-autenticacao'});
            }

            if (!$this->transacao->getTid()) {
                // Atualiza a TID da Transação
                $this->transacao->setTid($xmlRetorno->tid);
            }
        } else {
            $this->transacao->setStatus(Transacao::STATUS_ERRO);
        }
    }

    /**
     *
     * Realiza a transação, nos parametros são passados para gerar o token e usar o AVS
     *
     * @param bool $gerarToken
     * @param bool $checkAvs
     *
     * @throws \Tritoq\Payment\Exception\InvalidArgumentException
     * @return $this
     */
    public function doTransacao($gerarToken = false, $checkAvs = false)
    {

        $_xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>"
            . "<%s id='%d' versao='%s'>"
            . "</%s>";

        $_xml = sprintf(
            $_xml,
            self::TRANSACAO_HEADER,
            self::TRANSACAO_ID,
            self::VERSAO,
            self::TRANSACAO_HEADER
        );

        $xml = new \SimpleXMLElement($_xml);

        // Adicionando blocos estáticos ao XML

        $this->addNodeDadosEc($xml);
        $this->addNodeDadosPortador($xml);
        $this->addNodeDadosPedido($xml);
        $this->addNodeFormaPagamento($xml);


        $xml->addChild('url-retorno', $this->loja->getUrlRetorno());
        $xml->addChild('autorizar', $this->transacao->getAutorizar());
        $xml->addChild('capturar', $this->transacao->getCapturar());
        $xml->addChild('campo-livre', $this->transacao->getCampoLivre());
        $xml->addChild('bin', substr($this->cartao->getNumero(), 0, 6));

        // Verifica se vai ser gerado o token

        $xml->addChild('gerar-token', $gerarToken ? 'true' : 'false');

        if ($this->habilitarAnaliseRisco) {
            if (!isset($this->analiseRisco)) {
                throw new InvalidArgumentException('Sem objeto de Analise de risco');
            }
            $analise = $xml->addChild('analise-fraude');
            $this->analiseRisco->criarXml($analise);
        }

        // Incorpora ao xml o AVS da Cielo

        if ($this->portador instanceof PortadorInterface && $checkAvs) {
            $this->addNodeAvs($xml);
        }

        // Envia a requisição a Cielo
        $requisicao = $this->enviaRequisicao($xml);

        // Atualiza informações da Transação
        $this->updateTransacao($requisicao, Transacao::REQUISICAO_TIPO_TRANSACAO);

        return $this;
    }

    /**
     *
     * Método que faz a autorização da Requisição previamente autenticada
     *
     * Geralmente utilizada em Cartão de Débito
     *
     * @return $this
     * @throws \Tritoq\Payment\Exception\ResourceNotFoundException
     */
    public function doAutorizacao()
    {
        if (strlen($this->transacao->getTid()) === 0) {
            throw new ResourceNotFoundException('Não foi possível fazer a autorização TID não informado!');
        }

        $_xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>"
            . "<%s id='%d' versao='%s'>"
            . "</%s>";

        $_xml = sprintf(
            $_xml,
            self::AUTORIZACAO_HEADER,
            self::AUTORIZACAO_ID,
            self::VERSAO,
            self::AUTORIZACAO_HEADER
        );

        $xml = new \SimpleXMLElement($_xml);

        // seta o TID da Transação
        $xml->addChild('tid', $this->transacao->getTid());

        // Adiciona os dados de credenciamento da Loja
        $this->addNodeDadosEc($xml);

        // Envia a requisição ao servidor da Cielo
        $requisicao = $this->enviaRequisicao($xml);

        // Atualiza informações da Transação
        $this->updateTransacao($requisicao, Transacao::REQUISICAO_TIPO_AUTORIZACAO);

        return $this;
    }

    /**
     *
     * Método que faz a requisição da captura da transação
     *
     * @return $this
     * @throws \Tritoq\Payment\Exception\ResourceNotFoundException
     */
    public function doCaptura()
    {
        if (strlen($this->transacao->getTid()) === 0) {
            throw new ResourceNotFoundException('Não foi possível fazer a autorização TID não informado!');
        }

        $_xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>"
            . "<%s id='%d' versao='%s'>"
            . "</%s>";

        $_xml = sprintf(
            $_xml,
            self::CAPTURA_HEADER,
            self::CAPTURA_ID,
            self::VERSAO,
            self::CAPTURA_HEADER
        );

        $xml = new \SimpleXMLElement($_xml);
        $xml->addChild('tid', $this->transacao->getTid());

        // Adiciona os dados de credenciamento da loja
        $this->addNodeDadosEc($xml);

        // Envia a requisição para a Cielo
        $requisicao = $this->enviaRequisicao($xml);

        // Atualiza informações da Transação
        $this->updateTransacao($requisicao, Transacao::REQUISICAO_TIPO_CAPTURA);

        return $this;
    }

    /**
     *
     * Método que faz a requisição de cancelamento da transação
     *
     * @return $this
     * @throws \Tritoq\Payment\Exception\ResourceNotFoundException
     */
    public function doCancela()
    {
        if (strlen($this->transacao->getTid()) === 0) {
            throw new ResourceNotFoundException('Não foi possível fazer a autorização TID não informado!');
        }

        $_xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>"
            . "<%s id='%d' versao='%s'>"
            . "</%s>";

        $_xml = sprintf(
            $_xml,
            self::CANCELAMENTO_HEADER,
            self::CANCELAMENTO_ID,
            self::VERSAO,
            self::CANCELAMENTO_HEADER
        );

        $xml = new \SimpleXMLElement($_xml);
        $xml->addChild('tid', $this->transacao->getTid());

        // Adiciona os dados de credenciamento da loja
        $this->addNodeDadosEc($xml);

        // Envia a requisição para a Cielo
        $requisicao = $this->enviaRequisicao($xml);

        // Atualiza informações da Transação
        $this->updateTransacao($requisicao, Transacao::REQUISICAO_TIPO_CANCELA);

        return $this;
    }

    /**
     *
     * Método que faz a requisição de consulta da transação
     *
     * @return $this
     * @throws \Tritoq\Payment\Exception\ResourceNotFoundException
     */
    public function doConsulta()
    {
        if (strlen($this->transacao->getTid()) === 0) {
            throw new ResourceNotFoundException('Não foi possível fazer a autorização TID não informado!');
        }

        $_xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>"
            . "<%s id='%d' versao='%s'>"
            . "</%s>";

        $_xml = sprintf(
            $_xml,
            self::CONSULTA_HEADER,
            self::CONSULTA_ID,
            self::VERSAO,
            self::CONSULTA_HEADER
        );

        $xml = new \SimpleXMLElement($_xml);
        $xml->addChild('tid', $this->transacao->getTid());

        // Adiciona os dados de credenciamento da loja
        $this->addNodeDadosEc($xml);

        // Envia a requisição a Cielo
        $requisicao = $this->enviaRequisicao($xml);

        // Atualiza informações da Transação
        $this->updateTransacao($requisicao, Transacao::REQUISICAO_TIPO_CONSULTA);

        return $this;
    }

    /**
     *
     * Método para debugar uma transação com simulação a um xml já salvo
     *
     * @param \SimpleXMLElement $xml
     *
     * @return $this
     */
    public function debugConsulta(\SimpleXMLElement $xml)
    {

        $requisicao = new Requisicao();
        $requisicao->setXmlRetorno($xml);

        $this->updateTransacao($requisicao, Transacao::REQUISICAO_TIPO_CONSULTA);

        return $this;
    }

    /**
     *
     * Seta o objeto Cartão
     *
     * @param \Tritoq\Payment\Cielo\Cartao $cartao
     *
     * @return $this
     */
    public function setCartao(Cartao $cartao)
    {
        $this->cartao = $cartao;
        return $this;
    }

    /**
     *
     * Retorna o objeto Cartão
     *
     * @return \Tritoq\Payment\Cielo\Cartao
     */
    public function getCartao()
    {
        return $this->cartao;
    }


    /**
     *
     * Seta a loja credenciada
     *
     * @param \Tritoq\Payment\Cielo\Loja $loja
     *
     * @return $this
     */
    public function setLoja(Loja $loja)
    {
        $this->loja = $loja;
        return $this;
    }

    /**
     *
     * Retorna a Loja
     *
     * @return \Tritoq\Payment\Cielo\Loja
     */
    public function getLoja()
    {
        return $this->loja;
    }

    /**
     *
     * Seta o Pedido
     *
     * @param \Tritoq\Payment\Cielo\Pedido $pedido
     *
     * @return $this
     */
    public function setPedido(Pedido $pedido)
    {
        $this->pedido = $pedido;
        return $this;
    }

    /**
     *
     * Retorna o Pedido
     *
     * @return \Tritoq\Payment\Cielo\Pedido
     */
    public function getPedido()
    {
        return $this->pedido;
    }

    /**
     *
     * Seta o Portador
     *
     * @param \Tritoq\Payment\PortadorInterface $portador
     *
     * @return $this
     */
    public function setPortador(PortadorInterface $portador)
    {
        $this->portador = $portador;
        return $this;
    }

    /**
     *
     * Retorna o Portador
     *
     * @return \Tritoq\Payment\PortadorInterface
     */
    public function getPortador()
    {
        return $this->portador;
    }

    /**
     *
     * Seta a Transação
     *
     * @param \Tritoq\Payment\Cielo\Transacao $transacao
     *
     * @return $this
     */
    public function setTransacao(Transacao $transacao)
    {
        $this->transacao = $transacao;
        return $this;
    }

    /**
     *
     * Retorna a transação
     *
     * @return \Tritoq\Payment\Cielo\Transacao
     */
    public function getTransacao()
    {
        return $this->transacao;
    }
} 