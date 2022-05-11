<?php

namespace App\Http\Controllers\Sweepstake;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SweepstakeProduct;
use App\Models\SweepstakeChoose;
use App\Models\SweepstakeDay;
use App\Models\SweepstakePrize;
use App\Models\Active;
use App\Models\User;
use App\Models\Order;
use App\Models\Adv;
use App\Models\Goubi;
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
			return $this->error('请先登录!', null, 401);
		}
		$uid 		= $_SESSION['_uid'];

		$prizes 	= SweepstakeChoose::userPrize();
		$prizegl	= SweepstakeChoose::$gailv;
		$choosed 	= SweepstakeChoose::where('id', $uid)->pluck('probability', 'index')->toArray();
		foreach($choosed as $ind => $item){
			if(isset($prizegl[$ind])){
				$prizegl[$ind] 	= (int)$item;
			}
		}
		$binggo 		= SweepstakeChoose::get_rand($prizegl);

		$today 			= SweepstakeDay::getRow($uid);
		$today->times--;
		if($today->yunqi < 60){
			$today->yunqi 	+= rand(1,5);
		}
		$today->save();

		// 检查中奖产品产品
		if(!isset($prizes[$binggo])){
			return $this->error('抽奖错误,抽奖次数扣除!');
		}
		if($prizes[$binggo]['prize'] == 1){// 抽中代币
			SweepstakePrize::add($uid, $prizes[$binggo]);
			$gb 				= new Goubi;
			$gb->id 			= $uid;
			$gb->added 			= $prizes[$binggo]['val'];
			$gb->remark 		= '抽奖';
			$gb->created_at 	= date('Y-m-d H:i:s');
			$gb->save();
		}elseif($prizes[$binggo]['prize'] == 2){// 抽中商品
			SweepstakePrize::add($uid, $prizes[$binggo]);
		}
		return $this->success(['res' => $binggo, 'today' => $today]);
	}

	// 中自选商品的奖后设置收货地址
	public function address(Request $request){

	}

	// 选择产品
	public function product(Request $request){
		if(!isset($_SESSION['_uid']) || $_SESSION['_uid'] < 1){
			return $this->error('请先登录!', null, 401);
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

	// 看广告后的回调
	public function plaied(Request $request){
		if(!isset($_SESSION['_uid']) || $_SESSION['_uid'] < 1){
			return $this->error('请先登录!', null, 401);
		}
		$uid 	= $_SESSION['_uid'];

		$whatYouWantForAdv 	= $request->input('forwhat');
		$platform 			= $request->input('platform');
		$msg 				= '';
		if($whatYouWantForAdv == 1){// 增加运气值
			$msg 			= '哇,运气瞬间好了很多!';
			$today 			= SweepstakeDay::getRow($uid);
			if($today->yunqi < 90){
				if($today->yunqi < 30){
					$yunqi 			= rand(1, 5);
				}elseif($today->yunqi < 50){
					$yunqi 			= rand(1, 4);
				}elseif($today->yunqi < 80){
					$yunqi 			= rand(1, 3);
				}elseif($today->yunqi < 80){
					$yunqi 			= rand(1, 2);
				}else{
					$yunqi 			= 1;
				}
				$today->yunqi 	+= $yunqi;
				$today->save();
			}else{
				$msg 	= '差不多得了昂~~';
			}
		}elseif($whatYouWantForAdv == 2){
			$msg 			= '抽奖次数增加了';
			$today 			= SweepstakeDay::getRow($uid);
			$arr 			= [
				$today->yunqi,
				50
			];
			$index 			= SweepstakeChoose::get_rand($arr);
			$choujiangAdd 	= 1;
			if($index == 0){
				$choujiangAdd 	= rand(2,4);
				$msg 		= '哇,运气爆棚,瞬间多了 ' . $choujiangAdd . ' 次抽奖!';
			}
			$today->times 	+= $choujiangAdd;
			$today->save();
		}else{
			return $this->error('非法请求!');
		}

		$adv 			= new Adv;
		$adv->uid 		= $uid;
		$adv->type 		= 3;
		$adv->status 	= 0;
		$adv->platform 	= $platform;
		$adv->addtime 	= time();
		$adv->biadd 	= 0;
		$adv->save();
		return $this->success($today, $msg);
	}
}
