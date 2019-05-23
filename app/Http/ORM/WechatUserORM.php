<?php
/**
 * Created by PhpStorm.
 * User: Mloong
 * Date: 2018/10/25
 * Time: 14:06
 */
namespace App\Http\ORM;

use App\Http\Module\WechatUser;

class WechatUserORM
{

      static function getOneInfoById($id)
      {
         return  WechatUser::where('id',$id)->get();
      }




}