<?php

namespace Tritoq\Payment\Cielo;

use Tritoq\Payment\Exception\InvalidArgumentException;

/**
 * Class Cartao
 *
 * Classe de representação do Cartão de crédito
 *
 * @category Library
 * @package Tritoq\Payment\Cielo
 * @copyright Artur Magalhães <artur@tritoq.com>
 * @license GPL-3.0+
 */
class Cartao
{
    /**
     *
     * Constante com um número de cartão de Crédito fornecido pela Cielo de Testes
     *
     * @const integer
     */
    const TESTE_CARTAO_NUMERO = 4012001037141112;

    /**
     *
     * Constante com um número de segurança do cartão de Crédito
     *
     * @const integer
     */
    const TESTE_CARTAO_CODIGO_SEGURANCA = 123;

    /**
     *
     * Constante com o tipo da bandeira de testes
     *
     * @const string
     */
    const TESTE_CARTAO_BANDEIRA = 'visa';

    /**
     *
     * Constante com a informação de validade do cartão de teste
     *
     * @const string
     */
    const TESTE_CARTAO_VALIDADE = '201805';

    /**
     *
     * Tipo de Bandeira Visa
     *
     * @const string
     */
    const BANDEIRA_VISA = 'visa';

    /**
     *
     * Tipo de Bandeira Mastercard
     *
     * @const string
     */
    const BANDEIRA_MASTERCARD = 'mastercard';

    /**
     *
     * Tipo de Bandeira Diners
     *
     * @const string
     */
    const BANDEIRA_DINERS = 'diners';

    /**
     *
     * Tipo de Bandeira American Express
     *
     * @const string
     */
    const BANDEIRA_AMERICAN_EXPRESS = 'amex';

    /**
     *
     * Tipo de Bandeira Discover
     *
     * @const string
     */
    const BANDEIRA_DISCOVER = 'discover';

    /**
     *
     * Tipo de Bandeira Elo
     *
     * @const string
     */
    const BANDEIRA_ELO = 'elo';

    /**
     *
     * Tipo de Bandeira JCB
     *
     * @const string
     */
    const BANDEIRA_JCB = 'jcb';

    /**
     *
     * Tipo de Bandeira AURA
     *
     * @const string
     */
    const BANDEIRA_AURA = 'aura';

    /**
     *
     * Número do Cartão
     *
     * @var integer
     */
    private $_numero;

    /**
     *
     * Bandeira do Cartão
     *
     * @var string
     */
    private $_bandeira;

    /**
     *
     * Indicador do código de segurança
     *
     * 0 - Não informado
     * 1 - Informado
     * 2 - Ilegível
     * 9 - Inexistente
     *
     *
     * @var integer
     */
    private $_indicador = 1;

    /**
     *
     * Código de Segurança do Cartão, obrigatório se o indicador for 1 - Informado
     *
     * @var string
     */
    private $_codigoSegurancaCartao;

    /**
     *
     * Nome do Portador impresso no cartão
     *
     * @var string
     */
    private $_nomePortador;

    /**
     *
     * Validade do Cartão no formato YYYYmm
     *
     * @var string
     */
    private $_validade;

    /**
     *
     * Seta a validade do Cartão, formato YYYYmm
     *
     * @param string $validade
     * @throws \Tritoq\Payment\Exception\InvalidArgumentException
     * @return $this
     */
    public function setValidade($validade)
    {
        if (preg_match('/([[:alpha:]]|[[:punct:]]|[[:space:]])/', $validade)) {
            throw new InvalidArgumentException('Data de validade inválida.');
        }

        if (strlen($validade) != 6) {
            throw new InvalidArgumentException('Data de validade inválida.');
        }

        if ($validade < date('Ym')) {
            throw new InvalidArgumentException('Cartão com validade ultrapassada.');
        }

        $this->_validade = substr($validade, 0, 6);

        return $this;
    }

    /**
     *
     * Retorna a validade do Cartão
     *
     * @return string
     */
    public function getValidade()
    {
        return $this->_validade;
    }


    /**
     *
     * Seta o número do Cartão
     *
     * @param int $numero
     * @return $this
     */
    public function setNumero($numero)
    {
        $this->_numero = preg_replace('/[^[:digit:]]/', '', $numero);
        return $this;
    }

    /**
     *
     * Retorna o número do Cartão
     *
     * @return int
     */
    public function getNumero()
    {
        return $this->_numero;
    }


    /**
     *
     * Seta a bandeira do cartão
     *
     * - Visa
     * - Master
     * - Diners
     * - Discover
     * - Elo
     * - American Express (amex)
     * - Jcb
     * - Aura
     *
     * @param string $bandeira
     * @throws InvalidArgumentException
     * @return $this
     */
    public function setBandeira($bandeira)
    {
        switch ($bandeira) {
            case self::BANDEIRA_VISA:
            case self::BANDEIRA_MASTERCARD:
            case self::BANDEIRA_DINERS:
            case self::BANDEIRA_DISCOVER:
            case self::BANDEIRA_ELO:
            case self::BANDEIRA_AMERICAN_EXPRESS:
            case self::BANDEIRA_JCB:
            case self::BANDEIRA_AURA:
                $this->_bandeira = $bandeira;
                return $this;
            default:
                throw new InvalidArgumentException("Bandeira {$bandeira} inválida");

        }
    }

    /**
     *
     * Retorna o bandeira
     *
     * @return string
     */
    public function getBandeira()
    {
        return $this->_bandeira;
    }

    /**
     *
     * Seta o Indicador do Cartão
     *
     * 0 - Não informado
     * 1 - Informado
     * 2 - Ilegível
     * 9 - Inexistente
     *
     * @param int $indicador
     * @throws InvalidArgumentException
     * @return $this
     */
    public function setIndicador($indicador)
    {
        switch ((integer)$indicador) {
            case 0:
            case 1:
            case 2:
            case 9:
                $this->_indicador = (integer)substr($indicador, 0, 1);
                return $this;
            default:
                throw new InvalidArgumentException('Indicador de segurança inválido');
        }
    }

    /**
     *
     * Retorna o indicador do Cartão
     *
     * @return int
     */
    public function getIndicador()
    {
        return $this->_indicador;
    }

    /**
     *
     * Seta o código de segurança do Cartão - 3 digitos ou 4 digitos caso for o Amex
     *
     * @param string $codigoSegurancaCartao
     * @throws InvalidArgumentException
     * @return $this
     */
    public function setCodigoSegurancaCartao($codigoSegurancaCartao)
    {
        $this->_codigoSegurancaCartao = preg_replace('/[^[:digit:]]/', '', $codigoSegurancaCartao);

        if (preg_match('/([[:alpha:]]|[[:punct:]]|[[:space:]])/', $codigoSegurancaCartao) || (strlen($codigoSegurancaCartao) < 3) || (strlen($codigoSegurancaCartao) > 4)) {
            throw new InvalidArgumentException('Código de segurança inválido.');
        } else {
            $this->_codigoSeguranca = $codigoSegurancaCartao;
            $this->setIndicador(1);
            return $this;
        }

    }

    /**
     *
     * Retorna o código de segurança do Cartão
     *
     * @return string
     */
    public function getCodigoSegurancaCartao()
    {
        return $this->_codigoSegurancaCartao;
    }

    /**
     *
     * Seta o nome do Portador do Cartão
     *
     * @param string $nomePortador
     * @return $this
     */
    public function setNomePortador($nomePortador)
    {
        $this->_nomePortador = $nomePortador;
        return $this;
    }

    /**
     *
     * Retorna o nome do Portador
     *
     * @return string
     */
    public function getNomePortador()
    {
        return $this->_nomePortador;
    }
} 