<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AboutController extends Controller{

    public function show(){
        if (!Auth::check()) return redirect('/login');

        return view('pages.about' , ['css' => ['navbar.css','about.css'],'can_create_events' => Auth::user()->userable->regular_userable_type == 'App\Organization', 'notifications' => Auth::user()->userable->notifications]);

    }
}