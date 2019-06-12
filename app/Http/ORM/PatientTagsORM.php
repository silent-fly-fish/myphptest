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

    static function getAllTotal() {
        
    }

}