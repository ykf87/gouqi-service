<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Heart;
use App\Models\History;
use App\Models\Adv;
use App\Models\Post;
use App\Models\Goubi;
use App\Models\Withdraw;
use App\Models\UserCard;
use App\Models\Bank;
use App\Models\Task;

class UserController extends Controller{
	/**
	 * 用户数据返回
	 */
	public function index(Request $request){
		$uid 		= $request->get('_uid');

		$user 		= User::find($uid);
		if($user){
			$arr 		= User::users($user, false);
		}else{
			$arr 		= [];
		}
		return $this->success($arr);
	}

	/**
	 * 用户登录
	 */
	public function login(Request $request){
		$arr 		= [];
		$phone 		= $request->input(User::$usernameKey);
		$pwd 		= $request->input('password');
		$isReg 		= $request->input('reg', 0);// 不存在是否直接注册
		$name 		= trim($request->input('name', ''));
		if(!$phone || !$pwd){
			return $this->error(__('用户名或密码错误!'));
		}

		$user 		= User::where(User::$usernameKey, $phone)->first();
		if(!$user){
			if($isReg == 1){
				return $this->success(User::sigin($username, $pwd, $name));
			}else{
				return $this->error(__('用户不存在,请先注册!'));
			}
		}elseif(!password_verify($pwd, $user->pwd)){
			return $this->error(__('用户名或密码错误!'));
		}
		$arr 		= User::login($user);

		return $this->success($arr);
	}

	/**
	 * 用户注册
	 */
	public function sigin(Request $request){
		$phone 			= $request->input(User::$usernameKey);
		$pwd 			= $request->input('password');
		$name 			= trim($request->input('name', ''));

		if(empty($phone) || empty($pwd)){
			return $this->error(__('用户名或密码错误!'));
		}

		$user 			= User::where(User::$usernameKey, $phone)->first();
		if($user){
			if(!password_verify($pwd, $user->pwd)){
				return $this->error(__('用户已存在!'));
			}
			return $this->success(User::users($user));
		}else{
			$arr 		= User::sigin($phone, $pwd, $name);
		}

		return $this->success($arr);
	}

	/**
	 * 密码重置
	 */
	public function reset(Request $request){
		$arr 		= [];

		return $this->success($arr);
	}

	/**
	 * 用户收藏列表
	 */
	public function watch(Request $request){
		$arr 		= Heart::list($request->get('_uid'));

		return $this->success($arr);
	}

	/**
	 * 浏览记录
	 */
	public function history(Request $request){
		$arr 		= History::list($request->get('_uid'));

		return $this->success($arr);
	}

	/**
	 * 添加收藏
	 */
	public function heart(Request $request){
		$pid 		= (int)$request->input('id', 0);
		$uid 		= $request->get('_uid', 0);

		if($pid < 1){
			return $this->error(__('请选择文章!'));
		}

		$rs         = Heart::where('id', $uid)->where('pid', $pid)->first();
		if($rs){
			if(Heart::where('id', $uid)->where('pid', $pid)->delete()){
				Post::find($pid)->decrement('hearted');
				return $this->success([], __('成功取消收藏!'));
			}else{
				return $this->error(__('取消失败!'));
			}
		}
		if(Heart::insert(['id' => $uid, 'pid' => $pid, 'addtime' => time()])){
			Post::find($pid)->increment('hearted');
			return $this->success([], __('添加成功!'));
		}

		return $this->error(__('添加失败!'));
	}

	/**
	 * 广告播放完成回调
	 */
	public function palied(Request $request){
		$uid 			= $request->get('_uid');
		// $type 			= (int)$request->get('type', 1);
		// if(!$type){
		// 	$type 		= 1;
		// }
		// $last 			= Adv::where('uid', $uid)->orderBy('addtime', 'DESC')->first();
		// if($last){
		// 	if((time() - $last->addtime) < 10){
		// 		return $this->error(__('请等待广告加载!'));
		// 	}
		// }
		// if(!Adv::insert(['uid' => $uid, 'addtime' => time(), 'type' => $type])){
		// 	return $this->error(__('广告添加失败!'));
		// }
		// if($type == 2){
		// 	Adv::addGoubi($uid);
		// }


		$tid 		= $request->input('tid');
		$task 		= Task::find($tid);
		if(!$task){
			return $this->error('非法请求');
		}

		$todayStart		= strtotime(date('Y-m-d 00:00:00'));
    	$todayEnd 		= $todayStart + 86399;
		$times 			= Adv::where('uid', $uid)->where('tid', $tid)->whereBetween('addtime', [$todayStart, $todayEnd])->count();
		if($times >= $task->max){
			Adv::insert(['uid' => $uid, 'addtime' => time(), 'tid' => $tid, 'status' => 0, 'type' => 1]);
			return $this->error('任务已完成!');
		}

		Adv::insert(['uid' => $uid, 'addtime' => time(), 'tid' => $tid, 'status' => 1, 'type' => 1]);
		$times++;
		$add 			= 0;
		if($task->min <= $times){
			$jifenHistory 		= Goubi::where('id', $uid)->where('tid', $tid)->whereBetween('created_at', [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')])->count();
			$maxTimes 			= ceil($task->max / $task->min);
			if($jifenHistory >= $maxTimes){
				return $this->error('任务已完成.');
			}else{
				$tm 			= date('Y-m-d H:i:s');
				$add 			= $task->prize;
				Goubi::insert(['id' => $uid, 'tid' => $tid, 'added' => $task->prize, 'created_at' => $tm, 'updated_at' => $tm]);
			}
		}

		$rarr 		= [
			'task' => [
				'id' => $task->id,
				'title' => $task->title,
				'max' => $task->max,
				'min' => $task->min,
				'prize' => $task->prize,
				'times' => $times,
			],
			'jifen' 	=> $add
		];
		return $this->success($rarr, __('广告播放成功!'));
		// return $this->success([], __('奖励成功!'));
	}

	/**
	 * 积分记录
	 */
	public function jifen(Request $request){
		$arr 		= Goubi::list($request->get('_uid'));

		return $this->success($arr);
	}

	/**
	 * 银行列表
	 */
	public function bank(){
		$arr 		= Bank::list();
		return $this->success($arr);
	}

	/**
	 * 我的银行卡列表
	 */
	public function mycard(Request $request){
		$arr 		= UserCard::list($request->get('_uid'));
		return $this->success($arr);
	}

	/**
	 * 添加银行卡
	 */
	public function card(Request $request){
		$id 			= (int)$request->input('id', 0);
		$name 			= trim($request->input('name', ''));
		$phone 			= trim($request->input('telphone', ''));
		$bankName 		= $request->input('type');
		$number 		= trim($request->input('number', ''));
		$uid 			= $request->get('_uid');

		if(!$name || mb_strlen($name, 'utf-8') > 16){
			return $this->error(__('请填写真实姓名!'));
		}
		if(!is_numeric($phone) || strlen($phone) != 11){
			return $this->error(__('请填写电话!'));
		}
		$number 		= str_replace(' ', '', $number);
		if(!is_numeric($number)){
			return $this->error(__('请正确填写银行卡号!'));
		}
		if(!$bankName){
			return $this->error('请选择银行!');
		}

		// 判断是否是选择银行
		if(!is_numeric($bankName)){
			$bankObj 	= Bank::where('name', $bankName)->first();
			if(!$bankObj){
				return $this->error('暂不支持 ' . $bankName . ' 提现!');
			}
			$bankName 	= $bankObj->id;
		}else{
			$bankObj 	= Bank::find($bankName);
			if(!$bankObj){
				return $this->error('请选择银行!');
			}
		}


		if($id < 1){
			$count 			= UserCard::where('uid', $uid)->count();
			if($count >= User::$maxCard){
				return $this->error(__('银行卡不允许超过' . User::$maxCard . '张!'));
			}
			$obj 			= new UserCard;
			$obj->uid 		= $uid;
			$obj->name 		= $name;
			$obj->phone 	= $phone;
			$obj->number 	= $number;
			$obj->type 		= $bankName;
			if($obj->save()){
				return $this->success(__('银行卡添加成功!'));
			}
			return $this->error(__('添加失败,请联系客服!'));
		}else{
			$obj 			= UserCard::find($id);
			if(!$obj){
				return $this->error(__('找不到记录!'));
			}
			if($obj->uid != $uid){
				return $this->error(__('非法请求!'));
			}
			$obj->name 		= $name;
			$obj->phone 	= $phone;
			$obj->number 	= $number;
			$obj->type 		= $bankName;
			if($obj->save()){
				return $this->success(__('更新成功!'));
			}
			return $this->error(__('更新失败!'));
		}
	}

	/**
	 * 提现申请
	 */
	public function tixian(Request $request){
		$jine 		= (float)$request->input('jine', 0.0);
		$cardid 	= (int)$request->input('cardid', 0);
		$uid 		= $request->get('_uid');
		if($jine < User::$minTixian){
			return $this->error(__('金额不能小于'.User::$minTixian.'元'));
		}
		if($cardid < 1){
			return $this->error(__('请选择提现银行卡!'));
		}

		$user_card 	= UserCard::find($cardid);
		if(!$user_card){
			return $this->error(__('卡号不存在!'));
		}
		if($user_card->uid != $uid){
			return $this->error('错误!');
		}

		$total 		= Goubi::userJifen($uid);
		if($total < $jine){
			return $this->error(__('金额不足!'));
		}

		$obj 			= new Withdraw;
		$obj->jine 		= $jine;
		$obj->cardid 	= $cardid;
		$obj->uid 		= $uid;
		$obj->money 	= $jine / 10;
		if($obj->save()){
			$gb 		= new Goubi;
			$gb->id 	= $uid;
			$gb->added 	= $obj->money * -1;
			$gb->status = -1;
			$gb->save();
			return $this->success(__('提现申请成功,预计到账金额 ' . $obj->money . ' 元!'));
		}
		return $this->error('提现失败,请联系客服!');
	}

	/**
	 * 提现记录
	 */
	public function withdraw(Request $request){
		$arr 		= Withdraw::list($request->get('_uid'));

		return $this->success($arr);
	}

	/**
	 * 任务列表
	 */
	public function tasks(Request $request){
		$uid 		= $request->get('_uid');
		$user 		= User::find($uid);
		$arr 		= Task::lists($user);

		return $this->success($arr);
	}
}
