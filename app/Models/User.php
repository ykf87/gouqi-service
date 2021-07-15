<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

class User extends Authenticatable{
    use HasFactory, Notifiable;

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
     * 根据用户生成token
     */
    public static function Token(self $user){
        // $config = $container->get(Configuration::class);
        // assert($config instanceof Configuration);
        $config = Configuration::forSymmetricSigner(
            // You may use any HMAC variations (256, 384, and 512)
            new Sha256(),
            // replace the value below with a key of your own!
            InMemory::base64Encoded('mBC5v1sOKVvbdEitdSBenu59nfNfhwkedkJVNabosTw=')
            // You may also override the JOSE encoder/decoder if needed by providing extra arguments here
        );

        $token = $config->builder()
                        ->issuedBy(env('APP_NAME'))
                        ->withClaim('uid', $user->id)
                        ->withHeader('gouqi', 'yhc')
                        ->getToken($config->signer(), $config->signingKey());

        $token->headers(); // Retrieves the token headers
        $token->claims(); // Retrieves the token claims

        // echo $token->headers()->get('foo'); // will print "bar"
        // echo $token->claims()->get('iss'); // will print "http://example.com"
        // echo $token->claims()->get('uid'); // will print "1"

        echo $token->toString();
    }

    /**
     * 验证token是否正确
     */
}
