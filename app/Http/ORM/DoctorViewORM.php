<?php


namespace App\Http\ORM;


use App\Http\Module\DoctorView;

class DoctorViewORM extends BaseORM
{
    static function addOne($data) {
        $model = new DoctorView();
        $data = self::isIncolumns($model, $data); //过滤添加参数

        $model->fill($data);
        $model->save();

        return $model->getKey();
    }

    static function getOne($patientId,$doctorId) {

        return DoctorView::query()
            ->select(DoctorView::$fields)
            ->where(['patient_id'=>$patientId,'doctor_id'=>$doctorId])
            ->first();
    }

    static function update($data) {
        $model = new DoctorView();
        $id = $data['id'];
        $data = self::isIncolumns($model, $data);

        return $model::whereRaw('id='.$id)->update($data);
    }

    static function getAllByDoctorIds($doctorIds) {
        $model = new DoctorView();
        $list = $model::query()
            ->selectRaw('sum(view_number) as view_numbers,doctor_id')
            ->whereIn('doctor_id',$doctorIds)
            ->groupBy('doctor_id')
            ->get()
            ->toArray();

        return $list;
    }

}