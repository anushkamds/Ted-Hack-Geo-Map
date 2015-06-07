<?php

/**
 * [RegistrationDao]
 * 
 * [class Description]
 * 
 * @version 1.0
 * @package 
 * @author Nuwan Chathuranga <nuwan@orangehrm.us.com>
 */
class RegistrationDao {

    public function executeQuery($query) {
        return DbManager::getConnection()->query($query);
    }

    public function addNewCourier($id = null, $name, $address, $telephone, $otherTele) {
        $query = '';
        if (is_null($id)) {
            $query .="INSERT INTO `courrier_service` (`id`, `name`, `address`, `telephone`, `other_telephone`) VALUES"
                    . "(null, '{$name}', '{$address}', '{$telephone}', '{$otherTele}'),";
        } else {
            $query .="UPDATE `courrier_service` SET `name` = '{$name}', `address` = {$address}', `telephone` = '{$telephone}', `other_telephone` = '{$otherTele}' WHERE `id` = '{$id}'";
        }
    }

}
