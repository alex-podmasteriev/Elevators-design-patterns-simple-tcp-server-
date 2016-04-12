<?php
class Command implements CommandInterface{

    protected $direction;
    protected $floor;
    protected $status = TRUE;

    public function __construct( $floor, $direction ) {
        $this->floor = $floor;
        $this->direction = $direction;
    }

    public function executed() {

        $this->status = FALSE;

    }

    public function getStatus() {

        return $this->status;

    }

    public function getFloor() {

        return $this->floor;
    }

    public function getDirection() {

        return $this->direction;

    }

}
