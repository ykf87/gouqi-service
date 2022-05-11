<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Heart;
use App\Models\History;
use App\Models\Adv;
use App\Models\Post;
use App\Models\Goubi;
use App\Models\Withdraw;
use App\Models\UserCard;
use App\Models\Bank;
use App\Models\Task;
use App\Models\Address;
use App\Models\Order;

class UserController extends Controller{
	/**
	 * 用户数据返回
	 */
	public function index(Request $request){
		$uid 		= $request->get('_uid');

		$user 		= User::find($uid);
		if($user){
			$arr 		= User::users($user, false);
		}else{
			$arr 		= [];
		}
		return $this->success($arr);
	}

	/**
	 * 用户登录
	 */
	public function login(Request $request){
		$arr 		= [];
		$phone 		= $request->input(User::$usernameKey);
		$pwd 		= $request->input('password');
		$isReg 		= $request->input('reg', 0);// 不存在是否直接注册
		$name 		= trim($request->input('name', ''));
		if(!$phone || !$pwd){
			return $this->error(__('用户名或密码错误!'));
		}

		$user 		= User::where(User::$usernameKey, $phone)->first();
		if(!$user){
			if($isReg == 1){
				return $this->success(User::sigin($username, $pwd, $name));
			}else{
				return $this->error(__('用户不存在,请先注册!'));
			}
		}elseif(!password_verify($pwd, $user->pwd)){
			return $this->error(__('用户名或密码错误!'));
		}
		$arr 		= User::login($user);

		return $this->success($arr);
	}

	/**
	 * 用户注册
	 */
	public function sigin(Request $request){
		$phone 			= $request->input(User::$usernameKey);
		$pwd 			= $request->input('password');
		$name 			= trim($request->input('name', ''));

		if(empty($phone) || empty($pwd)){
			return $this->error(__('用户名或密码错误!'));
		}

		$user 			= User::where(User::$usernameKey, $phone)->first();
		if($user){
			if(!password_verify($pwd, $user->pwd)){
				return $this->error(__('用户已存在!'));
			}
			return $this->success(User::users($user));
		}else{
			$arr 		= User::sigin($phone, $pwd, $name);
		}

		return $this->success($arr);
	}

	/**
	 * 密码重置
	 */
	public function reset(Request $request){
		$arr 		= [];

		return $this->success($arr);
	}

	/**
	 * 用户收藏列表
	 */
	public function watch(Request $request){
		$arr 		= Heart::list($request->get('_uid'));

		return $this->success($arr);
	}

	/**
	 * 浏览记录
	 */
	public function history(Request $request){
		$arr 		= History::list($request->get('_uid'));

		return $this->success($arr);
	}

	/**
	 * 添加收藏
	 */
	public function heart(Request $request){
		$pid 		= (int)$request->input('id', 0);
		$uid 		= $request->get('_uid', 0);

		if($pid < 1){
			return $this->error(__('请选择文章!'));
		}

		$rs         = Heart::where('id', $uid)->where('pid', $pid)->first();
		if($rs){
			if(Heart::where('id', $uid)->where('pid', $pid)->delete()){
				Post::find($pid)->decrement('hearted');
				return $this->success([], __('成功取消收藏!'));
			}else{
				return $this->error(__('取消失败!'));
			}
		}
		if(Heart::insert(['id' => $uid, 'pid' => $pid, 'addtime' => time()])){
			Post::find($pid)->increment('hearted');
			return $this->success([], __('添加成功!'));
		}

		return $this->error(__('添加失败!'));
	}

	/**
	 * 广告播放完成回调
	 */
	public function palied(Request $request){
		$uid 			= $request->get('_uid');
		$tid 			= $request->input('tid');
		$tagid 			= $request->input('tagid');

		$todayStart		= strtotime(date('Y-m-d 00:00:00'));
    	$todayEnd 		= $todayStart + 86399;
		if($tagid){
			$goubi 		= Goubi::where('tagid', $tagid)->first();
			if(!$goubi){
				return $this->error('未找到广告!');
			}
			if($tid != $goubi->tid){
				return $this->error('非法请求.');
			}
			$task 		= Task::find($goubi->tid);
			if(!$task){
				return $this->error('非法请求');
			}
			$times 		= Adv::where('uid', $uid)->where('tid', $tid)->whereBetween('addtime', [$todayStart, $todayEnd])->count();
			$rarr 		= [
				'task' => [
					'id' 	=> $task->id,
					'title' => $task->title,
					'max' 	=> $task->max,
					'min' 	=> $task->min,
					'prize' => $goubi->added,
					'times' => $times,
				],
				'jifen' 	=> $goubi->added
			];
			return $this->success($rarr, __('广告播放成功!!'));
		}
		// $type 			= (int)$request->get('type', 1);
		// if(!$type){
		// 	$type 		= 1;
		// }
		// $last 			= Adv::where('uid', $uid)->orderBy('addtime', 'DESC')->first();
		// if($last){
		// 	if((time() - $last->addtime) < 10){
		// 		return $this->error(__('请等待广告加载!'));
		// 	}
		// }
		// if(!Adv::insert(['uid' => $uid, 'addtime' => time(), 'type' => $type])){
		// 	return $this->error(__('广告添加失败!'));
		// }
		// if($type == 2){
		// 	Adv::addGoubi($uid);
		// }


		$task 		= Task::find($tid);
		if(!$task){
			return $this->error('非法请求');
		}

		//获取最后一次广告增益数据
		$last 		= Adv::where('uid', $uid)->orderByDesc('id')->first();
		if($last && (time() - $last->addtime) <= 10){
			return $this->error('请求太频繁!');
		}

		$times 			= Adv::where('uid', $uid)->where('tid', $tid)->whereBetween('addtime', [$todayStart, $todayEnd])->count();
		// $advObj 		= Adv::insert(['uid' => $uid, 'addtime' => time(), 'tid' => $tid, 'status' => 0, 'type' => 1, 'biadd' => 1]);
		$advObj 			= new Adv;
		$advObj->uid 		= $uid;
		$advObj->addtime 	= time();
		$advObj->platform 	= $task->platform;
		$advObj->tid 		= $tid;
		$advObj->status		= 0;
		$advObj->type 		= 1;
		$advObj->biadd 		= 1;
		$advObj->save();

		if($times >= $task->max){
			return $this->error('任务已完成!');
		}

		$times++;
		$add 			= 0;
		$msg 			= '奖励发放成功!';
		if($task->min <= $times){
			$jifenHistory 		= Goubi::where('id', $uid)->where('tid', $tid)->whereBetween('created_at', [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')])->count();
			$maxTimes 			= ceil($task->max / $task->min);
			if($jifenHistory >= $maxTimes){
				return $this->error('任务已完成.');
			}else{
				$tm 			= date('Y-m-d H:i:s');
				$add 			= $task->prize;
				Goubi::insert(['id' => $uid, 'tid' => $tid, 'added' => $add, 'created_at' => $tm, 'updated_at' => $tm, 'advid' => $advObj->id]);
			}
		}else{
			$cha 	= $task->min - $times;
			$msg 	= '再完成 ' . $cha . ' 次获得 ' . $task->prize . ' 币';
		}

		$rarr 		= [
			'task' => [
				'id' 	=> $task->id,
				'title' => $task->title,
				'max' 	=> $task->max,
				'min' 	=> $task->min,
				'prize' => $task->prize,
				'times' => $times,
			],
			'jifen' 	=> $add
		];
		return $this->success($rarr, $msg);
		// return $this->success([], __('奖励成功!'));
	}

	/**
	 * 积分记录
	 */
	public function jifen(Request $request){
		$arr 		= Goubi::list($request->get('_uid'));
		foreach($arr as &$item){
			if(!$item->remark){
				$item->remark 	= '获得积分';
			}
			if($item->added > 0){
				$item->added 	= '+' . $item->added;
			}
		}
		return $this->success($arr);
	}

	/**
	 * 银行列表
	 */
	public function bank(){
		$arr 		= Bank::list();
		return $this->success($arr);
	}

	/**
	 * 我的银行卡列表
	 */
	public function mycard(Request $request){
		$arr 		= UserCard::list($request->get('_uid'));
		return $this->success($arr);
	}

	/**
	 * 添加银行卡
	 */
	public function card(Request $request){
		$id 			= (int)$request->input('id', 0);
		$name 			= trim($request->input('name', ''));
		$phone 			= trim($request->input('telphone', ''));
		$bankName 		= $request->input('type');
		$number 		= trim($request->input('number', ''));
		$uid 			= $request->get('_uid');

		if(!$name || mb_strlen($name, 'utf-8') > 16){
			return $this->error(__('请填写真实姓名!'));
		}
		if(!is_numeric($phone) || strlen($phone) != 11){
			return $this->error(__('请填写电话!'));
		}
		$number 		= str_replace(' ', '', $number);
		if(!is_numeric($number)){
			return $this->error(__('请正确填写银行卡号!'));
		}
		if(!$bankName){
			return $this->error('请选择银行!');
		}

		// 判断是否是选择银行
		if(!is_numeric($bankName)){
			$bankObj 	= Bank::where('name', $bankName)->first();
			if(!$bankObj){
				return $this->error('暂不支持 ' . $bankName . ' 提现!');
			}
			$bankName 	= $bankObj->id;
		}else{
			$bankObj 	= Bank::find($bankName);
			if(!$bankObj){
				return $this->error('请选择银行!');
			}
		}


		if($id < 1){
			$count 			= UserCard::where('uid', $uid)->count();
			if($count >= User::$maxCard){
				return $this->error(__('银行卡不允许超过' . User::$maxCard . '张!'));
			}
			$obj 			= new UserCard;
			$obj->uid 		= $uid;
			$obj->name 		= $name;
			$obj->phone 	= $phone;
			$obj->number 	= $number;
			$obj->type 		= $bankName;
			if($obj->save()){
				return $this->success(__('银行卡添加成功!'));
			}
			return $this->error(__('添加失败,请联系客服!'));
		}else{
			$obj 			= UserCard::find($id);
			if(!$obj){
				return $this->error(__('找不到记录!'));
			}
			if($obj->uid != $uid){
				return $this->error(__('非法请求!'));
			}
			$obj->name 		= $name;
			$obj->phone 	= $phone;
			$obj->number 	= $number;
			$obj->type 		= $bankName;
			if($obj->save()){
				return $this->success(__('更新成功!'));
			}
			return $this->error(__('更新失败!'));
		}
	}

	/**
	 * 提现申请
	 */
	public function tixian(Request $request){
		$jine 		= (float)$request->input('jine', 0.0);
		$cardid 	= (int)$request->input('cardid', 0);
		$uid 		= $request->get('_uid');
		if($jine < User::$minTixian){
			return $this->error(__('金额不能小于'.User::$minTixian.'元'));
		}
		if($cardid < 1){
			return $this->error(__('请选择提现银行卡!'));
		}

		$user_card 	= UserCard::find($cardid);
		if(!$user_card){
			return $this->error(__('卡号不存在!'));
		}
		if($user_card->uid != $uid){
			return $this->error('错误!');
		}

		$total 		= Goubi::userJifen($uid);
		if($total < $jine){
			return $this->error(__('金额不足!'));
		}

		$obj 			= new Withdraw;
		$obj->jine 		= $jine;
		$obj->cardid 	= $cardid;
		$obj->uid 		= $uid;
		$obj->money 	= $jine / 10;
		if($obj->save()){
			$gb 		= new Goubi;
			$gb->id 	= $uid;
			$gb->added 	= $obj->money * -1;
			$gb->status = -1;
			$gb->remark = "提现";
			$gb->save();
			return $this->success(__('提现申请成功,预计到账金额 ' . $obj->money . ' 元!'));
		}
		return $this->error('提现失败,请联系客服!');
	}

	/**
	 * 提现记录
	 */
	public function withdraw(Request $request){
		$arr 		= Withdraw::list($request->get('_uid'));

		return $this->success($arr);
	}

	/**
	 * 任务列表
	 */
	public function tasks(Request $request){
		$uid 		= $request->get('_uid');
		$user 		= User::find($uid);
		$arr 		= Task::lists($user);

		return $this->success($arr);
	}

	/**
	 * 用户注销
	 * 17700000001
	 * 123456
	 */
	public function logout(Request $request){
		$uid 		= $request->get('_uid');
		$pwd 		= $request->input('password');
		if(!$pwd){
			return $this->error('请填写密码!');
		}

		$user 		= User::find($uid);
		if(!$user){
			return $this->error('账号不存在');
		}
		if(!password_verify($pwd, $user->pwd)){
			return $this->error('密码错误!');
		}
		// if(User::where('id', $uid)->delete()){
		if($user->delete()){
			return $this->success(__('注销成功'));
		}else{
			return $this->error('错误!');
		}
	}

	/**
	 * 收货地址列表
	 */
	public function addresses(Request $request){
		$uid 		= $request->get('_uid');
		$list 		= Address::where('uid', $uid)->orderByDesc('isdefault')->get();

		return $this->success(['list' => $list]);
	}

	/**
	 * 添加收货地址
	 */
	public function address(Request $request){
		$uid 		= $request->get('_uid');
		$added 		= Address::where('uid', $uid)->count();
		if($added >= Address::$maxAdd){
			return $this->error('最多允许添加 ' . Address::$maxAdd . ' 个地址.');
		}

		$name 		= trim($request->input('name', ''));
		$tel 		= trim($request->input('phone', ''));
		$addr 		= trim($request->input('address', ''));
		$default	= (int)$request->input('default', 0);
		if(!$name || !$tel || !$addr){
			return $this->error('信息不完整!');
		}

		$address 		= new Address;
		$address->uid 	= $uid;
		$address->name 	= $name;
		$address->tel 	= $tel;
		$address->address 	= $addr;
		$address->isdefault = $default == 1 ? 1 : 0;
		if($address->save()){
			return $this->success(null, '添加成功!');
		}
		return $this->error('添加失败!');
	}

	/**
	 * 删除收货地址
	 */
	public function rmvaddr(Request $request){
		$uid 		= $request->get('_uid');
		$id 		= (int)$request->input('id', 0);

		$addr 		= Address::find($id);
		if(!$addr){
			return $this->error('没有发现地址!');
		}else if($addr->uid != $uid){
			return $this->error('删除失败,非法请求!');
		}
		if($addr->delete()){
			return $this->success('', '删除成功!');
		}
		return $this->error('删除失败!');
	}

	/**
	 * 查看用户订单列表
	 */
	public function orders(Request $request){
		$uid 	= $request->get('_uid');
		$page 	= (int)$request->input('page', 1);
		$limit 	= (int)$request->input('limit', 10);
		if($page < 1){
			$page 	= 1;
		}
		if($limit < 1){
			$limit 	= 10;
		}
		$orders 	= Order::select('id', 'price', 'num', 'pro_title', 'cover', 'kuaidi_num', 'status', 'created_at')
						->where('user_id', $uid)->orderByDesc('id')->limit($limit)->forPage($page)->get();
		return $this->success(['list' => $orders]);
	}

	/**
	 * 查看用户订单详情
	 */
	public function order(Request $request){
		$uid 		= $request->get('_uid');
		$id 		= (int)$request->input('id', 0);
		if($id < 1){
			return $this->error('非法请求!!');
		}
		$order 		= Order::find($id);
		if(!$order || $order->user_id != $uid){
			return $this->error('非法请求!');
		}
		if($order->address_id > 0){
			$addr_obj 	= Address::find($order->address_id);
			if($addr_obj){
				if(!$order->address){
					$order->address 	= $addr_obj->address;
				}
				if(!$order->phone){
					$order->phone 		= $addr_obj->tel;
				}
				if(!$order->name){
					$order->name 		= $addr_obj->name;
				}
			}
		}
		$arr 	= [
			'id'			=> $order->id,
			'product_id'	=> $order->product_id,
			'ctype'			=> $order->ctype,
			'task_id'		=> $order->task_id,
			'sale_price'	=> $order->sale_price,
			'price'			=> $order->price,
			'num'			=> $order->num,
			'pro_title'		=> $order->pro_title,
			'cover'			=> $order->cover,
			'address'		=> $order->address,
			'phone'			=> $order->phone,
			'name'			=> $order->name,
			'kuaidi'		=> $order->kuaidi,
			'kuaidi_num'	=> $order->kuaidi_num,
			'remark'		=> $order->remark,
			'status'		=> $order->status,
			'created_at'	=> $order->created_at,
		];

		return $this->success($arr);
	}
}
