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

/**
 * 根据地址拼接不完整的地址
 */
function fmtUrl($fromUrl, $url){
	if(!$url){
		echo $fromUrl, ' . ' . $url . ' - 连接格式化失败<br>';
		return false;
	}
	$url 		= strtolower(trim($url));
	if(substr($url, 0, 1) == '/'){
		$url 	= explode(':', $fromUrl)[0] . ':' . $url;
	}elseif(substr($url, 0, 4) != 'http'){
		return (strpos($fromUrl, '.html') !== false ? dirname($fromUrl) : rtrim($fromUrl, '/')) . '/' . $url;
	}
	return $url;
}

function httpget($url){
	$client = new \GuzzleHttp\Client(['base_uri' => 'https://ye.99.com.cn/']);
	$res = $client->request('GET', $url);
	$data = $res->getBody();
	return $data;
}

Route::get('/', function () {
    // abort(404);
    // return view('welcome');
    return file_get_contents(__DIR__ . '/../public/index.html');
});

Route::get('/agreement', function () {
    // abort(404);
    // return view('welcome');
    $cont 		= Post::where('key', 'agreement')->first();
    return view('agreement', $cont);
});


// 采集完成后做替换等操作
//{"https://nan.99.com.cn/changshi/":{"func":"get99","last":""}}
//{"https://nv.99.com.cn/baojian/":{"func":"get99","last":""}}
//

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
	$cates 			= Cate::whereRaw('spider is not null')->get();
	// dd($cates);
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
				$datas 					= array_reverse($datas);
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
	$ykd 		= date('Ymd');
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
	preg_match_all('`<img.+?src=[\'"](.+?)[\'"]`', $content, $images);
	$imagesArr		= [];
	if(isset($images[1])){
		$images 	= array_filter($images[1]);
		$appurl 	= env('APP_URL');
		foreach($images as $item){
			$toFile 	= 'public/posts/' . $ykd . '/' . basename($item);
			if(!Storage::exists($toFile)){
				Storage::put($toFile, Http::get($item));
			}else{
				echo $url, ' - ', $item, ': 图片存在, 跳过更新<br>';
			}
			$imageUrl 	= Storage::url($toFile);
			if(strpos($imageUrl, $appurl) === false){
				$imageUrl 		= $appurl . $imageUrl;
			}
			$imagesArr[$item]	= $imageUrl;
		}
		$content 		= str_replace(array_keys($imagesArr), $imagesArr, $content);
		$content 		= preg_replace('`width=".+?"`', '', $content);
		$content 		= preg_replace('`height=["\'].+?["\']`', '', $content);
	}
	$arr 			= [
		'cid'			=> $cateid,
		'title'			=> $title,
		'keywords'		=> $keyword,
		'description'	=> $description,
		'content'		=> $content,
		'cover'			=> array_values($imagesArr)[0] ?? null,
		'url'			=> $url,
	];
	return $arr;
}



