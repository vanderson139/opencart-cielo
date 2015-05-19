<?php

namespace Tritoq\Payment\Cielo\AnaliseRisco;

use Tritoq\Payment\Exception\InvalidArgumentException;

/**
 *
 * Representação de um pedido que será enviado para análise de risco da Cielo
 *
 *
 * Class PedidoAnaliseRisco
 *
 * @category Library
 * @copyright Artur Magalhães <nezkal@gmail.com>
 * @package Tritoq\Payment\Cielo\AnaliseRisco
 * @license GPL-3.0+
 */
class PedidoAnaliseRisco
{
    /**
     *
     * ID do pedido
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * Tipo de moeda
     *
     * @var string
     */
    protected $moeda = 'BRL';

    /**
     *
     * Preço Total do pedido = Valor do pedido + frete
     *
     * @var double
     */
    protected $precoTotal;

    /**
     *
     * Preço parcial do pedido, sem o frete
     *
     * @var double
     */
    protected $precoUnitario;

    /**
     *
     * Endereço de entrega
     *
     * @var string
     */
    protected $endereco;

    /**
     *
     * Complemento
     *
     * @var string
     */
    protected $complemento;

    /**
     *
     * Cidade do pedido
     *
     * @var string
     */
    protected $cidade;

    /**
     *
     * Sliga do Estado
     *
     * Ex: SC, RS, SP
     *
     * @var string
     */
    protected $estado;

    /**
     *
     * Cep de entrega (somente números)
     *
     * Ex: 89802140
     *
     * @var string
     */
    protected $cep;

    /**
     *
     * Sigla do páis
     *
     * Ex: BR
     *
     * @var string
     */
    protected $pais;

    /**
     *
     * Seta o ID do pedido
     *
     * @param int $id
     * @return $this
     * @throws \Tritoq\Payment\Exception\InvalidArgumentException
     */
    public function setId($id)
    {
        if (strlen($id) > 50) {
            throw new InvalidArgumentException('Id do pedido maior que 50 caracteres');
        }

        $this->id = $id;
        return $this;
    }

    /**
     *
     * Retorna o ID do pedido
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * Seta a moeda do pedido
     *
     * Ex: BRL
     *
     * @param string $moeda
     * @throws \Tritoq\Payment\Exception\InvalidArgumentException
     * @return $this
     */
    public function setMoeda($moeda)
    {
        if (strlen($moeda) != 3) {
            throw new InvalidArgumentException('Tipo de moeda ' . $moeda . ' inválida');
        }

        $this->moeda = strtoupper($moeda);

        return $this;
    }

    /**
     *
     * Retorna a moeda do pedido
     *
     * @return string
     */
    public function getMoeda()
    {
        return $this->moeda;
    }

    /**
     *
     * Seta o preço total do pedido
     *
     * Valor total do pedido + Frete
     *
     * @param float $precoTotal
     * @return $this
     */
    public function setPrecoTotal($precoTotal)
    {
        $this->precoTotal = (double)$precoTotal;
        return $this;
    }

    /**
     *
     * Retorna o preço total do pedido
     *
     * @return float
     */
    public function getPrecoTotal()
    {
        return $this->precoTotal;
    }

    /**
     *
     * Seta o preço parcial do pedido sem o frete
     *
     *
     * @param float $precoUnitario
     * @return $this
     */
    public function setPrecoUnitario($precoUnitario)
    {
        $this->precoUnitario = (double)$precoUnitario;
        return $this;
    }

    /**
     *
     * Retorna o preço parcial/unitário do pedido
     *
     * @return float
     */
    public function getPrecoUnitario()
    {
        return $this->precoUnitario;
    }

    /**
     *
     * Seta o CEP do pedido (somente números)
     *
     * Ex: 89802140
     *
     *
     * @param string $cep
     * @throws \Tritoq\Payment\Exception\InvalidArgumentException
     * @return $this
     */
    public function setCep($cep)
    {
        if (preg_match('/([[:alpha:]]|[[:punct:]]|[[:space:]])/', $cep)) {
            throw new InvalidArgumentException('Valor de CEP inválido');
        }
        $this->cep = $cep;
        return $this;
    }

    /**
     *
     * Retorna o CEP do pedido
     *
     * @return string
     */
    public function getCep()
    {
        return $this->cep;
    }

    /**
     *
     * Seta a cidade do pedido
     *
     * @param string $cidade
     * @return $this
     */
    public function setCidade($cidade)
    {
        $this->cidade = $cidade;
        return $this;
    }

    /**
     *
     * Retorna a cidade do pedido
     *
     * @return string
     */
    public function getCidade()
    {
        return $this->cidade;
    }

    /**
     *
     * Seta o complemento do pedido
     *
     * @param string $complemento
     * @return $this
     */
    public function setComplemento($complemento)
    {
        $this->complemento = $complemento;
        return $this;
    }

    /**
     *
     * Retorna o complemento do pedido
     *
     * @return string
     */
    public function getComplemento()
    {
        return $this->complemento;
    }

    /**
     *
     * Seta o endereço do pedido
     *
     * @param string $endereco
     * @return $this
     */
    public function setEndereco($endereco)
    {
        $this->endereco = $endereco;
        return $this;
    }

    /**
     *
     * Retorna o endereço do pedido
     *
     * @return string
     */
    public function getEndereco()
    {
        return $this->endereco;
    }

    /**
     *
     * Seta a sliga do Estado do pedido
     *
     * @param string $estado
     * @throws \Tritoq\Payment\Exception\InvalidArgumentException
     * @return $this
     */
    public function setEstado($estado)
    {
        if (strlen($estado) < 2) {
            throw new InvalidArgumentException('Sigla de estado inválido');
        }
        $this->estado = $estado;
        return $this;
    }

    /**
     *
     * Retorna o sliga do estado
     *
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     *
     * Seta o país do pedido
     *
     * @param string $pais
     * @throws \Tritoq\Payment\Exception\InvalidArgumentException
     * @return $this
     */
    public function setPais($pais)
    {
        if (strlen($pais) != 2) {
            throw new InvalidArgumentException('Sigla de país inválido');
        }
        $this->pais = $pais;
        return $this;
    }

    /**
     *
     * Retorna o país do pedido
     *
     * @return string
     */
    public function getPais()
    {
        return $this->pais;
    }


}