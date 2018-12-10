<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$config = include(dirname(__FILE__).'/../config.php');
require(dirname(__FILE__).'/../src/Interest.php');

$interest = new Interest($config);
$interestResult = $interest->get();

var_dump($interestResult);