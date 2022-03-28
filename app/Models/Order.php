<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model{
    use HasFactory;
    public $ctypes = ['下单', '签到', '抽奖'];
    public $statuss = [-1 => '订单取消', 0 => '待付款', 1 => '待发货', 2 => '已发货', 3 => '已签收', 4 => '订单完成'];

    public function getCtypeAttribute($val){
    	return $this->ctypes[$val] ?? '未知';
    }

     public function getStatusAttribute($val){
     	return $this->statuss[$val] ?? '未知';
    }
}
