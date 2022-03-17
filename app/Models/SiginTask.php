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
    	$taskProducts 	= SiginTask::select('id', 'product_id', 'need_day as mustdays', 'get_time as startat')
							->where('user_id', $uid)
							->where('status', '>=', 0)
							->orderByDesc('id')
							->first();
		if($taskProducts){
			$taskProducts	= $taskProducts->toArray();
		}else{
			return false;
		}

		$taskProducts['product'] 		= [];
		$t1			= 'sigin_products';
		$t2 		= 'products';
		$product 					= SiginProduct::select("$t2.id", "$t2.title as name", "$t2.cover", "$t2.price as sale", 'sendout', 'max_own')
										->leftJoin($t2, "$t1.product_id", '=', "$t2.id")->first();
		$taskProducts['product']	= $product ? $product->toArray() : false;

		$taskProducts['list']		= SiginLog::sigined($taskProducts);
		return $taskProducts;
    }
}
