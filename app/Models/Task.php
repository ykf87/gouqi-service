<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Adv;
use App\Models\Config;

class Task extends Model{
    use HasFactory;

    /**
     * 获取当前用户可做的任务列表
     */
    public static function lists($user){
        $isadv          = Config::find(5);
        if($isadv && $isadv->status == 1 && $isadv->val != 'true'){
            return [];
        }

    	$obj 			= self::where('status', 1)->orderBy('sorts', 'DESC')->get();
    	$todayStart		= strtotime(date('Y-m-d 00:00:00'));
    	$todayEnd 		= $todayStart + 86399;

    	$advs 			= Adv::where('uid', $user->id)->whereBetween('addtime', [$todayStart, $todayEnd])->get();
    	$advarr 		= [];
    	foreach($advs as $item){
    		$advarr[$item->tid]		= isset($advarr[$item->tid]) ? ($advarr[$item->tid] + 1) : 1;
    	}

    	$arr 			= [];
        $max            = 100000;
    	foreach($obj as $index => $item){
    		if(self::fmtRule($item->rule, $user) !== true){
    			continue;
    		}

    		$item->times 			= $advarr[$item->id] ?? 0;
    		unset($item->rule);
    		unset($item->sorts);
    		unset($item->created_at);
    		unset($item->updated_at);
    		unset($item->status);
            $arrk                   = $index;
            if($item->times >= $item->max){
                $arrk               = $max--;
            }
    		$arr[$arrk] 					= $item->toArray();
    	}
        ksort($arr);
    	return array_values($arr);
    }

    /**
     * 解析rule
     */
    public static function fmtRule($rule, $user){
    	if(!$rule){
    		return true;
    	}
    	$rule 		= json_decode($rule, true);
    	if(!$rule || !is_array($rule)){
    		return true;
    	}

    	foreach($rule as $type => $item){
    		switch($type){
    			case 'user':
    				if(isset($item['created_at'])){
    					$ruleTj 	= strtotime($item['created_at']);
    					$ctime 		= strtotime($user->created_at);
    					if($ctime >= $ruleTj){
    						return false;
    					}
    				}
    			break;
    		}
    	}
    	return true;
    }
}
