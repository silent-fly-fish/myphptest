<?php


namespace App\Http\Admin;


use App\Http\ORM\RefereeORM;

class RefereesCtl
{
    /**
     * 获取列表
     * @param $data
     */
    static function getRefereeList($data) {
        $list = RefereeORM::getAllList($data);

        jsonOut('success',$list);
    }

    /**
     * 添加销售员
     * @param $data
     */
    static function addOne($data) {

        $name = isset($data['name'])? $data['name'] : '';
        $phone = isset($data['phone'])? $data['phone'] : '';
        $isExist = RefereeORM::isByNameOrPhone($name,$phone);
        if($isExist) {
            jsonOut('isRefereeNameOrPhone',false);
        }

        $result = RefereeORM::addOne($data);
        if($result) {
            jsonOut('success',true);
        }
        jsonOut('success',false);
    }

    /**
     * 修改或删除销售人员
     * @param $data
     */
    static function update($data) {
        $id = isset($data['id'])? $data['id'] : '';
        $name = isset($data['name'])? $data['name'] : '';
        $phone = isset($data['phone'])? $data['phone'] : '';
        $isExist = RefereeORM::isByNameUpdateOrPhone($id,$name,$phone);
        if($isExist) {
            jsonOut('isRefereeNameOrPhone',false);
        }

        $result = RefereeORM::update($data);
        if($result) {
            jsonOut('success',true);
        }
        jsonOut('success',false);
    }

}