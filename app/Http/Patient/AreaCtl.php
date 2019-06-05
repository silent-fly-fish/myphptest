<?php


namespace App\Http\Patient;


use App\Http\Module\AreaCity;
use App\Http\ORM\AreaCityORM;
use App\Http\ORM\AreaCountryORM;
use App\Http\ORM\AreaProvinceORM;

class AreaCtl
{
    /**
     * 获取省市区三级列表
     */
    static function getAreaList() {
        $provinceList = AreaProvinceORM::getAll();
        $cityList = AreaCityORM::getAll();
        $countryList = AreaCountryORM::getAll();
        $cityCodeList = [];
        $countryCodeList = [];

        foreach ($countryList as $k=>$v) {
            $countryCodeList[$v['p_code']][] = $v;
        }
        foreach ($cityList as $k => $v) {
            $temp = $v;
            $temp['country_list'] = isset($countryCodeList[$v['code']])? $countryCodeList[$v['code']] : [];

            $cityCodeList[$v['p_code']][] = $temp;
        }


        foreach ($provinceList as $k=>$v) {
            $provinceList[$k]['city_list'] = $cityCodeList[$v['code']];
        }



        jsonOut('success',$provinceList);
    }
}