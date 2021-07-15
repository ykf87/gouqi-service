<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller{
	public function sigin(){
		return $this->success();
	}
}
