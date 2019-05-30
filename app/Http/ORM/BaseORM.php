<?php


namespace App\Http\ORM;


use Illuminate\Support\Facades\Schema;

class BaseORM
{
    static function isIncolumns($model,$arr)
    {

        $columns = Schema::getColumnListing( $model->getTable());

        foreach ($arr as $k=>$v){

            if (!in_array($k,$columns)){

                unset($arr[$k]);
            }

        }

        return $arr;
    }
}