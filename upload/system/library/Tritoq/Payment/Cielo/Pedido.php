<?php

namespace Tritoq\Payment\Cielo;

use Tritoq\Payment\Exception\InvalidArgumentException;

/**
 *
 * Representação do Pedido / Compra do Cliente
 *
 *
 * Class Pedido
 *
 * @category Library
 * @copyright Artur Magalhães <nezkal@gmail.com>
 * @package Tritoq\Payment\Cielo
 * @license GPL-3.0+
 */
class Pedido
{
    /**
     *
     * Pedido realizado em Português
     *
     * @const string
     */
    const IDIOMA_PORTUGUES = 'PT';

    /**
     *
     * Pedido realizado em Espanhol
     *
     * @const string
     */
    const IDIOMA_ESPANHOL = 'ES';

    /**
     *
     * Pedido realizado em Inglês
     *
     * @const string
     */
    const IDIOMA_INGLES = 'EN';


    /**
     *
     * Número/Identificador do pedido
     *
     * @var string
     */
    private $_numero;

    /**
     *
     * DataHora do pedido
     *
     * @var \DateTime
     */
    private $_dataHora;

    /**
     *
     * Breve descrição do pedido
     *
     * @var string
     */
    private $descricao;

    /**
     *
     * Idiome do pedido
     *
     * @var string
     */
    private $_idioma = 'PT';

    /**
     *
     * Tipo de moeda que foi efetivada a compra, verificar padrões internacionais de numeração
     *
     * 986 - Real Brasileiro (3 dígitos)
     *
     * @var int
     */
    private $_moeda = 986;

    /**
     *
     * Caso a compra seja feita de uma passagem área, informar a taxa de embarque
     *
     * @var string
     */
    private $_taxaEmbarque;

    /**
     *
     * Valor total da compra
     *
     * @var integer
     */
    private $_valor;


    /**
     *
     * Seta o número do pedido
     *
     * @param string $numero
     * @throws InvalidArgumentException
     * @return $this
     */
    public function setNumero($numero)
    {
        // if (preg_match('/([[:alpha:]]|[[:punct:]]|[[:space:]])/', $numero)) {
        //     throw new InvalidArgumentException('Número do pedido inválido.');
        if (strlen($numero) > 20) {
            throw new InvalidArgumentException('Número do pedido inválido. Max length of 20 chars.');
        } else {
            $this->_numero = substr($numero, 0, 20);
            return $this;
        }
    }

    /**
     *
     * Retorna o número do pedido
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->_numero;
    }

    /**
     *
     * Seta a datahora do pedido
     *
     * @param \DateTime $dataHora
     * @throws InvalidArgumentException
     * @return $this
     */
    public function setDataHora(\DateTime $dataHora)
    {
        $this->_dataHora = $dataHora->format('Y-m-d') . 'T' . $dataHora->format('H:i:s');
        return $this;
    }

    /**
     *
     * Retorna da datahora do pedido
     *
     * @return \DateTime
     */
    public function getDataHora()
    {
        return $this->_dataHora;
    }

    /**
     *
     * Seta a descrição do pedido
     *
     * @param string $descricao
     * @return $this
     */
    public function setDescricao($descricao)
    {
        $this->descricao = substr($descricao, 0, 99);
        return $this;
    }

    /**
     *
     * Retorna a descrição do pedido
     *
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricao;
    }


    /**
     *
     * Seta o idioma do pedido
     *
     * PT - Português
     * EN - Inglês
     * ES - Espanhol
     *
     *
     * @param string $idioma
     * @throws InvalidArgumentException
     * @return $this
     */
    public function setIdioma($idioma)
    {
        switch ($idioma) {
            case self::IDIOMA_PORTUGUES:
            case self::IDIOMA_ESPANHOL:
            case self::IDIOMA_INGLES:
                $this->_idioma = $idioma;
                return $this;
            default:
                throw new InvalidArgumentException('Idioma Inválido');
        }

    }

    /**
     *
     * Retorna o idioma do pedido
     *
     * @return string
     */
    public function getIdioma()
    {
        return $this->_idioma;
    }

    /**
     *
     * Seta a moeda do pedido, ver padrões internacionais
     *
     * 986 - Real brasileiro (3 dígitos)
     *
     * @param int $moeda
     * @throws InvalidArgumentException
     * @return $this
     */
    public function setMoeda($moeda)
    {
        if (preg_match('/([[:alpha:]]|[[:punct:]]|[[:space:]])/', $moeda)) {
            throw new InvalidArgumentException('Moeda inválida');
        } else {
            $this->_moeda = (integer)substr($moeda, 0, 3);
            return $this;
        }
    }

    /**
     *
     * Retorna a moeda
     *
     * @return int
     */
    public function getMoeda()
    {
        return $this->_moeda;
    }

    /**
     *
     * Seta o valor de taxa de embarque caso o pedido seja uma passagem área
     *
     * @param string $taxaEmbarque
     * @return $this
     */
    public function setTaxaEmbarque($taxaEmbarque)
    {
        $this->_taxaEmbarque = $taxaEmbarque;
        return $this;
    }

    /**
     *
     * Retorna a taxa de embarque
     *
     * @return string
     */
    public function getTaxaEmbarque()
    {
        return $this->_taxaEmbarque;
    }

    /**
     *
     * Seta o valor total do pedido / compra
     *
     * @param string $valor
     * @throws InvalidArgumentException
     * @return $this
     */
    public function setValor($valor)
    {
        if (preg_match('/([[:alpha:]]|[[:punct:]]|[[:space:]])/', $valor)) {
            throw new InvalidArgumentException('Valor inválido.');
        } else {
            $this->_valor = substr($valor, 0, 12);
            return $this;
        }
    }

    /**
     *
     * Retorna o valor total do pedido
     *
     * @return string
     */
    public function getValor()
    {
        return $this->_valor;
    }
} 