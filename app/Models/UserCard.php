<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCard extends Model{
    use HasFactory;

	public static function list(int $uid){
    	$page		= request()->get('page');
    	$limit 		= request()->get('limit');

    	$page 		= (int)$page;
    	if($page < 1) $page = 1;
    	$limit 		= (int)$limit;
    	if($limit < 1) $limit 	= env('PAGE_LIMIT', 10);

    	$obj 		= self::select('a.id', 'a.name', 'a.phone', 'a.number', 'a.created_at', 'b.name as bankname', 'b.ico')->from('user_cards as a')
    					->rightJoin('banks as b', 'b.id', '=', 'a.type')->where('a.uid', $uid);

    	return $obj->orderBy('a.id', 'ASC')->forPage($page, $limit)->get();
    }
}
