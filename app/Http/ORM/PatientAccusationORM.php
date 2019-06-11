<?php


namespace App\Http\ORM;

use App\Http\Module\PatientAccusation;
class PatientAccusationORM extends BaseORM
{
    static function addOne($data) {
        $model = new PatientAccusation();
        $data = self::isIncolumns($model, $data); //过滤添加参数

        $model->fill($data);
        $model->save();

        return $model->getKey();
    }

    static function getOneInfoById($id,$fields=[]){
        if(!empty($fields)){
            $ret = PatientAccusation::select($fields)->where(['id'=>$id])->whereIn('r_status',[1,2])->first();
        }else{
            $ret = PatientAccusation::select(PatientAccusation::$fields)->where(['id'=>$id])->whereIn('r_status',[1,2])->first();
        }

        $ret = $ret?$ret->toArray():[];
        return  $ret;
    }

    static function updateByID($data){
        $PatientAccusationM = PatientAccusation::where([
            'id'=>$data['id']
        ])->first();

        if ($PatientAccusationM){

            $data=self::isIncolumns($PatientAccusationM,$data);
            $PatientAccusationM->fill($data);
            $ret= $PatientAccusationM->save();
            return  $ret;

        }
        return 0;
    }

    static function getAll($postData=[]){

        $page = isset($postData['page'])?$postData['page']:1;
        $size = isset($postData['size'])?$postData['size']:10;
        $status = isset($postData['status'])?$postData['status']:0;
        $type = isset($postData['type'])?$postData['type']:0;

        $model = new PatientAccusation();

        $query = $model::query()->from('user_patient_accusation as a')->select([
            'a.id', 'a.patient_id', 'a.type', 'a.content', 'a.accusation_id', 'a.reason', 'a.r_status', 'a.created_at', 'u.name',
        ])->leftJoin('user_patient as u','a.patient_id','=','u.id');
        $queryTotal = $model::query()->from('user_patient_accusation as a')->select([
            'a.id', 'a.patient_id', 'a.type', 'a.content', 'a.accusation_id', 'a.reason', 'a.r_status', 'a.created_at', 'u.name',
        ])->leftJoin('user_patient as u','a.patient_id','=','u.id');

        if($status){
            $query->where(['a.r_status'=>$status]);
            $queryTotal->where(['a.r_status'=>$status]);
        }

        if($type){
            $query->where(['a.type'=>$type]);
            $queryTotal->where(['a.type'=>$type]);
        }

        //分页
        $offset = getOffsetByPage($page,$size);
        $query->offset($offset);
        $query->limit($size);

        //排序
        $query->orderBy('a.created_at','desc');

        $datas = $query->get();
        $datas = $datas?$datas->toArray():[];
        $total = $queryTotal->count();

        $ret['list'] = $datas;
        $ret['total'] = $total;

        return $ret;
    }


}