<?php
/**
 * Created by PhpStorm.
 * User: Mloong
 * Date: 2019/7/16
 * Time: 14:11
 */
namespace App\Http\ORM;
use Illuminate\Support\Facades\Schema;
use App\Http\Module\Record;

class RecordORM
{

    static function getModel()
    {
        return new Record();
    }


    static function getOneInfoById($id)
    {
        $model = self::getModel();

        return $model::find($id);
    }


    static function addOne($data)
    {


        $model = self::getModel();

        $columns = Schema::getColumnListing($model->getTable());

        $arr = array_keys($data);

        $res = array_diff($arr, $columns);

        if (!empty($res)) {

            jsonOut('validationErrorMore');
        }


        $model->fill($data);

        $model->save();

        return $model->getKey();

    }

    static function updateById($id, $updateData)
    {
        $model = self::getModel();

        return $model::where('id', $id)->update($updateData);
    }

    static function getList($conditions, $pageSize, $fields = [], $orderby = ['id', 'desc'])
    {


        $model = self::getModel();

        $query = $model::query();
        $queryTotal = $model::query();
        //查询条件
        if (!empty($conditions)) {
            $query->where($conditions);
            $queryTotal->where($conditions);
        }

        //分页
        $offset = getOffsetByPage($pageSize['page'], $pageSize['size']);
        $query->offset($offset);
        $query->limit($pageSize['size']);
        //排序
        $query->orderBy($orderby[0], $orderby[1]);
        $query->orderBy('id', 'desc');
        //返回字段
        if (!empty($fields)) {
            $query->select($fields);
        }
        $ret['list'] = $query->get()->toArray();
        $ret['total'] = $queryTotal->count();
        return $ret;

    }

    static function getDataByPidAndCode( $patientId, $categoryCode){

        $model = self::getModel();

        $conditions=[
            ['patient_id','=',$patientId] ,
            ['category_code','=',$categoryCode],
            ['r_status','=',1]
        ];

        return $model::where($conditions)->get();

    }

    static function getCycleByPId( $patientId, $categoryCode){

        $model = self::getModel();

        $conditions=[
            ['patient_id','=',$patientId] ,
            ['category_code','=',$categoryCode],
            ['r_status','=',1]
        ];

        return $model::where($conditions)->groupby('cycle_id')->get();


    }


}