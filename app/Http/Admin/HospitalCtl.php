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

    /**
     * 获取医院列表
     * @param $getData
     */
    static function getHospitalList($getData) {

        $hospitalList = HospitalORM::getAllByList($getData);

        jsonOut('success',$hospitalList);
    }

    /**
     * 获取医院详情
     * @param $hospitalId
     */
    static function getHospitalInfo($hospitalId) {
        $hospitalInfo = HospitalORM::getOneById($hospitalId);

        jsonOut('success',$hospitalInfo);
    }

}