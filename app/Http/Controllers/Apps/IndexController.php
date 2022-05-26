<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller{
	public function index(Request $request){
		$arr 		= [
			'menu'	=> [
				[
					'icon'	=> '<i class="iconfont icon-shangpin"></i>',
					'txt'	=> '首页',
					'link'	=> '/pages/index/index',
					'active'	=> 1,
				],[
					'icon'	=> '<i class="iconfont icon-quan"></i>',
					'txt'	=> '圈子',
					'link'	=> '/pages/index/index',
					'active'	=> 0,
				],[
					'icon'	=> '<i class="iconfont icon-icongongju"></i>',
					'txt'	=> '工具',
					'link'	=> '/pages/index/index',
					'active'	=> 0,
				],[
					'icon'	=> '<i class="iconfont icon-zhuanqian"></i>',
					'txt'	=> '互动',
					'link'	=> '/pages/user/login/login',
					'active'	=> 0,
				],[
					'icon'	=> '<i class="iconfont icon-bussiness-man-fill"></i>',
					'txt'	=> '我的',
					'link'	=> '/pages/user/index/index',
					'active'	=> 0,
				],
			],
			'appname' => '多米猫',
		];
		return $this->success($arr);
	}
}
