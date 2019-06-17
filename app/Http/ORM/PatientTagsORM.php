<?php


namespace App\Http\ORM;


use App\Http\Module\PatientTags;

class PatientTagsORM extends BaseORM
{
    static function addOne($data) {
        $model = new PatientTags();
        $data = self::isIncolumns($model, $data); //过滤添加参数

        $model->fill($data);
        $model->save();

        return $model->getKey();
    }

    static function getAllByDoctorId($doctorId) {
        $list = PatientTags::query()
            ->select(PatientTags::$fields)
            ->where(['doctor_id'=>$doctorId])
            ->get();

        return $list;
    }

    static function getCountByTagId($doctorId,$tagId) {
        $count = PatientTags::query()
            ->select(PatientTags::$fields)
            ->whereRaw("doctor_id = $doctorId and find_in_set($tagId,tag_id_str)")
            ->count();

        return $count;
    }

    static function getOneByDoctorIdAndPatientId($doctorId,$patientId) {
        $info = PatientTags::query()
            ->select(PatientTags::$fields)
            ->where(['doctor_id'=>$doctorId,'patient_id'=>$patientId])
            ->first();

        return $info;
    }

    static function update($data) {
        $model = new PatientTags();
        $doctorId = $data['doctor_id'];
        $patientId = $data['patient_id'];
        $data = self::isIncolumns($model, $data);

        return $model::where(['doctor_id'=>$doctorId,'patient_id'=>$patientId])->update($data);
    }

    static function updateBatchById($data) {

        return self::updateBatch('user_patient_tags',$data);
    }

    static function getAllByPatientIds($patientIds,$doctorId) {
        $list = PatientTags::query()
            ->select(PatientTags::$fields)
            ->where(['doctor_id'=>$doctorId])
            ->whereIn('patient_id',$patientIds)
            ->get();

        return $list;
    }


}