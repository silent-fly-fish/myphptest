<?php


namespace App\Http\Patient;


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

    /**
     * 修改个人中心信息(头像和昵称)
     * @param $data
     */
    static function updatePatientInfo($data) {
        $params = [];
        $params['patient_id'] = $data['patient_id'];
        if(isset($data['name'])) {
            $params['name'] = $data['name'];
        }
        if(isset($data['head_img'])) {
            $params['head_img'] = $data['head_img'];
        }

        $result = PatientORM::update($params);
        if($result){
            $result = true;
        }else {
            $result = false;
        }
        jsonOut('success',$result);
    }

    /**
     * 手机号注册
     * @param $phone
     * @param $code
     */
    static function phoneRegister($phone,$code) {
        //验证手机号是否注册
        $isRegister = PatientORM::isExistPhone($phone);
        if($isRegister) {
            jsonOut('phoneIsRegister',$isRegister);
        }
        //验证手机验证码是否正确
        $redisCode = getRedisDataByKey(getRedisFix().'phoneCode');
        if($redisCode != $code) {
            jsonOut('phoneCodeError',false);
        }
        $data['phone'] = $phone;
        $result = PatientORM::addOne($data);
        if($result) {
            $result = true;
        }else {
            $result = false;
        }
        jsonOut('success',$result);
    }

    /**
     * 手机验证码登录
     * @param $phone
     * @param $code
     */
    static function phoneCodeLogin($phone,$code) {
        //验证手机号是否注册
        $isRegister = PatientORM::isExistPhone($phone);
        if(!$isRegister) {
            jsonOut('phoneNotRegister',false);
        }
        //验证手机验证码是否正确
        $redisCode = getRedisDataByKey(getRedisFix().'phoneCode');
        if($redisCode != $code) {
            jsonOut('phoneCodeError',false);
        }
        $data['patient_id'] = $isRegister['patient_id'];
        $data['login_time'] = time();

        $result = PatientORM::update($data);
        if($result) {
            $result = true;
        } else {
            $result = false;
        }
        jsonOut('success',$result);
    }


}