<?php
/**
 *
 */
 require_once __DIR__ . '/../lib/DbManager.php';

class UpdateLocation{
	
	public function updateLocation($lat, $log, $id){
		$query = "UPDATE `driver` SET `lat`=:lat, `log`=:log, `last_update_time`=:time WHERE `id`=:id";		
        $st = DbManager::getConnection()->prepare($query);
		
        $st->bindParam(":lat", $lat);
        $st->bindParam(":log", $log);		
        $st->bindParam(":id", $id);   
        $st->execute();
	}
	
}