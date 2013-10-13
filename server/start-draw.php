<?php 

error_reporting(E_ALL);

set_time_limit(0);

include 'vendor/NotORM/NotORM.php';
include 'LottoData.php';

$config = include 'config.php';

$config = $config['server'];

echo PHP_EOL.PHP_EOL;

echo '> generating winning numbers...'. PHP_EOL;

$draw = LottoData::instance()->draw();

if($draw['status'] < 1)
{
	echo '> Winning combinations has been selected already.'.PHP_EOL.PHP_EOL;

	die;
}

echo '> winning combinations ['.$draw['combination'].']'.PHP_EOL;
echo '> draw date ['.$draw['draw_date'].']'.PHP_EOL;

echo '> checking bets'.PHP_EOL;

foreach($draw['results'] as $result)
{
	echo '------------'.PHP_EOL;
	echo 'combination: '.$result['combination'].PHP_EOL;
	echo 'matched: '.$result['matched'].PHP_EOL;
	echo 'winner: '.($result['winner'] ? 'yes' : 'no').PHP_EOL;
}

echo PHP_EOL;
echo '> end of draw.'.PHP_EOL.PHP_EOL;