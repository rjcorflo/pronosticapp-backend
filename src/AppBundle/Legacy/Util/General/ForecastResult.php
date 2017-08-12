<?php

namespace AppBundle\Legacy\Util\General;

/**
 * Message result for control and response.
 *
 * @package RJ\PronosticApp\Util\General
 */
class ForecastResult
{
    protected $date;

    protected $matchday_id;

    protected $corrects = [];

    protected $errors = [];

    /**
     * ForecastResult constructor.
     */
    public function __construct()
    {
        $this->date = new \DateTime();
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getMatchdayId()
    {
        return $this->matchday_id;
    }

    /**
     * @param mixed $matchday_id
     */
    public function setMatchdayId($matchday_id)
    {
        $this->matchday_id = $matchday_id;
    }

    /**
     * @return array
     */
    public function getCorrects(): array
    {
        return $this->corrects;
    }

    /**
     * @param $idMatch
     * @param $localGoals
     * @param $awayGoals
     * @param $risk
     */
    public function addCorrect($idMatch, $localGoals, $awayGoals, $risk)
    {
        $data['id_partido'] = $idMatch;
        $data['goles_local'] = $localGoals;
        $data['goles_visitante'] = $awayGoals;
        $data['riesgo'] = $risk;

        $this->corrects[] = $data;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param int $idMatch
     * @param null|string $cause
     */
    public function addError(int $idMatch, ?string $cause)
    {
        $data['id_partido'] = $idMatch;
        $data['motivo'] = $cause;

        $this->errors[] = $data;
    }
}
