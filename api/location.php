<?php
require 'UpdateLocation.php';
$updateLocation=new UpdateLocation();
$updateLocation->updateLocation($_POST['lat'], $_POST['log'], $_POST['id']); 
echo json_encode("done");
?>