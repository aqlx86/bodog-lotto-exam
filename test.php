<?php

$start = '07:00:00';
$end = '20:00:00';

$c = date('H:i:s');
$c = '07:00:00';
$c = '06:59:00';
$c = '19:59:00';
$c = '20:00:01';

var_dump($c >= $start AND $c <= $end);