<?php
define( 'A', __DIR__ );
require_once A . DIRECTORY_SEPARATOR . '/config/ServerConfig.php';
require_once A . DIRECTORY_SEPARATOR . 'autoload.php';

set_time_limit(0);

if (

    ! ( $server = stream_socket_server("tcp://" . ServerConfig::$host . ":" . ServerConfig::$port, $err_num, $err_string ) )

) {

	exit ( ServerConfig::$server_crash . " - { $err_num } - { $err_string } - \r\n" );

}

while ( TRUE ) {


	$client = stream_socket_accept( $server, -1 );

	if ( $client ) {

		$pid = pcntl_fork();

		if ( $pid == -1 ) {

			exit( ServerConfig::$pid_error );

		} elseif ( ! $pid ) {

            $command = fgets( $client, 2048 );

            while( $line= fgets( $client, 2048 ) ) {

                if( trim( $line ) === '' ) {
                    break;

                }
            }

			$request = explode( ' ', $command );

			$app = new Router( $request );

            @fwrite($client, "HTTP/1.1 200 OK\r\n");
            @fwrite($client, "Access-Control-Allow-Origin: *\r\n");
            @fwrite($client, "Content-Type: application/json\r\n");
            @fwrite($client, "Connection: close\r\n\r\n");
            @fwrite( $client, $app->response() );

			fclose( $client );

			exit ();

		} elseif( $pid ) {

            fclose( $client );

        }
	}

}
