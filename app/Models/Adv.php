<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Goubi;
class Adv extends Model{
    use HasFactory;
    private static $beishu 	= 1;

    /**
     * 根据看广告次数,计算今日看文章获得积分数量
     */
    public static function fanbeiTimes($uid){
    	$tm 		= strtotime(date('Y-m-d'));
    	$times 		= self::where('id', $uid)->where('addtime', '>=', $tm)->where('type', 2)->limit(5)->count();
    	if($times < 1) $times = 1;
    	return $times * self::$beishu;
    }

    /**
     * 看广告加积分
     * 每5次翻倍
     */
    public static function addGoubi($uid){
    	$base 		= 3;
    	$step 		= 2;
    	$tm 		= strtotime(date('Y-m-d'));
    	$times 		= self::where('id', $uid)->where('addtime', '>=', $tm)->where('type', 1)->limit(5)->count();

    	if($times < 1) $times = 1;
    	$added 		= $base * $times;
    	$tm 		= date('Y-m-d H:i:s');
    	Goubi::insert(['id' => $uid, 'added' => $added, 'created_at' => $tm, 'updated_at' => $tm]);
    }
}
