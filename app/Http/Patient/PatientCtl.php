<?php


namespace App\Http\Patient;


use App\Http\Module\PatientHistory;
use App\Http\ORM\PatientHistoryORM;
use App\Http\ORM\PatientORM;
use App\Http\ORM\PatientSuggestORM;

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

    /**
     * 退出登录
     * @param $patientId
     */
    static function logout($patientId) {
        //todo 去除登录信息
    }

    /**
     * 添加历史记录
     * @param $data
     */
    static function addHistory($data) {
        //查看搜索历史
        $history = PatientHistoryORM::getOneByPatientIdAndSearchAndType($data['patient_id'],$data['search'],$data['type']);
        if($history) {
            $params['id'] = $history['id'];
            $params['number'] = $history['number'] + 1;
            $result = PatientHistoryORM::update($params);

        }else{
            $result = PatientHistoryORM::addOne($data);
        }
        if($result) {
            $result = true;
        }else {
            $result = false;
        }
        jsonOut('success', $result);
    }

    /**
     * 意见反馈
     * @param $data
     */
    static function addSuggest($data) {
        if(isset($data['img_urls'])) {
            $data['img_urls'] = implode(',',$data['img_urls']);
        }
        $result = PatientSuggestORM::addOne($data);
        if($result) {
            $result = true;
        }else {
            $result = false;
        }
        jsonOut('success',$result);
    }

    /**
     * 输入用户邀请码
     * @param $patientId
     * @param $inviteCode
     */
    static function addInvitation($patientId,$inviteCode) {

        //验证邀请码是否存在
        $patientInfo = PatientORM::getOneByCode($inviteCode);
        if(empty($patientInfo)) {
            jsonOut('inviteCodeNotExist',false);
        }

        if($patientInfo['id'] == $patientId) {
            jsonOut('inviteCodeNotSelf',false);
        }


        $data['patient_id'] = $patientId;
        $data['invite_code'] = $inviteCode;
        $result = PatientORM::update($data);
        //todo 邀请人和被邀人完成邀请任务各获得积分 1.添加积分 2.几天积分记录
        if($result) {
            jsonOut('success',true);
        }
        jsonOut('success',false);
    }


}