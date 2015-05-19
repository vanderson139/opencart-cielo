<?php

namespace Tritoq\Payment\Cielo;


use Tritoq\Payment\Exception\InvalidArgumentException;
use Tritoq\Payment\Exception\ResourceNotFoundException;

/**
 *
 * Representação de uma requisição/chamada de URL
 *
 * Ela é responsável por enviar e buscar as informações no webservice da Cielo
 *
 *
 * Class Requisicao
 *
 * @category  Library
 * @copyright Artur Magalhães <nezkal@gmail.com>
 * @package   Tritoq\Payment\Cielo
 * @license   GPL-3.0+
 */
class Requisicao
{
    /**
     *
     * URL de chamada
     *
     * @var string
     */
    private $url;

    /**
     *
     * Objeto XML de Requisição
     *
     * @var \SimpleXMLElement
     */
    private $xmlRequisicao;

    /**
     *
     * Objeto XML de Retorno/Resposta
     *
     * @var \SimpleXMLElement
     */
    private $xmlRetorno;

    /**
     *
     * Valor de retorno em texto
     *
     * @var string
     */
    private $retorno;

    /**
     *
     * Status da resposta
     *
     * @var int
     */
    private $status = 200;

    /**
     *
     * Armazena mensagens de erros
     *
     * @var array
     */
    private $errors = array();

    /**
     * @var array
     */
    private $info = array();

    /**
     *
     * Versão SSL da conexão
     *
     * @var integer
     */
    private $sslVersion = 4;

    function __construct($options = null)
    {
        if (is_array($options)) {

            if (isset($options['sslVersion']) && is_int($options['sslVersion'])) {
                $this->sslVersion = $options['sslVersion'];
            }
        }
    }


    /**
     *
     * Retorna se a requisição conteve algum erro
     *
     * @return bool
     */
    public function containsError()
    {
        return sizeof($this->errors) > 0;
    }

    /**
     *
     * Retorna os erros ocorridos na requisição
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     *
     * Retorna informações da Requisição
     *
     * @return array
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     *
     * Retorna o valor de retorno
     *
     * @return string
     */
    public function getRetorno()
    {
        return $this->retorno;
    }

    /**
     *
     * Retorna em XML a resposta da requisição
     *
     * @return \SimpleXMLElement
     */
    public function getXmlRetorno()
    {
        return $this->xmlRetorno;
    }

    /**
     *
     * Retorna o status da resposta
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     *
     * Seta a URL que será chamada
     *
     * @param string $url
     *
     * @throws \Tritoq\Payment\Exception\InvalidArgumentException
     * @return $this
     */
    public function setUrl($url)
    {
        $valida = filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED);

        if ($valida == false) {
            throw new InvalidArgumentException('URL de retorno inválida.');
        }

        $this->url = $url;
        return $this;
    }

    /**
     *
     * Retorna a URL de chamada
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     *
     * Seta o XML de requisição
     *
     * @param \SimpleXMLElement $xmlRequisicao
     *
     * @return $this
     */
    public function setXmlRequisicao(\SimpleXMLElement $xmlRequisicao)
    {
        $this->xmlRequisicao = $xmlRequisicao;
        return $this;
    }

    /**
     *
     * Retorna o XML de Requisição
     *
     * @return \SimpleXMLElement
     */
    public function getXmlRequisicao()
    {
        return $this->xmlRequisicao;
    }

    /**
     *
     * Seta o XML de Retorno
     *
     * @param \SimpleXMLElement $xmlRetorno
     *
     * @return $this
     */
    public function setXmlRetorno($xmlRetorno)
    {
        $this->xmlRetorno = $xmlRetorno;
        return $this;
    }

    /**
     *
     * Metódo de chamada da requisição
     *
     * Feita em curl
     *
     * @param bool $ssl
     *
     * @throws \Tritoq\Payment\Exception\ResourceNotFoundException
     * @throws \Exception
     * @return $this
     */
    public function send($ssl = false)
    {
        if (!$this->xmlRequisicao instanceof \SimpleXMLElement) {
            throw new ResourceNotFoundException('XML de requisição está vazio');
        }

        // Iniciando o objeto Curl
        $_curl = curl_init();

        // Retornar a transferência ao objeto
        curl_setopt($_curl, CURLOPT_RETURNTRANSFER, 1);

        // Sempre utilizar uma nova conexão
        curl_setopt($_curl, CURLOPT_FRESH_CONNECT, 1);

        // Retornar Header
        curl_setopt($_curl, CURLOPT_HEADER, 0);

        // Modo verboso
        curl_setopt($_curl, CURLOPT_VERBOSE, 0);

        // Mostrar o corpo da requisição
        curl_setopt($_curl, CURLOPT_NOBODY, 0);

        // Seguir redirecionamentos
        curl_setopt($_curl, CURLOPT_FOLLOWLOCATION, 1);

        // Abrindo a url
        curl_setopt($_curl, CURLOPT_URL, $this->url);

        // Habilitando o método POST
        curl_setopt($_curl, CURLOPT_POST, true);

        // envio os campos
        curl_setopt($_curl, CURLOPT_POSTFIELDS, "mensagem={$this->xmlRequisicao->asXML()}");

        //  o tempo em segundos de espera para obter uma conexão
        curl_setopt($_curl, CURLOPT_CONNECTTIMEOUT, 10);

        //  o tempo máximo em segundos de espera para a execução da requisição (curl_exec)
        curl_setopt($_curl, CURLOPT_TIMEOUT, 40);

        if (is_string($ssl)) {
            // verifica a validade do certificado
            curl_setopt($_curl, CURLOPT_SSL_VERIFYPEER, true);

            // verifica se a identidade do servidor bate com aquela informada no certificado
            curl_setopt($_curl, CURLOPT_SSL_VERIFYHOST, 2);

            // informa a localização do certificado para verificação com o peer
            curl_setopt($_curl, CURLOPT_CAINFO, $ssl);
            curl_setopt($_curl, CURLOPT_SSLVERSION, $this->sslVersion);
        }

        // Faz a requisição HTTP
        $result = utf8_encode(curl_exec($_curl));

        // Armazenando informações da requisição

        $info = curl_getinfo($_curl);

        // Fecho a conexão
        curl_close($_curl);

        // Verificando o status da requisição
        $this->status = (integer)(isset($info['http_code']) ? $info['http_code'] : 400);

        // Armazenando as informaçoes
        $this->info = $info;

        // Se o serviço estiver OK
        if ($this->status != 400) {

            $this->retorno = $result;

            // tenta armazenar em um XML o resultado
            try {
                $this->xmlRetorno = new \SimpleXMLElement($result);
            } catch (\Exception $e) {
                $this->errors[] = $e->getTraceAsString();
            }

            // Se a resposta tiver uma tag de erro
            if (!empty($this->xmlRetorno->erro)) {
                $this->errors[] = $this->xmlRetorno->erro;
            }

        } else {
            $this->retorno = $result;
            $this->errors[] = $result;
        }

        return $this;
    }
} 