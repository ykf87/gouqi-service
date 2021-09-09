<?php
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

use App\Models\Cate;
use App\Models\Post;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is in maintenance / demo mode via the "down" command
| we will load this file so that any pre-rendered content can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists(__DIR__.'/../storage/framework/maintenance.php')) {
    require __DIR__.'/../storage/framework/maintenance.php';
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = tap($kernel->handle(
    $request = Request::capture()
))->send();

$kernel->terminate($request, $response);












/**
 * 根据地址拼接不完整的地址
 */
function fmtUrls($fromUrl, $url){
	if(!$url){
		echo $fromUrl, ' . ' . $url . ' - 连接格式化失败<br>';
		return false;
	}
	$url 		= strtolower(trim($url));
	if(substr($url, 0, 2) == '//'){
		$url 	= explode(':', $fromUrl)[0] . ':' . $url;
	}elseif(substr($url, 0, 1) == '/'){
		// $url 	= explode(':', $fromUrl)[0] . ':' . $url;
		$url 		= baseDomins($fromUrl) . $url;
	}elseif(substr($url, 0, 4) != 'http'){
		return (strpos($fromUrl, '.html') !== false ? dirname($fromUrl) : rtrim($fromUrl, '/')) . '/' . $url;
	}
	return $url;
}

/**
 * 获取根域名
 */
function baseDomins($url){
	return substr($url, 0, strpos($url, '/', 9));
}

function httpget($url){
	try {
		$client = new \GuzzleHttp\Client(['base_uri' => 'https://ye.99.com.cn/']);
		$res = $client->request('GET', $url);
		$data = $res->getBody();
		return $data;
	} catch (Exception $e) {
		echo $e->getMessage(), "\r\n<br>";
		return false;
	}
}


	set_time_limit(0);
	// dd(baseDomins('https://sdwewe.com/wee/fdf.html'));
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
				$datas 		= $func($url, [$url => $sconf['last'] ?? null], $item->id, true, $item);
				if(!$datas || !is_array($datas)){
					continue;
				}
				// print_r($datas);
				if(!empty($datas)){
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
	}


function get99($url, $lastGet, $cid, $first = false){
	// $response 	= Http::get($url);
	// $res 		= $response->body();
	// dd($lastGet);
	$res 			= httpget($url);

	$arrs 		= [];
	$links 		= [];
	preg_match_all('`DlistWfc[\w\W]+?<h2[\w\W]+?href="(.+?)"`', $res, $links);
	if(isset($links[1])){
		echo $url, '---------------------<br>';
		$forPages 			= true;
		foreach($links[1] as $contUrl){
			$contUrl 		= fmtUrls($url, $contUrl);
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
					$nextUrl 			= fmtUrls($url, $nextUrl);
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
function getJkzx($url, $lastGet, $cateid, $first = true, $cateObj = null){
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
	$spider 		= json_decode($cateObj->spider, true);

	if(isset($spider[$url]['page'])){
		$finalPage 	= $spider[$url]['page'];
	}else{
		$finalPage 	= $lastPage;
	}
	$lastUrl 		= $lastGet[$url] ?? null;
	for(;$finalPage > 0; $finalPage--){
		if($finalPage == 1){
			$u 		= $url;
		}elseif($pageLink != ''){
			$u 		= fmtUrls($url, preg_replace('`\d+`', $finalPage, $pageLink));
		}else{
			break;
		}
		$lists 		= getJkzxList($u, $cateid, null, $lastUrl);
		if($lists === false){
			continue;
		}
		DB::beginTransaction();
		try {
			Post::insert($lists);
			$spider[$url]['last'] 	= $lists[0]['url'];
			$spider[$url]['page']	= $finalPage;
			$cateObj->spider 		= json_encode($spider);
			$cateObj->save();
			DB::commit();
		} catch (Exception $e) {
			DB::rollBack();
			echo $e->getMessage(), "<br>\r\n";
		}
		
		// $arr 		= array_merge($arr, $lists);
		// echo $u, ' - http://www.keduguke.com/jknx/p2.html<br>';
	}
	return null;
}

/**
 * 获取健康资讯列表
 */
function getJkzxList($url, $cateid, $content = null, $lastUrl = null){
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
		$alink 			= fmtUrls($url, $alink);
		if($lastUrl == $alink){
			return false;
		}
		$rs 			= getJkzxContent($alink, $cateid);
		if($rs === false){
			for($i = 3; $i > 0; $i--){
				$rs 	= getJkzxContent($alink, $cateid);
				if($rs != false){
					break;
				}
			}
			if($rs == false){
				return false;
			}
		}
		$arr[] 			= $rs;
	}
	return $arr;
}

/**
 * 获取健康资讯内容
 */
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

	return Post::fmtData($arr, $url, time() + 31536000);
	dd($title, $keyword, $description, $content);
}
