<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cate extends Model{
    use HasFactory;
    public $timestamps = false;

   	public static function list(){
    	$page		= request()->get('page');
    	$limit 		= request()->get('limit');

    	$page 		= (int)$page;
    	if($page < 1) $page = 1;
    	$limit 		= (int)$limit;
    	if($limit < 1) $limit 	= env('PAGE_LIMIT', 10);

    	$obj 		= self::select('id', 'name')->orderBy('id', 'ASC')->forPage($page, $limit)->get();
    	return $obj;
    }
}
