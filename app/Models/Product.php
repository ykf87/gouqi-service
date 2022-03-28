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
}
