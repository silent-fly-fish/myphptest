<?php


namespace App\Http\Admin;


use App\Http\ORM\HospitalORM;

class HospitalCtl
{
    /**
     * 添加医院
     * @param $data
     */
    static function addHospital($data) {

        $result = HospitalORM::addOne($data);
        if($result) {
            jsonOut('success',true);
        }
        jsonOut('success',false);
    }

    /**
     * 修改医院信息
     * @param $data
     */
    static function updateHospital($data) {

        $result = HospitalORM::update($data);
        if($result) {
            jsonOut('success',true);
        }
        jsonOut('success',false);
    }

}