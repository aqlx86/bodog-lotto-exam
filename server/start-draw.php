<?php 

error_reporting(E_ALL);

set_time_limit(0);

include 'vendor/NotORM/NotORM.php';
include 'LottoData.php';

$config = include 'config.php';

$config = $config['server'];

echo '> generating winning numbers...'. PHP_EOL;

echo '> winning combinations ['.LottoData::instance()->draw().']'.PHP_EOL;
