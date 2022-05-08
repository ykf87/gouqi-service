<?php

namespace App\Http\Controllers\Sweepstake;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SweepstakeProduct;

class SweepstakeController extends Controller{
	public function index(Request $request){
		$res 	= SweepstakeProduct::list();
		$prize 	= [
			[
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
			]
		];
		return view('default.Sweepstake.index', ['prize' => $prize]);
	}
}
