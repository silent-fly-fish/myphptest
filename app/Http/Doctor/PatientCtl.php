<?php


namespace App\Http\Doctor;


use App\Http\ORM\PatientAttentionORM;

class PatientCtl
{

    /**
     * 获取患者列表
     * @param $doctorId
     * @param $page
     * @param $size
     * @param $tagId
     */
    static function getPatientList($doctorId,$page,$size,$tagId) {

        $patientList = PatientAttentionORM::getAll($doctorId,$page,$size,$tagId);

        jsonOut('success',$patientList);
    }


}