<?php


namespace App\Http\ORM;


use App\Http\Module\AreaProvince;

class AreaProvinceORM extends BaseORM
{
    static function getOneById($id) {

        return AreaProvince::find($id);
    }

}