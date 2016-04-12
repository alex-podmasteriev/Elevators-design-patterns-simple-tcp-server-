<?php
class Get extends Route {


    public function config( ) {

        $config = new stdClass();
        $config->elevators = Config::$elevators;
        $config->floors = Config::$floors;

        return json_encode( $config );

    }

    public function elevators( ) {

        $elevators = CPU::getElevators();

        return json_encode( $elevators );

    }

}

