<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Device;

class CheckController extends Controller{
    public function uid(Request $request){
    	$uid 		= $request->header('UID', '');
    	if(!$uid){
    		return false;
    	}
    	$dvc 	= Device::where('uid', $uid)->first();
    	if(!$dvc){
            $oc         = new Device;
            $oc->uid    = $uid;
            $oc->save();
    		return false;
    	}elseif($dvc->status != 1){
            return false;
        }
    	echo password_hash($dvc->uid, PASSWORD_DEFAULT);
    	return true;
    }
}
