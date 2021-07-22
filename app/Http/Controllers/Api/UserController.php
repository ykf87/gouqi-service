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

class UserController extends Controller{
	/**
	 * 用户登录
	 */
	public function login(Request $request){
		$arr 		= [];
		$phone 		= $request->input(User::$usernameKey);
		$pwd 		= $request->input('password');
		$isReg 		= $request->input('reg', 0);// 不存在是否直接注册
		if(!$phone || !$pwd){
			return $this->error(__('用户名或密码错误!'));
		}

		$user 		= User::where(User::$usernameKey, $phone)->first();
		if(!$user){
			if($isReg == 1){
				return $this->success(User::sigin($username, $pwd));
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
			$arr 		= User::sigin($phone, $pwd);
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
		$type 			= (int)$request->get('type', 1);
		if(!$type){
			$type 		= 1;
		}
		$last 			= Adv::where('uid', $uid)->orderBy('addtime', 'DESC')->first();
		if($last){
			if((time() - $last->addtime) < 10){
				return $this->error(__('请等待广告加载!'));
			}
		}
		if(!Adv::insert(['uid' => $uid, 'addtime' => time(), 'type' => $type])){
			return $this->error(__('广告添加失败!'));
		}

		if($type == 2){
			Adv::addGoubi($uid);
		}

		return $this->success([], __('奖励成功!'));
	}

	/**
	 * 积分记录
	 */
	public function jifen(Request $request){
		$arr 		= Goubi::list($request->get('_uid'));

		return $this->success($arr);
	}
}
