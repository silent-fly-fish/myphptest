<?php


namespace App\Http\Doctor;


use App\Http\Module\PatientTags;
use App\Http\ORM\DoctorTagsORM;
use App\Http\ORM\PatientAttentionORM;
use App\Http\ORM\PatientTagsORM;
use Illuminate\Support\Facades\DB;

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
                if(isset($tagArr2[$vv])) {
                    $tagArr2[$vv]++;
                }
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
        $isTagName = DoctorTagsORM::isTagName($doctorId,$tagName);
        if($isTagName) {
            jsonOut('tagNameError',false);
        }
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
     * @param $patientIds
     * @param $tagIds
     */
    static function assignTag($doctorId,$patientIds,$tagIds) {
        $tagIds = implode(',',$tagIds);
        if(strpos($tagIds,',') !== false){
            $tagIds = ','.$tagIds.',';
        }
        if(empty($patientIds) || !is_array($patientIds)) {
            jsonOut('error',false);
        }
        $patientAddIds = [];
        $patientUpdateIds = [];
        foreach ($patientIds as $k => $v) {
            //患者是否存在标签信息
            $patientTagInfo = PatientTagsORM::getOneByDoctorIdAndPatientId($doctorId,$v);
            if($patientTagInfo){
                $patientUpdateIds[] = $v;
            }else {
                $patientAddIds[] = $v;
            }
         }

        DB::beginTransaction();
        try{
            if(!empty($patientUpdateIds)) {
                //存在标签需要更新标签信息
                $tagData = [
                    'patient_ids' => $patientUpdateIds,
                    'doctor_id' => $doctorId,
                    'tag_id_str' => $tagIds
                ];
                @PatientTagsORM::update($tagData);
            }
            if(!empty($patientAddIds)) {
                $tagAddData = [];
                foreach ($patientAddIds as $k => $v) {
                    $tagAddData[] =  [
                        'doctor_id' => $doctorId,
                        'patient_id' => $v,
                        'tag_id_str' => $tagIds,
                        'created_at' => time()
                    ];
                }

                @PatientTagsORM::addAll($tagAddData);
            }
            DB::commit();
            jsonOut('success',true);
        }catch (\Exception $exception) {
            DB::rollback();
            jsonOut('success',false);
        }

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

    /**
     * 获取患者的标签列表
     * @param $doctorId
     * @param $patientId
     */
    static function getPatientTags($doctorId,$patientId) {
        //医生的标签列表
        $doctorTagList = DoctorTagsORM::getAllByDoctorId($doctorId);
        //用户在该医生被赋予的标签
        $patientInfo = PatientTagsORM::getOneByDoctorIdAndPatientId($doctorId,$patientId);
        $tagIdStr = isset($patientInfo['tag_id_str'])? $patientInfo['tag_id_str'] : '';
        if(empty($tagIdStr)) {
            foreach ($doctorTagList as $k => $v) {
                $doctorTagList[$k]['is_own'] = 0;
            }

            jsonOut('success',$doctorTagList);
        }
        $tagArr = array_filter(explode(',',$tagIdStr));

        foreach ($doctorTagList as $k => $v) {
            $temp = 0;
            foreach ($tagArr as $kk => $vv) {
                if($vv == $v['id']) {
                    $temp = 1;break;
                }
            }
            $doctorTagList[$k]['is_own'] = $temp;
        }

        jsonOut('success',$doctorTagList);
    }

}