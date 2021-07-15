<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * json 的成功返回
     */
    public function success($data = null, $msg = ''){
    	return $this->resp($data, $msg);
    }

    /**
     * json 的失败返回
     */
    public function error($msg = '', $data = null){
    	return $this->resp($data, $msg, 500);
    }

    /**
     * json 的返回通用接口
     */
    public function resp($data = null, $msg = '', $code = 200, $respcode = 200){
    	$rs 	= [
    		'code'		=> $code,
    		'data'		=> $data,
    		'msg'		=> $msg
    	];
    	return response()->json($rs, $respcode ? $respcode : $code);
    }
}
