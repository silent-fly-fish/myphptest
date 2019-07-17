<?php


namespace App\Http\Admin;


use App\Http\ORM\DoctorVisitORM;

class DoctorVisitsCtl
{
    /**
     * 获取医生出诊信息列表
     * @param $doctorId
     */
    static function getDoctorVisitList($doctorId) {

        $doctorVisitList = DoctorVisitORM::getVisitsByDoctorId($doctorId);

        foreach ($doctorVisitList as $k => $v) {
            $doctorVisitList[$k]['visit_json'] = json_decode($v['visit_json']);
        }

        jsonOut('success', $doctorVisitList);
    }

    /**
     * 更新或添加出诊信息
     * @param $data
     * @throws \Exception
     */
    static function updateOrAddByData($data)
    {
        $result = DoctorVisitORM::updateOrAddByData($data);

        jsonOut('success',$result);
    }

    /**
     * 删除出诊信息
     * @param $postData
     */

    static function delByID($postData){
        $id = isset($postData['id'])?$postData['id']:'';
        $doctorId = isset($postData['doctor_id'])?$postData['doctor_id']:'';
        $recID = DoctorVisitORM::delByID($id);

        if($recID){
            return jsonOut('success',true);
        }else{
            return jsonOut('error',false);
        }

    }

}