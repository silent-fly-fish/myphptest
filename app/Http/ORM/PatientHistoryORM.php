<?php


namespace App\Http\ORM;


use App\Http\Module\PatientHistory;

class PatientHistoryORM extends BaseORM
{
    static function addOne($data) {
        $model = new PatientHistory();
        $data = self::isIncolumns($model, $data); //过滤添加参数

        $model->fill($data);
        $model->save();

        return $model->getKey();
    }

    static function getOneByPatientIdAndSearchAndType($patientId,$search,$type) {

        return PatientHistory::where(['search'=>$search,'patient_id'=>$patientId,'type'=>$type])->first();
    }

    static function update($data) {
        $model = new PatientHistory();
        $id = $data['id'];
        $data = self::isIncolumns($model, $data);

        return $model::whereRaw('id='.$id)->update($data);
    }
}