<?php
namespace Tritoq\Payment\Cielo\AnaliseRisco;

/**
 *
 * Interface de Implementação para Cliente e verifição de Análise de Risco
 *
 * Interface ClienteAnaliseRiscoInterface
 * @package Tritoq\Payment\Cielo
 */
interface ClienteAnaliseRiscoInterface
{

    /**
     * Retorna o Endereço do Cliente
     *
     * Ex: Rua Marechal Deodoro, 110
     *
     * @return string
     */
    public function getEndereco();

    /**
     *
     * Retorna a cidade do cadastro do cliente
     *
     * Ex: Chapecó
     *
     * @return string
     */
    public function getCidade();

    /**
     *
     * Retorna o complemento do endereço do cliente
     *
     * Ex: Sala 1008
     *
     * @return string
     */
    public function getComplemento();

    /**
     *
     * Retorna a sigla do Estado do Cliente
     *
     * Ex: SC
     *
     * @return string
     */
    public function getEstado();

    /**
     *
     * Retorna a sigla do País do Cliente
     *
     * Ex: BR
     *
     * @return string
     */
    public function getPais();

    /**
     * Retorna o código postal / CEP / do cliente, somente números
     *
     * Ex: 89802140
     *
     * @return string
     *
     */
    public function getCep();

    /**
     *
     * Retorna o número de Identificação do Cliente
     *
     * Ex: 40
     *
     * @return string
     */
    public function getId();

    /**
     *
     * Retorna a senha de acesso do cliente à loja
     *
     * Ex: 12345
     *
     * @return string
     */
    public function getSenha();

    /**
     *
     * Retorna o número de CPF/CNPJ do cliente
     *
     * Ex: 123456789123
     *
     * @return string
     */
    public function getDocumento();

    /**
     *
     * Retorna o e-mail do cliente
     *
     * Ex: email@email.com.br
     *
     * @return string
     */
    public function getEmail();

    /**
     *
     * Retorna o Nome do Cliente
     *
     * Ex: Fulano
     *
     * @return string
     */
    public function getNome();

    /**
     *
     * Retorna o Sobrenome do Cliente
     *
     * Ex: Sobrenome
     *
     * @return string
     */
    public function getSobrenome();

    /**
     *
     * Retorna o telefone de contato do cliente (somente números)
     *
     * Ex: 04988053925
     *
     * @return string
     */
    public function getTelefone();

    /**
     * Retorna o número de IP do cliente
     *
     * Ex: 192.168.1.254
     *
     * @return string
     */
    public function getIp();
} 