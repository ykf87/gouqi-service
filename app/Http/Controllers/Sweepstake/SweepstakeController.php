<?php

namespace App\Http\Controllers\Sweepstake;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SweepstakeProduct;
use App\Models\SweepstakeChoose;
use App\Models\SweepstakeDay;
use App\Models\Active;
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
		$uid 	= $_SESSION['_uid'] ?? null;
		$res 	= SweepstakeProduct::list();
		$prize 	= SweepstakeChoose::userPrize($uid);
		$info 	= Active::find(1);
		$today 	= [];

		if($uid){
			$today 	= SweepstakeDay::getRow($uid);
		}

		$getedObj 		= Order::select('name', 'pro_title')->orderByDesc('ctype')->orderByDesc('id')->limit(20)->get();
		$geted 			= [];
		foreach($getedObj as $item){
			$geted[] 	= '恭喜 ' . mb_substr($item->name, 0, 1, 'utf-8') . '** 抽中 ' . $item->pro_title;
		}
		return view('default.Sweepstake.index', ['prize' => $prize, 'geted' => $geted, 'today' => $today, 'info' => $info]);
	}

	// 获取中奖结果
	public function prize(Request $request){
		if(!isset($_SESSION['_uid']) || $_SESSION['_uid'] < 1){
			return $this->error('请先登录!');
		}
		$uid 		= $_SESSION['_uid'];

		$prizegl	= SweepstakeChoose::$gailv;
		$choosed 	= SweepstakeChoose::where('id', $uid)->pluck('probability', 'index')->toArray();
		foreach($choosed as $ind => $item){
			if(isset($prizegl[$ind])){
				$prizegl[$ind] 	= (int)$item;
			}
		}
		$binggo 		= SweepstakeChoose::get_rand($prizegl);
		// var_dump($binggo);
		// dd($prizegl);
		return $this->success($binggo);
	}

	// 选择产品
	public function product(Request $request){
		if(!isset($_SESSION['_uid']) || $_SESSION['_uid'] < 1){
			return $this->error('请先登录!');
		}
		$uid 	= $_SESSION['_uid'];
		$pid 	= (int)$request->input('id', 0);
		$index 	= (int)$request->input('index', -1);

		if($pid < 1 || $index < 0){
			return $this->error('参数错误!');
		}

		$product 	= SweepstakeProduct::row($pid);
		if(!$product){
			return $this->error('产品不存在!');
		}
		$now 		= time();
		if($product->start > 0 && $product->start > $now){
			return $this->error('产品还未开始!');
		}
		if($product->end > 0 && $product->end <= $now){
			return $this->error('您选的产品已经过期!');
		}
		if($product->stocks < 1){
			return $this->error('您选的商品库存不足!');
		}

		// 检查是否超过允许的次数
		if($product->max_own > 0){
			$getTimes 	= Order::where('user_id', $uid)->where('product_id', $pid)
							->where('ctype', 2)->where('status', '>', -1)->count();
			if($getTimes >= $product->max_own){
				return $this->error('您已经拥有过该产品!');
			}
		}

		$prize 	= SweepstakeChoose::userPrize($uid);
		if(!isset($prize[$index])){// 防止index越界
			return $this->error('非法请求!');
		}

		$choosed 		= SweepstakeChoose::where('id', $uid)->pluck('pid', 'index')->toArray();
		if(!isset($choosed[$index])){
			if(count($choosed) > 2){
				return $this->error('您太贪心了~~');
			}
			if(SweepstakeChoose::addpro($product, $uid, $index)){
				$img 				= $product->cover;
				if(!$img){
					$img 			= explode(',', $product->images)[0];
				}
				$prize[$index]		= ['title' => $product->title, 'proimg' => $img, 'id' => $pid];
				return $this->success($prize, '产品选择成功!');
			}
			return $this->error('出错了,请联系我们!');
		}

		if(SweepstakeChoose::updpro($product, $uid, $index)){
			$img 				= $product->cover;
			if(!$img){
				$img 			= explode(',', $product->images)[0];
			}
			$prize[$index]		= ['title' => $product->title, 'proimg' => $img, 'id' => $pid];
			return $this->success($prize, '产品更换成功!');
		}
		return $this->error('出错了,请联系我们!');
		// // 如果原来有商品被覆盖,则原商品需要加库存
		// if(isset($prize[$index]['id'])){
		// 	$oriPro 	= SweepstakeProduct::find($prize[$index]['id']);
		// 	$oriPro->stocks++;
		// 	$oriPro->save();
		// }
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
