<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Signer\Key\InMemory;

class User extends Authenticatable{
    use HasFactory, Notifiable;
    private static $configObj   = null;
    private static $secret = 'Le$Sshidy!sV$IUfMF4Z@0zwdzcJ9y4KIO28oBwBkTPcZxO^E7uqB39nbOx&X!ucww';
    private static $type   = 'Bearer ';
    public static $usernameKey = 'phone';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * 用户注册
     */
    public static function sigin($username, $pwd){
        $user           = new self;
        $user->phone    = $username;
        $user->pwd      = password_hash($pwd, PASSWORD_DEFAULT);

        $user->save();
        return self::login($user);
    }

    /**
     * 用户登录
     */
    public static function login(self $user){
        return self::users($user);
    }

    /**
     * 最终返回的user数组
     */
    public static function users(self $user){
        $arr                = [];
        $arr['id']          = $user->id;
        $arr['nickname']    = $user->nickname;
        $arr['avatar']      = $user->avatar;
        $arr['level']       = $user->level;
        $arr['sex']         = $user->sex;
        $arr['parent']      = $user->parent;
        $arr['username']    = $user->username;
        $arr['token']       = self::token($user);
        return $arr;
    }

    /**
     * 构造 config
     */
    public static function getConfig(){
        if(self::$configObj === null){
            $key = InMemory::plainText(SELF::$secret);
            self::$configObj = Configuration::forSymmetricSigner(
                // You may use any HMAC variations (256, 384, and 512)
                new Sha256(),
                // replace the value below with a key of your own!
                $key
                // You may also override the JOSE encoder/decoder if needed by providing extra arguments here
            );
            // self::$configObj    = Configuration::forSymmetricSigner(
            //     new Sha256(),
            //     InMemory::base64Encoded('mBC5v1sOKVvbdEitdSBenu59nfNfhwkedkJVNabosTw=')
            // );
        }
        return self::$configObj;
    }

    /**
     * 根据用户生成token
     */
    public static function token($user, $ttl = '+2 year'){
        $config         = self::getConfig();
        $now            = new \DateTimeImmutable();
        $token          = $config->builder()
                            ->issuedBy(env('APP_NAME'))
                            ->permittedFor(env('APP_URL'))
                            ->issuedAt($now)
                            ->canOnlyBeUsedAfter($now->modify($ttl))//'+365 day'
                            ->withHeader('Author', config('author'));
        if($user instanceof self){
            $user       = $user->toArray();
        }
        if(is_numeric($user)){
            $token      = $token->withClaim('id', $user);
        }elseif(is_array($user)){
            $narr           = [
                'id'        => $user['id'],
                'phone'     => Crypt::encryptString($user[self::$usernameKey]),
                'status'    => $user['status'],
            ];
            foreach($narr as $k => $v){
                $token      = $token->withClaim($k, $v);
            }
        }else{
            return null;
        }
        $token      = $token->getToken($config->signer(), $config->signingKey());
        return self::$type . $token->toString();
    }

    /**
     * 解码 token
     */
    public static function decry($token = null){
        $config             = self::getConfig();
        if(!$token){
            $request        = request();
            $token          = $request->header('Authorization');
            if(substr($token, 0, 1) == '{'){
                $token  = json_decode($token, true);
                $token  = $token['value'] ?? null;
                if(!$token){
                    return false;
                }
            }
        }
        try{
            $token      = substr($token, (strlen(self::$type)));
            if($token && substr_count($token, '.') >= 2){
                $token      = $config->parser()->parse($token);
                assert($token instanceof Plain);
                return $token;
            }
        }catch (\InvalidArgumentException $e){
            return false;
        }
        return false;
    }
}
