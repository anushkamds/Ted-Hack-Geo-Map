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
 * [CourierService]
 * 
 * [class Description]
 * 
 * @version 1.0
 * @package 
 * @author Nuwan Chathuranga <nuwan@orangehrm.us.com>
 */
class CourierService {

    protected $id;
    protected $name;
    protected $address;
    protected $telephone;
    protected $otherTelephone;

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getTelephone() {
        return $this->telephone;
    }

    public function getOtherTelephone() {
        return $this->otherTelephone;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setAddress($address) {
        $this->address = $address;
    }

    public function setTelephone($telephone) {
        $this->telephone = $telephone;
    }

    public function setOtherTelephone($otherTelephone) {
        $this->otherTelephone = $otherTelephone;
    }

    public function save() {
        $query = "INSERT INTO `courrier_service` (`name`, `address`, `telephone`, `other_telephone`) VALUES (:name, :address, :telephone, :other_telephone)";
        $st = DbManager::getConnection()->prepare($query);
        $st->bindParam(":name", $this->name);
        $st->bindParam(":address", $this->address);
        $st->bindParam(":telephone", $this->telephone);
        $st->bindParam(":other_telephone", $this->otherTelephone);
        $st->execute();
        $this->setId($this->getLastInsertedId());
    }

    public function executeQuery($query) {
        return DbManager::getConnection()->query($query);
    }

    public function getCourierServiceList() {
        $query = "SELECT * FROM `courrier_service`";
        return DbManager::getConnection()->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function getLastInsertedId() {
        $query = "SELECT max(id) as last_id FROM `courrier_service`";
        $id = DbManager::getConnection()->query($query)->fetchAll(PDO::FETCH_ASSOC);
        return $id['last_id'];
    }

}
