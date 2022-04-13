<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model{
    use HasFactory;
    public $timestamps = false;
    public static $type 	= [
    	0 	=> '正常商品',
    	1 	=> '签到赠送'
    ];
    public static $status 	= [
    	-1	=> '下架',
    	0	=> '无效',
    	1 	=> '正常',
    ];
    public static $statusLabel 	= [
    	-1	=> 'default',
    	0	=> 'warning',
    	1 	=> 'success',
    ];

    public function getImagesAttribute($val){
    	return explode(',', $val);
    }
    public function setImagesAttribute($val){
    	return $val ? implode(',', $val) : null;
    }
}
