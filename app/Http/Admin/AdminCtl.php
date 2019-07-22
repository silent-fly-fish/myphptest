<?php


namespace App\Http\Admin;


use App\Http\ORM\AdminLoginLogORM;
use App\Http\ORM\AdminORM;
use Illuminate\Support\Facades\DB;

class AdminCtl
{
    /**
     * 后台用户登录
     * @param $username
     * @param $password
     * @param $ip
     */
    static function adminLogin($username,$password,$ip) {

        $userInfo = AdminORM::getOneByUsername($username);
        //账户不存在
        if(empty($userInfo)) {
            jsonOut('accountPasswordError',false);
        }
        //账号已被禁用
        if($userInfo['r_status'] == 0) {
            jsonOut('accountPasswordError',false);
        }
        $salt = $userInfo['salt'];
        $password = md5(md5($password).$salt);
        //密码错误
        if($password != $userInfo['password']) {
            jsonOut('accountPasswordError',false);
        }
        //写入登录日志
        $loginData = [
            'admin_id' => $userInfo['id'],
            'ip' => $ip
        ];
        $adminData = [
            'id' => $userInfo['id'],
            'login_time' => time()
        ];
        DB::beginTransaction();
        try{
            @AdminLoginLogORM::addOne($loginData);
            @AdminORM::update($adminData);
            DB::commit();
            $info = [
                'id' => $userInfo['id'],
                'username' => $userInfo['username'],
                'icon' => $userInfo['icon'],
                'nick_name' => $userInfo['nick_name']
            ];

            jsonOut('success',$info);

        }catch (\Exception $e) {
            DB::rollBack();
            jsonOut('error',false);
        }

    }

    /**
     * 添加后台管理人员
     * @param $data
     */
    static function addAdmin($data) {
        $password = $data['password'];
        $salt = substr(md5(time()),0,4);
        $data['password'] = md5(md5($password).$salt);
        $data['salt'] = $salt;

        $result = AdminORM::addOne($data);
        if($result) {
            jsonOut('success',true);
        }
        jsonOut('success',false);
    }

}