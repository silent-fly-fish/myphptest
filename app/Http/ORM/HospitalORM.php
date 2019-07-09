<?php


namespace App\Http\ORM;


use App\Http\Module\Hospital;

class HospitalORM extends BaseORM
{
    static function getAllByProvinceId($provinceId) {

        return Hospital::select(Hospital::$fields)->where(['province_code'=>$provinceId,'r_status'=>1])->get()->toArray();
    }

    static function getAllByCityId($cityId) {

        return Hospital::select(Hospital::$fields)->where(['city_code'=>$cityId,'r_status'=>1])->get()->toArray();
    }

    static function getAllByCountryId($countryId) {

        return Hospital::select(Hospital::$fields)->where(['county_code'=>$countryId,'r_status'=>1])->get()->toArray();
    }

    static function addOne($data) {
        $model = new Hospital();
        $data = self::isIncolumns($model, $data); //过滤添加参数

        $model->fill($data);
        $model->save();

        return $model->getKey();
    }

    static function update($data) {
        $model = new Hospital();
        $id = $data['id'];
        $data = self::isIncolumns($model, $data);

        return $model::whereRaw('id='.$id)->update($data);
    }

    static function getAllByList($getData) {
        $model = new Hospital();
        $query = $model::query();
        $queryTotal = $model::query();
        $page = $getData['page'];
        $size = $getData['size'];

        $query->select([
            'user_hospital.id',
            'user_hospital.name',
            'user_hospital.level',
            'user_hospital.r_status',
            'user_hospital.province_code',
            'user_hospital.city_code',
            'user_hospital.area_code',
            'user_hospital.address',
            'ap.name as province_name',
            'ac.name as city_name',
            'acy.name as area_name',
        ])
            ->leftJoin('user_area_province as ap','user_hospital.province_code','=','ap.code')
            ->leftJoin('user_area_city as ac','user_hospital.city_code','=','ac.code')
            ->leftJoin('user_area_country as acy','user_hospital.area_code','=','acy.code');

        $queryTotal->select([
            'user_hospital.id',
            'user_hospital.name',
            'user_hospital.level',
            'user_hospital.r_status',
            'user_hospital.province_code',
            'user_hospital.city_code',
            'user_hospital.area_code',
            'user_hospital.address',
            'ap.name as province_name',
            'ac.name as city_name',
            'acy.name as area_name',
        ])
            ->leftJoin('user_area_province as ap','user_hospital.province_code','=','ap.code')
            ->leftJoin('user_area_city as ac','user_hospital.city_code','=','ac.code')
            ->leftJoin('user_area_country as acy','user_hospital.area_code','=','acy.code');

        if(isset($getData['name'])) {
            $query->where('user_hospital.name','like','%'.$getData['name'].'%');
            $queryTotal->where('user_hospital.name','like','%'.$getData['name'].'%');
        }

        if(isset($getData['r_status'])) {
            $query->where(['user_hospital.r_status'=>$getData['r_status']]);
            $queryTotal->where(['user_hospital.r_status'=>$getData['r_status']]);
        }

        $offset = getOffsetByPage($page, $size);
        $query->offset($offset);
        $query->limit($size);

        $query->orderByRaw('user_hospital.id desc');

        $total = $queryTotal->count();
        $list = $query->get();
        $data['total'] = $total;
        $data['list'] = $list;

        return $data;
    }

    static function getOneById($hospitalId) {
        $info = Hospital::query()
            ->select([
                'user_hospital.id',
                'user_hospital.name',
                'user_hospital.level',
                'user_hospital.r_status',
                'user_hospital.province_code',
                'user_hospital.city_code',
                'user_hospital.area_code',
                'user_hospital.address',
                'user_hospital.public_hospital',
                'user_hospital.description',
                'ap.name as province_name',
                'ac.name as city_name',
                'acy.name as area_name'
            ])
            ->leftJoin('user_area_province as ap','user_hospital.province_code','=','ap.code')
            ->leftJoin('user_area_city as ac','user_hospital.city_code','=','ac.code')
            ->leftJoin('user_area_country as acy','user_hospital.area_code','=','acy.code')
            ->find($hospitalId);

        return $info;
    }

}