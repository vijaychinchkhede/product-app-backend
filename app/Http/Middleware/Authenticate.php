<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use App\Models\User;
use Illuminate\Support\Str;


class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    // protected function redirectTo($request)
    // {
    //     if (! $request->expectsJson()) {
    //         return route('login');
    //     }
    // }

    public function handle($request, Closure $next)
    {
        $header = $request->header();
        $strToken = $header['authorization'] ? Str::after($header['authorization'][0], 'Bearer '):'';
        $intUser = User::where('token',$strToken)->count();
        if ($intUser > 0) {
            return $next($request);
        }else{
            return response()->json(['status'=>401 ,'message' =>'Unauthorized']);
        }
 
    }
}
