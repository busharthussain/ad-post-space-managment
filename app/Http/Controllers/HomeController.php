<?php

namespace App\Http\Controllers;

use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['changeLanguage']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        \Auth::logout();
//       $data = \DB::table('password_resets')->get();
//        dd($data);
//        return view('home');
    }
    function changeLanguage($lang){
        if($lang == 'dutch'){
            session(['lang'=>$lang]);
        }else{
            session(['lang'=>'english']);
        }
        return back();
    }
}
