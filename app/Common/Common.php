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
        jsonOut('0','接口尚未定义');

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
        jsonOut('0','接口尚未定义');
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
        $url.= '?'.$buff;
    }else{
        $url.= '/'.$data;
    }

    return HttpRequest::get($url);
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
        jsonOut('0','接口尚未定义');


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


    $host = getConfig('SERVER_HOST').'/'.$apiDefine[0].'/'.$apiDefine[1];

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




/**全局输出方法
 * @param string $key
 *@return unknown
 */
function jsonOut($code = 0, $msg = "", $data = []){


    if(is_null($data)){
        $data= new stdClass();
    }

    $result = [
        'code' => $code,
        'msg' => $msg,
        'data' => $data,
    ];

    echo json_encode($result);
    exit;

}



/** ==============================数据的处理工具函数 -======================================
 *
 */









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





