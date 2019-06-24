<?php


namespace App\Http\Admin;


use App\Http\ORM\SysOptionsORM;

class OptionsCtl
{
    /**
     * 添加配置信息
     * @param $data
     */
    static function addOption($data) {

        $result = SysOptionsORM::addOne($data);
        if($result) {
            jsonOut('success',true);
        }
        jsonOut('success',false);
    }

    static function delOption($data) {

        $result = SysOptionsORM::delete($data);
        if($result) {
            jsonOut('success',true);
        }
        jsonOut('success',false);
    }

    /**
     * 获取配置列表
     * @param $type
     */
    static function getOptionList($type) {

        $optionList = SysOptionsORM::getAllByType($type);

        jsonOut('success',$optionList);
    }

    /**
     * 置顶操作
     * @param $type
     * @param $id
     */
    static function top($type,$id) {
        $maxSort = SysOptionsORM::getMaxSortByType($type);

        $data['sort'] = $maxSort + 1;
        $data['id'] = $id;
        $data['type'] = $type;
        $result = SysOptionsORM::update($data);
        if($result) {
            jsonOut('success',true);
        }
        jsonOut('success',false);
    }

}