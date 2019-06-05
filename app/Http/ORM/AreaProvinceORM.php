<?php


namespace App\Http\ORM;


use App\Http\Module\AreaProvince;

class AreaProvinceORM extends BaseORM
{
    static function getOneById($id) {

        return AreaProvince::find($id);
    }

    static function getAll() {
        $model = new AreaProvince();
        $list = $model
            ->select(AreaProvince::$fields)
            ->orderBy('id','asc')
            ->get()
            ->toArray();

        return $list;
    }

}