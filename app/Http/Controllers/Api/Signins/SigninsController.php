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
use App\Models\Collection;
use App\Models\Address;

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
		$uid 	= $request->get('_uid');
		$id 	= (int)$request->input('id', 0);
		if($id < 1){
			return $this->error('请选择要收藏的商品!');
		}

		$res 	= Collection::where('user_id', $uid)->where('product_id', $id)->get();
		if(count($res) > 0){
			foreach($res as $item){
				if($item->ctype == 1){
					return $this->error('产品已收藏!');
				}
			}
		}
		$coll 				= new Collection;
		$coll->user_id 		= $uid;
		$coll->product_id	= $id;
		$coll->ctype 		= 1;
		$coll->addtime 		= time();
		if($coll->save()){
			return $this->success('', '收藏成功!');
		}
		return $this->error('收藏失败!');
	}

	// 取消收藏商品
	public function giveuncollection(Request $request){
		$uid 	= $request->get('_uid');
		$id 	= (int)$request->input('id', 0);
		$coll 	= Collection::where('user_id', $uid)->where('product_id', $id)->where('ctype', 1)->first();
		if($coll && $coll->delete()){
			return $this->success('', '取消成功!');
		}
		return $this->error('取消失败!');
	}

	// 领取商品
	public function giveget(Request $request){
		$uid 		= $request->get('_uid');
		$task_id 	= (int)$request->input('id');
		$addr_id 	= (int)$request->input('address_id');
		$remark 	= $request->input('remark');

		if($task_id < 1 || $addr_id < 1){
			return $this->error('非法请求!');
		}
		$task 		= SiginTask::find($task_id);
		if(!$task){
			return $this->error('您未领取任务!');
		}
		if($task->user_id != $uid){
			return $this->error('非法请求!');
		}
		if($task->status != 0){
			$msg 			= '您的签到暂未完成!';
			switch($task->status){
				case 1:
					$msg 	= '您的产品已经领取';
				break;
				case -1:
					$msg 	= '您的任务已取消!';
				break;
				case -2:
					$msg 	= '您的任务已过期!';
				break;
			}
			return $this->error($msg);
		}
		$address 		= Address::find($addr_id);
		if(!$address || $address->uid != $uid){
			return $this->error('您选择的地址不存在!');
		}
		if(!$address->name || !$address->tel || !$address->address){
			return $this->error('请先完善您的收货地址!');
		}
		$task_logs 	= SiginLog::sigined($task);
		if($task_logs['mustdays'] > $task_logs['days']){
			return $this->error('签到天数未达标!');
		}
		
	}
}
