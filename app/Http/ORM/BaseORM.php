<?php


namespace App\Http\ORM;


use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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


    /**
     * 批量更新数据
     * @param string $tableName 表名
     * @param array $multipleData 要更新的数组（）
     * @return bool
     */
    static function updateBatch($tableName = "", $multipleData = array()){

        if( $tableName && !empty($multipleData) ) {

            // column or fields to update
            $updateColumn = array_keys($multipleData[0]);
            $referenceColumn = $updateColumn[0]; //e.g id
            unset($updateColumn[0]);
            $whereIn = "";

            $q = "UPDATE ".$tableName." SET ";
            foreach ( $updateColumn as $uColumn ) {
                $q .=  $uColumn." = CASE ";

                foreach( $multipleData as $data ) {
                    $q .= "WHEN ".$referenceColumn." = ".$data[$referenceColumn]." THEN '".$data[$uColumn]."' ";
                }
                $q .= "ELSE ".$uColumn." END, ";
            }
            foreach( $multipleData as $data ) {
                $whereIn .= "'".$data[$referenceColumn]."', ";
            }
            $q = rtrim($q, ", ")." WHERE ".$referenceColumn." IN (".  rtrim($whereIn, ', ').")";

            // Update
            return DB::update(DB::raw($q));

        } else {
            return false;
        }
    }
}