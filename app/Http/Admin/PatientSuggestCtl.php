<?php


namespace App\Http\Admin;

use App\Http\ORM\PatientSuggestORM;
use App\Http\ORM\PatientORM;

class PatientSuggestCtl
{
    /**
     * 获取列表
     * @param $data
     */
    static function getPatientSuggestList($getData) {
        $fields = ['id','patient_id','reason','img_urls','updated_at'];
        $ret = PatientSuggestORM::getAllList($getData,$fields);

        if($ret['total'] > 0){
            $patientIDS = array_unique(array_column($ret['list'],'patient_id'));
            $patientArrJson = PatientORM::getBatchByIDS($patientIDS);

            $patientInfo = [];
            foreach ($ret['list'] as $k=>$v) {
                $patientInfo = isset($patientArrJson->{$v['patient_id']})?$patientArrJson->{$v['patient_id']}:'';

                $ret['list'][$k]['img_urls'] = isset($v['img_urls'])?@explode(';',$v['img_urls'])[0]:[];
                $ret['list'][$k]['patient_name'] = isset($patientInfo['name'])?$patientInfo['name']:'';
                $ret['list'][$k]['updated_at'] = $v['updated_at']?date('Y-m-d',$v['updated_at']):'';
            }
        }
        return jsonOut('success',$ret);

    }

    static function getPatientSuggestInfo($suggest_id){
        $fields = ['id','patient_id','reason','img_urls','updated_at'];

        $postData = ['id'=>$suggest_id];
        $ret = PatientSuggestORM::getPatientSuggestInfo($postData,$fields);

        if(!$ret){
            jsonOut('NoFoundData',  []);
        }
        $ret['img_urls'] = isset($ret['img_urls'])?@explode(';',$ret['img_urls']):[];
        $ret['updated_at'] = $ret['updated_at']?date('Y-m-d',$ret['updated_at']):'';
        return jsonOut('success',$ret);
    }
}