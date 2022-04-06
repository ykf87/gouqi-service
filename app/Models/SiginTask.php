<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\SiginProduct;
use App\Models\SiginLog;

class SiginTask extends Model{
    use HasFactory;
    public $timestamps = false;

    public function product(){
    	return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }

    // 根据用户id获取签到任务信息
    public static function siginInfo($uid){
    	$taskProducts 	= self::select('id', 'product_id', 'need_day', 'get_time')
							->where('user_id', $uid)
							->where('status', 0)
							->orderByDesc('id')
							->first();
		if(!$taskProducts){
			return false;
		}else{
			// $taskProducts	= $taskProducts->toArray();
		}
		$siginLog	= SiginLog::sigined($taskProducts);

		$taskInfo 	= $taskProducts->toArray();
		if($siginLog !== false){
			$taskInfo['product'] 		= [];
			$t1			= 'sigin_products';
			$t2 		= 'products';
			$product 					= SiginProduct::select("$t2.id", "$t2.title as name", "$t2.cover", "$t2.price as sale", 'sendout', 'max_own')
											->leftJoin($t2, "$t1.product_id", '=', "$t2.id")->where("$t1.id", $taskProducts->product_id)->first();
			$taskInfo['product']		= $product ? $product->toArray() : false;
			$taskInfo 					= array_merge($taskInfo, $siginLog);
		}else{
			return false;
		}

		return $taskInfo;
    }
}
