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
require_once '../../lib/DbManager.php';
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
    $check=explode(" ",$content);
	$flag=true;
	if ($check[0]=='REV' && sizeof($check)==3) {
		if ($check[2]>=1 && $check[2]<=5) {
			$number=explode(':',$address);
			updateReview($check[1],$number[1],$check[2]);
		} else{
			$flag=false;
		}
	} elseif (!$flag) {
		echo "Invalid Review Responce";
	} else{
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
	}
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
			$responseMsg=getResponce($splitMessage[0], $splitMessage[1]);
			$receiver = new SmsReceiver();
			saveRequest($splitMessage,$receiver->getAddress(),$responseMsg);
		}
	} else {
		$responseMsg='Invalid Message content format should be [source] <space> [destination]';
	}
	return $responseMsg;
}

function checkValidCity($cityName){
	$existing = getLocationByName($cityName);
    if (is_null($existing)) {
        return false;
    }
	return true;
}

function getResponce($source, $destination) {
	$sourceInfo = getLocationByName($source);
    if (is_null($sourceInfo)) {
        return 'source name could not found';
    }
	$destinationInfo = getLocationByName($destination);
    if (is_null($destinationInfo)) {
        return 'destination name could not found';
    }
	include_once '../../lib/DriverSearch.php';
	$driverFinder = new DriverSearch();
    $driversNearSourceLocation = $driverFinder->findDriversNearSourceLocation(array($sourceInfo->lat, $sourceInfo->lng));
    if ($driversNearSourceLocation) {
        //TODO send sms to drivers and wait for their response.
    }
	$matchingDrivers = $driverFinder->getMatchingDrivers(array($sourceInfo->lat, $sourceInfo->lng), array($destinationInfo->lat, $destinationInfo->lng), 5);
	$rowFormat = '%firstName% %lastName% %phone%';
	$driverRows = array();
	foreach ($matchingDrivers as $driver) {
		$replacements = array('%firstName%' => $driver['first_name'], '%lastName%' => $driver['last_name'], '%phone%' => $driver['mobile_number']);
		$driverRows[] = strtr($rowFormat, $replacements);
	}
	return count($driverRows) > 0 ? "List of Drivers \n" . implode("\n", $driverRows) : 'No matching driver found';
}

function saveRequest($splitMessage,$address,$responseMsg){
	$number=explode(':',$address);
	$query = "Insert INTO  `request_log` (`requestNumber` ,`source` ,`destination` ,`responce`) VALUES (:requestNumber,:source ,:destination ,:responce)";
	$st = DbManager::getConnection()->prepare($query);
	$st->bindParam(":requestNumber", $number[1]);
	$st->bindParam(":source", $splitMessage[0]);
	$st->bindParam(":destination", $splitMessage[1]);
	$st->bindParam(":responce", $responseMsg);
	$st->execute();
}

function getLocationByName($locationName, $fuzzy = 1.0) {
    $pdo = DbManager::getConnection();
    $sth = $pdo->prepare('select latitude as lat, longitude as lng, "LK" as countryCode from location where place_name = :name');
    $sth->execute(array('name' => $locationName));
    $location = $sth->fetchObject();
    if ($location) {
        return $location;
    }
	require_once 'Services/GeoNames.php';
	$geo = new Services_GeoNames();
	$locations = $geo->search(array('q' => $locationName, 'username' => 'damith', 'maxRows' => '10', 'fuzzy' => $fuzzy));
	$lkLocations = array_filter($locations, function($val) {
        return $val->countryCode == 'LK';
    });
    return array_shift($lkLocations);
}

function updateReview($driverId,$userNumber,$review){
	$query="UPDATE `tad_courier`.`review_table` SET `rating` = $review WHERE `driver_id` =$driverId AND `userNumber` = $userNumber";
	$sth = DbManager::getConnection()->query($query);
	return $sth->execute();
}