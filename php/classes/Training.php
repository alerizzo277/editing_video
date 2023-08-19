<?php

class Training{
    private $id;
    private $team;
    private $start_date_time;
    private $end_date_time;
    private $reservation;

    function __construct($id, $team, $start_date_time, $end_date_time, $reservation){
        $this->id = $id;
        $this->team = $team;
        $this->start_date_time = $start_date_time;
        $this->end_date_time = $end_date_time;
        $this->reservation = $reservation;
    }

    


    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of team
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * Set the value of team
     */
    public function setTeam($team): self
    {
        $this->team = $team;

        return $this;
    }

    /**
     * Get the value of start_date_time
     */
    public function getStartDateTime()
    {
        return $this->start_date_time;
    }

    /**
     * Set the value of start_date_time
     */
    public function setStartDateTime($start_date_time): self
    {
        $this->start_date_time = $start_date_time;

        return $this;
    }

    /**
     * Get the value of end_date_time
     */
    public function getEndDateTime()
    {
        return $this->end_date_time;
    }

    /**
     * Set the value of end_date_time
     */
    public function setEndDateTime($end_date_time): self
    {
        $this->end_date_time = $end_date_time;

        return $this;
    }

    /**
     * Get the value of reservation
     */
    public function getReservation()
    {
        return $this->reservation;
    }

    /**
     * Set the value of reservation
     */
    public function setReservation($reservation): self
    {
        $this->reservation = $reservation;

        return $this;
    }
}