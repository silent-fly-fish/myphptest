<?php


namespace App\Http\ORM;

use App\Http\Module\PatientSuggest;
class PatientSuggestORM extends BaseORM
{
    static function addOne($data) {
        $model = new PatientSuggest();
        $data = self::isIncolumns($model, $data); //过滤添加参数

        $model->fill($data);
        $model->save();

        return $model->getKey();
    }


    static function getAllList($postData=[],$fields=[],$orderby=['updated_at','desc']){
        $page = !empty($postData['page'])?$postData['page']:1;
        $size = !empty($postData['size'])?$postData['size']:10;
        $search = isset($postData['search'])?$postData['search']:'';


        $query = PatientSuggest::where(['r_status'=>1]);
        $queryTotal = PatientSuggest::where(['r_status'=>1]);

        if($search){
            $query->where('reason','like',"%$search%");
            $queryTotal->where('reason','like',"%$search%");
        }

        //分页
        $offset = getOffsetByPage($page,$size);
        $query->offset($offset);
        $query->limit($size);

        //排序
        $query->orderBy($orderby[0],$orderby[1]);

        //返回字段
        if(!empty($fields)){
            $query->select($fields);
        }else{
            $query->select(PatientSuggest::$fields);
        }

        $datas = $query->get();
        $datas = $datas?$datas->toArray():[];
        $total = $queryTotal->count();

        $ret['list'] = $datas;
        $ret['total'] = $total;

        return $ret;
    }


    static function getPatientSuggestInfo($id,$fields=[])
    {
        if(!empty($fields)){
            $ret = PatientSuggest::select($fields)->where(['id'=>$id,'r_status'=>1])->first();
        }else{
            $ret = PatientSuggest::select(PatientSuggest::$fields)->where(['id'=>$id,'r_status'=>1])->first();
        }

        $ret = $ret?$ret->toArray():[];
        return  $ret;
    }
}