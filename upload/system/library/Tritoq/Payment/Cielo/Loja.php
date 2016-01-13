<?php

namespace Tritoq\Payment\Cielo;

use Tritoq\Payment\Exception\InvalidArgumentException;

/**
 *
 * Representação da Loja Credenciada à Cielo
 *
 *
 * Class Loja
 *
 * @category Library
 * @copyright Artur Magalhães <nezkal@gmail.com>
 * @package Tritoq\Payment\Cielo
 * @license GPL-3.0+
 */
class Loja
{
    /**
     *
     * Indicação de ambiente de testes
     *
     * @const string
     */
    const AMBIENTE_TESTE = 'teste';

    /**
     *
     * Indicação de ambiente de produção
     *
     * @const string
     */
    const AMBIENTE_PRODUCAO = 'producao';

    /**
     *
     * Constante de informação sobre número da loja para testes
     *
     * @const integer
     */
    const LOJA_NUMERO_AMBIENTE_TESTE = 1006993069;
    /**
     *
     * Constante da chave de testes
     *
     * @const string
     */
    const LOJA_CHAVE_AMBIENTE_TESTE = '25fbb99741c739dd84d7b06ec78c9bac718838630f30b112d033ce2e621b34f3';
    /**
     *
     * Ambiente atual
     *
     * teste - Ambiente de Testes
     * producao - Ambiente de Producao (Loja rodando no servidor)
     *
     * @var string
     */
    private $_ambiente = 'teste';

    /**
     *
     * Número da loja junto à Cielo
     *
     * @var integer
     */
    private $_numeroLoja;

    /**
     *
     * Chave de acesso
     *
     * @var string
     */
    private $_chave;

    /**
     *
     * Nome da Loja - até 13 caracteres (irá aparecer na fatura do cliente)
     *
     * @var string
     */
    private $_nomeLoja;

    /**
     *
     * URL de retorno caso haja autenticação
     *
     * @var string
     */
    private $_urlRetorno;

    /**
     *
     * Caminho do certificado de segurança
     *
     * @var string
     */
    private $_sslCertificado;

    /**
     *
     * Seta o certificado de segurança
     *
     * @param string $sslCertificado
     * @throws \Tritoq\Payment\Exception\InvalidArgumentException
     */
    public function setSslCertificado($sslCertificado = '')
    {
        if (!is_string($sslCertificado)) {
            throw new InvalidArgumentException('Certificado Inválido');
        }

        $this->_sslCertificado = $sslCertificado;
    }

    /**
     *
     * Retorna o certificado de segurança
     *
     * @return string
     */
    public function getSslCertificado()
    {
        return $this->_sslCertificado;
    }


    /**
     *
     * Seta o ambiente
     *
     * teste - Ambiente de testes
     * producao - Ambiente de produção (Loja rodando no servidor)
     *
     * @param string $ambiente
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setAmbiente($ambiente)
    {
        switch ($ambiente) {
            case 'teste':
            case 'producao':
            case 'produção':

                $this->_ambiente = $ambiente;
                return $this;

            default:
                throw new InvalidArgumentException("Ambiente '{$ambiente}' inválido");
        }

    }

    /**
     *
     * Retorna o ambiente
     *
     * @return string
     */
    public function getAmbiente()
    {
        return $this->_ambiente;
    }

    /**
     *
     * Seta o número da loja credenciada a Cielo
     *
     * @param int $numeroLoja
     * @throws InvalidArgumentException
     * @return $this
     */
    public function setNumeroLoja($numeroLoja)
    {
        if (strlen($numeroLoja) > 20) {
            throw new InvalidArgumentException("Numero {$numeroLoja} inválido");
        }

        $this->_numeroLoja = $numeroLoja;
        return $this;
    }

    /**
     *
     * Retorna o número da loja
     *
     * @return int
     */
    public function getNumeroLoja()
    {
        return $this->_numeroLoja;
    }

    /**
     * @param string $chave
     * @throws InvalidArgumentException
     * @return $this
     */
    public function setChave($chave)
    {
        if (strlen($chave) > 100) {
            throw new InvalidArgumentException('Chave Inválida');
        }
        $this->_chave = $chave;

        return $this;
    }

    /**
     *
     * Retorna a Chave
     * @return string
     */
    public function getChave()
    {
        return $this->_chave;
    }


    /**
     *
     * Seta o nome da Loja (máximo 13 caracteres)
     *
     * @param string $nomeLoja
     * @throws InvalidArgumentException
     * @return $this
     */
    public function setNomeLoja($nomeLoja)
    {
        if (strlen($nomeLoja) > 13) {
            throw new InvalidArgumentException('O nome da loja não pode contar mais que 13 caracteres');
        }

        $this->_nomeLoja = $this->removeAcentos($nomeLoja);

        return $this;
    }

    /**
     *
     * Retorna o nome da Loja
     *
     * @return string
     */
    public function getNomeLoja()
    {
        return $this->_nomeLoja;
    }

    /**
     *
     * Retorna o URL de retorno para autenticação
     *
     * @return string
     */
    public function getUrlRetorno()
    {
        return $this->_urlRetorno;
    }

    /**
     *
     * Seta a URL de retorno para autenticação
     *
     * @param $urlRetorno
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setUrlRetorno($urlRetorno)
    {
        $valida = filter_var($urlRetorno, FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED);

        if ($valida == false) {
            throw new InvalidArgumentException('URL de retorno inválida.');
        }

        $this->_urlRetorno = substr($urlRetorno, 0, 1024);

        return $this;
    }

    /**
     *
     * Remove os acentos do texto
     *
     * @param $string
     * @return String
     */
    private function removeAcentos($string) {
        $troca = array();

        if (extension_loaded('mbstring')) {
            if (mb_detect_encoding($string . 'x', 'UTF-8, ISO-8859-1') == 'UTF-8') {
                $string = utf8_decode(strtolower($string));
            }
        }

        $string = strtolower($string);
        // Código ASCII das vogais
        $ascii['a'] = range(224, 230);
        $ascii['e'] = range(232, 235);
        $ascii['i'] = range(236, 239);
        $ascii['o'] = array_merge(range(242, 246), array(240, 248));
        $ascii['u'] = range(249, 252);
        // Código ASCII dos outros caracteres
        $ascii['b'] = array(223);
        $ascii['c'] = array(231);
        $ascii['d'] = array(208);
        $ascii['n'] = array(241);
        $ascii['y'] = array(253, 255);
        foreach ($ascii as $key=>$item) {
            $acentos = '';

            foreach ($item AS $codigo) {
                $acentos .= chr($codigo);
            }

            $troca[$key] = '/['.$acentos.']/i';
        }
        $string = preg_replace(array_values($troca), array_keys($troca), $string);

        return strtoupper($string);
    }
}
