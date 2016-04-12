<?php
define( 'A', __DIR__ );
require_once __DIR__ . DIRECTORY_SEPARATOR . 'autoload.php';

$system = CPU::getInstance();

while ( TRUE ) {
	$system->moveElevators();
	sleep(1);
}
