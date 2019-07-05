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

    /**
     * 更新单个医生相关信息
     * @param $data
     */
    static function updateDoctor($data) {
        $params['doctor_id'] = $data['doctor_id'];
        if(isset($data['favorable_rate'])) {
            $params['favorable_rate'] = $data['favorable_rate'];
        }
        $result = DoctorORM::update($params);

        jsonOut('success',$result);
    }

    /**
     * 批量更新医生信息（跑好评率脚本）
     * @param $data
     */
    static function updateBatchDoctor($data) {

        $result = DoctorORM::updateBatchById($data);
        if($result) {
            jsonOut('success',true);
        }

        jsonOut('success',false);
    }

}