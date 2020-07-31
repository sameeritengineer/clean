<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->roles()->first()->name == "admin")
        {
            return view('home');
        }
        else if(auth()->user()->roles()->first()->name == "support")
        {
            return view('support.dashboard');
        }
        else if(auth()->user()->roles()->first()->name == "manager")
        {
            return view('manager.dashboard');
        }
        else if(auth()->user()->roles()->first()->name == "supervisior")
        {
            return view('supervisior.dashboard');
        }
        else
        {
            return view('home');
        }
    }
}
