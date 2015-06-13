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
	
	$responseMsg="";
	$explodeArray=explode(' ',$content);
	if($explodeArray[0]=='D'){
		$requestId=$explodeArray[2];
		if($explodeArray[1]=='F'){
			// Driver finishing the transaction
			//function goes here
			$responseMsg="Delivery $requestId Ended..";
		}
	}else{
		$responseMsg="Invalid SMS template.. ie:- D [D/F] TXN";
	}
    

	// Create the sender object server url
	$sender = new SmsSender("https://localhost:7443/sms/send");

	//sending a one message
	$applicationId = "APP_000001";
	$encoding = "0";
	$version =  "1.0";
	$password = "password";
	$sourceAddress = "77000";
	$deliveryStatusRequest = "1";
	$charging_amount = ":1.00";
	$destinationAddresses = array("tel:94771122336");
	$binary_header = "";
	$res = $sender->sms($responseMsg, $destinationAddresses, $password, $applicationId, $sourceAddress, $deliveryStatusRequest, $charging_amount, $encoding, $version, $binary_header);

} catch (SmsException $ex) {
    //throws when failed sending or receiving the sms
    error_log("ERROR: {$ex->getStatusCode()} | {$ex->getStatusMessage()}");
}

function endDriverTransaction($requestId, $driverNo){
	$selectQuery="SELECT id FROM driver WHERE mobile_number='$driverNo'";
	$sthSelect=DbManager::getConnection()->query($selectQuery);
	$driverData = $sthSelect->fetch(PDO::FETCH_ASSOC);
	
	$selectQuery="SELECT * FROM request_log WHERE id='$requestId'";
	$sthSelect=DbManager::getConnection()->query($selectQuery);
	$requestData = $sthSelect->fetch(PDO::FETCH_ASSOC);
	
	$query="UPDATE `tad_courier`.`review_table` SET `req_sent` = '1' WHERE `review_table`.`id` =$id";
	$sth = DbManager::getConnection()->query($query);
	return $sth->execute();
}
