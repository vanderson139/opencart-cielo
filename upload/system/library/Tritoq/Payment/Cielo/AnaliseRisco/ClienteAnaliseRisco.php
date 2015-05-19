<?php
namespace Tritoq\Payment\Cielo\AnaliseRisco;

class ClienteAnaliseRisco extends \stdClass implements ClienteAnaliseRiscoInterface
{

    /**
     * Retorna o Endereço do Cliente
     *
     * Ex: Rua Marechal Deodoro, 110
     *
     * @return string
     */
    public function getEndereco()
    {
        // TODO: Implement getEndereco() method.
        return $this->endereco;
    }

    /**
     *
     * Retorna a cidade do cadastro do cliente
     *
     * Ex: Chapecó
     *
     * @return string
     */
    public function getCidade()
    {
        // TODO: Implement getCidade() method.
        return $this->cidade;
    }

    /**
     *
     * Retorna o complemento do endereço do cliente
     *
     * Ex: Sala 1008
     *
     * @return string
     */
    public function getComplemento()
    {
        // TODO: Implement getComplemento() method.
        return $this->complemento;
    }

    /**
     *
     * Retorna a sigla do Estado do Cliente
     *
     * Ex: SC
     *
     * @return string
     */
    public function getEstado()
    {
        // TODO: Implement getEstado() method.

        return $this->estado;
    }

    /**
     *
     * Retorna a sigla do País do Cliente
     *
     * Ex: BR
     *
     * @return string
     */
    public function getPais()
    {
        // TODO: Implement getPais() method.
        return $this->pais;
    }

    /**
     * Retorna o código postal / CEP / do cliente, somente números
     *
     * Ex: 89802140
     *
     * @return string
     *
     */
    public function getCep()
    {
        // TODO: Implement getCep() method.
        return $this->cep;
    }

    /**
     *
     * Retorna o número de Identificação do Cliente
     *
     * Ex: 40
     *
     * @return string
     */
    public function getId()
    {
        // TODO: Implement getId() method.
        return $this->id;
    }

    /**
     *
     * Retorna a senha de acesso do cliente à loja
     *
     * Ex: 12345
     *
     * @return string
     */
    public function getSenha()
    {
        // TODO: Implement getSenha() method.
        return $this->senha;
    }

    /**
     *
     * Retorna o número de CPF/CNPJ do cliente
     *
     * Ex: 123456789123
     *
     * @return string
     */
    public function getDocumento()
    {
        // TODO: Implement getDocumento() method.
        return $this->documento;
    }

    /**
     *
     * Retorna o e-mail do cliente
     *
     * Ex: email@email.com.br
     *
     * @return string
     */
    public function getEmail()
    {
        // TODO: Implement getEmail() method.
        return $this->email;
    }

    /**
     *
     * Retorna o Nome do Cliente
     *
     * Ex: Fulano
     *
     * @return string
     */
    public function getNome()
    {
        // TODO: Implement getNome() method.
        return $this->nome;
    }

    /**
     *
     * Retorna o Sobrenome do Cliente
     *
     * Ex: Sobrenome
     *
     * @return string
     */
    public function getSobrenome()
    {
        // TODO: Implement getSobrenome() method.
        return $this->sobrenome;
    }

    /**
     *
     * Retorna o telefone de contato do cliente (somente números)
     *
     * Ex: 04988053925
     *
     * @return string
     */
    public function getTelefone()
    {
        // TODO: Implement getTelefone() method.
        return $this->telefone;
    }

    /**
     * Retorna o número de IP do cliente
     *
     * Ex: 192.168.1.254
     *
     * @return string
     */
    public function getIp()
    {
        // TODO: Implement getIp() method.
        return $this->ip;
    }

} 