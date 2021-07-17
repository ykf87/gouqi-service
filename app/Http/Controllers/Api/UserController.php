<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Heart;

class UserController extends Controller{
	private $usernameKey 	= 'email';
	/**
	 * 用户登录
	 */
	public function login(Request $request){
		$arr 		= [];
		$phone 		= $request->input($this->usernameKey);
		$pwd 		= $request->input('password');
		$isReg 		= $request->input('reg', 0);// 不存在是否直接注册
		if(!$phone || !$pwd){
			return $this->error(__('用户名或密码错误!'));
		}

		$user 		= User::where('telphone', $phone)->first();
		if(!$user){
			if($isReg == 1){
				return $this->success(User::sigin($username, $pwd));
			}else{
				return $this->errro(__('用户不存在,请先注册!'));
			}
		}
		$arr 		= User::login($user);

		return $this->success($arr);
	}

	/**
	 * 用户注册
	 */
	public function sigin(Request $request){
		$phone 			= $request->input($this->usernameKey);
		$pwd 			= $request->input('password');

		if(empty($phone) || empty($pwd)){
			return $this->error(__('用户名或密码错误!'));
		}

		$user 			= User::where('telphone', $phone)->first();
		if($user){
			if(password_verify($pwd, $user->password)){
				$this->error(__('用户已存在!'));
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
	 * 用户收藏
	 */
	public function watch(Request $request){
		$arr 		= Heart::list($request->get('_uid'));

		return $this->success($arr);
	}

	/**
	 * 历史记录
	 */
	public function history(Request $request){
		$arr 		= [];

		return $this->success($arr);
	}

	/**
	 * 添加收藏
	 */
	public function heart(Request $request){
		$arr 		= [];

		return $this->success($arr);
	}

	/**
	 * 广告播放完成回调
	 */
	public function palied(Request $request){
		$arr 			= [];

		return $this->success($arr);
	}
}
