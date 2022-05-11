<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SweepstakeProduct;

class SweepstakeChoose extends Model{
	use HasFactory;
	public $timestamps = false;
	public static $prizes = [[
			'title'		=> '谢谢',
			'text'		=> '谢谢参与',
			'prize'		=> false,
		],[
			'title'		=> '请选择商品',
			'proimg'	=> '/image/product.png'
		],[
			'title'		=> '10省币',
			'icon'		=> 'iconfont icon-jinbi2'
		],[
			'title'		=> '谢谢',
			'text'		=> '谢谢参与',
			'prize'		=> false,
		],[
			'title'		=> '请选择商品',
			'proimg'	=> '/image/product.png'
		],[
			'title'		=> '50省币',
			'icon'		=> 'iconfont icon-jinbi2'
		],[
			'title'		=> '谢谢',
			'text'		=> '谢谢参与',
			'prize'		=> false,
		],[
			'title'		=> '请选择商品',
			'proimg'	=> '/image/product.png'
		],
	];
	public static $gailv 	= [
		30,
		0,
		10,
		20,
		0,
		5,
		35,
		0
	];

	//获取当前用户奖品
	public static function userPrize($uid = null){
		if(!$uid){
			return self::$prizes;
		}
		$t1 	= 'sweepstake_chooses';
		$t2 	= 'sweepstake_products';
		$t3 	= 'products';
		$pros 	= self::select("$t1.probability", "$t2.start", "$t2.end", "$t3.title", "$t3.cover", "$t3.images", "$t1.index", "$t1.pid", "$t2.stocks")
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
		// dd($pros->toArray());
		foreach($pros as $item){
			if($item->stocks < 1){
				$outTimePros[]	= $item->pid;
			}else if(($item->start > 0 && $item->start > $now) || ($item->end > 0 && $item->end <= $now)){
				$outTimePros[]	= $item->pid;
			}elseif($item->stocks < 1){
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

	//添加用户选择的商品
	public static function addpro($product, $uid, $index){
		$obj 				= new self;
		$obj->id 			= $uid;
		$obj->pid 			= $product->id;
		$obj->index 		= $index;
		$obj->probability 	= $product->probability;
		if($obj->save()){
			$product->stocks 	= $product->stocks - 1;
			return $product->save();
		}
		return false;
	}

	//更新已选择的商品
	public static function updpro($product, $uid, $index){
		$row 		= self::where('id', $uid)->where('index', $index)->first();
		if(!$row){
			return false;
		}
		if($row->pid == $product->id){
			return false;
		}
		if(SweepstakeProduct::where('id', $product->id)->decrement('stocks')){
			SweepstakeProduct::where('id', $row->pid)->increment('stocks');
			return self::where('id', $uid)->where('index', $index)->update(['pid' => $product->id, 'probability' => $product->probability]);
		}
		return false;
	}

	// 概率中奖算法
	public static function get_rand($proArr) {
		$result = '';
		//概率数组的总概率精度
		$proSum = array_sum($proArr);
		//概率数组循环
		foreach ($proArr as $key => $proCur) {
			$randNum = mt_rand(1, $proSum);
			if ($randNum <= $proCur) {
				$result = $key;  //获得奖品的ID
				break;
			} else {
				$proSum -= $proCur;
			}   
		}
		return $result;
	}
}
