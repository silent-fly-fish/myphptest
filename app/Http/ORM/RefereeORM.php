<?php


namespace App\Http\ORM;


use App\Http\Module\Referee;

class RefereeORM extends BaseORM
{
    static function addOne($data) {
        $model = new Referee();
        $data = self::isIncolumns($model, $data); //过滤添加参数

        $model->fill($data);
        $model->save();

        return $model->getKey();
    }

    static function update($data) {
        $model = new Referee();
        $id = $data['id'];
        $data = self::isIncolumns($model, $data);

        return $model::whereRaw('id='.$id)->update($data);
    }

}