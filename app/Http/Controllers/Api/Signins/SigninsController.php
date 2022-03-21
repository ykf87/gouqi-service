<?php

namespace App\Http\Controllers\Api\Signins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\SiginTask;
use App\Models\SiginLog;
use App\Models\SiginProduct;
use App\Models\Product;
use App\Models\Order;

class SigninsController extends Controller{
	//签到首页
	public function signed(Request $request){
		$uid 		= $request->get('_uid');
		$user 		= User::select('id', 'name as nickname', 'sex', 'level', 'avatar')->find($uid);
		if(!$user){
			return $this->error('找不到用户!');
		}
		$taskInfos 		= SiginTask::siginInfo($uid);

		$arr 		= [
			'user'		=> $user,
			'signed'	=> $taskInfos,
			'issigin'	=> $taskInfos && isset($taskInfos['issigin']) ? $taskInfos['issigin'] : false,
		];
		return $this->success($arr);
	}

	//领取任务
	public function choose(Request $request){
		$product_id 	= (int)$request->input('product_id', 0);
		$uid 			= $request->get('_uid');

		if(!$uid){
			return $this->error('请登录!', null, 401);
		}
		$user 			= User::select('id', 'name as nickname', 'sex', 'level', 'avatar')->find($uid);
		if(!$user){
			return $this->error('找不到用户!');
		}
		if($product_id < 1){
			return $this->error('请选择产品!');
		}
		$hasTask 		= SiginTask::where('user_id', $uid)->where('status', 0)->orderByDesc('id')->first();
		if($hasTask){
			return $this->error('您有签到任务未结束!');
		}
		$product 		= SiginProduct::find($product_id);
		if(!$product){
			return $this->error('您选的产品不存在!');
		}
		if($product->status != 1){
			return $this->error('您选的产品已下架!');
		}
		if($product->stocks < 1){
			return $this->error('您选的产品库存不足!');
		}
		$now 	= time();
		if($product->start_time && $product->start_time > $now){
			return $this->error('您选的产品还未开始参与活动!');
		}
		if($product->end_time && $product->end_time < $now){
			return $this->error('您选的产品已结束!');
		}

		$nums 	= Order::where('status', '>=', 0)->where('user_id', $uid)->where('product_id', $product_id)->count();
		if($nums >= $product->max_own){
			return $this->error('您已经获得过该商品!');
		}
		$task 	= new SiginTask;
		$task->user_id 		= $uid;
		$task->product_id 	= $product_id;
		$task->need_day 	= $product->days;
		$task->get_time 	= time();
		if(!$task->save()){
			return $this->error('任务');
		}
		$taskInfos 		= SiginTask::siginInfo($uid);

		$arr 		= [
			'user'		=> $user,
			'signed'	=> $taskInfos,
			'issigin'	=> $taskInfos && isset($taskInfos['issigin']) ? $taskInfos['issigin'] : false,
		];
		return $this->success($arr);
	}

	// 商品列表
	public function giveaways(Request $request){
		$now 	= time();
		$page 	= (int)$request->input('page', 1);
		$limit 	= (int)$request->input('limit', 10);
		if($page < 1){
			$page 	= 1;
		}
		if($limit < 1){
			$limit 	= 10;
		}
		$t1		= 'sigin_products';
		$t2 	= 'products';
		$arr 	= [];
		$res 	= SiginProduct::select("$t2.id", "$t2.cover", "$t2.images", "$t2.title", "$t1.days", "$t1.sendout", "$t1.max_own", "$t2.sale", "$t1.collection")
						->rightJoin("$t2", "$t1.product_id", '=', "$t2.id")
						->where("$t1.status", 1)
						->where("$t2.main_status", 1)
						->whereRaw("if($t1.start_time > 0, start_time <= $now, true)")
						->whereRaw("if($t1.end_time > 0, end_time > $now, true)")
						->orderByDesc("$t1.sortby")->limit($limit)->forPage($page)->get();
		if(count($res) > 0){
			$arr['list']	= $res;
		}else{
			return $this->error('暂无商品!');
		}
		return $this->success($arr);
	}

	// 商品详情
	public function giveinfo(Request $request){
		$id 	= (int)$request->input('id', 0);
		if($id < 1){
			return $this->error('请选择商品!');
		}

		$now 	= time();
		$t1		= 'sigin_products';
		$t2 	= 'products';
		$arr 	= [];
		$res 	= SiginProduct::select("$t2.id", "$t2.cover", "$t2.images", "$t2.title", "$t1.days", "$t1.sendout", "$t1.max_own", "$t2.sale", "$t1.collection")
						->rightJoin("$t2", "$t1.product_id", '=', "$t2.id")
						->where("$t1.status", 1)
						->where("$t2.main_status", 1)
						->whereRaw("if($t1.start_time > 0, start_time <= $now, true)")
						->whereRaw("if($t1.end_time > 0, end_time > $now, true)")
						->where("$t2.id", $id)
						->first();
		if(!$res){
			return $this->error('找不到商品!');
		}
		return $this->success($res);
	}

	// 收藏商品
	public function givecollection(Request $request){
		$id 	= (int)$request->input('id', 0);
		if($id < 1){
			return $this->error('请选择要收藏的商品!');
		}

		$siginProduct 	= SiginProduct::where('product_id', $id)->first();
		if(!$siginProduct){
			return $this->error('找不到商品!');
		}
		$product 		= Product::find($id);
	}

	// 取消收藏商品
	public function giveuncollection(Request $request){

	}

	// 领取商品
	public function giveget(Request $request){

	}
}
