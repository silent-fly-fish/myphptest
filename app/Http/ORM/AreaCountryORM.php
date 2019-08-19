<?php


namespace App\Http\ORM;


use App\Http\Module\AreaCountry;

class AreaCountryORM extends BaseORM
{
    static function getAll() {
        $model = new AreaCountry();
        $list = $model
            ->select(AreaCountry::$fields)
            ->get();

        return $list;
    }

}