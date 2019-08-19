<?php


namespace App\Http\ORM;


use App\Http\Module\TempOverseasHospital;

class TempOverseasHospitalORM extends BaseORM
{
    static function getOneById($id) {

        return TempOverseasHospital::query()
            ->select(TempOverseasHospital::$fields)
            ->find($id);
    }

    static function getAll() {
        $list = TempOverseasHospital::query()
            ->select([
                'id',
                'first_img',
                'hospital_name',
                'address'
            ])
            ->orderBy('sort','desc')
            ->get();

        return $list;
    }

}