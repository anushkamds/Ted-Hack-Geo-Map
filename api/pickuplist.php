<?php
require 'GetServiceList.php';
$getServiceList=new GetServiceList();
echo json_encode($getServiceList->getPickupList());
?>
