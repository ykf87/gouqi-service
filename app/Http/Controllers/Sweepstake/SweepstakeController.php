<?php

namespace App\Http\Controllers\Sweepstake;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SweepstakeProduct;
use App\Models\SweepstakeChoose;
use App\Models\User;
use App\Models\Order;
use Lcobucci\JWT\Token\Plain;
session_start();

class SweepstakeController extends Controller{
	public function index(Request $request){
		$token 	= $request->get('token');
		if($token){
			$jwt        = User::decry($token);
			if($jwt instanceof Plain){
                $id         = $jwt->claims()->get('id');
                if($id > 0){
                	$_SESSION['_uid']	= $id;
                	header('Location:' . route('sweepstake.index'));
                	exit();
                }
            }
		}
		$res 	= SweepstakeProduct::list();
		$prize 	= SweepstakeChoose::userPrize($_SESSION['_uid'] ?? null);

		$getedObj 		= Order::select('name', 'pro_title')->orderByDesc('id')->limit(20)->get();
		$geted 			= [];
		foreach($getedObj as $item){
			$geted[] 	= '恭喜 ' . mb_substr($item->name, 0, 1, 'utf-8') . '** 抽中 ' . $item->pro_title;
		}
		return view('default.Sweepstake.index', ['prize' => $prize, 'geted' => $geted]);
	}

	// 获取中奖结果
	public function prize(Request $request){

	}

	// 选择产品
	public function product(Request $request){
		$uid 	= $_SESSION['_uid'];
		if($uid < 1){
			return $this->error('请先登录!');
		}
	}

	// 商品列表
	public function products(Request $request){
		$now 	= time();
		$page 	= (int)$request->get('page', 1);
		$limit 	= (int)$request->get('limit', 10);
		if($page < 1){
			$page 	= 1;
		}
		if($limit < 1){
			$limit 	= 10;
		}
		$t1		= 'sweepstake_products';
		$t2 	= 'products';
		$arr 	= [];
		$res 	= SweepstakeProduct::select("$t2.id","$t2.cover","$t2.images","$t2.title","$t1.probability","$t2.main_sendout as sendout","$t1.max_own","$t2.sale","$t1.stocks")
						->rightJoin("$t2", "$t1.id", '=', "$t2.id")
						->where("$t1.status", 1)
						->where("$t2.main_status", 1)
						->whereRaw("if($t1.start > 0, start <= $now, 1)")
						->whereRaw("if($t1.end > 0, end > $now, 1)")
						->orderByDesc("$t1.orderby")->orderByDESC("$t2.id")->limit($limit)->offset(($page-1)*$limit)->get();
		if(count($res) > 0){
			$arr['list']	= $res;
		}else{
			return $this->error('暂无商品!');
		}
		return $this->success($arr);
	}
}
