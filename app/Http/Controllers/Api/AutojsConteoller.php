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

    	$arr 		= [7 => [24 => 10, 50 => 15], 8 => [0 => 10], 9 => [0 => 10], 10 => [0 => 20], 11 => [0 => 13], 12 => [0 => 15], 13 => [8 => 0], 14 => [23], 15 => [11], 21 => [40 => 13, 50 => 22], 22 => [01 => 11, 40 => 20], 23 => [01 => 10, 29 => 5]];
    	return response()->json($arr, 200);
    }
}
