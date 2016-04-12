<?php
class Elevator implements ElevatorInterface, TouchableElevatorInterface {


    protected $name;
    protected $state = 'stop';
    protected $direction = '';
    protected $door_status = 'closed';
    protected $current_floor = 1;
    protected $destination_floors = [];

    use TouchableElevator;

    public function __construct( $name ) {

        $this->name = Config::$elev_name_pref . $name;

        $file = __DIR__ . '/elevators/' . $this->name . '.json';

        if ( file_exists( $file ) ) {

            $data = json_decode( file_get_contents( $file ) );

            $this->state = $data->state;
            $this->direction = $data->direction;
            $this->door_status = $data->door_status;
            $this->current_floor = $data->current_floor;
            $this->destination_floors = $data->destination_floors;

        }
    }

    public function getStatement(){

        $file = __DIR__ . '/elevators/' . $this->name . '.json';

        if ( file_exists( $file ) ) {

            $data = json_decode( file_get_contents( $file ) );

            $this->state = $data->state;
            $this->direction = $data->direction;
            $this->door_status = $data->door_status;
            $this->current_floor = $data->current_floor;
            $this->destination_floors = $data->destination_floors;

            return $data;

        }
        return FALSE;
    }
  

    /* Executing commands */

    public function isStandingAtFloor( $command ) {

        if (
            $command->getStatus()

            && $this->state === 'stop'

            && empty ( $this->destination_floors )

            && $command->getFloor() === $this->current_floor

            && $this->door_status === 'closed'

        ) {

            $this->acceptCommand( $command );
            $command->executed();

        }

        return $command;

    }

    public function isMovingToFloor( $command ) {

        if (

            $command->getStatus()

            && $this->direction == $command->getDIrection() && (

                ( $this->direction == 'up' && $this->current_floor < $command->getFloor() && $command->getFloor() <= max( $this->destination_floors ) )
                ||
                (  $this->direction == 'down' && $this->current_floor > $command->getFloor() && $command->getFloor() >= min( $this->destination_floors ) )
            )
        ) {

            $this->acceptCommand( $command );
            $command->executed();

        }

        return $command;

    }

    public function isStandingAtAnotherFloor( $command ){

        if (
            $command->getStatus()

            && $this->state === 'stop'

            && empty ( $this->destination_floors )

            && $this->door_status === 'closed'

        ) {

            $this->acceptCommand( $command );
            $command->executed();

        }

        return $command;

    }


    protected function acceptCommand( $command ) {

        $floor = $command->getFloor();

        if ( ! in_array( $floor, $this->destination_floors ) ) {

            array_push( $this->destination_floors, $floor );

            $this->destination_floors = array_values( $this->destination_floors );
        }

        if ( min( $this->destination_floors ) > $this->current_floor ) {

            $this->direction = 'up';

        } elseif( max( $this->destination_floors ) < $this->current_floor ) {

            $this->direction = 'down';
        }

        $this->saveStatement();

    }

}

