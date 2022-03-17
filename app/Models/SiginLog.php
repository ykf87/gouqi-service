<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiginLog extends Model{
    use HasFactory;
    public $timestamps = false;

    public static function sigined($sigintask){
    	if(!$sigintask){
    		return [];
    	}
    	$start 		= $sigintask['startat'];
    	$mustDays	= $sigintask['mustdays'];
    	$sigined 	= self::where('sigin_task_id', $sigintask['id'])->orderByDesc('id')->get()->toArray();
    	for($i = $mustdays; $i > 0; $i--){

    	}
    	return self::where('sigin_task_id', $sigintask['id'])->orderByDesc('id')->get()->toArray();
    }
}
