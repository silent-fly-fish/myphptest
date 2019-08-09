<?php


namespace App\Http\ORM;


use App\Http\Module\DoctorTags;

class DoctorTagsORM extends BaseORM
{
    static function addOne($data) {
        $model = new DoctorTags();
        $data = self::isIncolumns($model, $data); //过滤添加参数

        $model->fill($data);
        $model->save();

        return $model->getKey();
    }

    static function getAllByDoctorId($doctorId) {
        $query = DoctorTags::query();
        $list = $query
            ->select(DoctorTags::$fields)
            ->where(['doctor_id'=>$doctorId,'r_status'=> 1])
            ->get()
            ->toArray();

        return $list;
    }

    static function deleteByDoctorId($doctorId,$tagId) {

        return DoctorTags::where(['doctor_id' => $doctorId,'id'=>$tagId])->update(['r_status'=>0]);
    }

    static function isTagName($doctorId,$tagName) {
        return DoctorTags::where(['doctor_id' => $doctorId,'tag_name' => $tagName,'r_status' => 1])->count();
    }

}