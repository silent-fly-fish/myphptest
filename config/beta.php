<?php
/**
 * Created by PhpStorm.
 * User: Mloong
 * Date: 2019/5/15
 * Time: 10:44
 */
use Illuminate\Support\Facades\App;
/**
* 生产环境配置信息
*/
if(App::environment('production')){

    return [
        //域名地址
        'SERVER_HOST'=>'https://dev.portal.futurefertile.com',
        //定义测试服上线ip，地址
        'CIRCLE_SYS_IP'   =>'127.0.0.1:32090/index.php',
        'ORDER_SYS_IP'    =>'127.0.0.1:32091/index.php',
        'QUESTION_SYS_IP' =>'127.0.0.1:32092/index.php',
        'ARTICLE_SYS_IP'  =>'127.0.0.1:32093/index.php',
        'USER_SYS_IP'     =>'127.0.0.1:32094/index.php',
        'INTERGRAL_SYS_IP'=>'127.0.0.1:32095/index.php',

    ];
}
/**
 * 开发环境配置信息
 */

return [
    //域名地址
    'SERVER_HOST'=>'https://dev.portal.futurefertile.com',
    //定义测试服上线ip，地址
    'CIRCLE_SYS_IP'   =>'47.97.5.140:32090/index.php',
    'ORDER_SYS_IP'    =>'47.97.5.140:32091/index.php',
    'QUESTION_SYS_IP' =>'147.97.5.140:32092/index.php',
    'ARTICLE_SYS_IP'  =>'47.97.5.140:32093/index.php',
    'USER_SYS_IP'     =>'47.97.5.140:32094/index.php',
    'INTERGRAL_SYS_IP'=>'47.97.5.140:32095/index.php',


];