<?php

namespace App\Http\Controllers\Api\Signins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SigninsController extends Controller{
	//签到首页
	public function signed(Request $request){
		$uid 		= $request->get('_uid');
	}
}
