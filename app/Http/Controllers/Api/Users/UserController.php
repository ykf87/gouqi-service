<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller{
	private $usernameKey 	= 'email';
	/**
	 * 用户注册
	 */
	public function login(Request $request){
		$arr 		= [];
		$phone 		= $request->input($this->usernameKey);
		$pwd 		= $request->input('password');
		$isReg 		= $request->input('reg', 0);

		$user 		= self::where('telphone', $phone)->first();
		if(!$user){
			if($isReg == 1){
				
			}else{
				$arr['code']	= 404;
				$arr['msg']		= __('用户不存在,请先注册!');
			}
		}

		return $this->success($arr);
	}

	/**
	 * 用户登录
	 */
	public function sigin(Request $request){
		$phone 		= $request->input($this->usernameKey);
		$pwd 		= $request->input('password');

		$user 		= self::where('telphone', $phone)->first();
		if(!$user){

		}
		$arr 		= [];

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
		$arr 		= [];

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
