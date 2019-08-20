<?php


namespace App\Http\Admin;
use App\Http\ORM\SysOptionsORM;

class SysOptionsCtl
{
    /**
     * 获取科室
     * @param $branchId
     */
    static function getBranchList() {
        $branchList = SysOptionsORM::getAllByType($type='branch');
        if(!$branchList) {
            jsonOut('NoFoundData',false);
        }
        $branchList = $branchList?$branchList->toArray():[];

        jsonOut('success', $branchList);
    }

    /**
     * 获取职称
     */
    static function getPositionList() {
        $positionList = SysOptionsORM::getAllByType($type='title');
        if(!$positionList) {
            jsonOut('NoFoundData',false);
        }
        $positionList = $positionList?$positionList->toArray():[];

        jsonOut('success', $positionList);
    }
}