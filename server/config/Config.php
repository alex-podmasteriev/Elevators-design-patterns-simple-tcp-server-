<?php
class Config {

	static $base_url = A;
	static $elevators = 2;
	static $floors = 9;
	static $elev_name_pref = 'Elevator_';

	static $default_controller = 'error';
	static $default_method = 'index';
	static $default_args = NULL;
	static $main_controller = 'get';
	static $main_method = 'config';
	static $main_args = NULL;

	static $magic = array(
		'__construct',
		'__destruct',
		'__call',
		'__callStatic',
		'__get',
		'__set',
		'__isset',
		'__unset',
		'__sleep',
		'__wakeup',
		'__toString',
		'__invoke',
		'__set_state',
		'__clone',
		'__debugInfo',
	);
}
