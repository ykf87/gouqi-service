<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller{
	public function config(Request $request){
		$arr 		= [
			'version'	=> '1.0.0',
			'versions'	=> '100',
			'appname'	=> __('枸杞健康'),
			'new'		=> false,
		];
		return $this->success();
	}

	/**
	 * 广告播放完成回调
	 */
	public function palied(Request $request){
		$arr 			= [];
		return $this->success($arr);
	}

	/**
	 * 分类列表信息
	 */
	public function cate(Request $request){
		$page 		= $request->input('page', 1);
		$limit 		= $request->input('limit', env('PAGE_LIMIT', 10));

		$arr 		= [
			[
				''
			],
		];

		return $this->success($arr);
	}

	/**
	 * 文章列表
	 */
	public function list(Request $request){
		$page 		= $request->input('page', 1);
		$limit 		= $request->input('limit', env('PAGE_LIMIT', 10));
		$arr 		= [];

		return $this->success($arr);
	}

	/**
	 * 文章详情
	 */
	public function info(Request $request, $id = null){
		$arr 		= [
			'id'			=> 1,
			'cate'			=> 1,
			'cover'			=> 'sdfsdf',
			'content'		=> '测试',
			'created_at'	=> '2021-07-15',
		];

		return $this->success($arr);
	}
}
