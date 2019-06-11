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

}