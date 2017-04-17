<?php

namespace App\Http\Controllers;

class FrontendController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
    
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('layouts.app');
    }
}