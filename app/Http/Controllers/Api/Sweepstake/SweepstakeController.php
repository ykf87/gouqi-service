<?php

namespace App\Http\Controllers\Api\Sweepstake;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SweepstakeProduct;

class SweepstakeController extends Controller{
	public function index(Request $request){
		$res 	= SweepstakeProduct::list();
		dd($res);
	}
}
