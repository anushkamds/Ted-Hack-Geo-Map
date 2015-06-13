<?php
/**
 *
 */
 require_once __DIR__ . '/../lib/DbManager.php';

class GetServiceList{
	
    public function getDeliveryList(){
        $query = "SELECT * FROM `service_log` WHERE `service_status` = 0 ORDER BY `id` DESC";
        $list= DbManager::getConnection()->query($query)->fetchAll(PDO::FETCH_ASSOC);
        return $list;
    }

    public function getPickupList(){
        $query = "SELECT * FROM `service_log` WHERE `service_status` = 1 ORDER BY `id` DESC";
        $list= DbManager::getConnection()->query($query)->fetchAll(PDO::FETCH_ASSOC);
        return $list;
    }	
	
}
