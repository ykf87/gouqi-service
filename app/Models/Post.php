<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use App\Models\Heart;

class Post extends Model{
    use HasFactory;

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
}
