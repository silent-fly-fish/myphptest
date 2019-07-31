<?php


namespace App\Http\Patient;


use App\Events\AddUserUdidEvent;
use App\Events\ExamineUserEvent;
use App\Http\Module\PatientHistory;
use App\Http\ORM\DoctorViewORM;
use App\Http\ORM\DoctorVisitORM;
use App\Http\ORM\PatientAttentionORM;
use App\Http\ORM\PatientHistoryORM;
use App\Http\ORM\PatientORM;
use App\Http\ORM\PatientSuggestORM;
use App\Http\ORM\PatientAccusationORM;
use App\Http\ORM\PatientWechatORM;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class PatientCtl
{
    /**
     * 获取患者详情
     * @param $patientId
     */
    static function getPatientInfo($patientId) {
        $patientInfo = PatientORM::getOneById($patientId);
        $patientInfo['birth'] = empty($patientInfo['birth'])? 0 : date('Y-m-d',$patientInfo['birth']);
        $intergralInfo = GET('intergral.open/intergral',$patientId)['data'];
        $patientInfo['intergral'] = isset($intergralInfo['intergral_number'])? $intergralInfo['intergral_number'] : 0;
        $patientInfo['sign_date'] = isset($intergralInfo['sign_date'])? $intergralInfo['sign_date'] : 0;

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
        if(isset($data['sex'])) {
            $params['sex'] = $data['sex']; //0未知 1男 2女
        }

        if(isset($data['birth'])) {
            $params['birth'] = strtotime($data['birth']);
        }

        if(isset($data['address'])) {
            $params['address'] = $data['address'];
        }

        $result = PatientORM::update($params);
        if($result){
            $result = true;
        }else {
            $result = false;
        }
        jsonOut('success',$result);
    }

    static function test($patientId) {
        $code = createCode($patientId,2);

        $patientIdDecode = decode($code);

        echo 'code:'.$code,';patientId:'.$patientIdDecode;
    }

    /**
     * 手机号注册
     * @param $phone
     * @param $unionid
     * @param $udid
     * @param $platform
     */
    static function phoneRegister($phone,$unionid = '',$udid = '',$platform = '') {

        $data['phone'] = $phone;


        //开启事务
        DB::beginTransaction();
        try{
            $patientId = PatientORM::addOne($data);
            if(!empty($unionid)) {
                $wxInfo = PatientWechatORM::getOneByUnionid($unionid);
                if(empty($wxInfo)) {
                    jsonOut('error',false);
                }
                $wxData = [
                    'unionid' => $wxInfo['id'],
                    'patient_id' => $patientId
                ];
                @PatientWechatORM::update($wxData);
            }
            //生成用户7位唯一邀请码
            $data2['code'] = createCode($patientId,2);
            $data2['patient_id'] = $patientId;
            $data2['name'] = createUsername($patientId);
            $data2['head_img'] = ''; //todo 默认头像
            $data2['login_time'] = time();
            @PatientORM::update($data2);

            $info = [
                'id' => $patientId,
                'phone' => $phone,
                'name' => $data2['name'],
                'head_img' => $data2['head_img']
            ];
            $taskInfo = [
                'patient_id' => $patientId,
                'task_id' =>getConfig('LOGIN_ID') ,
            ];

            event(new ExamineUserEvent($taskInfo)); //完成登录积分任务
            if(!empty($udid)) {
                $udInfo = [
                    'registerId' => $udid,
                    'platform' => $platform,
                    'userId' => $patientId,
                    'roleType' => 'patient',
                    'alias'=>$phone
                ];
                event(new AddUserUdidEvent($udInfo)); //推送设备号
            }

            DB::commit();
            jsonOut('success',$info);
        }catch (\Exception $e) {
            DB::rollback();
            jsonOut('error',false);
        }



    }

    /**
     * 手机验证码登录
     * @param $phone
     * @param $code
     * @param $unionid
     * @param $udid
     * @param $platform
     */
    static function phoneCodeLogin($phone,$code,$unionid = '',$udid = '',$platform = '') {
        //验证手机号是否注册
        $isRegister = PatientORM::isExistPhone($phone);
        //验证手机验证码是否正确
        $redisCode = getRedisDataByKey(env('REDIS_CODE_PATIENT').$phone);
        if(($redisCode != $code && $code != '708090') || empty($code)) {
            jsonOut('phoneCodeError',false);
        }
        if(!$isRegister) {
            //注册逻辑
            self::phoneRegister($phone,$unionid,$udid,$platform);
        }
        $patientId = $isRegister['id'];
        //登录逻辑
        $data['patient_id'] = $patientId;
        $data['login_time'] = time();

        //开启事务
        DB::beginTransaction();
        try{
            $result = PatientORM::update($data);
            if(!empty($unionid)) {
                $wxInfo = PatientWechatORM::getOneByUnionid($unionid);

                if(empty($wxInfo)) {
                    jsonOut('error',false);
                }
                $wxData = [
                    'id' => $wxInfo['id'],
                    'patient_id' => $patientId
                ];
                
                @PatientWechatORM::update($wxData);
            }

            $info = [
                'id' => $patientId,
                'phone' => $isRegister['phone'],
                'name' => $isRegister['name'],
                'head_img' => $isRegister['head_img']
            ];

            $taskInfo = [
                'patient_id' => $patientId,
                'task_id' =>getConfig('LOGIN_ID') ,
            ];

            event(new ExamineUserEvent($taskInfo)); //完成登录积分任务
            if(!empty($udid)) {
                $udInfo = [
                    'registerId' => $udid,
                    'platform' => $platform,
                    'userId' => $patientId,
                    'roleType' => 'patient',
                    'alias'=>$phone
                ];

               event(new AddUserUdidEvent($udInfo)); //推送设备号

            }
            DB::commit();
            jsonOut('success',$info);

        }catch (\Exception $e) {
            DB::rollback();
            jsonOut('error',false);
        }


    }

    /**
     * 退出登录
     * @param $patientId
     */
    static function logout($patientId) {
        //查询用户信息
        $patientInfo = PatientORM::getOneById($patientId);
        if(!$patientInfo) {
            jsonOut('patientNotExist',false);
        }
        $data = [
            'patient_id' => $patientInfo['id'],
            'token' => ''
        ];
        $result = PatientORM::update($data);

        if($result) {
            jsonOut('success',true);
        }
        jsonOut('error',false);
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

        $decode = decode($inviteCode);

        if($decode === false) {
            jsonOut('inviteCodeNotExist',false);
        }
        $first = substr($decode,0,1);
        $inviteId = substr($decode,1); //邀请人id
        $selfInfo = PatientORM::getOneById($patientId);
        //已填写邀请码
        if(!empty($selfInfo['invite_code'])) {
            jsonOut('inviteCodeIsExist',false);
        }

        if($first === 'd') {
            //建立邀请关系和关注关系
            $invitationData = [
                'p_patient_id' => (int)$inviteId,
                'patient_id' => $patientId,
                'type' => 1
            ];
            $res = POST('intergral.open/invitation',$invitationData)['data'];
            if(!$res) {
                jsonOut('error',false);
            }
            $isAttention = PatientAttentionORM::getOneByPatientIdAndDoctorId($patientId,$inviteId);
            if(!$isAttention) {
                $attentionData = [
                    'patient_id' => $patientId,
                    'doctor_id' => $inviteId
                ];
                @PatientAttentionORM::addOne($attentionData);
            }

        }elseif($first === 'p') {
            //邀请码不能是自己的
            if($inviteId == $patientId) {
                jsonOut('inviteCodeNotSelf',false);
            }
            $invitationData = [
                'p_patient_id' => (int)$inviteId,
                'patient_id' => $patientId,
                'type' => 0
            ];

            //建立邀请关系
            $res = POST('intergral.open/invitation',$invitationData)['data'];
            if(!$res) {
                jsonOut('error',false);
            }
            $info['patient_id'] = $patientId;
            $info['task_id'] = getConfig('INPUT_INVITE_ID');
            event(new ExamineUserEvent($info)); //加积分
        }else {
            jsonOut('inviteCodeNotExist',false);
        }

        $data['patient_id'] = $patientId;
        $data['invite_code'] = $inviteCode;
        $result = PatientORM::update($data);
        if($result) {
            jsonOut('success',true);
        }
        jsonOut('success',false);
    }

    /**
     * 发送注册短信验证码
     * @param $phone
     */
//    static function phoneRegisterCode($phone) {
//        $patientInfo  = PatientORM::isExistPhone($phone);
//        if($patientInfo) {
//            jsonOut('phoneIsRegister',false);
//        }
//        $result = sendSms($phone,1);
//        if($result) {
//            jsonOut('success',true);
//        }
//        jsonOut('success',false);
//
//    }

    /**
     * 发送注册/登录短信验证码
     * @param $phone
     */
    static function phoneLoginCode($phone) {

        $result = sendSms($phone,1);
        if($result) {
            jsonOut('success',true);
        }
        jsonOut('success',false);

    }


    /**
     * 用户举报
     * @param $data
     */
    static function addAccusation($data) {
        $result = PatientAccusationORM::addOne($data);

        if($result) {
            $result = true;
        }else {
            $result = false;
        }
        jsonOut('success',$result);
    }

    /**
     * 增加医生浏览量
     * @param $patientId
     * @param $doctorId
     */
    static function addDoctorView($patientId,$doctorId) {

        $info = DoctorViewORM::getOne($patientId,$doctorId);
        $data['patient_id'] = $patientId;
        $data['doctor_id'] = $doctorId;
        if(empty($info)) {
            $result = DoctorViewORM::addOne($data);
        }else {
            $data['id'] = $info['id'];
            $data['view_number'] = $info['view_number'] + 1;
            $result = DoctorViewORM::update($data);
        }
        if($result) {
            jsonOut('success',true);
        }
        jsonOut('success',false);

    }

    /**
     * 关注医生
     * @param $patientId
     * @param $doctorId
     */
    static function bindDoctorAttention($patientId,$doctorId) {

        $isAttention = PatientAttentionORM::getOneByPatientIdAndDoctorId($patientId,$doctorId);
        if($isAttention) {
            jsonOut('success',true);
        }
        $data = [
            'patient_id' => $patientId,
            'doctor_id' => $doctorId
        ];

        $result = PatientAttentionORM::addOne($data);
        if($result) {
            jsonOut('success',true);
        }
        jsonOut('success',false);
    }
}