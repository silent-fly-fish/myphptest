<?php


namespace App\Http\ORM;


use App\Http\Module\AreaCity;

class AreaCityORM extends BaseORM
{
    static function getAll() {
        $model = new AreaCity();
        $list = $model
            ->select(AreaCity::$fields)
            ->get();

        return $list;
    }

}