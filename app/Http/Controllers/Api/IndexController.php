<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller{
	public function config(Request $request){
		return $this->success();
	}
}
