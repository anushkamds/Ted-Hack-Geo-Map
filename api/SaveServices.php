<?php
/**
 *
 */
 require_once __DIR__ . '/../lib/DbManager.php';

class SaveServices{
	
	public function saveService($driverid, $name, $number,$from, $to, $serviceType){
 		$query = "INSERT INTO `service_log` (`driver_id`, `customer_name`, `customer_phone_no`, `from`,`to`,`service_status`) VALUES (:driver_id, :customer_name, :customer_phone_no, :from, :to, :service_status)";;
        $st = DbManager::getConnection()->prepare($query);
        $st->bindParam(":driver_id", $driverid);
        $st->bindParam(":customer_name", $name);
        $st->bindParam(":customer_phone_no", $number);
		$st->bindParam(":from", $from);
		$st->bindParam(":to", $to);
        $st->bindParam(":service_status",$serviceType);
		$st->execute(); 
		return $this->getLastInsertedId();
	}
	 protected function getLastInsertedId() {
        $query = "SELECT max(id) as last_id FROM `service_log`";
        $id = DbManager::getConnection()->query($query)->fetchAll(PDO::FETCH_ASSOC);
        return $id[0]['last_id'];
    }
	
}