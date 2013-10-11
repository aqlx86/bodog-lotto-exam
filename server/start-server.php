<?php

error_reporting(E_ALL);

set_time_limit(0);

ob_implicit_flush();

include 'vendor/NotORM/NotORM.php';
include 'vendor/websocket/websockets.php';


$config = include 'config.php';

include 'LottoData.php';
include 'LottoServer.php';

// start the server
$server = new LottoServer($config['server']['address'] , $config['server']['port']);