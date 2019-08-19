<?php


namespace App\Http\ORM;


use App\Http\Module\PatientAttention;

class PatientAttentionORM extends BaseORM
{
    static function addOne($data) {
        $model = new PatientAttention();
        $data = self::isIncolumns($model, $data); //过滤添加参数

        $model->fill($data);
        $model->save();

        return $model->getKey();
    }

    static function getAll($doctorId,$page,$size,$tagId = 0) {
        $patientAttentionModel = new PatientAttention();
        $query = $patientAttentionModel::query();
        $queryTotal = $patientAttentionModel::query();

        $query->select([
            'p.name',
            'p.phone',
            'p.head_img',
            'user_patient_attention.patient_id'
        ])
            ->whereRaw('user_patient_attention.doctor_id='.$doctorId)
            ->leftJoin('user_patient_tags as pt', function ($join){
                $join->on('pt.patient_id','=','user_patient_attention.patient_id')
                    ->on('pt.doctor_id','=','user_patient_attention.doctor_id');
            })
            ->leftJoin('user_patient as p','p.id','=','user_patient_attention.patient_id');

        $queryTotal->select([
            'p.name',
            'p.phone',
            'p.img',
            'user_patient_attention.patient_id'
        ])
            ->whereRaw('user_patient_attention.doctor_id='.$doctorId)
            ->leftJoin('user_patient_tags as pt', function ($join){
                $join->on('pt.patient_id','=','user_patient_attention.patient_id')
                    ->on('pt.doctor_id','=','user_patient_attention.doctor_id');
            })
            ->leftJoin('user_patient as p','p.id','=','user_patient_attention.patient_id');

        if(!empty($tagId)) {
            $query->whereRaw("FIND_IN_SET($tagId,pt.tag_id_str)");
            $queryTotal->whereRaw("FIND_IN_SET($tagId,pt.tag_id_str)");
        }

        $offset = getOffsetByPage($page, $size);
        $query->offset($offset);
        $query->limit($size);
        $query->orderByRaw('user_patient_attention.id desc');
        $total = $queryTotal->count();
        $datas = $query->get();


        $ret['total'] = $total;
        $ret['list'] = $datas;

        return $ret;

    }

    static function getAllTotalByDoctorId($doctorId) {
        $model = new PatientAttention();
        $queryTotal = $model::query();
        $total = $queryTotal
            ->where(['doctor_id'=>$doctorId])
            ->count();

        return $total;
    }

    static function getOneByPatientIdAndDoctorId($patientId,$doctorId) {
        $info = PatientAttention::query()
            ->select(PatientAttention::$fields)
            ->where(['patient_id'=>$patientId,'doctor_id'=>$doctorId])
            ->first();

        return $info;
    }

}