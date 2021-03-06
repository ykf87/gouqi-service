<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Cate;
use App\Models\Post;
use App\Models\User;
use App\Models\History;
use App\Models\Adv;
use App\Models\Goubi;
use App\Models\Reback;
use App\Models\Config;
use App\Models\Active;
use App\Models\AppMenu;
use Lcobucci\JWT\Token\Plain;

class IndexController extends Controller{
	public function config(Request $request){
		// $arr 		= [
		// 	'version'	=> '1.0.0',
		// 	'versions'	=> '100',
		// 	'appname'	=> __('枸杞健康'),
		// 	'new'		=> false,
		// 	'isadv'		=> false,// 是否开启广告
		// 	'service'	=> 'http://45.77.216.241/service.jpg',
		// ];
		// dd(date('Y-m-d H:i:s', strtotime('+30days')));
		$arr 		= Config::where('status', 1)->pluck('val', 'key');
		$arr['activities']		= Active::select('title', 'innerimg')->get();
		$arr['menu'] 			= AppMenu::select('icon', 'txt', 'url as link', 'active')->orderByDesc('sort')->limit(5)->get();
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
	public function info(Request $request, $id){
		$jwt        = User::decry();
		$uid 		= 0;
        if($jwt instanceof Plain){
            $uid    = $jwt->claims()->get('id');
        }
		$arr 		= Post::info((int)$id, $uid);

		if(!$arr){
			return $this->error(__('找不到文章!'));
		}
		$isheart 		= $arr->is_heart;
		unset($arr->is_heart);
    	$arr->viewed 	+= 1;
    	$arr->save();
    	$arr->is_heart 	= $isheart;

    	if($uid > 0){
        	$isview = History::where('id', $uid)->where('pid', $id)->first();
        	if($isview){
        		History::where('id', $uid)->where('pid', $id)->update(['addtime' => time()]);
        	}else{
            	History::insert(['id' => $uid, 'pid' => $id, 'addtime' => time()]);
	        	$times		= Adv::fanbeiTimes($uid);
	        	$time 		= date('Y-m-d H:i:s');
	        	Goubi::insert(['id' => $uid, 'added' => $times, 'created_at' => $time, 'updated_at' => $time]);
        	}
        }

		return $this->success($arr);
	}

	/**
	 * 一些协议内容
	 * 45.77.216.241
	 */
	public function agreement(Request $request){
		$key 		= trim($request->input('key', ''));
		$arr 		= Post::where('key', $key)->first();
		if(!$arr){
			return $this->error(__('找不到文章!'));
		}
		return $this->success($arr);
	}

	/**
	 * 帮助反馈
	 */
	public function help(Request $request){
		$phone 		= trim($request->input('phone', ''));
		$title 		= trim($request->input('title', ''));
		$cont 		= trim($request->input('cont', ''));

		if(empty($phone) || empty($title)){
			return $this->error(__('请填写完整!'));
		}

		$jwt        = User::decry();
		$uid 		= 0;
        if($jwt instanceof Plain){
            $uid    = $jwt->claims()->get('_uid');
        }

        $model 		= new Reback;
        $model->phone 	= $phone;
        $model->uid 	= $uid;
        $model->title 	= $title;
        $model->content = $cont;
        if($model->save()){
        	return $this->success(__('反馈成功!'));
        }
        return $this->error(__('提交失败!'));
	}

	/**
	 * 采集
	 */
	public function spider(Request $request){

	}
}
