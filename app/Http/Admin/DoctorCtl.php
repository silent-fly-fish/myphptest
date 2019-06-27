<?php


namespace App\Http\Admin;


use App\Http\Module\Doctor;
use App\Http\ORM\DoctorORM;
use App\Http\ORM\HospitalORM;

class DoctorCtl
{
    /**
     * 获取医生详情
     * @param $doctorId
     */
    static function getDoctorInfo($doctorId) {
        $doctorInfo = DoctorORM::getInfoById($doctorId);

        jsonOut('success', $doctorInfo);
    }

    /**
     * 医生列表
     * @param $data
     */
    static function getDoctorList($data) {
//        //地区筛选
//        $hospitalIds = [];
//        if(isset($data['province_id'])) {
//            $hospitalIds = HospitalORM::getAllByProvinceId($data['province_id']);
//        } else if(isset($data['city_id'])) {
//            $hospitalIds = HospitalORM::getAllByCityId($data['city_id']);
//        } else if(isset($data['country_id'])) {
//            $hospitalIds = HospitalORM::getAllByCountryId($data['country_id']);
//        }
//        $data['hospital_id'] = count($hospitalIds)? array_column($hospitalIds,'id') : [];

        $doctorList = DoctorORM::getAllList($data);

        jsonOut('success', $doctorList);
    }

    /**
     * 添加医生
     * @param $data
     */
    static function addDoctor($data) {
        $phone = $data['name'];
        $doctorInfo = DoctorORM::getOneByPhone($phone);
        if($doctorInfo) {
            jsonOut('phoneIsRegister',false);
        }
        $salt = substr(md5(time()),0,4);

        $data['password'] = md5(md5($data['password']).$salt);
        $data['category_ids'] = empty($data['category_ids'])? '':implode(',',$data['category_ids']);
        $data['salt'] = $salt;
        $result = DoctorORM::addOne($data);
        if($result) {
            $data['invite_code'] = createCode($result,1);
            $data['doctor_id'] = $result;
            @DoctorORM::update($data);
            jsonOut('success',true);
        }
        jsonOut('success',false);
    }

    /**
     * 更新医生信息
     * @param $data
     */
    static function updateDoctor($data) {
        $doctorInfo = DoctorORM::getOneById($data['doctor_id']);
        if(empty($doctorInfo)) {
            jsonOut('doctorNotExist',false);
        }
        if(isset($data['password'])) {
            $data['password'] = md5(md5($data['password']).$doctorInfo['salt']);
        }
        $result = DoctorORM::update($data);
        if($result) {
            jsonOut('success',true);
        }
        jsonOut('success',false);
    }

}