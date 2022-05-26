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
    public function success($data = null, $msg = '', $code = 200){
    	return $this->resp($data, $msg);
    }

    /**
     * json 的失败返回
     */
    public function error($msg = '', $data = null, $code = 500){
    	return $this->resp($data, $msg, $code);
    }

    /**
     * json 的返回通用接口
     */
    public function resp($data = null, $msg = '', $code = 200, $respcode = 200){
        $data           = $this->setEmpty($data);
    	$rs 	= [
    		'code'		=> $code,
    		'data'		=> $data,
    		'msg'		=> $msg,
    	];
        response()->header('Access-Control-Allow-Origin: *');
        response()->header('Access-Control-Allow-Credentials: true');
        response()->header('Access-Control-Allow-Methods: *');
        response()->header('Access-Control-Allow-Headers: Content-Type,Access-Token');
        response()->header('Access-Control-Expose-Headers: *');
        return response()->json($rs, $respcode ? $respcode : $code);
        return json_encode($rs, JSON_UNESCAPED_UNICODE);//JSON_FORCE_OBJECT
    }

    public function setEmpty($data){
        if(is_array($data)){
            foreach($data as $k => $v){
                if(is_null($v)){
                    $data[$k]   = '';
                }elseif(is_array($v)){
                    $data[$k]   = $this->setEmpty($v);
                }
            }
        }elseif(is_null($data)){
            $data       = '';
        }
        return $data;
    }

    /**
     * json 的成功返回
     */
    public function successjs($data = null, $msg = ''){
        return $this->respjs($data, $msg);
    }

    /**
     * json 的失败返回
     */
    public function errorjs($msg = '', $data = null){
        return $this->respjs($data, $msg, 500);
    }

    /**
     * json 的返回通用接口
     */
    public function respjs($data = null, $msg = '', $code = 200, $respcode = 200){
        $data           = $this->setEmpty($data);
        $rs     = [
            'code'      => $code,
            'data'      => $data,
            'msg'       => $msg,
        ];
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($rs, JSON_FORCE_OBJECT));
        return response()->json($rs, $respcode ? $respcode : $code);
    }
}
