<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AboutController extends Controller{

    public function show(){
        if (!Auth::check()) return redirect('/login');

        return view('pages.about' , ['is_admin' => false]);

    }
}