<?php


namespace App\Http\ORM;


use App\Http\Module\Referee;
use Carbon\Carbon;

class RefereeORM extends BaseORM
{
    static function addOne($data) {
        $model = new Referee();
        $data = self::isIncolumns($model, $data); //过滤添加参数

        $model->fill($data);
        $model->save();

        return $model->getKey();
    }

    static function update($data) {
        $model = new Referee();
        $id = $data['id'];
        $data = self::isIncolumns($model, $data);

        return $model::whereRaw('id='.$id)->update($data);
    }

    static function getAllList($data) {
        $page = $data['page'];
        $size = $data['size'];
        $model = new Referee();

        $query = $model::query();
        $queryTotal = $model::query();

        $query->where('r_status','=',1)->select($model::$fields);
        $queryTotal->where('r_status','=',1)->select($model::$fields);

        //手机号筛选
        if (isset($data['phone'])) {
            $query->where('phone','like','%'.$data['phone'].'%');
            $queryTotal->where('phone','like','%'.$data['phone'].'%');
        }

        //姓名筛选
        if (isset($data['name'])) {
            $query->where('name','like','%'.$data['name'].'%');
            $queryTotal->where('name','like','%'.$data['name'].'%');
        }

        $offset = getOffsetByPage($page, $size);
        $query->offset($offset);
        $query->limit($size);

        $query->orderBy('id','desc');
        $data = $query->get();

        $datas['total'] = $queryTotal->count();
        $datas['list'] = $data?$data->toArray():[];

        return $datas;
    }

    static function getOneById($id) {

        return Referee::query()
            ->select(Referee::$fields)
            ->find($id);
    }

    static function isByNameOrPhone($name='',$phone='') {
        $isExist = Referee::query()
            ->where('name','=',$name)
            ->orWhere('phone','=',$phone)
            ->count();

        return $isExist;
    }

    static function isByNameUpdateOrPhone($id='',$name='',$phone='') {
        $query = Referee::query();
        $isExist = $query
            ->where('id','<>',$id)
            ->whereRaw("(name = '$name' or phone = '$phone')")
            ->count();

        return $isExist;
    }


}