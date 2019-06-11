<?php


namespace App\Http\Patient;

use App\Http\ORM\DoctorORM;
use App\Http\ORM\DoctorTeamORM;
use App\Http\ORM\DoctorVisitORM;
use App\Http\ORM\HospitalORM;
use App\Http\ORM\PatientORM;

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
     * 获取医生列表页
     * @param $data
     * @return mixed
     */
    static function getDoctorList($data) {

        //地区筛选
        $hospitalIds = [];
        if(isset($data['province_id'])) {
            $hospitalIds = HospitalORM::getAllByProvinceId($data['province_id']);
        } else if(isset($data['city_id'])) {
            $hospitalIds = HospitalORM::getAllByCityId($data['city_id']);
        } else if(isset($data['country_id'])) {
            $hospitalIds = HospitalORM::getAllByCountryId($data['country_id']);
        }
        $data['hospital_id'] = count($hospitalIds)? array_column($hospitalIds,'id') : [];


        $doctorList = DoctorORM::getAll($data);

        jsonOut('success', $doctorList);
    }

    /**
     * 获取医生出诊信息
     * @param $doctorId
     */
    static function getDoctorVisitList($doctorId) {

        $doctorVisitList = DoctorVisitORM::getVisitsByDoctorId($doctorId);

        foreach ($doctorVisitList as $k => $v) {
            $doctorVisitList[$k]['visit_json'] = json_decode($v['visit_json']);
        }

        jsonOut('success', $doctorVisitList);
    }

    /**
     * 获取医生团队
     * @param $doctorId
     */
    static function getDoctorTeam($doctorId) {
        $doctorTeam = DoctorTeamORM::getAllByDoctorId($doctorId);

        jsonOut('success', $doctorTeam);
    }



}