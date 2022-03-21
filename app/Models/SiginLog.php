<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiginLog extends Model{
    use HasFactory;
    public $timestamps = false;

    // 获取签到信息
    public static function sigined($task){
    	if(!$task){
    		return false;
    	}
    	$start 		= strtotime(date('Y-m-d 00:00:00', $task->startat));
		$startUni 	= strtotime(date('Y-m-d', $start));
    	$mustDays	= $task->mustdays;
    	$sigined 	= self::where('sigin_task_id', $task->id)->orderBy('index')->orderBy('id')->get()->toArray();

    	$yiqiandao	= 0;//已签到
		$duanqian 	= 0;//断开签到
		$weiqian 	= 0;//还需签到
		$isReset 	= false;//任务是否重置
		$todaySigin	= false;//今日是否签到
		$todayStart = strtotime(date('Y-m-d'));
		$todayEnd 	= $todayStart + 86399;
		$todaySign 	= false;
		$needDay 	= $task->mustdays;
		for($i = 0; $i < $needDay; $i++){
		    $dayStart 	= $startUni + $i * 86400;
		    $dayEnd 	= $dayStart + 86399;
		    if($dayEnd > $todayEnd){
		    	$weiqian 	= $needDay - $i + $weiqian;
		    	break;
		    }
		    $issigind 	= false;
		    foreach($sigined as $k => $item){
		    	if($item['sigin_time'] <= $dayEnd && $item['sigin_time'] >= $dayStart){
		    		if($item['sigin_time'] <= $todayEnd && $item['sigin_time'] >= $todayStart){
		    			$todaySign 	= true;
		    		}
		    		$issigind 	= true;
		    		break;
		    	}
		    }
		    if($issigind == true){
		        $yiqiandao++;
		        echo date('Y-m-d', $dayStart) . "<br>";
		    }else{
		    	if($todayEnd <= $dayEnd){
			    	$weiqian++;
			    }else{
			        $duanqian++;
			    }
			}
			if($duanqian > 1){
				$isReset 	= true;
				$todaySign 	= false;
				break;
			}
		}
		if($isReset === true){
			$task->status 	= -2;
			$task->save();
			return false;
		}else{
			$arr 		= [
				'days'		=> $yiqiandao,
				'issigin'	=> $todaySign,
				'mustadv'	=> $duanqian > 0 ? true : false,
				'mustdays'	=> $needDay,
			];
			return $arr;
		}
    }
}
