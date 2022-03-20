<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiginLog extends Model{
    use HasFactory;
    public $timestamps = false;

    public static function sigined($task){
    	if(!$task){
    		return false;
    	}
    	$start 		= strtotime(date('Y-m-d 00:00:00', $task->startat));
    	$mustDays	= $task->mustdays;
    	$sigined 	= self::where('sigin_task_id', $task->id)->orderByAsc('index')->get();
        $yiqiandao  = 0;
        $duanqian   = 0;
    	foreach($sigined as $item){
            $st     = $item->sigin_time;
            if($st >= $start && $st < ($start+86400)){
                $yiqiandao++;
            }else{
                $duanqian++;
            }
            if($duanqian > 2){
                break;
            }
            $start  += 86400;
        }
    	return self::where('sigin_task_id', $task->id)->orderByDesc('id')->get()->toArray();
    }
}
