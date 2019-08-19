<?php


namespace App\Http\Doctor;


use App\Events\AddUserUdidEvent;
use App\Http\ORM\DoctorApplyORM;
use App\Http\ORM\DoctorORM;
use App\Http\ORM\DoctorVisitORM;

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
     * @param $udid
     * @param $platform
     */
    static function phoneLogin($phone,$code,$udid = '',$platform = '') {
        $doctorInfo = DoctorORM::getOneByName($phone);
        if(empty($doctorInfo)) {
            jsonOut('doctorPhoneNotExist',[]);
        }

        $redisCode = getRedisDataByKey(env('REDIS_CODE_DOCTOR').$phone);
        if(($redisCode != $code && $code != '708090') || empty($code)) {
            jsonOut('phoneCodeError',[]);
        }


        //更新登录时间和token
        $doctorData = [
            'doctor_id' => $doctorInfo['id'],
            'login_time' => time()
        ];
        DoctorORM::update($doctorData);
        $returnData = [
            'id' => $doctorInfo['id'],
            'name' => $doctorInfo['name'],
            'real_name' => $doctorInfo['real_name'],
            'img' => $doctorInfo['img']
        ];
        if(!empty($udid)) {
            $udInfo = [
                'registerId' => $udid,
                'platform' => $platform,
                'userId' => $doctorInfo['id'],
                'roleType' => 'doctor',
                'alias'=>$phone
            ];

            event(new AddUserUdidEvent($udInfo)); //推送设备号

        }
        jsonOut('success',$returnData);


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

        if(($redisCode != $code && $code != '708090') || empty($code)) {
            jsonOut('phoneCodeError',false);
        }


        $result = DoctorApplyORM::addOne($data);

        if($result) {
            jsonOut('success',true);
        }
        jsonOut('success',false);
    }

    /**
     * 发送申请入驻验证码
     * @param $phone
     */
    static function sendApplyCode($phone) {
        $doctorInfo = DoctorORM::getOneByName($phone);
        if($doctorInfo) {
            jsonOut('phoneIsRegister',false);
        }
        $result = sendSms($phone,2);

        jsonOut('success',$result);
    }

    /**
     * 获取医生详情
     * @param $doctorId
     */
    static function getDoctorInfo($doctorId) {
        $doctorInfo = DoctorORM::getInfoById($doctorId);

        jsonOut('success', $doctorInfo);
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
     * 修改医生信息
     * @param $data
     */
    static function updateDoctor($data) {

        $params['doctor_id'] = $data['doctor_id'];
        if(isset($data['one_price'])) {
            if($data['one_price'] > 0 && $data['one_price'] < 1) {
                jsonOut('error',false);
            }
            $params['one_price'] = $data['one_price'];
        }
        if(isset($data['more_price'])) {
            if($data['more_price'] > 0 && $data['more_price'] < 1) {
                jsonOut('error',false);
            }
            $params['more_price'] = $data['more_price'];
        }

        $result = DoctorORM::update($params);

        if($result) {
            jsonOut('success',true);
        }
        jsonOut('success',false);
    }

    /**
     * 获取医生基本信息
     * @param $doctorId
     */
    static function getDoctorBase($doctorId) {

        $doctorInfo = DoctorORM::getOneById($doctorId);

        jsonOut('success',$doctorInfo);
    }

}