<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$config = include(dirname(__FILE__).'/../config.php');
require(dirname(__FILE__).'/../src/FeedDatabase.php');

$feedDb = new FeedDatabase($config);
$feed = new Feed($config);
$dbGetResult = $feedDb->getFeedFromDatabase();
$feedResult = $feed->getJSONString($dbGetResult);
//var_dump($dbGetResult);
var_dump($feedResult);