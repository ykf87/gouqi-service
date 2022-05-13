<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Phone;
use App\Models\Person;
use GuzzleHttp\Client;
use App\Globals\Zhimahttp;

class AutojsConteoller extends Controller{
    public $header  = [
        'accept'            => 'text/html, */*; q=0.01',
        'Accept-Language'   => 'zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7',
        'Connection'        => 'keep-alive',
        'Content-Type'      => 'application/x-www-form-urlencoded; charset=UTF-8',
        'User-Agent'        => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/93.0.4577.82 Safari/537.36',
        'Host'              => 'wapi.http.linkudp.com',
        'Origin'            => 'https://zhimahttp.com',
        'Referer'           => 'https://zhimahttp.com/',
        'Sec-Fetch-Dest'    => 'empty',
        'Sec-Fetch-Mode'    => 'cors',
        'Sec-Fetch-Site'    => 'cross-site',
    ];

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

    	$arr 		= [6 => [0 => 30],7 => [0 => 10, 50 => 15], 8 => [0 => 10], 9 => [0 => 10], 10 => [0 => 20], 11 => [0 => 13], 12 => [0 => 15], 13 => [0 => 20], 14 => [23], 19 => [11], 20=>[1=>90], 21 => [0 => 100, 50 => 100], 22 => [01 => 11, 40 => 20], 23 => [01 => 10, 29 => 5]];
    	return response()->json($arr, 200);
    }

    public function zhimahttp(Request $request){
        $res    = Zhimahttp::getLink('13635241794', 'abcd1234');
        dd($res);
    }
}
