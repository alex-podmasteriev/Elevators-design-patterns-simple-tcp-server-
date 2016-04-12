<?php
class Router {

    private  $url;
    private  $controller;
    private  $method;
    private  $args = array();
    private $path;
    private $response;

    public function __construct( $request ){

        $this->path =
            __DIR__
            . DIRECTORY_SEPARATOR
            . ServerConfig::$rout_path
            . DIRECTORY_SEPARATOR;

        if ( isset ( $request[ 1 ] ) ) {

            $r = explode('?', $request[1]);
            $this->setUrl( $r[ 0 ] );
            $this->loadController( $this->controller, $this->method, $this->args );
        }
    }

    private function setUrl( $string ){
        $this->url = trim( $string );
        $this->url = explode( '/', $this->url );
        array_shift($this->url);
        $count = count( $this->url );
        for( $i=0 ; $i < $count; $i++ ){

            switch( $i ){
                case '0':
                    $this->controller = $this->setController( $this->url[$i] );
                    @require_once $this->path . $this->controller . '.php';
                    $this->method = Config::$default_method;
                    break;
                case '1':
                    $this->method = $this->setMethod( $this->url[$i] );
                    $this->args = Config::$default_args;
                    break;
                default:
                    $this->setArgs( $this->url[$i], $i );
            }
        }
    }

    private function setController( $name ) {
        $file = $this->path . $name . '.php';
        if( file_exists( $file ) ) {
            $controller = $name;
        } else {
            $controller = Config::$default_controller;
        }
        return $controller;
    }

    private function setMethod( $name ) {
        if( method_exists( $this->controller, $name ) ) {
            $check_public = new ReflectionMethod( $this->controller, $name );
            if( $check_public->isPublic () && ! array_search( $name, Config::$magic ) ) {
                $method = $name;
            } else {
                $this->controller = Config::$default_controller;
                $method = Config::$default_method;
            }
            unset( $check_public );
        } else {
            $this->controller = Config::$default_controller;
            $method = Config::$default_method;
        }
        return $method;
    }

    private function setArgs( $name, $i ){
        if ( count( $this->url ) > 3 ) {
            if( $i > 1 && $i % 2 == 0 ) {
                if( ! isset( $this->url [ $i+1 ]) ) {
                    $this->url[$i+1] = '';
                }
                $this->args [ $name ]=$this->url [$i+1];
            }
        } else {
            $this->args = $name;
        }
    }

    private function loadController( $controller, $method, $args ){

        @require_once $this->path . $controller . '.php';

        $controller = ucfirst( $controller );

        try{
            if( ! class_exists( $controller ) ) {

                throw new Exception( 'Class <b>'.$controller.'</b> was not defined!' );

            }
            $index = new $controller ();
            if( ! ( $index instanceof Route ) ) {

                throw new Exception('Class <b>'.$controller.'</b> does not extend class <b>Route</b>!');

            }
            if( ! method_exists( $index, $method ) ) {

                throw new Exception('Method <b>'.$method.'()</b> was not defined in class <b>'.$controller.'</b>!');

            }
            $this->response = $index->$method($args);

        } catch ( Exception $e ){

            die( $e->getMessage() );

        }
    }

    public function response(){

        return $this->response;
    }

}

