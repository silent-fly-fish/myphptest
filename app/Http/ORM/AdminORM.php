<?php


namespace App\Http\ORM;


use App\Http\Module\Admin;

class AdminORM extends BaseORM
{
    static function addOne($data) {
        $model = new Admin();
        $data = self::isIncolumns($model, $data); //过滤添加参数

        $model->fill($data);
        $model->save();

        return $model->getKey();
    }

    static function getOneById($id) {

        return Admin::query()->find($id);
    }

    static function getOneByUsername($username) {

        return Admin::query()
            ->where(['username' => $username])
            ->select(Admin::$fields)
            ->first();
    }

    static function update($data) {
        $model = new Admin();
        $id = $data['id'];
        $data = self::isIncolumns($model, $data);

        return $model::whereRaw('id='.$id)->update($data);
    }

}