<?php


namespace App\Http\Open;


use App\Http\ORM\PatientORM;

class PatientCtl
{
    /**
     * 获取患者详情
     * @param $patientId
     */
    static function getPatientInfo($patientId) {
        $patientInfo = PatientORM::getOneById($patientId);

        jsonOut('success',$patientInfo);
    }

    static function getPatientList($patientIds) {
        $patientIds = explode(',',$patientIds);
        $patientList = PatientORM::getAllByOpen($patientIds);

        jsonOut('success',$patientList);
    }

}