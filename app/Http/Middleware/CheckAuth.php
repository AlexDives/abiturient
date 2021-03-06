<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class CheckAuth
{

    public function handle($request, Closure $next)
    {
        $persCheck = DB::table('persons')->whereNotNull('secret_string')->get();
        foreach ($persCheck as $pers) {
            $timestampStart = strtotime($pers->date_crt);
            $timestampEnd = time();
            $correntHours = round((($timestampEnd - $timestampStart) / 60) / 60);
            //if ($correntHours >= 24) DB::table('persons')->where('id', $pers->id)->update(['secret_string' => 'delete']);
            if ($correntHours >= 24) DB::table('persons')->where('id', $pers->id)->delete();
        }

        if (!$request->session()->has('user_id') && !$request->session()->has('person_id')) {
            echo '<script>location.replace("/");</script>'; exit;
        } else {
            if (session('role_id') == 5) 
            {
                $user = DB::table('persons')->where(['id' => session('person_id')])->first();
                if ($user->is_block == 'T') 
                {
                    CheckAuth::logOut($request);
                }
                else return $next($request);
            }
            else {
                $user = DB::table('users')->where(['id' => session('user_id')])->first();
                if ($user->is_block == 'T') 
                {
                    CheckAuth::logOut($request);
                }
                else 
                {
                    DB::table('users')->where('id', session('user_id'))->update(['last_active' => date("Y-m-d H:i:s",time())]);
                    $role = DB::table('roles')
                        ->leftJoin('user_roles', 'user_roles.role_id', '=', 'roles.id')
                        ->select('roles.*')
                        ->where('user_roles.user_id', session('user_id') )->first();
                    session(['role_id' => $role->id]); 
                    if ($role->id > 0 && $role->id < 5)
                        return $next($request);
                    else 
                    {
                        CheckAuth::logOut($request);
                    }
                }
            }
        }
    }

    function logOut($request)
    {
        $request->session()->getHandler()->destroy($request->session()->getID());
        echo '<script>location.replace("/");</script>'; exit;
    }
}
