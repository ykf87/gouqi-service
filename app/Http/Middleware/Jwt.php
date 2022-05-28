<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Lcobucci\JWT\Token\Plain;
use Illuminate\Support\Facades\Log;

class Jwt{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     * ex. Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsIkF1dGhvciI6bnVsbH0.eyJpc3MiOiJMYXJhdmVsIiwiYXVkIjoiaHR0cDovL3d3dy5nb3VxaS5jb20iLCJpYXQiOjE2MjYzOTk3NjQuOTY0NjY0LCJuYmYiOjE3ODQxNjYxNjQuOTY0NjY0LCJpZCI6MX0.aEdGV0IgW1Ybo3NILHGRY1fTCF8_MFZNapEXChawDVc
     */
    public function handle(Request $request, Closure $next){
        try{
            $fenjie     = 1652343374;
            $jwt        = User::decry();
            if($jwt instanceof Plain){
                $id         = $jwt->claims()->get('id');
                if($id > 0){
                    $user   = User::find($id);
                    if(!$user){
                        return response()->json(['error' => 'Unauthorized', 'msg' => '用户不存在', 'code' => 401, 'data' => ['list' => null]]);
                    }

                    if(strtotime($user->updated_at->format('Y-m-d H:i:s')) > $fenjie){
                        try {
                            $utime  = $jwt->claims()->get('utime');
                            if($utime != $user->updated_at){
                                return response()->json(['error' => 'Unauthorized', 'msg' => '请先登录.', 'code' => 401, 'data' => ['list' => null]]);
                            }
                        } catch (\Exception $e) {
                            return response()->json(['error' => 'Unauthorized', 'msg' => '请先登录!', 'code' => 401, 'data' => ['list' => null]]);
                        }
                    }
                    $request->merge(['_uid' => $id]);
                    $request->merge(['_user' => $user]);
                    return $next($request);
                }
            }elseif($jwt instanceof User){
                $request->merge(['_uid' => $jwt->id]);
                $request->merge(['_user' => $jwt]);
                return $next($request);
            }
        }catch(\Exception $e){
            Log::error('JWT Middleware: ' . $e->getMessage());
        }

        return response()->json(['error' => 'Unauthorized', 'msg' => '请先登录!!', 'code' => 401, 'data' => ['list' => null]]);
    }
}
