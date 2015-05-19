<?php
namespace Tritoq\Payment\Cielo;


use Tritoq\Payment\PortadorInterface;

class Portador implements PortadorInterface
{
    /**
     * @var string
     */
    private $_endereco;

    /**
     * @var string
     */
    private $_complemento;

    /**
     * @var string
     */
    private $_numero;

    /**
     * @var string
     */
    private $_bairro;

    /**
     * @var string
     */
    private $_cep;

    /**
     * @param string $bairro
     * @return $this
     */
    public function setBairro($bairro)
    {
        $this->_bairro = $bairro;
        return $this;
    }

    /**
     * @return string
     */
    public function getBairro()
    {
        return $this->_bairro;
    }

    /**
     * @param string $cep
     * @return $this
     */
    public function setCep($cep)
    {
        $this->_cep = $cep;
        return $this;
    }

    /**
     * @return string
     */
    public function getCep()
    {
        return $this->_cep;
    }

    /**
     * @param string $complemento
     * @return $this
     */
    public function setComplemento($complemento)
    {
        $this->_complemento = $complemento;
        return $this;
    }

    /**
     * @return string
     */
    public function getComplemento()
    {
        return $this->_complemento;
    }

    /**
     * @param string $endereco
     * @return $this
     */
    public function setEndereco($endereco)
    {
        $this->_endereco = $endereco;
        return $this;
    }

    /**
     * @return string
     */
    public function getEndereco()
    {
        return $this->_endereco;
    }

    /**
     * @param string $numero
     * @return $this
     */
    public function setNumero($numero)
    {
        $this->_numero = $numero;
        return $this;
    }

    /**
     * @return string
     */
    public function getNumero()
    {
        return $this->_numero;
    }
} 