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
        $query = "Insert INTO  `driver`(nic,first_name,last_name,address,courrier_service_provide_id,mobile_number,other_number) VALUES (:nic, :first, :last, :address, :courier_id, :mobile_number, :other_number)";
        $st = DbManager::getConnection()->prepare($query);
        $st->bindParam(":nic", $this->nic);
        $st->bindParam(":first", $this->firstName);
        $st->bindParam(":last", $this->lastName);
        $st->bindParam(":address", $this->address);
        $st->bindParam(":courier_id", $this->courierServiceProviderId);
        $st->bindParam(":mobile_number", $this->mobileNumber);
        $st->bindParam(":other_number", $this->otherNumber);        
        $st->execute();
        $this->setId($this->getLastInsertedId());
    }

    protected function getLastInsertedId() {
        $query = "SELECT max(id) as last_id FROM `driver`";
        $id = DbManager::getConnection()->query($query)->fetchAll(PDO::FETCH_ASSOC);
        return $id[0]['last_id'];
    }

}
