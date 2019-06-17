<?php


namespace App\Http\Doctor;


use App\Http\Module\PatientTags;
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
            $tagIdArr = array_filter(explode(',',$v['tag_id_str']));
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

    /**
     * 医生添加标签分组
     * @param $doctorId
     * @param $tagName
     */
    static function addTag($doctorId,$tagName) {

        $data = [
            'doctor_id' => $doctorId,
            'tag_name' => $tagName,
            'is_system' => 0 //非系统标签
        ];
        $result = DoctorTagsORM::addOne($data);

        if($result) {
            jsonOut('success',true);
        }
        jsonOut('success',false);
    }

    /**
     * 删除标签分组
     * @param $doctorId
     * @param $tagId
     */
    static function deleteTag($doctorId,$tagId) {

        $result = DoctorTagsORM::deleteByDoctorId($doctorId,$tagId);

        if($result) {
            jsonOut('success',true);
        }
        jsonOut('success',false);
    }

    /**
     * 为患者打上标签
     * @param $doctorId
     * @param $patientId
     * @param $tagIds
     */
    static function assignTag($doctorId,$patientId,$tagIds) {
        $tagIds = implode(',',$tagIds);
        if(strpos($tagIds,',') !== false){
            $tagIds = ','.$tagIds.',';
        }

        //患者是否存在标签信息
        $patientTagInfo = PatientTagsORM::getOneByDoctorIdAndPatientId($doctorId,$patientId);

        if($patientTagInfo){
            //存在标签需要更新标签信息
            $tagData = [
                'patient_id' => $patientId,
                'doctor_id' => $doctorId,
                'tag_id_str' => $tagIds
            ];
            $result = PatientTagsORM::update($tagData);
        }else{
            $tagData = [
                'doctor_id' => $doctorId,
                'patient_id' => $patientId,
                'tag_id_str' => $tagIds
            ];
            $result = PatientTagsORM::addOne($tagData);
        }

        if($result) {
            jsonOut('success',true);
        }
        jsonOut('success',false);
    }

    /**
     * 删除患者标签分组
     * @param $doctorId
     * @param $patientIds
     * @param $tagId
     */
    static function delPatientTags($doctorId,$patientIds,$tagId) {
        $tag = ','.$tagId.',';
        $patientTagList = PatientTagsORM::getAllByPatientIds($patientIds,$doctorId);

        if(empty($patientTagList)){
            jsonOut('error',false);
        }
        $updateData = [];
        foreach ($patientTagList as $k => $v){
            if($tagId == $v['tag_id_str'] || $tag == $v['tag_id_str']) {
                $tag = '';
            }else {
                $tag = str_replace($tag,',',$v['tag_id_str']);
            }
            $updateData[$k]['id'] = $v['id'];
            $updateData[$k]['tag_id_str'] = $tag;
        }

        $result = PatientTagsORM::updateBatchById($updateData);

        if($result) {
            jsonOut('success',true);
        }
        jsonOut('success',false);

    }

}