<?php
require 'SaveServices.php';
$saveService = new SaveServices();
$id = $saveService->saveService($_POST['driverid'], $_POST['firstname']." ".$_POST['lastname'], $_POST['contactnumber'], $_POST['from'], $_POST['to'], $_POST['servicestate']); 
echo json_encode($id);
?>