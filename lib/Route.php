<?php

/*
 * OrangeHRM Enterprise is a closed sourced comprehensive Human Resource Management (HRM) 
 * System that captures all the essential functionalities required for any enterprise. 
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com 
 * 
 * OrangeHRM Inc is the owner of the patent, copyright, trade secrets, trademarks and any 
 * other intellectual property rights which subsist in the Licensed Materials. OrangeHRM Inc 
 * is the owner of the media / downloaded OrangeHRM Enterprise software files on which the 
 * Licensed Materials are received. Title to the Licensed Materials and media shall remain 
 * vested in OrangeHRM Inc. For the avoidance of doubt title and all intellectual property 
 * rights to any design, new software, new protocol, new interface, enhancement, update, 
 * derivative works, revised screen text or any other items that OrangeHRM Inc creates for 
 * Customer shall remain vested in OrangeHRM Inc. Any rights not expressly granted herein are 
 * reserved to OrangeHRM Inc. 
 * 
 * Please refer http://www.orangehrm.com/Files/OrangeHRM_Commercial_License.pdf for the license which includes terms and conditions on using this software. 
 *  
 */

/**
 * [Route]
 * 
 * [class Description]
 * 
 * @version 1.0
 * @package 
 * @author Nuwan Chathuranga <nuwan@orangehrm.us.com>
 */
class Route {

    protected $id;
    protected $source = '';
    protected $destination = '';
    protected $depatureTime = '';
    protected $arrivaTime = '';
    protected $days = '';
    protected $driverId;

    public function getId() {
        return $this->id;
    }

    public function getSource() {
        return $this->source;
    }

    public function getDestination() {
        return $this->destination;
    }

    public function getDepatureTime() {
        return $this->depatureTime;
    }

    public function getArrivaTime() {
        return $this->arrivaTime;
    }

    public function getDays() {
        return $this->days;
    }

    public function getDriverId() {
        return $this->driverId;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setSource($source) {
        $this->source = $source;
    }

    public function setDestination($destination) {
        $this->destination = $destination;
    }

    public function setDepatureTime($depatureTime) {
        $this->depatureTime = $depatureTime;
    }

    public function setArrivaTime($arrivaTime) {
        $this->arrivaTime = $arrivaTime;
    }

    public function setDays($days) {
        $this->days = $days;
    }

    public function setDriverId($driverId) {
        $this->driverId = $driverId;
    }

    public function save() {
        $query = "Insert INTO  `route`(source, destination, depature_time, arrival_time, days, driver_id) VALUES (:source, :destination, :depature_time, :arrival_time, :days, :driver_id)";
        $st = DbManager::getConnection()->prepare($query);
        $st->bindParam(":source", $this->source);
        $st->bindParam(":destination", $this->destination);
        $st->bindParam(":depature_time", $this->depatureTime);
        $st->bindParam(":arrival_time", $this->arrivaTime);
        $st->bindParam(":days", $this->days);
        $st->bindParam(":driver_id", $this->driverId);
        $st->execute();
        $this->setId($this->getLastInsertedId());
    }

    protected function getLastInsertedId() {
        $query = "SELECT max(id) as last_id FROM `route`";
        $id = DbManager::getConnection()->query($query)->fetchAll(PDO::FETCH_ASSOC);
        return $id[0]['last_id'];
    }

}
