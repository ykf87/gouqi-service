<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

use App\Models\Heart;

class Post extends Model{
    use HasFactory;
    //DELETE from b USING posts as a, posts as b where (a.id < b.id) and (a.url = b.url); 删除post url重复并保留老数据

    public static function list(){
    	$page		= request()->get('page');
    	$limit 		= request()->get('limit');

    	$cateId 	= request()->get('cate');
        $q          = trim(request()->get('q'), '');
    	$cateId 	= (int)$cateId;
    	$page 		= (int)$page;
    	if($page < 1) $page = 1;
    	$limit 		= (int)$limit;
    	if($limit < 1) $limit 	= env('PAGE_LIMIT', 10);
        $now        = time();

    	$obj 		= self::select('id', 'cid', 'cover', 'title', 'key', 'viewed', 'created_at', 'hearted')
                        ->whereRaw('if(stime>0, stime <= now(), 1)')->whereRaw('if(etime>0, etime >= now(), 1)')
                        ->where('status', 1)->whereRaw('`key` is null')
                        ->orderBy('sort', 'DESC')->orderBy('stime', 'DESC');
    	if($cateId > 0){
    		$obj 	= $obj->where('cid', $cateId);
    	}
        if($q){
            $obj    = $obj->where('title', 'like', "%$q%");
        }
        $res        = $obj->forPage($page, $limit)->get();
        foreach($res as &$item){
            if(!$item->cover){
                $item->cover    = env('APP_URL');
            }
        }
    	return $res;
    }

    /**
     * 详情
     */
    public static function info($id, $uid = null){
    	$row       = self::select('id', 'cid', 'cover', 'title', 'key', 'viewed', 'created_at', 'content', 'hearted')
                        ->whereRaw('if(stime>0, stime <= now(), 1)')->whereRaw('if(etime>0, etime >= now(), 1)')
                        ->where('status', 1)->find($id);
    	if(!$row){
    		return false;
    	}
        $row->is_heart      = false;
        if($uid > 0){
            $isheart        = Heart::where('id', $uid)->where('pid', $id)->first();
            if($isheart){
                $row->is_heart      = true;
            }
        }
        $row->content       = str_replace('\\','',$row->content);
    	return $row;
    }

    /**
     * 采集内容格式化并
     */
    public static function fmtData($data, $url, $maxtime = 0){
        if(self::where('url', $url)->first()){
            return false;
        }
        $ymd                = date('Ymd');
        $content            = $data['content'] ?? '';
        $title              = $data['title'] ?? '';
        if(!$title || !$content){
            return false;
        }
        $now                = time();

        preg_match_all('`<img.+?src=[\'"](.+?)[\'"]`', $content, $images);
        $imagesArr      = [];
        if(isset($images[1])){
            $images     = array_filter($images[1]);
            $appurl     = env('APP_URL');
            foreach($images as $item){
                $toFile     = 'public/posts/' . $ymd . '/' . basename($item);
                if(!Storage::exists($toFile)){
                    Storage::put($toFile, Http::get($item));
                }else{
                    echo "跳过图片\r\n";
                }
                $imageUrl   = Storage::url($toFile);
                if(strpos($imageUrl, $appurl) === false){
                    $imageUrl       = $appurl . $imageUrl;
                }
                $imagesArr[$item]   = $imageUrl;
            }
            $content        = str_replace(array_keys($imagesArr), $imagesArr, $content);
            $content        = preg_replace('`width=".+?"`', '', $content);
            $content        = preg_replace('`height=["\'].+?["\']`', '', $content);
        }
        $data['content']    = $content;
        $data['cover']      = isset($data['cover']) && $data['cover'] ? $data['cover'] : (array_values($imagesArr)[0] ?? null);
        if($maxtime <= $now){
            $maxtime        = $now + 2592000;
        }
        $data['stime']      = rand($now, $maxtime);
        // $arr            = [
        //     'cid'           => $cateid,
        //     'title'         => $title,
        //     'keywords'      => $keyword,
        //     'description'   => $description,
        //     'content'       => $content,
        //     'cover'         => array_values($imagesArr)[0] ?? null,
        //     'url'           => $url,
        // ];
        return $data;
    }
}
