<?php


namespace App\Http\ORM;


use App\Http\Module\Hospital;

class HospitalORM extends BaseORM
{
    static function getAllByProvinceId($provinceId) {

        return Hospital::select(Hospital::$fields)->where(['province_id'=>$provinceId,'r_status'=>1])->get()->toArray();
    }

    static function getAllByCityId($cityId) {

        return Hospital::select(Hospital::$fields)->where(['province_id'=>$cityId,'r_status'=>1])->get()->toArray();
    }

    static function getAllByCountryId($countryId) {

        return Hospital::select(Hospital::$fields)->where(['province_id'=>$countryId,'r_status'=>1])->get()->toArray();
    }

}