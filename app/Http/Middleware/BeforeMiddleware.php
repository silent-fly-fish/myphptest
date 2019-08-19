<?php
/**
 * Created by PhpStorm.
 * User: Amesante_lx
 * Date: 2017/9/14
 * Time: 11:28
 */

namespace App\Http\Middleware;

use Closure;


class BeforeMiddleware
{
    public $data = '';

    public $path = '';

    public function handle($request, Closure $next){
        // 之前的操作 验证入参是否合法
        $user_define = include __DIR__.'/user_define.php';

        $user_define = $user_define['USER_DEFINE'];

        $this->method = $request->method(); //请求方式
        if ($this->method == 'OPTIONS') die('');


        $json =  $request->all();

        $this->data = &$json;

        $user_define_key = strtolower($request->path());

        $this->path = $user_define_key;

        if (!isset($user_define[$user_define_key][$this->method])) {
            return $next($request);
        }

        if (count($this->data) == 0 && $this->method != 'GET') {
            $this->jsonOut(1);
        }

        $params = $user_define[$user_define_key][$this->method]['params'];
        $err_code = $user_define[$user_define_key][$this->method]['err_code'];

        foreach ($params as $vail_key => $vail_value) {
            $keys = array_keys($vail_value[0]);
            if($this->method == 'GET') {
                if (in_array("default", $keys)) {
                    if (!isset($json[$vail_key])) {
                        $json[$vail_key] = $vail_value[0]['default'];
                        $defaultObj[$vail_key]  = $vail_value[0]['default'];
                        $request->merge($defaultObj);
                    }
                }
                foreach ($vail_value[0] as $value_key => $value_value) {
                    switch ($value_key) {
                        case 'required':
                            if (!in_array('default', $keys) && (!isset($json[$vail_key]) || strlen($json[$vail_key]) ==0)) {
                                $this->jsonOut($vail_value[1], $err_code[$vail_value[1]]);
                            }
                            break;
                        case "integer":
                            if(isset($json[$vail_key])) {
                                if (!is_int($json[$vail_key])) {
                                    $this->jsonOut($vail_value[1], $err_code[$vail_value[1]]);
                                }
                            }
                            break;
                        case "default":
                            break;
                        case "min":
                            if(isset($json[$vail_key])) {
                                if ($value_value >= $json[$vail_key]) {
                                    $this->jsonOut($vail_value[1], $err_code[$vail_value[1]]);
                                }
                            }
                            break;
                        case "max":
                            if(isset($json[$vail_key])) {
                                if ($value_value <= $json[$vail_key]) {
                                    $this->jsonOut($vail_value[1], $err_code[$vail_value[1]]);
                                }
                            }
                            break;
                        case "numeric":
                            if(isset($json[$vail_key])) {
                                if (!is_numeric($json[$vail_key])) {
                                    $this->jsonOut($vail_value[1], $err_code[$vail_value[1]]);
                                }
                            }
                            break;
                        case "object":
                            if(isset($json[$vail_key])) {
                                if (!is_array($json[$vail_key])) {
                                    $this->jsonOut($vail_value[1], $err_code[$vail_value[1]]);
                                }
                            }
                            break;
                        case "Regx":
                            if(isset($json[$vail_key])) {
                                if (!preg_match($value_value, $json[$vail_key])) {
                                    $this->jsonOut($vail_value[1], $err_code[$vail_value[1]]);
                                }
                            }
                            break;
                        case "in":
                            if(isset($json[$vail_key])) {
                                if (!in_array($json[$vail_key], $value_value)) {
                                    $this->jsonOut($vail_value[1], $err_code[$vail_value[1]]);
                                }
                            }
                            break;

                        case "maxLength":
                            if(isset($json[$vail_key])) {
                                if ($value_value < mb_strlen($json[$vail_key])) {
                                    $this->jsonOut($vail_value[1], $err_code[$vail_value[1]]);
                                }
                            }
                            break;
                        case "minLength":
                            if(isset($json[$vail_key])) {
                                if ($value_value > mb_strlen($json[$vail_key])) {
                                    $this->jsonOut($vail_value[1], $err_code[$vail_value[1]]);
                                }
                            }
                            break;


                    }
                }
            } else{

                if (in_array("default", $keys)) {
                    if (!isset($json['data'][$vail_key])) {
                        $json['data'][$vail_key] = $vail_value[0]['default'];
                        $defaultObj['data'][$vail_key]  = $vail_value[0]['default'];
                        $request->merge($defaultObj);
                    }
                }
                foreach ($vail_value[0] as $value_key => $value_value) {
                    switch ($value_key) {
                        case 'required':
                            if (!in_array('default', $keys) && (!isset($json['data'][$vail_key]) || strlen($json['data'][$vail_key]) ==0)) {
                                $this->jsonOut($vail_value[1], $err_code[$vail_value[1]]);
                            }
                            break;
                        case "integer":
                            if(isset($json['data'][$vail_key])) {
                                if (!is_int($json['data'][$vail_key])) {
                                    $this->jsonOut($vail_value[1], $err_code[$vail_value[1]]);
                                }
                            }
                            break;
                        case "default":
                            break;
                        case "min":
                            if(isset($json['data'][$vail_key])) {
                                if ($value_value >= $json['data'][$vail_key]) {
                                    $this->jsonOut($vail_value[1], $err_code[$vail_value[1]]);
                                }
                            }
                            break;
                        case "max":
                            if(isset($json['data'][$vail_key])) {
                                if ($value_value <= $json['data'][$vail_key]) {
                                    $this->jsonOut($vail_value[1], $err_code[$vail_value[1]]);
                                }
                            }
                            break;
                        case "numeric":
                            if(isset($json['data'][$vail_key])) {
                                if (!is_numeric($json['data'][$vail_key])) {
                                    $this->jsonOut($vail_value[1], $err_code[$vail_value[1]]);
                                }
                            }
                            break;
                        case "object":
                            if(isset($json['data'][$vail_key])) {
                                if (!is_array($json['data'][$vail_key])) {
                                    $this->jsonOut($vail_value[1], $err_code[$vail_value[1]]);
                                }
                            }
                            break;
                        case "Regx":
                            if(isset($json['data'][$vail_key])) {
                                if (!preg_match($value_value, $json['data'][$vail_key])) {
                                    $this->jsonOut($vail_value[1], $err_code[$vail_value[1]]);
                                }
                            }
                            break;
                        case "in":
                            if(isset($json['data'][$vail_key])) {
                                if (!in_array($json['data'][$vail_key], $value_value)) {
                                    $this->jsonOut($vail_value[1], $err_code[$vail_value[1]]);
                                }
                            }
                            break;
                        case "maxLength":
                            if(isset($json['data'][$vail_key])) {
                                if ($value_value < mb_strlen($json['data'][$vail_key])) {
                                    $this->jsonOut($vail_value[1], $err_code[$vail_value[1]]);
                                }
                            }

                            break;
                        case "minLength":
                            if(isset($json['data'][$vail_key])) {
                                if ($value_value > mb_strlen($json['data'][$vail_key])) {
                                    $this->jsonOut($vail_value[1], $err_code[$vail_value[1]]);
                                }
                            }
                            break;
                    }
                }
            }
        }

        // post 对象中 包含 default 对象
        $request->replace($json);

        return $next($request);
    }

    public function jsonOut($status = 0, $msg = "", $data = [])
    {
        if ($status >= 1 && $status <= 100) {
            $msg = '入参不合法'."$status";
        } else if ($status > 100 || $status !== 0) {
            $user_define = include __DIR__.'/user_define.php';
            $user_define = $user_define['USER_DEFINE'];
            $user_define_key = $this->path;
            $msg = $user_define[$user_define_key][$this->method]['err_code'][$status];

        }

//        $result = [
//            'code' => 101,
//            'msg' => $msg,
//            'data' => $data
//        ];
//        die(json_encode($result));

        jsonOut( 'validationError',$msg);
    }
}