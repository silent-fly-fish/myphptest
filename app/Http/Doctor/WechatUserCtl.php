<?php
namespace App\Http\Doctor;

use App\Http\ORM\WechatUserORM;


/**
 * Created by PhpStorm.
 * User: Mloong
 * Date: 2018/10/25
 * Time: 13:53
 */
class WechatUserCtl
{
   static function getInfoById($id)

   {//将入参切分成数组，判断是否获取多条数据
       $ids= explode(",", $id);

       if(count($ids)==1){

        $ret=  WechatUserORM::getOneInfoById($ids[0]);
        jsonOut(0,'',  $ret);
       }

       $ret=  WechatUserORM::getBatchInfoByIds($ids);
       jsonOut(0,'',  $ret);
   }




   static function httpTest(){

       logText('111');



       $requests = [

      "key1" => [
               "url"=>'baseuser.patients',
               'method' => 'GET',
               "options"=>['id'=>1],
               "isFull"=>false
           ],
        "key2"=>   [
               "url"=>'base.cycle/all',
               'method' => 'POST',
               "options"=>[],
               "isFull"=>false
           ],

         ];

       $response= batchRequest($requests);
       jsonOut(0,'ok',$response);
   }

   static function   getAll($data)
   {
       $ret=  WechatUserORM::getAll($data);
       jsonOut(0,'',  $ret);
   }

   static function addOne($data)
   {
       $ret=  WechatUserORM::addOne($data);
       jsonOut(0,'',  $ret);
   }
    static function update($data)
    {
        $ret=  WechatUserORM::update($data);
        jsonOut(0,'',  $ret);
    }
}