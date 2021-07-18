<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Cate;
use App\Models\Post;
use App\Models\User;
use App\Models\History;
use Lcobucci\JWT\Token\Plain;

class IndexController extends Controller{
	public function config(Request $request){
		$arr 		= [
			'version'	=> '1.0.0',
			'versions'	=> '100',
			'appname'	=> __('枸杞健康'),
			'new'		=> false,
		];
		return $this->success($arr);
	}

	/**
	 * 分类列表信息
	 */
	public function cate(Request $request){
		$arr 		= Cate::list();

		return $this->success($arr);
	}

	/**
	 * 文章列表
	 */
	public function list(Request $request){
		$page 		= $request->input('page', 1);
		$limit 		= $request->input('limit', env('PAGE_LIMIT', 10));
		$arr 		= Post::list();

		return $this->success($arr);
	}

	/**
	 * 文章详情
	 */
	public function info(Request $request, $id = null){
		$jwt        = User::decry();
		$uid 		= 0;
        if($jwt instanceof Plain){
            $uid    = $jwt->claims()->get('_uid');
        }
		$id 		= $id ? $id : $request->get('_uid');
		$arr 		= Post::info((int)$id, $uid);

		if(!$arr){
			return $this->error(__('找不到文章!'));
		}
    	$arr->viewed 	+= 1;
    	$arr->save();

    	if($uid > 0){
        	$isview = History::where('id', $uid)->where('pid', $pid)->first();
        	if($isview){
        		History::where('id', $uid)->where('pid', $pid)->update(['addtime' => time()]);
        	}else{
            	History::insert(['id' => $uid, 'pid' => $id, 'addtime' => time()]);
        	}
        }

		return $this->success($arr);
	}

	/**
	 * 采集
	 */
	public function spider(Request $request){

	}
}
