<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller{
	/**
	 * 用户注册
	 */
	public function sigin(Request $request){
		$arr 		= [];

		return $this->success($arr);
	}

	/**
	 * 用户登录
	 */
	public function login(Request $request){
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
