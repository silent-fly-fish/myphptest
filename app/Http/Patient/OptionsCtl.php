<?php


namespace App\Http\Patient;

use App\Http\ORM\SysOptionsORM;
class OptionsCtl
{
    static function getOptionsByType($type) {
        $optionList = SysOptionsORM::getAllByType($type);

        jsonOut('success', $optionList);
    }

}