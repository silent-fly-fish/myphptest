<?php


namespace App\Http\ORM;

use App\Http\Module\Doctor;

class DoctorORM extends BaseORM
{
    static function getOneById($id) {

        return Doctor::select(Doctor::$fields)->find($id);
    }

    static function getInfoById($id) {
        $model = new Doctor();
        $doctorInfo = $model
            ->select([
            'user_doctor.id',
            'user_doctor.real_name',
            'user_doctor.hospital_id',
            'user_doctor.branch_id',
            'user_doctor.position_id',
            'h.name as hospital_name',
            'b.name as branch_name',
            'p.name as position_name',
            'h.level',
            'h.logo',
            'user_doctor.img',
            'user_doctor.good_at',
            'user_doctor.description',
            'user_doctor.one_price',
            'user_doctor.more_price',
            'user_doctor.phone_price'
            ])
            ->leftJoin('user_hospital as h','h.id','=','user_doctor.hospital_id')
            ->leftJoin('user_sys_options as b','b.id','=','user_doctor.branch_id')
            ->leftJoin('user_sys_options as p','p.id','=','user_doctor.position_id')
            ->where(['user_doctor.id'=>$id,'user_doctor.r_status'=>1])
            ->first();

        return $doctorInfo;
    }

    static function getBatchInfoByIds($ids) {

        return Doctor::whereIn('id', $ids)->get();
    }

    static function getAll($data) {

        $model = new Doctor();
        $page = $data['page'];
        $size = $data['size'];

        $query =$model::query();
        $queryTotal = $model::query();
        $query->select([
            'user_doctor.id',
            'user_doctor.real_name',
            'user_doctor.hospital_id',
            'user_doctor.branch_id',
            'user_doctor.position_id',
            'h.name as hospital_name',
            'b.name as branch_name',
            'p.name as position_name',
            'h.level',
            'h.logo',
            'user_doctor.img',
            'user_doctor.good_at',
            'user_doctor.description',
            'user_doctor.one_price',
            'user_doctor.more_price',
            'user_doctor.phone_price'
        ])
            ->leftJoin('user_hospital as h','h.id','=','user_doctor.hospital_id')
            ->leftJoin('user_sys_options as b','b.id','=','user_doctor.branch_id')
            ->leftJoin('user_sys_options as p','p.id','=','user_doctor.position_id');
        $queryTotal->select([
            'user_doctor.id',
            'user_doctor.real_name',
            'user_doctor.hospital_id',
            'user_doctor.branch_id',
            'user_doctor.position_id',
            'h.name as hospital_name',
            'b.name as branch_name',
            'p.name as position_name',
            'h.level',
            'h.logo',
            'user_doctor.img',
            'user_doctor.good_at',
            'user_doctor.description',
            'user_doctor.one_price',
            'user_doctor.more_price',
            'user_doctor.phone_price'
        ])
            ->leftJoin('user_hospital as h','h.id','=','user_doctor.hospital_id')
            ->leftJoin('user_sys_options as b','b.id','=','user_doctor.branch_id')
            ->leftJoin('user_sys_options as p','p.id','=','user_doctor.position_id');
        if(!empty($data['hospital_id'])) {
            $query->whereIn('user_doctor.hospital_id', $data['hospital_id']);
            $queryTotal->whereIn('user_doctor.hospital_id', $data['hospital_id']);
        }

        if(isset($data['category_id'])) {
            $query->whereRaw(FIND_IN_SET($data['category_id'], 'user_doctor.category_id_str'));
            $queryTotal->whereRaw(FIND_IN_SET($data['category_id'], 'user_doctor.category_id_str'));
        }
        if(isset($data['search'])) {
            $query->where('user_doctor.real_name','like','%'.$data['search'].'%');
            $queryTotal->where('user_doctor.real_name','like','%'.$data['search'].'%');
        }




        $offset = getOffsetByPage($page, $size);
        $query->offset($offset);
        $query->limit($size);
        //todo 暂定为逆序 可能是按热度查询
        $query->orderByRaw('user_doctor.sort desc,user_doctor.id desc');
        $total = $queryTotal->count();
        $datas = $query->get();


        $ret['total'] = $total;
        $ret['list'] = $datas;

        return $ret;
    }

    static function addOne($data) {
        $model = new Doctor();
        $data = self::isIncolumns($model, $data); //过滤添加参数

        $model->fill($data);
        $model->save();

        return $model->getKey();
    }

    static function update($data) {
        $model = new Doctor();
        $doctorId = $data['doctor_id'];
        $data = self::isIncolumns($model, $data);

        return $model::whereRaw('id='.$doctorId.' and r_status=1')->update($data);
    }

    static function getAllByOpen($doctorIds) {
        $model = new Doctor();
        $query = $model::query();
        $query->select([
            'user_doctor.id',
            'user_doctor.real_name',
            'user_doctor.hospital_id',
            'user_doctor.branch_id',
            'user_doctor.position_id',
            'h.name as hospital_name',
            'b.name as branch_name',
            'p.name as position_name',
            'h.level',
            'h.logo',
            'user_doctor.img',
            'user_doctor.good_at',
            'user_doctor.description',
            'user_doctor.one_price',
            'user_doctor.more_price',
            'user_doctor.phone_price'
        ])
            ->leftJoin('user_hospital as h','h.id','=','user_doctor.hospital_id')
            ->leftJoin('user_sys_options as b','b.id','=','user_doctor.branch_id')
            ->leftJoin('user_sys_options as p','p.id','=','user_doctor.position_id');
        if(!empty($doctorIds)) {
            $query->whereIn('user_doctor.id', $doctorIds);
        }
        $data = $query->get();

        return $data;
    }

    static function getBaseAllByOpen($doctorIds) {
        $model = new Doctor();
        $query = $model::query();
        $query->select(Doctor::$fields);
        if(!empty($doctorIds)) {
            $query->whereIn('id', $doctorIds);
        }
        $data = $query->get();

        return $data;
    }

    static function getOneByName($name) {
        return Doctor::select(Doctor::$fields)->where(['name'=>$name])->first();
    }


    static function getInfoByIds($ids) {
        $model = new Doctor();
        $doctorInfo = $model
            ->select([
                'user_doctor.id',
                'user_doctor.real_name',
                'user_doctor.hospital_id',
                'user_doctor.branch_id',
                'user_doctor.position_id',
                'h.name as hospital_name',
                'b.name as branch_name',
                'p.name as position_name',
                'h.level',
                'h.logo',
                'user_doctor.img',
                'user_doctor.good_at',
                'user_doctor.description',
                'user_doctor.one_price',
                'user_doctor.more_price',
                'user_doctor.phone_price'
            ])
            ->leftJoin('user_hospital as h','h.id','=','user_doctor.hospital_id')
            ->leftJoin('user_sys_options as b','b.id','=','user_doctor.branch_id')
            ->leftJoin('user_sys_options as p','p.id','=','user_doctor.position_id')
            ->where(['user_doctor.r_status'=>1])
            ->whereIn('user_doctor.id',$ids)
            ->get();

        return $doctorInfo;
    }
}