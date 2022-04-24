<?php

namespace App\Http\Controllers\Sweepstake;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SweepstakeController extends Controller{
	public function index(Request $request){
		return view('default.Sweepstake.index');
	}
}
