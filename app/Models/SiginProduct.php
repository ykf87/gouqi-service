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
}
