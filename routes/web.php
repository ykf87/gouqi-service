<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

use App\Models\Cate;
use App\Models\Post;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// /**
//  * 根据地址拼接不完整的地址
//  */
// function fmtUrl($fromUrl, $url){
// 	if(!$url){
// 		echo $fromUrl, ' . ' . $url . ' - 连接格式化失败<br>';
// 		return false;
// 	}
// 	$url 		= strtolower(trim($url));
// 	if(substr($url, 0, 2) == '//'){
// 		$url 	= explode(':', $fromUrl)[0] . ':' . $url;
// 	}elseif(substr($url, 0, 1) == '/'){
// 		// $url 	= explode(':', $fromUrl)[0] . ':' . $url;
// 		$url 		= baseDomin($fromUrl) . $url;
// 	}elseif(substr($url, 0, 4) != 'http'){
// 		return (strpos($fromUrl, '.html') !== false ? dirname($fromUrl) : rtrim($fromUrl, '/')) . '/' . $url;
// 	}
// 	return $url;
// }

// /**
//  * 获取根域名
//  */
// function baseDomin($url){
// 	return substr($url, 0, strpos($url, '/', 9));
// }

// function httpget($url){
// 	try {
// 		$client = new \GuzzleHttp\Client(['base_uri' => 'https://ye.99.com.cn/']);
// 		$res = $client->request('GET', $url);
// 		$data = $res->getBody();
// 		return $data;
// 	} catch (Exception $e) {
// 		echo $e->getMessage(), "\r\n<br>";
// 		return false;
// 	}
// }

Route::get('/', function () {
	$cont 	= [
		'title' 	=> '省得赚官网',
		'content'	=> '<div style="background:#333; color:#fff;display:flex;align-item:center;height:100vh;"></div>',
	];
    return view('welcome', $cont);
});

Route::get('/agreement', function () {
    $cont 		= Post::where('key', 'aggs')->first();
    return view('agreement', $cont);
});
Route::get('/private', function () {
    $cont 		= Post::where('key', 'pris')->first();
    return view('agreement', $cont);
});
Route::get('/privacy', function () {
    $cont 		= Post::where('key', 'pris')->first();
    return view('agreement', $cont);
});



// 抽奖
Route::group([
	'prefix'		=> 'sweepstake/',
	'namespace'		=> 'App\Http\Controllers\Sweepstake',
    'as'            => 'sweepstake.'
], function(){
	// Route::group([
	//     'middleware'    => ['jwt'],
	// ], function(){
 //    	Route::post('choose', 'SigninsController@choose')->name('choose');
	//     Route::post('signe', 'SigninsController@signe')->name('signe');
	//     Route::post('givecollection', 'SigninsController@givecollection')->name('givecollection');
	//     Route::post('giveuncollection', 'SigninsController@giveuncollection')->name('giveuncollection');
 //    	Route::post('giveget', 'SigninsController@giveget')->name('giveget');
 //    	Route::post('giveup', 'SigninsController@giveup')->name('giveup');
	// });
	Route::get('', 'SweepstakeController@index')->name('index');
	Route::post('products', 'SweepstakeController@products')->name('products');
	Route::post('product', 'SweepstakeController@product')->name('product');
	Route::post('prize', 'SweepstakeController@prize')->name('prize');
	Route::post('plaied', 'SweepstakeController@plaied')->name('plaied');
});



Route::group([
	'namespace'		=> 'App\\Http\\Controllers\\Apps',
	'name'			=> 'app.',
	'prefix'		=> 'app',
],function(){
	Route::get('/', 'IndexController@index')->name('index');
	Route::group([
		'name'		=> 'user.',
		'prefix'	=> 'user',
	],function(){
		Route::post('/login-signup', 'UserController@loginSignup')->name('ls');
	});
});

// 采集完成后做替换等操作
//{"https://nan.99.com.cn/changshi/":{"func":"get99","last":""}}
//{"https://nv.99.com.cn/baojian/":{"func":"get99","last":""}}
//


/**
Route::get('/rpurl', function () {
    $post 		= Post::where('cid', '>', 0)->orderBy('stime', 'ASC')->get();
    $cidLast 	= $post->pluck('stime', 'cid');
    foreach($post as $item){
    	// if($item->cover){
    	// 	$item->cover 	= str_replace('http://www.gouqi.com', env('APP_URL'), $item->cover);
    	// }
    	// $item->content 		= str_replace('http://www.gouqi.com', env('APP_URL'), $item->content);
    	// $item->content 		= str_replace(["\r\n", "\r", "\n", '<p></p>', '  '], '', $item->content);
    	if(!$item->stime){
    		if(isset($cidLast[$item->cid])){
    			$cateStime 		= $cidLast[$item->cid];
    			$toStime 		= time()+86400;
    		}else{
    			$cateStime 		= '1626775402';
    			$toStime 		= time();
    		}
    		// $cateStime 		= $cidLast[$item->cid] ?? '1626775402';
    		$item->stime 	= rand($cateStime, $toStime);
    		if(isset($cidLast[$item->cid]) && $cidLast[$item->cid] < $item->stime){
    			$cidLast[$item->cid] 	= $item->stime;
    		}
    	}
    	$item->save();
    }
});


Route::get('/spider', function () {
	set_time_limit(0);
	// dd(baseDomin('https://sdwewe.com/wee/fdf.html'));
	$cates 			= Cate::whereRaw('spider is not null')->get();
	// dd($cates->toArray());
	foreach($cates as $item){
		$spiders 	= json_decode($item->spider, true);
		if(!is_array($spiders)){
			continue;
		}
		$nspi 		= $spiders;
		foreach($spiders as $url => $sconf){
			$func 			= $sconf['func'] ?? null;
			if(!$func){
				continue;
			}
			if(function_exists($func)){
				$datas 		= $func($url, [$url => $sconf['last'] ?? null], $item->id, true);
				if(!$datas || !is_array($datas)){
					continue;
				}
				// print_r($datas);
				if(isset($datas['reverse'])){
					$datas 				= $datas['reverse'];
				}else{
					$datas 				= array_reverse($datas);
				}
				$nspi[$url]['last'] 	= $datas[0]['url'];
				Post::insert($datas);
				$item->spider 			= json_encode($nspi);
				$item->save();
			}
		}
	}

	// $lastUrl 	= ['https://ye.99.com.cn/fmdx/yej/' => 'https://ye.99.com.cn/fmdx/yej/2020/1229/764636.html'];
	// $datas 		= get99('https://ye.99.com.cn/fmdx/yej/', $lastUrl);

	// $datas 		= array_reverse($datas);
});

function get99($url, $lastGet, $cid, $first = false){
	// $response 	= Http::get($url);
	// $res 		= $response->body();
	$res 			= httpget($url);

	$arrs 		= [];
	$links 		= [];
	preg_match_all('`DlistWfc[\w\W]+?<h2[\w\W]+?href="(.+?)"`', $res, $links);
	if(isset($links[1])){
		echo $url, '---------------------<br>';
		$forPages 			= true;
		foreach($links[1] as $contUrl){
			$contUrl 		= fmtUrl($url, $contUrl);
			if(!$contUrl){
				continue;
			}
			if(isset($lastGet[$url]) && $lastGet[$url] == $contUrl){
				echo '已取到最新消息<br>';
				$forPages		= false;
				break;
			}
			$cres 				= getConten99($contUrl, $cid);
			if($cres && is_array($cres)){
				$arrs[]			= $cres;
			}else{
				echo $contUrl . ' - 内容获取失败<br>';
			}
		}

		if($first === true && $forPages){
			$i 			= 0;
			$listfmt 	= false;
			$max 		= 0;
			preg_match('`list_page[\w\W]+?<a.+?href=["\'](.+?)["\']`', $res, $pages);
			if(isset($pages[1]) && strpos($pages[1], 'list_') !== false){
				$listfmt 	= explode('_', explode('.', $pages[1])[0]);
				$max 		= end($listfmt);
				array_pop($listfmt);
			}
			if($listfmt){
				for(;$max > 0; $max--){
					$i++;
					// if($i >= 6) break;
					$nextPage 			= $listfmt;
					$nextPage[] 		= $max;
					$nextUrl 			= implode('_', $nextPage) . '.html';
					$nextUrl 			= fmtUrl($url, $nextUrl);
					$pageCont 			= get99($nextUrl, $lastGet, $cid);
					if($pageCont && is_array($pageCont)){
						foreach($pageCont as $zzzrt){
							$arrs[] 		= $zzzrt;
						}
					}
				}
			}
		}
	}else{
		echo $url . ' - 列表连接获取失败<br>';
	}
	return $arrs;
}

function getConten99($url, $cateid){
	// $response 	= Http::get($url);
	// $res 		= $response->body();
	$res 			= httpget($url);

	preg_match('`<meta.+?charset=(.+)"`', $res, $charset);
	$charset 	= $charset[1] ?? null;
	if($charset){
		$charset	= strtolower(trim($charset, '"\' '));
	}
	if(strpos($charset, 'utf') === false){
		$res 		= mb_convert_encoding($res, 'utf-8', $charset);
	}

	preg_match('`<h1.*>(.+?)<`', $res, $title);
	preg_match('`<meta.+?"keywords".+?content=[\'"](.+?)[\'"]`', $res, $keyword);
	preg_match('`<meta.+?"description".+?content=[\'"](.+?)[\'"]`', $res, $description);
	preg_match('`<div class="new_cont detail_con">([\w\W]+?)<div class="new_page">`', $res, $content);
	preg_match('`<h1.*>(.+?)<`', $res, $title);
	$title 			= $title[1] ?? null;
	$keyword 		= $keyword[1] ?? null;
	$description	= $description[1] ?? null;
	$content		= $content[1] ?? null;

	if(!$title || !$content){
		echo $url . ' - 文章详情获取失败<br>';
		return false;
	}
	$content 		= preg_replace('`<script[\w\W]+?</script>`', '', $content);
	$content 		= preg_replace('`<a.+?>`', '', $content);
	$content 		= preg_replace('`<p align="right">[\w\W]+?</p>`', '', $content);
	$content 		= str_replace('</a>', '', $content);
	// preg_match_all('`<img.+?src=[\'"](.+?)[\'"]`', $content, $images);
	// $imagesArr		= [];
	// if(isset($images[1])){
	// 	$images 	= array_filter($images[1]);
	// 	$appurl 	= env('APP_URL');
	// 	foreach($images as $item){
	// 		$toFile 	= 'public/posts/' . $ykd . '/' . basename($item);
	// 		if(!Storage::exists($toFile)){
	// 			Storage::put($toFile, Http::get($item));
	// 		}else{
	// 			echo $url, ' - ', $item, ': 图片存在, 跳过更新<br>';
	// 		}
	// 		$imageUrl 	= Storage::url($toFile);
	// 		if(strpos($imageUrl, $appurl) === false){
	// 			$imageUrl 		= $appurl . $imageUrl;
	// 		}
	// 		$imagesArr[$item]	= $imageUrl;
	// 	}
	// 	$content 		= str_replace(array_keys($imagesArr), $imagesArr, $content);
	// 	$content 		= preg_replace('`width=".+?"`', '', $content);
	// 	$content 		= preg_replace('`height=["\'].+?["\']`', '', $content);
	// }
	$arr 			= [
		'cid'			=> $cateid,
		'title'			=> $title,
		'keywords'		=> $keyword,
		'description'	=> $description,
		'content'		=> $content,
		'url'			=> $url,
	];

	return Post::fmtData($arr, $url);
	return $arr;
}


/**
 * 获取 “健康资讯”
 */
/**
function getJkzx($url, $lastGet, $cateid){
	$res 			= httpget($url);

	$lastPage 		= 1;
	$pageLink 		= '';
	preg_match('`class="last"[\w\W]+?href="(.+?)"`', $res, $last);
	if(isset($last[1])){
		$pageLink 	= $last[1];
		preg_match('`p([0-9]+?)\.`', $last[1], $tmp);
		$lastPage 	= $tmp[1] ?? 1;
	}

	$arr 			= [];
	for(;$lastPage > 0; $lastPage--){
		if($lastPage == 1){
			$u 		= $url;
		}elseif($pageLink != ''){
			$u 		= fmtUrl($url, preg_replace('`\d+`', $lastPage, $pageLink));
		}else{
			break;
		}
		$arr 		= array_merge($arr, getJkzxList($u, $cateid));
		// echo $u, ' - http://www.keduguke.com/jknx/p2.html<br>';
	}
	return ['reverse' => $arr];
}

/**
 * 获取健康资讯列表
 */
/**
function getJkzxList($url, $cateid, $content = null){
	if(!$content){
		$content 		= httpget($url);
	}

	preg_match_all('`<li.+?class="col-xs-6[\w\W]+?href="(.+?)"`', $content, $lists);
	if(!isset($lists[1]) || empty($lists[1])){
		echo $url, ' 列表获取链接失败!';
		return false;
	}

	$lists[1] 			= array_reverse($lists[1]);
	$arr 				= [];
	foreach($lists[1] as $alink){
		$alink 			= fmtUrl($url, $alink);
		$arr[] 			= getJkzxContent($alink, $cateid);
	}
	return $arr;
}

/**
 * 获取健康资讯内容
 */
/**
function getJkzxContent($url, $cateid){
	$res 		= httpget($url);
	preg_match('`<h1 class="entry-title">(.+?)</h1>`', $res, $title);
	preg_match('`<meta.+?"keywords".+?content=[\'"](.+?)[\'"]`', $res, $keyword);
	preg_match('`<div.+?entry\-description.+?>([\w\W]+?)</div>`', $res, $description);
	preg_match('`<div class="entry-content clearfix">([\w\W]+?)<div class="tag">`', $res, $content);

	$title 			= $title[1] ?? null;
	$keyword 		= $keyword[1] ?? null;
	$description 	= $description[1] ?? null;
	$content 		= $content[1] ?? null;

	if(!$title || !$content){
		echo $url, ' 内容获取失败!';
		return false;
	}
	if($description){
		$description 	= preg_replace('`\s+`', '', $description);
	}
	$content 			= preg_replace('`\s+`', '', $content);
	$content 			= str_replace(["\r\n", "\r", "\n"], '', $content);
	$content 			= str_replace('</mip-img>', '', $content);
	$content 			= str_replace('mip-img', 'img ', $content);

	$arr 			= [
		'cid'			=> $cateid,
		'title'			=> $title,
		'keywords'		=> $keyword,
		'description'	=> $description,
		'content'		=> $content,
		'url'			=> $url,
	];

	return Post::fmtData($arr, $url, 31536000);
	dd($title, $keyword, $description, $content);
}
**/



