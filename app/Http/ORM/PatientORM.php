<?php


namespace App\Http\ORM;

use App\Http\Module\Patient;
class PatientORM extends BaseORM
{
    static function getOneById($patientId) {

        return Patient::find($patientId, Patient::$fields);
    }

    static function getOneByPhone($phone){
        return Patient::select('phone','password','salt')->where(['phone'=>$phone,'r_status'=>1])->first();
    }

    static function getInByIds($patientIds) {

        return Patient::select(Patient::$fields)->where(['r_status'=>1])->whereIn('id',$patientIds)->get();
    }

    static function update($data) {
        $model = new Patient();
        $patientId = $data['patient_id'];
        $data = self::isIncolumns($model, $data);

        return $model::whereRaw('id='.$patientId.' and r_status=1')->update($data);
    }

    static function isExistPhone($phone) {
        $result = Patient::where(['phone'=>$phone])->first();

        return $result;
    }

    static function addOne($data) {
        $model = new Patient();
        $data = self::isIncolumns($model, $data); //过滤添加参数

        $model->fill($data);
        $model->save();

        return $model->getKey();
    }

    static function getAllByOpen($patientIds) {
        $patientList = Patient::select(Patient::$fields)
            ->whereIn('id',$patientIds)
            ->get();

        return $patientList;
    }

    static function getOneByCode($inviteCode) {
        $info = Patient::select(Patient::$fields)
            ->where(['code'=>$inviteCode])
            ->first();

        return $info;
    }

    static function getAll($search,$startTime,$endTime,$page,$size) {
        $model = new Patient();
        $query = $model::from('user_patient as p')->select([
            'p.id',
            'p.phone',
            'p.name',
            'p.sex',
            'p.head_img',
            'p.created_at',
            'p.cash',
            'p.invite_code',
            'p.code',
            'pw.open_id',
            'pw.nickname',
            'ps.name as patient_name',
            'd.real_name'
        ])->leftJoin('user_patient_wechat as pw','p.id','=','pw.patient_id')
            ->leftJoin('user_patient as ps','p.invite_code','=','ps.code')
            ->leftJoin('user_doctor as d','d.invite_code','=','p.invite_code')
            ->where(['p.r_status'=>1]);

        $queryTotal = $model::from('user_patient as p')->select([
            'p.id',
            'p.phone',
            'p.name',
            'p.sex',
            'p.head_img',
            'p.created_at',
            'p.cash',
            'p.invite_code',
            'p.code',
            'pw.open_id',
            'pw.nickname',
            'ps.name as patient_name',
            'd.real_name'
        ])->leftJoin('user_patient_wechat as pw','p.id','=','pw.patient_id')
            ->leftJoin('user_patient as ps','p.invite_code','=','ps.code')
            ->leftJoin('user_doctor as d','d.invite_code','=','p.invite_code')
            ->where(['p.r_status'=>1]);

        if($search){
            $query->where('p.phone','like',"%$search%")->orWhere('p.name','like',"%$search%")->orWhere('pw.nickname','like',"%$search%")->orWhere('p.invite_code','like',"%$search%");
            $queryTotal->where('p.phone','like',"%$search%")->orWhere('p.name','like',"%$search%")->orWhere('pw.nickname','like',"%$search%")->orWhere('p.invite_code','like',"%$search%");
        }

        if($startTime){
            $query->where('p.created_at','>=',strtotime($startTime));
            $queryTotal->where('p.created_at','>=',strtotime($startTime));
        }

        if($endTime){
            $query->where('p.created_at','<=',strtotime($endTime));
            $queryTotal->where('p.created_at','<=',strtotime($endTime));
        }

        $offset = getOffsetByPage($page, $size);
        $query->offset($offset);
        $query->limit($size);

        $query->orderByRaw('p.created_at desc');
        $data = $query->get();

        $datas['total'] = $queryTotal->count();
        $datas['list'] = $data?$data->toArray():[];

        return $datas;
    }


    static function getBatchByIDS($ids){
        $ret =  Patient::select('id','name')->whereIn('id',$ids)->where(['r_status'=>1])->get();

        if (count($ret) > 0){
            $ret = actionGetObjDataByData($ids,$ret,'id');
        }

        return $ret;
    }

}