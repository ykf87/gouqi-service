<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goubi extends Model{
    use HasFactory;
    public $timestamps = false;

    public static function list($uid){
    	$page		= request()->get('page');
    	$limit 		= request()->get('limit');

    	$page 		= (int)$page;
    	if($page < 1) $page = 1;
    	$limit 		= (int)$limit;
    	if($limit < 1) $limit 	= env('PAGE_LIMIT', 10);

    	$obj        = self::where('id', $uid)->orderBy('id', 'DESC');
    	return $obj->forPage($page,$limit)->get();
    }
}
