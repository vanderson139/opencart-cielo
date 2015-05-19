<?php
/**
 * Created by PhpStorm.
 * User: arturmagalhaes
 * Date: 20/03/14
 * Time: 16:17
 */

namespace Tritoq\Payment;


interface PortadorInterface
{
    /**
     * @return string
     */
    public function getEndereco();

    /**
     * @return string
     */
    public function getBairro();

    /**
     * @return string
     */
    public function getComplemento();

    /**
     * @return string
     */
    public function getNumero();

    /**
     * @return string
     */
    public function getCep();
} 