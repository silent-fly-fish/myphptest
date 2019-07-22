<?php


namespace App\Http\ORM;


use App\Http\Module\AdminLoginLog;

class AdminLoginLogORM extends BaseORM
{
    static function addOne($data) {
        $model = new AdminLoginLog();
        $data = self::isIncolumns($model, $data); //过滤添加参数

        $model->fill($data);
        $model->save();

        return $model->getKey();
    }

}