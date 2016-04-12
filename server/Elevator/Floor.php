<?php
class Floor {

    protected $number;

    public function __construct($number) {

        $this->number = $number;
    }

    public function pressButton( $direction ) {

        $command = new Command( $this->number, $direction );

        CPU::setCommand( $command );

    }

    public function getNumber() {

        return $this->number;

    }
}



