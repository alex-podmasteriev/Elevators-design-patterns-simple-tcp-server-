<?php
interface ElevatorInterface {

    public function isStandingAtFloor( $command ) ;

    public function isMovingToFloor( $command );

    public function isStandingAtAnotherFloor( $command );

}

