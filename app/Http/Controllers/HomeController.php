<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        $Breadcrumb="";
       
        return view('home', [
            'Titre' => 'Tableau de bord' ,
            'Breadcrumb'=> $Breadcrumb , // add as much as you want
         ]);
    }

    public function List()
    {
        return view('pages.Tables');
    }

    public function logout()
    {
        Auth::logout();
        Session::flush();
        return view('auth.login');
    }
}
