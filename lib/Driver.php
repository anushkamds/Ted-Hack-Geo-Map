<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DriverSave
 *
 * @author chathura
 */
require_once 'DbManager.php';

class Driver {
    
    protected $id;
    protected $nic;
    protected $firstName;
    protected $lastName;
    protected $address;
    protected $courierServiceProviderId;
    protected $mobileNumber;
    protected $otherNumber;
    public function getMobileNumber() {
        return $this->mobileNumber;
    }

    public function getOtherNumber() {
        return $this->otherNumber;
    }

    public function setMobileNumber($mobileNumber) {
        $this->mobileNumber = $mobileNumber;
    }

    public function setOtherNumber($otherNumber) {
        $this->otherNumber = $otherNumber;
    }

        public function getId() {
        return $this->id;
    }

    public function getNic() {
        return $this->nic;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getCourierServiceProviderId() {
        return $this->courierServiceProviderId;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setNic($nic) {
        $this->nic = $nic;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    public function setAddress($address) {
        $this->address = $address;
    }

    public function setCourierServiceProviderId($courierServiceProviderId) {
        $this->courierServiceProviderId = $courierServiceProviderId;
    }

    public function save() {
        $query = "Insert INTO driver (NIC,first_name,last_name,address,courrier_service_provide_id) VALUES (:nic, :first, :last, :address, :courier_id)";
        $st = DbManager::getConnection()->prepare($query);
        $st->bind_param(":nic",  $this->getNic());
        $st->bind_param(":first", $this->getFirstName());
        $st->bind_param(":last", $this->getLastName());
        $st->bind_param(":address",  $this->getAddress());
        $st->bind_param(":courier_id", $this->getCourierServiceProviderId());
        
        $st->execute();
    }
    
}
