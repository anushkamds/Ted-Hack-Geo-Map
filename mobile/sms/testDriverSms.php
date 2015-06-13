<?php

$content="D S 3452";
include_once '../../lib/sms/SmsReceiver.php';
include_once '../../lib/sms/SmsSender.php';
include_once '../log.php';
require_once '../../lib/DbManager.php';
ini_set('error_log', 'sms-app-error.log');
$requestId=10;
$selectQuery="SELECT * FROM request_log WHERE id='$requestId'";
	$sthSelect=DbManager::getConnection()->query($selectQuery);
	$requestData = $sthSelect->fetch(PDO::FETCH_ASSOC);
var_dump($requestData);