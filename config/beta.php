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

    ];
}
/**
 * 开发环境配置信息
 */

return [
    //域名地址
    'SERVER_HOST'=>'https://dev.portal.futurefertile.com',


];