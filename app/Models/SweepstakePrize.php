<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SweepstakePrize extends Model{
	use HasFactory;

	public static function add($uid, $prize){
		$obj 		= new self;
		$obj->uid 	= $uid;
		$obj->title = $prize['title'];
		if(isset($prize['id'])){
			$obj->pid 	= $prize['id'];
		}
		return $obj->save();
	}
}
