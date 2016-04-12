<?php
class Press extends Route {

	public function button( $args ){
		if ( is_array( $args )
			&& isset( $args['floor'] )
			&& isset( $args['direction'] )
			&& ( intval($args['floor']) > 0 && intval( $args['floor']) <= Config::$floors )
			&& ($args['direction'] == 'up' || $args['direction'] == 'down')
		) {
			CPU::pressButton( intval($args['floor']), $args['direction']);
			$response = new stdClass();
			$response->status = 'Success';
			return json_encode( $response );
		}

		$response = new stdClass();
		$response->status = 'Error';
		return json_encode( $response );

	}
}
