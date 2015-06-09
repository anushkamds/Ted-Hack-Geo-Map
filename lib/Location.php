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
 * [Location]
 * 
 * [class Description]
 * 
 * @version 1.0
 * @package 
 * @author Nuwan Chathuranga <nuwan@orangehrm.us.com>
 */
class Location {

    protected $id;
    protected $name = '';
    protected $lat;
    protected $log;

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getLat() {
        return $this->lat;
    }

    public function getLog() {
        return $this->log;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setLat($lat) {
        $this->lat = $lat;
    }

    public function setLog($log) {
        $this->log = $log;
    }

    public function save() {
        $location = $this->getLocationByLatAndLog($this->lat, $this->log);
        if ($location instanceof Location) {
            $this->setId($location->getId());
            $this->setLat($location->getLat());
            $this->setLog($location->getLog());
            $this->setName($location->getName());
        } else {
            $query = "Insert INTO  `location`(name, lat, log) VALUES (:name, :lat, :log)";
            $st = DbManager::getConnection()->prepare($query);
            $st->bindParam(":name", $this->name);
            $st->bindParam(":lat", $this->lat);
            $st->bindParam(":log", $this->log);
            $st->execute();
            $this->setId($this->getLastInsertedId());
        }
    }

    protected function getLastInsertedId() {
        $query = "SELECT max(id) as last_id FROM `location`";
        $id = DbManager::getConnection()->query($query)->fetchAll(PDO::FETCH_ASSOC);
        return $id[0]['last_id'];
    }

    public function getLocationByLatAndLog($lat, $log) {
        $query = "SELECT * FROM `location` WHERE lat={$lat} AND log={$log}";
        $locationData = DbManager::getConnection()->query($query)->fetchAll(PDO::FETCH_ASSOC);
        if (isset($locationData[0])) {
            $location = new Location();
            $this->setId($locationData[0]['id']);
            $this->setLat($locationData[0]['lat']);
            $this->setLog($locationData[0]['log']);
            $this->setName($locationData[0]['name']);
            return $location;
        }
        return false;
    }

}
