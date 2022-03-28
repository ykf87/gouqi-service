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
			'issigin'	=> $taskInfos && isset($taskInfos['issigin']) ? $taskInfos['issigin'] : false,
		];
		if($taskInfos){
			$arr['signed']	= $taskInfos;
		}
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

		$nums 	= Order::where('status', '>', 0)->where('user_id', $uid)->where('product_id', $product_id)->where('ctype', 1)->count();
		if($nums >= $product->max_own){
			return $this->error('您已经获得过该商品!');
		}

		//领取任务时,如果已存在任务,则自动取消
		$hasTask 		= SiginTask::where('user_id', $uid)->where('status', 0)->orderByDesc('id')->first();
		if($hasTask){
			$hasTask->status 	= -1;
			if(!$hasTask->save()){
				return $this->error('任务领取失败!');
			}
			// return $this->error('您有签到任务未结束!');
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

	//签到
	public function signe(Request $request){
		$uid 		= $request->get('_uid');
		$taskid 	= (int)$request->input('id');
		if($taskid < 1){
			return $this->error('任务不存在!');
		}
		$task 		= SiginTask::find($taskid);
		if(!$task || $task->user_id != $uid){
			return $this->error('任务不存在!');
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
		$task_logs 	= SiginLog::sigined($task);
		if($task_logs['mustdays'] <= $task_logs['days']){
			return $this->error('您的任务已完成,请领取商品!');
		}
		if($task_logs['issigin']){
			return $this->error('您今日已签到!');
		}

		$siginTime 		= time();
		$msg 			= '签到成功!';
		if($task_logs['mustadv'] == true){
			$siginTime 	= strtotime(date('Y-m-d', strtotime('-1 day')));
			$msg 		= '补签成功!';
		}
		$signLog 					= new SiginLog;
		$signLog->user_id 			= $uid;
		$signLog->sigin_task_id 	= $task->id;
		$signLog->product_id 		= $task->product_id;
		$signLog->index 			= $task_logs['days'] + 1;
		$signLog->sigin_time 		= $siginTime;
		if($signLog->save()){
			$taskInfos 		= SiginTask::siginInfo($uid);
			$arr 		= [
				'user'		=> User::select('id', 'name as nickname', 'sex', 'level', 'avatar')->find($uid),
				'signed'	=> $taskInfos,
				'issigin'	=> $taskInfos && isset($taskInfos['issigin']) ? $taskInfos['issigin'] : false,
			];
			return $this->success($arr, $msg);
		}
		return $this->error('签到失败,请联系客服!');
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
					return $this->success('', '产品已收藏!');
				}
			}
		}
		$product 	= SiginProduct::where('product_id', $id);
		if(!$product){
			return $this->error('收藏失败,商品不存在!');
		}

		$coll 				= new Collection;
		$coll->user_id 		= $uid;
		$coll->product_id	= $id;
		$coll->ctype 		= 1;
		$coll->addtime 		= time();
		if($coll->save()){
			$product->collection 	+= 1;
			$product->save();
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
			$product 	= SiginProduct::where('product_id', $id);
			if($product){
				$product->collection 	-= 1;
				$product->save();
			}
			return $this->success('', '取消成功!');
		}
		return $this->error('取消失败!');
	}

	// 领取商品
	public function giveget(Request $request){
		$uid 		= $request->get('_uid');
		$task_id 	= (int)$request->input('id');
		$addr_id 	= (int)$request->input('address_id');
		$address 	= trim($request->input('address', ''));
		$phone 		= trim($request->input('phone', ''));
		$name 		= trim($request->input('name', ''));
		$remark 	= $request->input('remark');

		if($task_id < 1){
			return $this->error('非法请求!');
		}
		if($addr_id < 1 && !$address){
			return $this->error('非法请求!');
		}
		if($address){
			if(!$name || !$phone){
				return $this->error('请填写完整收货信息!');
			}
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
		//检查商品和库存
		$product 	= SiginProduct::where('product_id', $task->product_id)->first();
		if(!$product){
			return $this->error('您选择的商品已经下架!');
		}
		if($product->stocks < 1){
			return $this->error('该商品库存不足!');
		}
		$productInfo 		= Product::find($task->product_id);
		if(!$productInfo){
			return $this->error('您选择的商品已经下架!');
		}

		//收货地址检查
		if($addr_id){
			$addrobj 		= Address::find($addr_id);
			if(!$addrobj || $addrobj->uid != $uid){
				return $this->error('您选择的地址不存在!');
			}
			if(!$addrobj->name || !$addrobj->tel || !$addrobj->address){
				return $this->error('请先完善您的收货地址!');
			}
		}

		//任务完成情况检查
		$task_logs 	= SiginLog::sigined($task);
		if($task_logs['mustdays'] > $task_logs['days']){
			return $this->error('签到天数未达标!');
		}


		//检查产品是否领取上限
		$geted 	= Order::where('status', '>', 0)->where('user_id', $uid)->where('product_id', $task->product_id)->where('ctype', 1)->count();
		if($geted >= $product->max_own){
			return $this->error('您已经领过该商品,无法继续领取!');
		}

		$order 				= new Order;
		$order->user_id 	= $uid;
		$order->product_id 	= $task->product_id;
		$order->task_id 	= $task->id;
		$order->ctype 		= 1;
		$order->sale_price 	= $productInfo->sale;
		$order->price 		= 0;
		$order->pro_title 	= $productInfo->title;
		$order->cover 		= $productInfo->cover;
		$order->address_id 	= $addr_id;
		$order->address 	= $address;
		$order->phone 		= $phone;
		$order->name 		= $name;
		$order->num 		= 1;
		$order->remark 		= $remark;
		$order->status 		= 1;
		if($order->save()){
			$task->status 	= 1;
			$task->save();
			$product->sendout	+= 1;
			$product->stocks	-= 1;
			$product->save();
			$productInfo->main_sendout 	+= 1;
			$productInfo->selled 		+= 1;
			$productInfo->main_stock 	-= 1;
			$productInfo->save();
			return $this->success('', '领取成功!');
		}
		return $this->error('领取失败,请联系客服!');
	}

	//取消签到任务
	public function giveup(Request $request){
		$uid 		= $request->get('_uid');
		$taskid 	= (int)$request->get('id');
		if($taskid < 1){
			return $this->error('任务不存在!');
		}
		$task 		= SiginTask::find($taskid);
		if(!$task || $task->user_id != $uid){
			return $this->error('任务不存在!');
		}
		if($task->status == -1){
			return $this->success('', '取消成功!');
		}
		$task->status 	= -1;
		if($task->save()){
			return $this->success('', '取消成功!');
		}
		return $this->error('取消失败,请联系客服!');
	}
}
