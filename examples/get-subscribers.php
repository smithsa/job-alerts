<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$config = include(dirname(__FILE__).'/../config.php');
require(dirname(__FILE__).'/../src/Subscriber.php');

$subscriber = new Subscriber($config);
$members = $subscriber->getAll();

var_dump($members );