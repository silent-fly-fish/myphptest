<?php


namespace App\Http\ORM;


use App\Http\Module\DoctorApply;

class DoctorApplyORM extends BaseORM
{
    static function addOne($data) {
        $model = new DoctorApply();
        $data = self::isIncolumns($model, $data); //过滤添加参数

        $model->fill($data);
        $model->save();

        return $model->getKey();
    }

    static function getOneById($id) {

        return DoctorApply::select(DoctorApply::$fields)->find($id);
    }

    static function getAll($data) {
        $model = new DoctorApply();
        $page = $data['page'];
        $size = $data['size'];
        $query = $model::query();
        $queryTotal = $model::query();

        $query->select($model::$fields);
        $queryTotal->select($model::$fields);

        //医生姓名筛选
        if(isset($data['name'])) {
            $query->where('name','like','%'.$data['name'].'%');
            $queryTotal->where('name','like','%'.$data['name'].'%');
        }

        //医生手机号筛选
        if(isset($data['phone'])) {
            $query->where('phone','like','%'.$data['phone'].'%');
            $queryTotal->where('phone','like','%'.$data['phone'].'%');
        }

        //医院名称筛选
        if(isset($data['hospital'])) {
            $query->where('hospital','like','%'.$data['hospital'].'%');
            $queryTotal->where('hospital','like','%'.$data['hospital'].'%');
        }

        //审核状态筛选
        if(isset($data['apply_status'])) {
            $query->where(['apply_status'=>$data['apply_status']]);
            $queryTotal->where(['apply_status'=>$data['apply_status']]);
        }

        $offset = getOffsetByPage($page, $size);
        $query->offset($offset);
        $query->limit($size);
        $query->orderBy('id','asc');
        $total = $queryTotal->count();
        $datas = $query->get();



        $ret['total'] = $total;
        $ret['list'] = $datas;

        return $ret;
    }

    static function update($data) {
        $model = new DoctorApply();
        $id = $data['id'];
        $data = self::isIncolumns($model, $data);

        return $model::where(['id'=>$id])->update($data);
    }


}