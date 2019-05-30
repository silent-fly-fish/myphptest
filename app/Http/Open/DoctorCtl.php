<?php


namespace App\Http\Open;

use App\Http\ORM\DoctorORM;
class DoctorCtl
{
    /**
     * 获取医生详情
     * @param $id
     */
    static function getDoctorInfo($id) {

        $doctorInfo = DoctorORM::getInfoById($id);

        jsonOut('success', $doctorInfo);
    }

    /**
     * 获取单个医生基本信息
     * @param $id
     */
    static function getOneDoctor($id) {

        $doctorInfo = DoctorORM::getOneById($id);

        jsonOut('success', $doctorInfo);
    }

    /**
     * 获取医生列表详情
     * @param $doctorIds
     */
    static function getDoctorList($doctorIds) {
        $doctorIds = explode(',',$doctorIds);

        $doctorList = DoctorORM::getAllByOpen($doctorIds);

        jsonOut('success', $doctorList);
    }

    /**
     * 获取医生列表基本信息（头像和名称）
     * @param $doctorIds
     */
    static function getDoctorListBase($doctorIds) {
        $doctorIds = explode(',',$doctorIds);

        $doctorList = DoctorORM::getBaseAllByOpen($doctorIds);

        jsonOut('success', $doctorList);
    }


}