<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SweepstakeProduct extends Model{
	use HasFactory;
	public $timestamps = false;
	public static function list($page = 1, $limit = 10){
		$t1 	= 'products';
		$t2 	= 'sweepstake_products';
		$now 	= time();
		return self::select("$t1.title", "$t1.price", "$t1.sale", "$t1.cover", "$t1.main_sendout", "$t2.stocks")
			->rightJoin($t1, "$t1.id", '=', "$t2.id")
			->where("$t1.main_status", 1)->where("$t2.status", 1)
			->whereRaw("if($t2.start > 0, $t2.start <= $now, 1)")
			->whereRaw("if($t2.end > 0, $t2.end > $now, 1)")
			// ->orderByDesc("$t2.orderby")->orderByDESC("$t1.id")->limit($limit)->offset(($page-1)*$limit);
			->inRandomOrder()->limit($limit)->offset(($page-1)*$limit);
	}

	// 获取抽奖产品信息
	public static function row($pid){
		$t1 	= 'sweepstake_products';
		$t2 	= 'products';

		$row 	= self::select("$t1.start","$t1.end","$t1.stocks","$t1.probability","$t1.max_own","$t2.title","$t2.cover","$t2.images","$t1.id")
				->leftJoin("$t2", "$t1.id", '=', "$t2.id")
				->where("$t1.id", $pid)
				->where("$t1.status",1)->where("$t2.main_status", 1)->first();
		return $row;
	}
}
