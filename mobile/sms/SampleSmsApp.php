<?php
/**
 *   (C) Copyright 1997-2013 hSenid International (pvt) Limited.
 *   All Rights Reserved.
 *
 *   These materials are unpublished, proprietary, confidential source code of
 *   hSenid International (pvt) Limited and constitute a TRADE SECRET of hSenid
 *   International (pvt) Limited.
 *
 *   hSenid International (pvt) Limited retains all title to and intellectual
 *   property rights in these materials.
 */

include_once '../../lib/sms/SmsReceiver.php';
include_once '../../lib/sms/SmsSender.php';
include_once '../log.php';
ini_set('error_log', 'sms-app-error.log');

try {
    $receiver = new SmsReceiver(); // Create the Receiver object

    $content = $receiver->getMessage(); // get the message content
    $address = $receiver->getAddress(); // get the sender's address
    $requestId = $receiver->getRequestID(); // get the request ID
    $applicationId = $receiver->getApplicationId(); // get application ID
    $encoding = $receiver->getEncoding(); // get the encoding value
    $version = $receiver->getVersion(); // get the version

    logFile("[ content=$content, address=$address, requestId=$requestId, applicationId=$applicationId, encoding=$encoding, version=$version ]");

    $responseMsg;

    //your logic goes here......
    
    $responseMsg = getSourceAndDestination($content);

    // Create the sender object server url
    $sender = new SmsSender("https://localhost:7443/sms/send");

    //sending a one message

 	$applicationId = "APP_000001";
 	$encoding = "0";
 	$version =  "1.0";
    $password = "password";
    $sourceAddress = "77000";
    $deliveryStatusRequest = "1";
    $charging_amount = ":15.75";
    $destinationAddresses = array("tel:94771122336");
    $binary_header = "";
    $res = $sender->sms($responseMsg, $destinationAddresses, $password, $applicationId, $sourceAddress, $deliveryStatusRequest, $charging_amount, $encoding, $version, $binary_header);

} catch (SmsException $ex) {
    //throws when failed sending or receiving the sms
    error_log("ERROR: {$ex->getStatusCode()} | {$ex->getStatusMessage()}");
}


/*
	Extract Source and Destination from SMS message
**/
function getSourceAndDestination($smsMessage){
	$splitMessage=explode(' ', $smsMessage);
	$responseMsg='';
	if (sizeof($splitMessage) == 2) {
		$validation=true;
		if (!checkValidCity($splitMessage[0])) {
			$responseMsg.=' Invalid Source City Name';
			$validation=false;
		}
		if (!checkValidCity($splitMessage[1])) {
			$responseMsg.=' Invalid Destination City Name';
			$validation=false;
		}
		if ($validation) {
			if (saveRequest($splitMessage)) {
				$responseMsg=getResponce($splitMessage[0], $splitMessage[1]);
			}
		}
	} else {
		$responseMsg='Invalid Message content format should be [source] <space> [destination]';
	}
	return $responseMsg;
}

function checkValidCity($cityName){
	//check city is existing in the database;
	return true;
}

function getResponce($source, $destination) {
	$soureInfo = getLocationByName($source);
	$destInfo = getLocationByName($destination);
	include_once '../../lib/DriverSearch.php';
	$driverFinder = new DriverSearch();
	$matchingeDrivers = $driverFinder->getMatchingDrivers(array($soureInfo->lat, $soureInfo->lng), array($destInfo->lat, $destInfo->lng), 5);
	$rowFormat = '%firstName% %lastName% %phone%';
	$driverRows = array();
	foreach ($matchingeDrivers as $driver) {
		$replacements = array('%firstName%' => $driver['first_name'], '%lastName%' => $driver['last_name'], '%phone%' => $driver['mobile_number']);
		$driverRows[] = strtr($rowFormat, $replacements);
	}
	return "List of Drivers \n" . implode("\n", $driverRows);
}

function saveRequest($validRequest){
	return true;
}

function getLocationByName($locationName, $fuzzy = 1.0) {
	require_once 'Services/GeoNames.php';
	$geo = new Services_GeoNames();
	$locations = $geo->search(array('q' => $locationName, 'username' => 'damith', 'maxRows' => '10', 'fuzzy' => $fuzzy));
	return array_shift($locations);
}
