<?php


namespace App\Http\Patient;
use App\Events\ExamineUserEvent;
use App\Http\ORM\PatientORM;
use App\Http\ORM\PatientWechatORM;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Overtrue\LaravelWeChat\Facade as EasyWechat;

class WechatCtl
{

    /**
     * 获取accessToken
     * @param $product
     * @param $code
     * @return array
     */
    static function getTokenByCode($product,$code) {
        $app = EasyWechat::officialAccount($product);

        //获取accessToken
        $token = $app->oauth->getAccessToken($code);


        $accessToken = $token['token'];
        $expiresIn =$token['expires_in'];
        $unionid = $token['unionid'];

        $user = $app->oauth->user($accessToken);

        $userInfo = $user->getOriginal();

        $isWechatRegister = PatientWechatORM::getOneByUnionid($unionid);

        //是否授权当前系统
        if(!$isWechatRegister) {
            $data = [
                'open_id' => $user->getId(),
                'nickname' => $user->getNickname(),
                'head_img' => $user->getAvatar(),
                'unionid' => $unionid
            ];
            $result = PatientWechatORM::addOne($data);

            if($result) {
                jsonOut('success',$unionid);
            }
            jsonOut('error',false);
        }
        //是否绑定系统用户
        if(!empty($isWechatRegister['patient_id'])) {
            $patientId = $isWechatRegister['patient_id'];
            $info['patient_id'] = $info['id'] = $patientId;
            $info ['token'] = getUserToken($patientId);

            $result = PatientORM::update($info);
            if($result) {
                jsonOut('success',$info);
            }

            jsonOut('error',false);
        }

        jsonOut('success',$unionid);

    }


    /**
     * 绑定微信账号
     * @param $phone
     * @param $code
     * @param $unionid
     */
    static function bindPhone($phone,$code,$unionid) {


        //验证手机验证码是否正确
        $redisCode = getRedisDataByKey(env('REDIS_CODE_PATIENT').$phone);
        if(($redisCode != $code && $code != '708090') || empty($code)) {
            jsonOut('phoneCodeError',false);
        }
        //验证手机号是否注册
        $isRegister = PatientORM::isExistPhone($phone);

        $data['login_time'] = time();
        $data['token'] = getUserToken($isRegister['id']);
        $wechatData = [
            'unionid' => $unionid,
            'patient_id' => $isRegister['id']
        ];
        if(!$isRegister) {
            $data['phone'] = $phone;
            DB::beginTransaction();
            try {

                PatientWechatORM::update($wechatData);
                PatientORM::addOne($data);
                $info = [
                    'id' => $isRegister['id'],
                    'token' => $data['token']
                ];
                DB::commit();
                jsonOut('success',$info);
            } catch (\Exception $e){
                DB::rollback();
                jsonOut('error',false);
            }
        }else {
            $data['patient_id'] = $isRegister['id'];
            DB::beginTransaction();
            try {
                PatientWechatORM::update($wechatData);
                PatientORM::update($data);
                $info = [
                    'id' => $isRegister['id'],
                    'token' => $data['token']
                ];
                $taskInfo = [
                    'patient_id' => $isRegister['id'],
                    'task_id' =>getConfig('LOGIN_ID') ,
                ];
                event(new ExamineUserEvent($taskInfo));
                DB::commit();
                jsonOut('success',$info);
            } catch (\Exception $e){
                DB::rollback();
                jsonOut('error',false);
            }


        }

    }

    /**
     * 发送绑定账号验证码
     * @param $phone
     */
    static function sendBindCode($phone) {
        $result = sendSms($phone,1);
        if($result) {
            jsonOut('success',true);
        }
        jsonOut('success',false);
    }


}