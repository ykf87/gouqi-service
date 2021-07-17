<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;
class Heart extends Model{
    use HasFactory;

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

    	// $obj 		= self::orderBy('addtime', 'DESC');
    	// $obj 		= $obj->where('id', $uid);
    	$obj 			= DB::table('hearts')->rightJoin('posts', 'posts.id', '=', 'hearts.pid')
    						->select('posts.key', 'posts.title', 'posts.cover', 'posts.viewed')
    						->where('hearts.id', $uid)->where('posts.status', '>', 0);
    	return $obj->forPage($page)->limit($limit)->get();
    }
}
