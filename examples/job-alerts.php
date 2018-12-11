<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$config = include(dirname(__FILE__).'/../config.php');
require(dirname(__FILE__).'/../src/JobAlert.php');
require(dirname(__FILE__).'/../src/Subscriber.php');

$subscriberObject = new Subscriber($config);
$subscribers = $subscriberObject->getAll();
$jobAlertObject = new JobAlert($config);

$emailTemplate = new EmailTemplate($config);
$emailTemplate->description_is_sanitized = true;

$jobAlertResult = $jobAlertObject->sendAlerts($subscribers, $emailTemplate, $config['mail']['email_subject']);
var_dump($jobAlertResult);