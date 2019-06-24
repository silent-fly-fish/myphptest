<?php


namespace App\Http\ORM;

use App\Http\Module\SysOptions;
class SysOptionsORM extends BaseORM
{
    static function getAllByType($type) {

        return SysOptions::select(SysOptions::$fields)
            ->where(['type'=>$type,'r_status'=>1])
            ->orderByRaw('sort desc')
            ->get();
    }

    static function addOne($data) {
        $model = new SysOptions();
        $data = self::isIncolumns($model, $data); //过滤添加参数

        $model->fill($data);
        $model->save();

        return $model->getKey();
    }

    static function delete($data) {
        $model = new SysOptions();
        $id = $data['id'];
        $data = self::isIncolumns($model, $data);

        return $model::whereRaw('id='.$id)->update(['r_status'=>0]);
    }

    static function getMaxSortByType($type) {
        $type = SysOptions::query()
            ->where(['type'=>$type])
            ->max('sort');

        return $type;
    }

    static function update($data) {
        $model = new SysOptions();
        $id = $data['id'];
        $type = $data['type'];
        unset($data['type']);
        $data = self::isIncolumns($model, $data);

        return $model::where(['id'=>$id,'type'=>$type])->update($data);
    }

}