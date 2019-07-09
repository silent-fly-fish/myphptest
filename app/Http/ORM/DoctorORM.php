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
            'user_doctor.category_id_str',
            'user_doctor.description',
            'user_doctor.one_price',
            'user_doctor.more_price',
                'user_doctor.name',
                'user_doctor.telephone'
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
            'user_doctor.more_price'
        ])
            ->leftJoin('user_hospital as h','h.id','=','user_doctor.hospital_id')
            ->leftJoin('user_sys_options as b','b.id','=','user_doctor.branch_id')
            ->leftJoin('user_doctor_hots as dh','dh.doctor_id','=','user_doctor.id')
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
            'user_doctor.more_price'
        ])
            ->leftJoin('user_hospital as h','h.id','=','user_doctor.hospital_id')
            ->leftJoin('user_sys_options as b','b.id','=','user_doctor.branch_id')
            ->leftJoin('user_doctor_hots as dh','dh.doctor_id','=','user_doctor.id')
            ->leftJoin('user_sys_options as p','p.id','=','user_doctor.position_id');
        if(!empty($data['area'])) {
            $query->whereIn('user_doctor.hospital_id', $data['area']);
            $queryTotal->whereIn('user_doctor.hospital_id', $data['area']);
        }

        if(isset($data['category_id'])) {
            $query->whereRaw("find_in_set($data[category_id], user_doctor.category_id_str)");
            $queryTotal->whereRaw("find_in_set($data[category_id], user_doctor.category_id_str)");
        }
        if(isset($data['search'])) {
            $query->where('user_doctor.real_name','like','%'.$data['search'].'%');
            $queryTotal->where('user_doctor.real_name','like','%'.$data['search'].'%');
        }

        if(isset($data['hospital_id'])) {
            $query->where(['hospital_id'=>$data['hospital_id']]);
            $queryTotal->where(['hospital_id'=>$data['hospital_id']]);
        }




        $offset = getOffsetByPage($page, $size);
        $query->offset($offset);
        $query->limit($size);

        $query->orderByRaw('dh.total_score desc,user_doctor.id asc');
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

    static function updateBatchById($data) {

        $model = new Doctor();
        $table = $model->getTable();
        return self::updateBatch($table,$data);
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
            'user_doctor.more_price'
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
                'user_doctor.more_price'
            ])
            ->leftJoin('user_hospital as h','h.id','=','user_doctor.hospital_id')
            ->leftJoin('user_sys_options as b','b.id','=','user_doctor.branch_id')
            ->leftJoin('user_sys_options as p','p.id','=','user_doctor.position_id')
            ->where(['user_doctor.r_status'=>1])
            ->whereIn('user_doctor.id',$ids)
            ->get();

        return $doctorInfo;
    }

    static function getAllList($data) {

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
            'user_doctor.category_id_str'
        ])
            ->leftJoin('user_hospital as h','h.id','=','user_doctor.hospital_id')
            ->leftJoin('user_sys_options as b','b.id','=','user_doctor.branch_id')
            ->leftJoin('user_doctor_hots as dh','dh.doctor_id','=','user_doctor.id')
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
            'user_doctor.more_price'
        ])
            ->leftJoin('user_hospital as h','h.id','=','user_doctor.hospital_id')
            ->leftJoin('user_sys_options as b','b.id','=','user_doctor.branch_id')
            ->leftJoin('user_doctor_hots as dh','dh.doctor_id','=','user_doctor.id')
            ->leftJoin('user_sys_options as p','p.id','=','user_doctor.position_id');


        if(!empty($data['hospital_id'])) {
            $query->where('user_doctor.hospital_id','=',$data['hospital_id']);
            $queryTotal->where('user_doctor.hospital_id','=', $data['hospital_id']);
        }
        //类目筛选
        if(isset($data['category_id'])) {
            $query->whereRaw("find_in_set($data[category_id], user_doctor.category_id_str)");
            $queryTotal->whereRaw("find_in_set($data[category_id], user_doctor.category_id_str)");
        }
        //医生名称筛选
        if(isset($data['real_name'])) {
            $query->where('user_doctor.real_name','like','%'.$data['real_name'].'%');
            $queryTotal->where('user_doctor.real_name','like','%'.$data['real_name'].'%');
        }
        //科室刷选
        if(isset($data['branch_id'])) {
            $query->where('user_doctor.branch_id','=',$data['branch_id']);
            $queryTotal->where('user_doctor.branch_id','=',$data['branch_id']);
        }
        //手机号筛选
        if(isset($data['name'])) {
            $query->where('user_doctor.name','like','%'.$data['name'].'%');
            $queryTotal->where('user_doctor.name','like','%'.$data['name'].'%');
        }


        $offset = getOffsetByPage($page, $size);
        $query->offset($offset);
        $query->limit($size);
//        $query->orderByRaw('user_doctor.sort desc,user_doctor.id desc');
        $query->orderByRaw('dh.total_score desc,user_doctor.id asc');
        $total = $queryTotal->count();

        $datas = $query->get();
        $datas = $datas?$datas->toArray():[];


        $ret['total'] = $total;
        $ret['list'] = $datas;

        return $ret;
    }

    static function getOneByPhone($phone) {

        return Doctor::query()
            ->select(Doctor::$fields)
            ->where('name','=',$phone)
            ->first();
    }

    static function getAllNotPage() {
        return Doctor::query()
            ->select(Doctor::$fields)
            ->whereRaw('uptime != 0')
            ->get()
            ->toArray();
    }

    static function getAllByTeam($data) {
        $model = new Doctor();
        $query = $model::query();
        $queryTotal = $model::query();
        $page = $data['page'];
        $size = $data['size'];

        $query->select([
            'user_doctor.id',
            'user_doctor.real_name',
            'user_doctor.name',
            'user_doctor.hospital_id',
            'user_doctor.created_at',
            'user_doctor.r_status',
            'h.name as hospital_name'
        ])
            ->leftJoin('user_hospital as h','user_doctor.hospital_id','=','h.id');

        $queryTotal->select([
            'user_doctor.id',
            'user_doctor.real_name',
            'user_doctor.name',
            'user_doctor.hospital_id',
            'user_doctor.created_at',
            'user_doctor.r_status',
            'h.name as hospital_name'
        ])
            ->join('user_hospital as h','user_doctor.hospital_id','=','h.id');

        //医生姓名筛选
        if(isset($data['real_name'])) {
            $query->where('user_doctor.real_name','like','%'.$data['real_name'].'%');
            $queryTotal->where('user_doctor.real_name','like','%'.$data['real_name'].'%');
        }

        //医生手机号筛选
        if(isset($data['name'])) {
            $query->where('user_doctor.name','like','%'.$data['name'].'%');
            $queryTotal->where('user_doctor.name','like','%'.$data['name'].'%');
        }

        //医院筛选
        if(isset($data['hospital_id'])) {
            $query->where(['user_doctor.hospital_id'=>$data['hospital_id']]);
            $queryTotal->where(['user_doctor.hospital_id'=>$data['hospital_id']]);
        }

        if(isset($data['r_status'])) {
            $query->where(['user_doctor.r_status'=>$data['r_status']]);
            $queryTotal->where(['user_doctor.r_status'=>$data['r_status']]);
        }

        $offset = getOffsetByPage($page, $size);
        $query->offset($offset);
        $query->limit($size);

        $query->orderBy('user_doctor.id','asc');
        $total = $queryTotal->count();
        $datas = $query->get();

        $ret['total'] = $total;
        $ret['list'] = $datas;

        return $ret;

    }

    static function getBatchByIDS($ids){
        $ret =  Doctor::select('id','real_name')->whereIn('id',$ids)->where(['r_status'=>1])->get();

        if (count($ret) > 0){
            $ret = actionGetObjDataByData($ids,$ret,'id');
        }

        return $ret;
    }
}