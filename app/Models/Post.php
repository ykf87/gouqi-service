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
    	$cateId 	= (int)$cid;

    	$page 		= (int)$page;
    	if($page < 1) $page = 1;
    	$limit 		= (int)$limit;
    	if($limit < 1) $limit 	= env('PAGE_LIMIT', 10);

    	$obj 		= self::orderBy('id', 'DESC');
    	if($cateId > 0){
    		$obj 	= $obj->where('cid', $cateId);
    	}
    	return $obj->forPage($page)->limit($limit)->get();
    }

    /**
     * 详情
     */
    public static function info($id){
    	$row 		= self::find($id);

    	if(!$row){
    		return false;
    	}
    	$row->viewed 	+= 1;
    	$row->save();
    	return $row;
    }
}