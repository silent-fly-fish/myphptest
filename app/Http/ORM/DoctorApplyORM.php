<?php


namespace App\Http\ORM;


use App\Http\Module\DoctorApply;

class DoctorApplyORM extends BaseORM
{
    static function addOne($data) {
        $model = new DoctorApply();
        $data = self::isIncolumns($model, $data); //过滤添加参数

        $model->fill($data);
        $model->save();

        return $model->getKey();
    }

    static function getOneById($id) {

        return DoctorApply::select(DoctorApply::$fields)->find($id);
    }


}