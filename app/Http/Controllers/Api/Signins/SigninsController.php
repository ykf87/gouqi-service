<?php

namespace App\Http\Controllers\Api\Signins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\SiginTask;
use App\Models\SiginLog;

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
}
