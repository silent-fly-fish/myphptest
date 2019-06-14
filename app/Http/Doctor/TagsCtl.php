<?php


namespace App\Http\Doctor;


use App\Http\ORM\DoctorTagsORM;
use App\Http\ORM\PatientAttentionORM;
use App\Http\ORM\PatientTagsORM;

class TagsCtl
{
    /**
     * 获取医生标签列表
     * @param $doctorId
     */
    static function getTagsList($doctorId) {
        $doctorTags = DoctorTagsORM::getAllByDoctorId($doctorId);

        if(empty($doctorTags)) {
            jsonOut('success',[]);
        }
        $patientTags = PatientTagsORM::getAllByDoctorId($doctorId);
        if(empty($patientTags)) {
            foreach($doctorTags as $k => $v) {
                $doctorTags[$k]['total'] = 0;
            }
            jsonOut('success',$doctorTags);
        }
        $tagArr = array_column($doctorTags,'id');
        $tagArr2 = [];
        foreach ($tagArr as $k => $v) {
            $tagArr2[$v] = 0;
        }
        foreach($patientTags as $k => $v) {
            $tagIdArr = explode(',',$v['tag_id_str']);
            foreach($tagIdArr as $kk=>$vv) {
                $tagArr2[$vv]++;
            }
        }

        foreach($doctorTags as $k => $v) {
            $doctorTags[$k]['total'] = $tagArr2[$v['id']];
        }

        jsonOut('success',$doctorTags);
    }

    /**
     * 获取关注该医生总用户数量
     * @param $doctorId
     */
    static function getAllTotal($doctorId) {
        $total = PatientAttentionORM::getAllTotalByDoctorId($doctorId);

        jsonOut('success',$total);
    }

}