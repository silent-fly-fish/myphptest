<?php


namespace App\Http\Open;


use App\Http\ORM\PatientORM;
use App\Http\ORM\DoctorORM;

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

    static function updatePatientInfo($data) {
        $params['patient_id'] = $data['patient_id'];
        $patientInfo = PatientORM::getOneById($data['patient_id']);
        if(!$patientInfo) {
            jsonOut('patientNotExist',false);
        }
        if(isset($data['intergral'])) {
            $params['intergral'] = $data['intergral'] + $patientInfo['intergral'];
        }
        if(isset($data['sign_date'])) {
            $params['sign_date'] = $data['sign_date'];
        }

        $result = PatientORM::update($params);
        if($result){
            jsonOut('success',true);
        }
        jsonOut('success',false);
    }

    static function decrease($data) {
        $params['patient_id'] = $data['patient_id'];
        $patientInfo = PatientORM::getOneById($data['patient_id']);
        if(!$patientInfo) {
            jsonOut('patientNotExist',false);
        }
        if(isset($data['intergral'])) {
            $params['intergral'] = $patientInfo['intergral'] - $data['intergral'];
        }

        $result = PatientORM::update($params);
        if($result){
            jsonOut('success',true);
        }
        jsonOut('success',false);
    }


    /**
     * 获取患者、医生对应的信息
     * @param $getData
     */
    static function getPatientDoctorInfo($getData){
        $patientId = isset($getData['patient_id'])?$getData['patient_id']:0;
        $doctorId = isset($getData['doctor_id'])?$getData['doctor_id']:0;

        $patientInfo = PatientORM::getOneById($patientId);
        if(!$patientInfo) {
            jsonOut('patientNotExist',false);
        }
        $doctorInfo = DoctorORM::getInfoById($doctorId);
        if(!$doctorInfo) {
            jsonOut('doctorNotExist',false);
        }
        jsonOut('success',['patients'=>$patientInfo,'doctors'=>$doctorInfo]);
    }
}