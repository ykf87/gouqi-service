<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;
class Heart extends Model{
    use HasFactory;
    public $timestamps = false;
    /**
     * 收藏
     */
    public static function list($uid){
    	$page		= request()->get('page');
    	$limit 		= request()->get('limit');

    	$page 		= (int)$page;
    	if($page < 1) $page = 1;
    	$limit 		= (int)$limit;
    	if($limit < 1) $limit 	= env('PAGE_LIMIT', 10);

    	$obj        = DB::table('hearts as h')->rightJoin('posts as p', 'h.pid', '=', 'p.id')
                        ->select('p.id', 'p.cover', 'p.title', 'p.key', 'p.viewed', 'p.created_at')
                        ->where('h.id', $uid)->where('p.status', 1);
        $obj        = $obj->orderBy('h.addtime', 'DESC');
    	return $obj->forPage($page, $limit)->get();
    }
}
