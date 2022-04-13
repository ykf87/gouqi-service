<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiginProduct extends Model{
    use HasFactory;
    public $timestamps = false;

    public function getImagesAttribute($val){
    	return explode(',', $val);
    }
    public function getStartTimeAttribute($val){
    	return $val > 0 ? date('Y-m-d H:i:s', $val) : null;
    }
    public function setStartTimeAttribute($val){
    	return $val ? strtotime($val) : 0;
    }
    public function getEndTimeAttribute($val){
    	// dd($val);
    	return $val > 0 ? date('Y-m-d H:i:s', $val) : null;
    }
    public function setEndTimeAttribute($val){
    	return $val ? strtotime($val) : 0;
    }
}
