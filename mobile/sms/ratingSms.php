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

checkReviewRequest();

function checkReviewRequest(){
	$query = 'SELECT * FROM `review_table` WHERE `req_sent` =0';
    $sth = DbManager::getConnection()->query($query);
	$results = $sth->fetchAll(PDO::FETCH_ASSOC);
	foreach($results as $reviewRequest){
		sendReviewRequest($reviewRequest['driver_id'],array('tel:'.$reviewRequest['userNumber']));
		setSentRequest($reviewRequest['id']);
	}
	if(sizeof($results)==0){
		echo "No Review Request to send";
	}
}

function setSentRequest($id){
	$query="UPDATE `tad_courier`.`review_table` SET `req_sent` = '1' WHERE `review_table`.`id` =$id";
	$sth = DbManager::getConnection()->query($query);
	return $sth->execute();
}

function sendReviewRequest($driverId,$destinationAddresses=array('tel:94771122336')){
	try {
		
		$responseMsg = "Please send your review for previous eCarrier service
		REV $driverId <review 1-5> 77000";

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
		//$destinationAddresses = array("tel:94771122336");
		$binary_header = "";
		$res = $sender->sms($responseMsg, $destinationAddresses, $password, $applicationId, $sourceAddress, $deliveryStatusRequest, $charging_amount, $encoding, $version, $binary_header);

	} catch (SmsException $ex) {
		error_log("ERROR: {$ex->getStatusCode()} | {$ex->getStatusMessage()}");
	}
}
