<?php
namespace App\Http\Module;
use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: Mloong
 * Date: 2018/10/25
 * Time: 14:05
 */
class WechatUser extends Model
{
    protected $table = 'sd_wechat_user';

    protected $dateFormat = 'U';

    protected $guarded = [

    ];
}