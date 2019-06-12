<?php


namespace App\Http\Doctor;


use App\Http\Module\DoctorApply;
use App\Http\ORM\DoctorApplyORM;
use App\Http\ORM\DoctorORM;

class DoctorCtl
{
    /**
     * 发送登录短信验证码
     * @param $phone
     */
    static function phoneLoginCode($phone) {
        $doctorInfo = DoctorORM::getOneByName($phone);
        if(empty($doctorInfo)) {
            jsonOut('doctorPhoneNotExist',false);
        }

        if($doctorInfo['r_status'] == 0) {
            jsonOut('doctorPhoneStop',false);
        }

        $result = sendSms($phone,2);

        jsonOut('success',$result);
    }

    /**
     * 用户手机号登录
     * @param $phone
     * @param $code
     */
    static function phoneLogin($phone,$code) {
        $doctorInfo = DoctorORM::getOneByName($phone);
        if(empty($doctorInfo)) {
            jsonOut('doctorPhoneNotExist',false);
        }
        $redisCode = getRedisDataByKey(env('REDIS_CODE_DOCTOR').$phone);
        if($redisCode == $code && empty($code)) {
            //更新登录时间和token
            $doctorData = [
                'doctor_id' => $doctorInfo['id'],
                'login_time' => time(),
                'token' => '', //TODO
                'token_time' => '' //TODO
            ];
            DoctorORM::update($doctorData);
            $returnData = [
                'id' => $doctorInfo['id'],
                'token' => '',
                'name' => $doctorInfo['name'],
                'real_name' => $doctorInfo['real_name'],
                'img' => $doctorInfo['img']
            ];
            jsonOut('success',$returnData);
        }
        jsonOut('phoneCodeError',false);
    }

    /**
     * 医生申请入驻
     * @param $data
     */
    static function apply($data) {
        $phone = $data['phone'];
        $code = (int)$data['code'];
        //验证手机号是否注册
        $doctorInfo = DoctorORM::getOneByName($phone);
        if($doctorInfo) {
            jsonOut('phoneIsRegister',false);
        }
        //验证验证码是否正确
        $redisCode = getRedisDataByKey(env('REDIS_CODE_DOCTOR').$phone);

        if($redisCode !== $code || empty($code)) {
            jsonOut('phoneCodeError',false);
        }


        $result = DoctorApplyORM::addOne($data);

        if($result) {
            jsonOut('success',true);
        }
        jsonOut('success',false);
    }

    static function sendApplyCode($phone) {
        $doctorInfo = DoctorORM::getOneByName($phone);
        if($doctorInfo) {
            jsonOut('phoneIsRegister',false);
        }
        $result = sendSms($phone,2);

        jsonOut('success',$result);
    }

}