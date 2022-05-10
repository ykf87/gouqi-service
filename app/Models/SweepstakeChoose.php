<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Models\Prize;

class SweepstakeChoose extends Model{
	use HasFactory;
	public $timestamps = false;
	public static $prizes = [[
			'title'		=> '谢谢',
			'text'		=> '谢谢参与',
		],[
			'title'		=> '请选择商品',
			'proimg'	=> '/image/product.png'
		],[
			'title'		=> '10省币',
			'icon'		=> 'iconfont icon-jinbi2'
		],[
			'title'		=> '谢谢',
			'text'		=> '谢谢参与',
		],[
			'title'		=> '请选择商品',
			'proimg'	=> '/image/product.png'
		],[
			'title'		=> '50省币',
			'icon'		=> 'iconfont icon-jinbi2'
		],[
			'title'		=> '谢谢',
			'text'		=> '谢谢参与',
		],[
			'title'		=> '请选择商品',
			'proimg'	=> '/image/product.png'
		],
	];

	//获取当前用户奖品
	public static function userPrize($uid = null){
		if(!$uid){
			return self::$prizes;
		}
		$t1 	= 'sweepstake_chooses';
		$t2 	= 'sweepstake_products';
		$t3 	= 'products';
		$pros 	= self::select("$t1.probability", "$t2.start", "$t2.end", "$t3.title", "$t3.cover", "$t3.images", "$t1.index", "$t1.pid", "$t2.stocks", "$t3.main_stock")
				->leftJoin($t2, "$t1.pid", '=', "$t2.id")
				->leftJoin($t3, "$t2.id", '=', "$t3.id")
				->where("$t1.id", $uid)
				->where("$t2.status", 1)->where("$t3.main_status", 1)
				->limit(3)->get();
		$outTimePros 	= [];
		$now 			= time();

		if(!$pros){
			return self::$prizes;
		}
		$userPrize 		= self::$prizes;
		foreach($pros as $item){
			if($item->stock < 1){
				$outTimePros[]	= $item->pid;
			}else if($item->start > $now || $item->end <= time()){
				$outTimePros[]	= $item->pid;
			}elseif($item->stock < 1 || $item->main_stock < 1){
				$outTimePros[]	= $item->pid;
			}else{
				$img 			= $item->cover;
				if(!$img){
					$img 		= explode(',', $item->images)[0];
				}
				$userPrize[$item->index] 	= [
					'title'		=> $item->title,
					'proimg'	=> $img,
					'id'		=> $item->pid,
				];
			}
		}
		return $userPrize;
	}
}
