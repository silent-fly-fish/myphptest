<?php


namespace App\Http\Admin;


use App\Http\Module\Doctor;
use App\Http\ORM\DoctorApplyORM;
use App\Http\ORM\DoctorORM;
use App\Http\ORM\SysOptionsORM;

use App\Http\ORM\HospitalORM;

class DoctorCtl
{
    /**
     * 获取医生详情
     * @param $doctorId
     */
    static function getDoctorInfo($doctorId) {
        $doctorInfo = DoctorORM::getInfoById($doctorId);
        if(!$doctorInfo) {
            jsonOut('doctorNotExist',false);
        }
        $doctorInfo = $doctorInfo?$doctorInfo->toArray():[];
        $tagArr = GET('tag.open/doctortag',$doctorId)['data'];

        $sysOptionsArr = SysOptionsORM::getAllByType($type='category');
        if($sysOptionsArr) {
            $sysOptionsArr = $sysOptionsArr->toArray();
            $ids = array_unique(array_column($sysOptionsArr, 'id'));
            $sysOptionsJson = actionGetObjDataByData($ids, $sysOptionsArr, 'id');

            $categoryInfo = [];
            foreach (explode(',',$doctorInfo['category_id_str']) as $k=>$v){
                $categoryInfo['id'] = isset($sysOptionsJson->{$v}['id'])?$sysOptionsJson->{$v}['id']:'';
                $categoryInfo['name'] = isset($sysOptionsJson->{$v}['name'])?$sysOptionsJson->{$v}['name']:'';
                $doctorInfo['categorys'][$k] = $categoryInfo;
            }
        }
        $doctorInfo['tag_ids'] = $tagArr;
        jsonOut('success', $doctorInfo);
    }

    /**
     * 医生列表
     * @param $data
     */
    static function getDoctorList($data) {
//        //地区筛选
//        $hospitalIds = [];
//        if(isset($data['province_id'])) {
//            $hospitalIds = HospitalORM::getAllByProvinceId($data['province_id']);
//        } else if(isset($data['city_id'])) {
//            $hospitalIds = HospitalORM::getAllByCityId($data['city_id']);
//        } else if(isset($data['country_id'])) {
//            $hospitalIds = HospitalORM::getAllByCountryId($data['country_id']);
//        }
//        $data['hospital_id'] = count($hospitalIds)? array_column($hospitalIds,'id') : [];

        $ret = DoctorORM::getAllList($data);
        if($ret['total'] > 0){
            $sysOptionsArr = SysOptionsORM::getAllByType($type='category');
            if($sysOptionsArr){
                $sysOptionsArr = $sysOptionsArr->toArray();
                $ids = array_unique(array_column($sysOptionsArr,'id'));
                $sysOptionsJson = actionGetObjDataByData($ids,$sysOptionsArr,'id');

                foreach ($ret['list'] as $k=>$v) {
                    $temp=[];
                    foreach (explode(',',$v['category_id_str']) as $kb=>$vb){
                        $arr=[];
                        if($vb){
                            $arr['id'] = isset($sysOptionsJson->{$vb}['id'])?$sysOptionsJson->{$vb}['id']:'';
                            $arr['name']= isset($sysOptionsJson->{$vb}['name'])?$sysOptionsJson->{$vb}['name']:'';
                        }

                        if(!empty($arr)){
                            $temp[]=$arr ;
                        }
                    }
                    unset($ret['list'][$k]['category_ids_str']);

                    $ret['list'][$k]['categorys'] =         $temp;
                }
            }
        }

        jsonOut('success', $ret);
    }

    /**
     * 添加医生
     * @param $data
     */
    static function addDoctor($data) {
        $phone = $data['name'];
        $doctorInfo = DoctorORM::getOneByPhone($phone);
        if($doctorInfo) {
            jsonOut('phoneIsRegister',false);
        }
        $salt = substr(md5(time()),0,4);

        $data['password'] = md5(md5($data['password']).$salt);
        $data['category_ids'] = empty($data['category_ids'])? '':implode(',',$data['category_ids']);
        $data['salt'] = $salt;
        $result = DoctorORM::addOne($data);
        if($result) {
            $tagData = ['doctor_id'=>$result,'tag_ids'=>$data['tag_ids']];
            POST('tag.open/doctortag',$tagData)['data'];

            $data['invite_code'] = createCode($result,1);
            $data['doctor_id'] = $result;
            @DoctorORM::update($data);
            jsonOut('success',true);
        }
        jsonOut('success',false);
    }

    /**
     * 更新医生信息
     * @param $data
     */
    static function updateDoctor($data) {
        $doctorInfo = DoctorORM::getOneById($data['doctor_id']);
        if(empty($doctorInfo)) {
            jsonOut('doctorNotExist',false);
        }
        if(isset($data['password'])) {
            $data['password'] = md5(md5($data['password']).$doctorInfo['salt']);
        }
        $result = DoctorORM::update($data);
        if($result) {
            $tagData = ['doctor_id'=>$data['doctor_id'],'tag_ids'=>$data['tag_ids']];
            PUT('tag.open/doctortag',$tagData)['data'];
            jsonOut('success',true);
        }
        jsonOut('success',false);
    }

    /**
     * 申请入驻列表
     * @param $data
     */
    static function applyDoctorList($data) {

        $list = DoctorApplyORM::getAll($data);

        jsonOut('success',$list);
    }

    /**
     * 审核申请入驻医生
     * @param $id
     * @param $applyStatus
     * @param $desc
     */
    static function checkDoctor($id,$applyStatus,$desc) {

        $data = [
            'id' => $id,
            'apply_status' => $applyStatus,
            'desc' => $desc
        ];
        $result = DoctorApplyORM::update($data);
        if($result) {
            jsonOut('success',true);
        }
        jsonOut('success',false);
    }

}