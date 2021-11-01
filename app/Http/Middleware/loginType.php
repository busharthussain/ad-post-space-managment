<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 8/13/2018
 * Time: 9:44 AM
 */
namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;

class loginType {

    public function handle($request, Closure $next, $guard = null)
    {
        if (isAppUser()) {
//            \Session::flash('message', 'Password is reset successfully!');
            \Auth::logout();
            return redirect()->back()->with('message', 'Password is reset successfully!');
            return redirect('/login');
        }

        return $next($request);
    }
}
