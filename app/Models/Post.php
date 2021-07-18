<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model{
    use HasFactory;

    public static function list(){
    	$page		= request()->get('page');
    	$limit 		= request()->get('limit');
    	$cateId 	= request()->get('cid');
        $q          = trim(request()->get('q'), '');
    	$cateId 	= (int)$cid;

    	$page 		= (int)$page;
    	if($page < 1) $page = 1;
    	$limit 		= (int)$limit;
    	if($limit < 1) $limit 	= env('PAGE_LIMIT', 10);
        $now        = time();

    	$obj 		= self::select('id', 'cate', 'cover', 'title', 'key', 'viewed', 'created_at', 'hearted')
                        ->whereRaw('if(stime>0, stime <= now(), 1)')->whereRaw('if(etime>0, etime >= now(), 1)')
                        ->where('status', 1)
                        ->orderBy('sort', 'DESC')->orderBy('id', 'DESC');
    	if($cateId > 0){
    		$obj 	= $obj->where('cid', $cateId);
    	}
        if($q){
            $obj    = $obj->where('title', 'like', "%$q%");
        }
    	return $obj->forPage($page)->limit($limit)->get();
    }

    /**
     * 详情
     */
    public static function info($id, $uid = null){
    	$row       = self::select('id', 'cate', 'cover', 'title', 'key', 'viewed', 'created_at', 'content', 'hearted')
                        ->whereRaw('if(stime>0, stime <= now(), 1)')->whereRaw('if(etime>0, etime >= now(), 1)')
                        ->where('status', 1)->find($id);
    	if(!$row){
    		return false;
    	}
        if($uid > 0){
            if(Hearts::where('id', $uid)->where('pid', $id)->first()){
                $row->is_heart      = true;
            }else{
                $row->is_heart      = false;
            }
        }
    	return $row;
    }
}
