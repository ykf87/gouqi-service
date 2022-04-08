<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Globals\Ens;
use App\Models\PcClient;

class OkexClientController extends Controller{
	public function auth(Request $request){
		$uuid 		= $request->input('uuid');
		if(!$uuid){
			return response('', 404);
		}
		$uuid 		= Ens::decrypt($uuid);
		if(!$uuid){
			return response('', 404);
		}

		$row 		= PcClient::where('uuid', $uuid)->first();
		if(!$row){
			$randpwd = '';
			for ($i = 0; $i < 16; $i++){
				$randpwd .= chr(mt_rand(33, 126));
			}

			$row 	= new PcClient;
			$row->uuid 		= $uuid;
			$row->ip 		= $request->ip();
			$row->key 		= Ens::encrypt($randpwd);
			$row->status 	= 0;
			$row->save();
			return response()->json(['code' => 401, 'msg' => '您的设备未授权!']);
		}
		if($row->status != 1){
			return response()->json(['code' => 401, 'msg' => '您的设备未授权!']);
		}
		$now 		= time();
		if($row->starttime > 0 && $row->starttime > $now){
			return response()->json(['code' => 401, 'msg' => '授权未生效,生效时间: ' . date('Y-m-d H:i:s', $row->starttime)]);
		}
		if($row->endtime > 0 && $row->endtime <= $now){
			return response()->json(['code' => 401, 'msg' => '授权已过期!']);
		}
		return response()->json(['code' => 200, 'msg' => '成功!', 'key' => $row->key]);
	}
}
