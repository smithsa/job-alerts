<?php

$config = include(dirname(__FILE__).'/../config.php');
require(dirname(__FILE__).'/../src/FeedDatabase.php');

$feedDb = new FeedDatabase($config);
$dbCreateResult = $feedDb->createTable();
var_dump($dbCreateResult);