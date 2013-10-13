<?php 

return array(
	'server' => array
	(
		// if deployed in a different machine put server ip or name
		// otherwise localhost or 127.0.0.1
		'server_name' => '127.0.0.1',

		// leave as is
		'server_script' => '/server/start-server.php',
		
		// leave as is
		'address' => '0.0.0.0',

		// leave as is
		'port' => 9000,
	),

	'database' => array
	(
		// database name
		'name' => 'test_lotto',

		// database user
		'user' => 'root',

		// database password
		'password' => '123456'
	),

	'game' => array(
		'start' => '07:00:00',
		'end' => '20:00:00',
		'draw' => '21:00:00'
	)
);