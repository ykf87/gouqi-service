<?php

namespace App\Http\Controllers\Api\Signins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\SiginTask;
use App\Models\SiginLog;
use App\Models\SiginProduct;
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
}
