<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Withdraw;

class Goubi extends Model{
    use HasFactory;
    public $timestamps = false;

    public static function list($uid){
    	$page		= request()->get('page');
    	$limit 		= request()->get('limit');

    	$page 		= (int)$page;
    	if($page < 1) $page = 1;
    	$limit 		= (int)$limit;
    	if($limit < 1) $limit 	= env('PAGE_LIMIT', 10);

    	$obj        = self::where('id', $uid)->orderBy('id', 'DESC');
    	return $obj->forPage($page,$limit)->get();
    }

    /**
     * 获取用户总可用积分
     */
    public static function userJifen($uid){
    	$his 		= Goubi::where('id', $uid)->where('status', 1)->sum('added');
    	$wit 		= Withdraw::where('uid', $uid)->where('status', '>=', 0)->sum('jine');
    	return abs($his - $wit);
    }
}
