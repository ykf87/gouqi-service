<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

class UserController extends Controller{

	/**
	 * 登录注册统一接口,如果账号不存在自动注册!
	 */
	public function loginSignup(Request $request){
		$phone 		= trim($request->input('phone', ''));
		$password 	= trim($request->input('password', ''));

		if(!$phone || !$password){
			return $this->error('非法用户!');
		}

		$user 		= User::where('phone', $phone)->first();
		if(!$user){
			$user 					= new User;
			$user->phone 			= $phone;
			$user->pwd 				= password_hash($password, PASSWORD_DEFAULT);
			$user->remember_token 	= rand(1, 10000);
			if(!$user->save()){
				return $this->error('账号注册失败!');
			}
			$user 				= User::find($user->id);
		}else if(!password_verify($password, $user->pwd)){
			return $this->error('密码错误!');
		}elseif($user->status != 1){
			return $this->error('您的账号被禁用!');
		}else{
			$user->remember_token 	= rand(1, 10000);
			if(!$user->save()){
				return $this->success($user, '登录成功!');
			}
		}

		$keys 			= array_flip(User::$returnKey);
		$arr 			= array_intersect_key($user->toArray(), $keys);
		$arr['token']	= User::token($arr);
		return $this->success($arr);
	}
}
