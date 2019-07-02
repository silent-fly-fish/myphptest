<?php
/**
 * Created by PhpStorm.
 * User: LX
 * Date: 17/4/12
 * Time: 下午4:22
 */

use App\Common\HttpRequest;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use Illuminate\Support\Facades\Redis;


/**===============================http请求相关==========================
 */


/** 定义http协议POST请求方法
 * @param  string $url
 * @param  array|string $data
 * @param bool $isFull
 * @return  array
 */
function POST($url='',$data = [],$isFull=false){

    if ($isFull){
        $realUrl =  $url;
    }else{
        $realUrl = getApiUrl($url) ;
    }
    if (!$realUrl)
        jsonOut('InterfaceUrlError');

    return HttpRequest::post($realUrl,['data' => $data]);
}

/** 定义http协议GET请求方法
 * @param  string $url
 * @param  array|string $data
 * @param bool $isFull
 * @return  array
 */

function GET($url='',$data = [],$isFull=false){

    if ($isFull){
        return HttpRequest::get($url);
    }else{
        $realUrl= getApiUrl($url);
    }
    if (!$realUrl)
        jsonOut('InterfaceUrlError');
    // 存在查询参数进行拼接
    if (is_array($data)&&(!empty($data))) {

        $buff = "";
        foreach ($data as $k => $v)
        {
            if($v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        $realUrl.= '?'.$buff;
    }else{
        $realUrl.= '/'.$data;
    }

    return HttpRequest::get($realUrl);
}

/** 定义http协议PUT请求方法
 * @param  string $url
 * @param  array $data
 * @param bool $isFull
 * @return  array
 */
function PUT($url='',$data = [],$isFull=false){
    if ($isFull){
        $realUrl =  $url;
    }else{
        $realUrl= getApiUrl($url);
    }
    if (!$realUrl)
        jsonOut('InterfaceUrlError');


    return HttpRequest::put($realUrl,['data' => $data]);
}
/** 定义http协议批量请求方法
 * @param  array $requests
 * @return array
 */

function batchRequest($requests){

   foreach ($requests as $key=>$value){
       $insert=[];
       if($value['isFull']){
           $insert['url']=$value['url'];
       }else{
           $insert['url']= getApiUrl($value['url']);
       }

       //当传输地址为get方式
       if($value['method']=='GET'){
           if (is_array($value['options'])) {

               $buff = "";
               foreach ($value['options'] as $k => $v)
               {
                   if($v != "" && !is_array($v)){
                       $buff .= $k . "=" . $v . "&";
                   }
               }

               $buff = trim($buff, "&");
               $insert['url'].= '?'.$buff;
           }else{
               $insert['url'].= '/'.$value['options'];
           }
       }

       $insert['method']=$value['method'];
       $insert['options']=$value['options'];
       $data[$key]=$insert;

   }



    $response = HttpRequest::batchRequest($data);

    return $response;
}

/**url转换
 * @param  string $url
 * @return bool|string
 */

function getApiUrl($url){

    //1. 校验apiNmae 格式是否正确
    $apiDefine = explode('.',$url);

    //2. 验证是否定义微服务接口
    $apiList = \Illuminate\Support\Facades\Config::get('micapis');
    if (!isset($apiList[$apiDefine[0]]))
        return false;

    //3.验证服务接口是否存在
    $apis = array_keys($apiList[$apiDefine[0]]);

    if (!in_array($apiDefine[1],$apis))
        return false;


    $host = getConfig(strtoupper($apiDefine[0]).'_SYS_IP').'/'.$apiDefine[1];

    return $host;
}








/**===============================系统方法相关==========================
 */

/**
* @param string $content 日志内容
* @param string $chinnel 日志标题
*/
function logText($content,$chinnel = 'futurefertile'){
    $log = new Logger($chinnel);
    $log->pushHandler((new StreamHandler(storage_path('/logs/'.$chinnel.date('y-m-d').'.log'), Logger::INFO))->setFormatter(new LineFormatter(null, null, true, true)));


    $contenStr = '======= BEGIN ================================='.PHP_EOL;
    $contenStr .= $content.PHP_EOL;
    $contenStr .= '================================== END ======'.PHP_EOL;

    $log->addInfo($contenStr);
}



/**获取系统配置信息
* @param string $key
*@return unknown
 */
function getConfig($key){

    $configMsg = \Illuminate\Support\Facades\Config::get('beta');

    return  $configMsg[$key];
}

/**获取错误状态码的配置信息
 * @param string $key
 *@return unknown
 */
function getEnumConfig($key){

    $configMsg = \Illuminate\Support\Facades\Config::get('errMsg');

    return  $configMsg[$key];
}




/**全局输出方法
 * @param string $key
 *@return unknown
 */
function jsonOut($enum, $data = []){


    if(is_null($data)){
        $data= new stdClass();
    }

    $result = [
        'code' => getEnumConfig($enum)['code'],
        'msg' => getEnumConfig($enum)['msg'],
        'data' => $data,
    ];

    echo json_encode($result);
    exit;

}

/**
 * 生成唯一订单号
 * @return  string
 */
function getOrderSn()
{
    $time = explode ( " ", microtime () );
    $time = $time[1] . ($time[0] * 1000);
    $time = explode ( ".", $time);
    $time = isset($time[1]) ? $time[1] : 0;
    $time = date('YmdHis') + $time;

    /* 选择一个随机的方案 */
    mt_srand((double) microtime() * 1000000);
    return $time . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
}

/** ==============================数据的处理工具函数 -======================================
 *
 */


/**
 * 结果结合list 转化为 key=>array() 对象
 *
 * @param unknown $ids
 * @param unknown $list
 * @param string $compare_key
 * @return unknown
 */
function actionGetObjDataByData($ids, $list, $compare_key = 'id')
{
    $retData = new stdClass();
    // 遍历数据按格式返回
    foreach ($ids as $id) {
        $tempData = array();
        foreach ($list as $info) {
            if ($id == $info[$compare_key]) {
                $tempData = $info;
            }
        }
        $retData->$id = $tempData;
    }
    return $retData;
}

/**
 * 分页页码偏移量计算
 * @param $page 页码
 * @param $count 每页的条数
 * @return float|int
 */
function getOffsetByPage($page, $count){
    return ($page-1)*$count;
}

/**
 * 生成7位唯一邀请码
 * @param $user_id
 * @param $type 1代表医生 2代表患者
 * @return string
 */
function createCode($user_id,$type) {

    static $source_string = '6GIVR3JEN47K5HSC9WUBLPXOMQAZDYF182T';

    $num = $user_id;

    $code = '';

    while ( $num > 0) {

        $mod = $num % 35;

        $num = ($num - $mod) / 35;

        $code = $source_string[$mod].$code;

    }

    if(empty($code[5]))

        $code = str_pad($code,6,'0',STR_PAD_LEFT);

    $code = $type.$code;
    return $code;

}

/**
 * 邀请码解密
 * @param $code
 * @return float|int
 */
function decode($code) {

    if(strlen($code) != 7) {
        return false;
    }
    $type = substr($code,0,1);

    $code = substr($code,1);

    static $source_string = '6GIVR3JEN47K5HSC9WUBLPXOMQAZDYF182T';

    if (strrpos($code, '0') !== false)

        $code = substr($code, strrpos($code, '0')+1);

    $len = strlen($code);

    $code = strrev($code);

    $num = 0;

    for ($i=0; $i < $len; $i++) {

        $num += strpos($source_string, $code[$i]) * pow(35, $i);

    }

    if($type == 1) {
        $num = 'd'.$num;
    }elseif($type == 2) {
        $num = 'p'.$num;
    }else {
        return false;
    }
    return $num;

}

/**
 * 发送短信验证码
 *  @param  [type] $phone 用户手机号
 * @param  [type] $flag    用户识别1是患者 2是医生
 * @return
 */
function sendSms($phone,$flag){
    $url = 'http://api.func.futurefertile.com/sms/sendcode';
    $code = rand(100000, 999999);
    $data = [
        'phone' => $phone,
        'code' => $code
    ];

    $ret = POST($url,$data,true);
    if($ret['status'] === 0){
        if($flag == 1){
            setRedisDataWithKey(env('REDIS_CODE_PATIENT').$phone,$code,300);
        }else{
            setRedisDataWithKey(env('REDIS_CODE_DOCTOR').$phone,$code,300);
        }
        return true;
    }
    return false;
}


/**
 * 生成用户token
 * @param $patientId
 * @return string
 */
function getUserToken($patientId) {
    $time = time(); //当前时间戳
    $rand = rand(1000,9999);
    $token = md5(md5($patientId.$time).$rand);

    return $token;
}

/** =====================redis缓存相关配置===================== **/

function getRedisFix(){
    return env('SYS_CODE').'_';
}


function getRedisDataByKey($key){

    if(Redis::exists(getRedisFix().$key)){
        $ret =  json_decode(Redis::get(getRedisFix().$key),true);
        return $ret;
    }
    return 0;
}

function delRedisByKey($key){

    if(Redis::exists(getRedisFix().$key)){

        return Redis::del(getRedisFix().$key);
    }
    return 0;
}


function setRedisDataWithKey($key,$data,$time=null){
    //此处直接写入时间为60秒
    if(!$time){
        return Redis::setex(getRedisFix().$key,60,json_encode($data));
    }

    return Redis::setex(getRedisFix().$key,$time,json_encode($data));
}





