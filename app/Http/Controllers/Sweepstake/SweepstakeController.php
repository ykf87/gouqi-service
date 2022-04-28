<?php

namespace App\Http\Controllers\Sweepstake;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SweepstakeProduct;

class SweepstakeController extends Controller{
	public function index(Request $request){
		$res 	= SweepstakeProduct::list();
		return view('default.Sweepstake.index');
	}
}
