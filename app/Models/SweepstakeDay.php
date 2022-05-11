<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SweepstakeDay extends Model{
	use HasFactory;
	public $timestamps = false;

	public static function getRow($uid){
		$row 	= self::find($uid);
		$today 	= date('Ymd');
		if(!$row){
			$row 		= new self;
			$row->id 	= $uid;
			$row->ymd 	= $today;
			$row->yunqi 	= rand(0,5);
			$row->save();
		}else if($row->ymd != $today){
			$row->ymd 		= $today;
			$row->videos 	= 0;
			$row->yunqi 	= rand(0, 5);
			$row->times 	= 1;
			$row->save();
		}
		return $row;
	}
}
