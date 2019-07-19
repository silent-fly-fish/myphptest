<?php


namespace App\Http\Admin;


use App\Http\ORM\AccountORM;

class AccountCtl
{
    /**
     * 添加账号
     * @param $data
     */
    static function addOne($data) {
        $name = isset($data['name'])? $data['name'] : '';
        $password= isset($data['password'])? $data['password'] : '';
        $isExist = AccountORM::isExistByAccountName($name);
        if($isExist) {
            jsonOut('accountIsExist',false);
        }
        $data['salt'] = substr(md5(time()),0,4);
        $data['password'] = md5(md5($password).$data['salt']);
//        print_r($isExist);exit;
        $result = AccountORM::addOne($data);
        if($result) {
            jsonOut('success',true);
        }else{
            jsonOut('error',false);
        }

    }

    /**
     * 修改账号
     * @param $data
     */
    static function updateOne($data){
        $id = isset($data['id'])? $data['id'] : '';
        $name = isset($data['name'])? $data['name'] : '';
        $password = isset($data['password'])? $data['password'] : '';
        $isExist = AccountORM::isExistByUpdateAccountName($id,$name);
        if($isExist) {
            jsonOut('accountIsExist',false);
        }
        $data['salt'] = substr(md5(time()),0,4);
        $data['password'] = md5(md5($password).$data['salt']);

        $result = AccountORM::updateOne($data);
        if($result) {
            jsonOut('success',true);
        }else{
            jsonOut('error',false);
        }
    }

    /**
     * 登录接口
     * @param $data
     */
    static function login($data){
        $name = isset($data['name'])? $data['name'] : '';
        $password = isset($data['password'])? $data['password'] : '';

        $info = AccountORM::getInfoByName($name);
        if(!$info) {
            jsonOut('NoFoundData',false);
        }

        if($info['password'] != md5(md5($password).$info['salt'])){
            jsonOut('passwordError',false);
        }
//        print_r($info);exit;
        $result = AccountORM::updateOne(['id'=>$info['id'],'updated_at'=>time()]);
        if($result) {
            jsonOut('success',$info);
        }else{
            jsonOut('error',false);
        }
    }
}