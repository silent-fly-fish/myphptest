<?php


namespace App\Http\ORM;

use App\Http\Module\Patient;
class PatientORM extends BaseORM
{
    static function getOneById($patientId) {

        return Patient::find($patientId, Patient::$fields);
    }

    static function update($data) {
        $model = new Patient();
        $patientId = $data['patient_id'];
        $data = self::isIncolumns($model, $data);

        return $model::whereRaw('id='.$patientId.' and r_status=1')->update($data);
    }

    static function isExistPhone($phone) {
        $result = Patient::where(['phone'=>$phone])->find();
        if($result){
            return true;
        }
        return false;
    }

    static function addOne($data) {
        $model = new Patient();
        $data = self::isIncolumns($model, $data); //过滤添加参数

        $model->fill($data);
        $model->save();

        return $model->getKey();
    }

    static function getAllByOpen($patientIds) {
        $patientList = Patient::select(Patient::$fields)
            ->whereIn('id',$patientIds)
            ->get();

        return $patientList;
    }


}