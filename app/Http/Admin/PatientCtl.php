<?php


namespace App\Http\Admin;


use App\Http\ORM\PatientORM;
use App\Http\ORM\DoctorORM;

class PatientCtl
{
    /**
     * 获取患者详情
     * @param $patientId
     */
    static function getPatientInfo($patientId) {
        $patientInfo = PatientORM::getOneById($patientId);
        if(!$patientInfo) {
            jsonOut('patientNotExist',false);
        }
        $patientInfo = $patientInfo->toArray();

        $tagArr = GET('tag.open/patienttag',$patientId)['data'];
        $patientInfo['tag_ids'] = $tagArr;
//        print_r($patientInfo);exit;

        jsonOut('success',$patientInfo);
    }

    /**
     * 患者列表
     * @param $getData
     */
    static function getPatientList($getData) {
        $search = isset($getData['search'])?$getData['search']:'';
        $startTime = isset($getData['start_time'])?$getData['start_time']:0;
        $endTime = isset($getData['end_time'])?$getData['end_time']:0;
        $page = empty($getData['page'])?$getData['page']:1;
        $size = empty($getData['size'])?$getData['size']:10;

        $ret = PatientORM::getAll($search,$startTime,$endTime,$page,$size);

        if($ret['total'] > 0){
           foreach ($ret['list'] as $k=>$v){
               $ret['list'][$k]['created_at'] = $v['created_at']?date('Y-m-d',($v['created_at'])):'';
           }
        }
        jsonOut('success',$ret);
    }
    /**
     * 患者打标签
     * @param $data
     */
    static function addPatientTag($data) {
        $patientInfo = PatientORM::getOneById($data['patient_id']);

        if(!$patientInfo) {
            jsonOut('patientNotExist',false);
        }

        $tagData = ['patient_id'=>$data['patient_id'],'tag_ids'=>$data['tag_ids']];
        $ret = POST('tag.open/patienttag',$tagData)['data'];

        if($ret){
            jsonOut('success',true);
        }else{
            jsonOut('error',false);
        }

    }


    /**
     * 患者修改标签
     * @param $getData
     */
    static function updatePatientTag($data) {
        $patientInfo = PatientORM::getOneById($data['patient_id']);

        if(!$patientInfo) {
            jsonOut('patientNotExist',false);
        }

        $tagData = ['patient_id'=>$data['patient_id'],'tag_ids'=>$data['tag_ids']];
        $ret = PUT('tag.open/patienttag',$tagData)['data'];

        if($ret){
            jsonOut('success',true);
        }else{
            jsonOut('error',false);
        }

    }
}