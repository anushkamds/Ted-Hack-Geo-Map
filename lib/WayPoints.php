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
 * [WayPoints]
 * 
 * [class Description]
 * 
 * @version 1.0
 * @package 
 * @author Nuwan Chathuranga <nuwan@orangehrm.us.com>
 */
class WayPoints {

    protected $routeId;
    protected $locationId;

    public function getRouteId() {
        return $this->routeId;
    }

    public function getLocationId() {
        return $this->locationId;
    }

    public function setRouteId($routeId) {
        $this->routeId = $routeId;
    }

    public function setLocationId($locationId) {
        $this->locationId = $locationId;
    }

    public function save() {
        $query = "Insert INTO  `way_point`(route_id, location_id) VALUES (:route_id, :location_id)";
        $st = DbManager::getConnection()->prepare($query);
        $st->bindParam(":route_id", $this->routeId);
        $st->bindParam(":location_id", $this->locationId);
        $st->execute();
    }

}
