<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$config = include(dirname(__FILE__).'/../config.php');
require(dirname(__FILE__).'/../src/FeedDatabase.php');

$feedDb = new FeedDatabase($config);
$dbDeletedResult = $feedDb->deleteJobEntriesTable();
var_dump($dbDeletedResult);

$feedDb = new FeedDatabase($config);
$dbPopulateResult = $feedDb->updateTable();
var_dump($dbPopulateResult);