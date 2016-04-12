<?php
abstract class Route{

    public function index() {
        $response = new stdClass();
        $response->status = 'Error';
        return json_encode( $response );
    }

}