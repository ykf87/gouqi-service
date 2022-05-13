<?php
/**
 * 获取芝麻ip方法
 */
namespace App\Globals;

use GuzzleHttp\Client;
class Zhimahttp{
	public static $baseUri 	= 'https://wapi.http.linkudp.com';
	public static $timeout 	= 5.0;
	public static $proxy 	= null;
	private static $client	= null;
	private static $file 	= __DIR__ . '/sessid.txt';
	private static $header  = [
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

    public static function getLink($phone, $password, $num = 1, $howlong = 1, $repeat = 1, $protocol = 2, $province = '', $city = '350100'){
    	$params        = [
            'num'       => $num,//ip数量
            'port_type' => $protocol,//ip协议 1-HTTP 2-HTTPS 3-SOCKS5 4-SOCKS5账号密码
            'time_id'   => $howlong,//1:5-25min 2:25min-3h 3:3-6h 4:6-12h
            'type'      => 2,//返回的格式, 1-TXT 2-JSON 3-HTML
            'pro_id'    => $province,
            'city_id'   => $city,
            'yys'       => 0,//100026:联通 100017:电信
            'time_show' => true,
            'city_show' => true,
            'yys_show'  => true,
            'region_type'   => 1,
            'line_break'    => 1,
            'port_bit'      => 4,//端口号长度
            'm_repeat'      => $repeat,//1:自动去重 2:单日去重 3:不去重
        ];

        $uri 	= 'http://webapi.http.zhimacangku.com/getip?num=%d&type=2&pro=%s&city=%s&yys=0&port=2&time=%d&ts=1&ys=1&cs=1&lb=1&sb=0&pb=4&mr=%d&regions=';
        return sprintf($uri, $num, $province, $city, $howlong, $repeat);

        $sessid 		= self::sessid();
        if(!$sessid){
        	$sessid 	= self::Login($phone, $password);
        }

        $res 			= self::getLinkDo($sessid, $params);
        $res 			= json_decode($res, true);

        if(!isset($res['ret_data']['link'])){
        	$sessid 	= self::Login($phone, $password);
        	$res 		= self::getLinkDo($sessid, $params);
	        $res 		= json_decode($res, true);
	        if(!isset($res['ret_data']['link'])){
	        	return false;
	        }
        }
        return $res['ret_data']['link'];
    }

    // 请求封装
    private static function getLinkDo($sessid, $params){
    	$client 		= self::Client();
    	$response 		= $client->post('/index/api/new_get_ips', [
            'headers'   => self::Headers(['session-id' => $sessid]),
            'form_params'   => $params
        ]);
        return $response->getBody()->getContents();
    }

	private static function Login($phone, $password){
		$params     = [
            'phone'     => $phone,
            'password'  => $password,
            'remember'  => 1,
        ];
        $response 	= self::Client()->post('/index/users/login_do', [
            'headers'       => self::Headers(),
            'form_params'   => $params,
        ]);
        $body 		= $response->getBody()->getContents();
        $res 		= json_decode($body, true);
        if(isset($res['ret_data'])){
        	if(self::sessid($res['ret_data'])){
        		return $res['ret_data'];
        	}
        }
        return false;
        //{"ret":0,"code":"1","msg":"登录成功","ret_data":"93nq6va5ujs6tqbputb4krkhj5","timestamp":1652438375}
	}

	// 请求头
	private static function Headers(array $arr = []){
		$header 		= self::$header;
		if(!empty($arr)){
			$header 	= array_merge($header, $arr);
		}
		return $header;
	}

	// guzz客户端
	private static function Client(){
		if(self::$client){
			return self::$client;
		}
		$conf 		= [
			'base_uri'		=> self::$baseUri,
			'timeout'		=> self::$timeout,
		];
		if(self::$proxy){
			$conf['proxy']	= ['http' => self::$proxy, 'https' => self::$proxy];
		}
		self::$client 		= new Client($conf);
		return self::$client;
	}

	// session id
	private static function sessid($val = null){
		if($val){
			return file_put_contents(self::$file, $val);
		}elseif(!file_exists(self::$file)){
			return false;
		}
		return file_get_contents(self::$file);
	}
}