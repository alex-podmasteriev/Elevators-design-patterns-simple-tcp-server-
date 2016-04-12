<?php
class CPU implements CPUInterface{

    protected static $_instance;

    protected $floors=[];
    protected $elevators=[];

    private function __construct( $floors, $elevators ) {

        for( $i = 1; $i <= $floors; $i++ ) {

            $this->floors[ $i-1 ] = new Floor( $i );
        }
        for( $i = 0; $i < $elevators; $i++ ) {

            $this->elevators [ $i ] = new Elevator( $i );
        }
    }

    public static function getInstance() {

        if ( self::$_instance === NULL ) {

            $elevators = Config::$elevators ;
            $floors = Config::$floors;

            self::$_instance = new self ( $floors, $elevators ) ;
        }

        return self::$_instance;
    }


    public static function setCommand( $command ) {

        if ( get_class( $command ) === 'Command' ) {

            $elevators = self::$_instance->elevators;

            foreach( $elevators as $elevator ) {

                $command = $elevator->isStandingAtFloor( $command );

                if ( ! $command->getStatus() ) {

                    break;
                }

            }

            if ( $command->getStatus() ) {

                foreach( $elevators as $elevator ) {

                    $command = $elevator->isMovingToFloor( $command );

                    if ( ! $command->getStatus() ) {

                        break;
                    }

                }

            }

            if ( $command->getStatus() ) {

                foreach( $elevators as $elevator ) {

                    $command = $elevator->isStandingAtAnotherFloor( $command );

                    if ( ! $command->getStatus() ) {

                        break;
                    }

                }

            }

        }
    }

    public static function pressButton( $number, $direction ) {

        $floors = self::getInstance()->floors;

        foreach ( $floors as $floor ) {

            if ( $floor->getNumber() == $number ) {

                $floor->pressButton( $direction );

            }

        }

    }

    public function moveElevators(){
        foreach ( $this->elevators as $elevator){
            $elevator->touch();
        }
    }

    public static function getElevators(){

        $data = [];

        foreach ( self::getInstance()->elevators as $elevator ) {

            array_push( $data, $elevator->getStatement() );
        }

        return $data;

    }

}

