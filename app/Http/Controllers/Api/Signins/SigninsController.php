<?php

namespace App\Http\Controllers\Api\Signins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\SiginTask;

class SigninsController extends Controller{
	//签到首页
	public function signed(Request $request){
		$uid 		= $request->get('_uid');
		$user 		= User::select('id', 'name as nickname', 'sex', 'level', 'avatar')->find($uid);
		if(!$user){
			return $this->error('找不到用户!');
		}
		$taskProducts 		= SiginTask::siginInfo($uid);
		$sigins 			= SiginLog::sigined($taskProducts);
		
		$arr 		= [
			'user'		=> $user,
			'signed'	=> $taskProducts,
			'issigin'	=> false,
			'advs'		=> 0,
			'advs'		=> 0,
		];
		return $this->success($arr);
	}
}
