<?php
/**
 * @author Artur Magalhães <nezkal@gmail.com>
 */

namespace Tritoq\Payment\Cielo\AnaliseRisco;


class AnaliseResultado
{

    /**
     *
     * Constante indicativa de status alto risco
     *
     * @const string
     */
    const STATUS_ALTO_RISCO = 'alto risco';

    /**
     *
     * Constante indicativa de status baixo risco
     *
     * @const string
     */
    const STATUS_BAIXO_RISCO = 'baixo risco';

    /**
     *
     * Constante indicativa de status médio risco
     *
     * @const string
     */
    const STATUS_MEDIO_RISCO = 'medio risco';

    /**
     *
     * Constante indicativa de status não presente
     *
     * @const string
     */
    const STATUS_NAO_PRESENTE = 'não presente';

    /**
     *
     * Status da Análise
     *
     * @var string
     */
    private $status;

    /**
     *
     * Status da Revisão da Análise
     *
     * @var string
     */
    private $statusRevisao;

    /**
     *
     * Data da análise
     *
     * @var \DateTime
     */
    private $data;

    /**
     *
     * Lista dos detalhes resultantes da Análise de Risco
     *
     * @var array
     */
    private $detalhes;

    /**
     *
     * Score / Pontuação de risco avaliada pela análise
     *
     * @var integer
     */
    private $pontuacao;

    /**
     *
     * Acão realizada pela Análise
     *
     * @var string
     */
    private $acao;

    /**
     *
     * Recomendação retornada pela análise
     *
     * @var string
     */
    private $recomendacao;

    /**
     * @param string $acao
     * @return $this
     */
    public function setAcao($acao)
    {
        $this->acao = $acao;
        return $this;
    }

    /**
     * @return string
     */
    public function getAcao()
    {
        return $this->acao;
    }

    /**
     * @param \DateTime $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = new \DateTime($data);
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     *
     * Adiciona detalhes da análise
     *
     * @param $info
     * @return $this
     */
    public function addDetalhes($info)
    {
        $this->detalhes[] = $info;
        return $this;
    }

    /**
     * @param array $detalhes
     * @return $this
     */
    public function setDetalhes($detalhes)
    {
        $this->detalhes = $detalhes;
        return $this;
    }

    /**
     * @return array
     */
    public function getDetalhes()
    {
        return $this->detalhes;
    }

    /**
     * @param int $pontuacao
     * @return $this
     */
    public function setPontuacao($pontuacao)
    {
        $this->pontuacao = $pontuacao;
        return $this;
    }

    /**
     * @return int
     */
    public function getPontuacao()
    {
        return $this->pontuacao;
    }

    /**
     * @param string $recomendacao
     * @return $this
     */
    public function setRecomendacao($recomendacao)
    {
        $this->recomendacao = $recomendacao;
        return $this;
    }

    /**
     * @return string
     */
    public function getRecomendacao()
    {
        return $this->recomendacao;
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus($status)
    {
        switch ($status) {
            case self::STATUS_ALTO_RISCO:
            case self::STATUS_BAIXO_RISCO:
            case self::STATUS_MEDIO_RISCO:
                $this->status = $status;
                break;
            default:
                $this->status = self::STATUS_NAO_PRESENTE;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $statusRevisao
     * @return $this
     */
    public function setStatusRevisao($statusRevisao)
    {
        switch ($statusRevisao) {
            case self::STATUS_ALTO_RISCO:
            case self::STATUS_BAIXO_RISCO:
            case self::STATUS_MEDIO_RISCO:
                $this->statusRevisao = $statusRevisao;
                break;
            default:
                $this->status = self::STATUS_NAO_PRESENTE;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getStatusRevisao()
    {
        return $this->statusRevisao;
    }

    /**
     *
     * Incorporta o XML a classe e seta os valores
     *
     * @param \SimpleXMLElement $xml
     * @return $this
     */
    public function setUp(\SimpleXMLElement $xml)
    {

        $this->setStatus($xml->nivelRiscoAnaliseFraude);
        $this->setPontuacao($xml->scoreAnaliseFraude);
        $this->setRecomendacao($xml->recomendacaoAnaliseFraude);
        $this->setAcao($xml->acao);
        $this->setData($xml->dataAnaliseFraude);

        if ($xml->count() > 0) {
            foreach ($xml->listaDetalhesAnaliseFraude->detalhesAnaliseFraude as $subitem) {
                $this->addDetalhes($subitem);
            }
        }

        return $this;
    }


}