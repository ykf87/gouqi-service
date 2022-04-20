<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Adv;
use App\Models\Goubi;

class AdbsCallbackController extends Controller{
	public function xinliangxiang(Request $request){
		$appSec 	= 'FYJo5V7G83U7C2POcBMlbyGkrW3s1hHu';
		$time 		= $request->input('time');
		$sign 		= $request->input('sign');
		if(!$time || !$sign){
			return response()->json([], 401);
		}
		$md5 		= md5($appSec . $time);
		if($md5 != $sign){
			return response()->json([], 401);
		}

		$uid 		= (int)$request->input('userId', 0);
		$extra 		= $request->input('extra');
		$type 		= '';
		$tid 		= 0;
		if($extra){
			$arr 		= json_decode($extra, true);
			if(!$uid){
				$uid = $arr['userId'] ?? null;
			}
			$type 		= $arr['type'] ?? null;
			$tid 		= $arr['tid'] ?? null;
			$tid 		= (int)$tid;
		}
		if(!$uid || !$type || !$tid){
			return response()->json([], 400);
		}
		$respArr	= ['code'=>200, 'msg'=>'success', 'data'=>true];

		if($type != 'default'){
			return response()->json($respArr);
		}

		//任务写入处理
		$task 		= Task::find($tid);
		if(!$task || $task->status < 1){
			return response()->json($respArr);
		}
		$tagId 		= $request->input('tagId');
		$stepNum 	= $request->input('stepNum');
		$icpm 		= (float)$request->input('icpm', 0);

		$todayStart		= strtotime(date('Y-m-d 00:00:00'));
    	$todayEnd 		= $todayStart + 86399;
		$times 			= Adv::where('uid', $uid)->where('tid', $tid)->whereBetween('addtime', [$todayStart, $todayEnd])->count();
		$userGet 		= intval($icpm / 10 * 0.4);
		if($userGet < 1){
			$userGet 	= 1;
		}

		$advObj 			= new Adv;
		$advObj->uid 		= $uid;
		$advObj->addtime 	= time();
		$advObj->tid 		= $tid;
		$advObj->status		= 0;
		$advObj->type 		= 1;
		$advObj->cpm 		= $icpm;
		$advObj->biadd 		= $userGet;
		$advObj->tagId 		= $tagId;
		$advObj->save();

		if($times >= $task->max){
			return response()->json($respArr);
		}

		$times++;
		$add 			= 0;
		if($task->min <= $times){
			$jifenHistory 		= Goubi::where('id', $uid)->where('tid', $tid)->whereBetween('created_at', [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')])->count();
			$maxTimes 			= ceil($task->max / $task->min);
			if($jifenHistory >= $maxTimes){
				return response()->json($respArr);
			}else{
				$tm 			= date('Y-m-d H:i:s');
				$add 			= $task->prize;
				Goubi::insert(['id' => $uid, 'tid' => $tid, 'added' => $userGet, 'created_at' => $tm, 'updated_at' => $tm, 'advid' => $advObj->id, 'tagid' => $tagId]);
			}
		}
		return response()->json($respArr);
	}
}
