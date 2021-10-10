<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Phone;
use App\Models\Person;

class AutojsConteoller extends Controller{
    public function index(Request $request){
    	$uuid 		= $request->input('uuid');
    	if(!$uuid){
    		return response()->json([], 400);
    	}

    	$phone 		= Phone::where('uuid', $uuid)->first();
    	if(!$phone){
    		$ps 	= Person::where('status', 1)->get()->toArray();
    		if(count($ps) < 1){
    			return response()->json([], 401);
    		}
    		$index 	= array_rand($ps, 1);
    		$config = $ps[$index]['config'];

    		$phone 	= new Phone;
    		$phone->uuid 	= $uuid;
    		$phone->person 	= $ps[$index]['id'];
    		$phone->info 	= json_encode($request->all());
    		$phone->save();
    	}elseif($phone->status == 0){
    		return response()->json([], 500);
    	}else{
    		$cid 	= $phone->person;
    		$config = Person::find($cid)->pluck('config');
    	}
    	dd($config);
    }
}
