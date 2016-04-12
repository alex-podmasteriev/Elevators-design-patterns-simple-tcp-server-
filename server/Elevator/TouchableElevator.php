<?php
trait TouchableElevator {


    /*Implemented method*/
    public function touch() {

        switch( $this->state ) {
            case 'stop':
                $this->standingElevator();
                break;

            case 'move':
                $this->move()->movingElevator();
                break;
        }
        $this->saveStatement();
    }

    public function getFloor() {

        return $this->current_floor;
    }

    protected function ready() {

        $this->state = 'move';

        return $this;

    }

    protected function move() {

        if( $this->door_status === 'closed' ) {

            switch( $this->direction ) {

                case 'up':

                    $this->moveUp();

                    break;

                case 'down':

                    $this->moveDown();

                    break;
            }
        }
        return $this;
    }

    protected function moveUp() {

        if ( $this->state === 'move' && $this->current_floor < Config::$floors ) {

            $this->current_floor = $this->current_floor + 1;
        }

    }

    protected function moveDown() {

        if ( $this->state === 'move'  && $this->current_floor > 1 ) {

            $this->current_floor = $this->current_floor - 1;
        }

    }

    protected function unsetDirection( ) {

        if ( empty ( $this->destination_floors ) ) {

            $this->direction = '';

        }
        return $this;
    }

    protected function stop() {

        $this->state = 'stop';
        return $this;
    }

    protected function openDoor() {

        if ( $this->state === 'stop' ) {

            $this->door_status = 'opened';
        }
        return $this;
    }

    protected function closeDoor() {

        $this->door_status = 'closed';

        return $this;
    }

    protected function complete() {

        if ( $this->door_status === 'opened' && in_array( $this->current_floor, $this->destination_floors ) ) {

            unset(
                $this->destination_floors [ array_search( $this->current_floor, $this->destination_floors ) ]
            );
            $this->destination_floors = array_values( $this->destination_floors );

        }
        return $this;
    }

    protected function movingElevator(){

        if ( in_array( $this->current_floor, $this->destination_floors ) ) {

            $this->stop()->openDoor()->complete();

        }


    }

    protected function standingElevator() {

        switch ( $this->door_status ) {

            case 'opened':

                $this->closeDoor();

                break;

            case 'closed':

                if ( ! empty ( $this->destination_floors )  ) {

                    $this->ready();

                } else {

                    $this->unsetDirection();
                }

                break;
        }


    }

    protected function saveStatement(){

        $file = __DIR__ . '/elevators/' . $this->name.'.json';

        $data = new stdClass();
        $data->name = $this->name;
        $data->state = $this->state;
        $data->direction = $this->direction;
        $data->door_status = $this->door_status;
        $data->current_floor = $this->current_floor;
        $data->destination_floors = $this->destination_floors;

        @mkdir( __DIR__ . '/elevators', 0777);
        @chmod( $file, 0777 );
        file_put_contents( $file,  json_encode( $data ) );
        @chmod( $file, 0777 );

    }


}
