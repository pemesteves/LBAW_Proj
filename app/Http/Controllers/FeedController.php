<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Post;
use App\Report;
use App\RegularUser;

class FeedController extends Controller{

    public function show(){
        if (!Auth::check()) return redirect('/login');

        $user =  Auth::user();

        //return get_class($user->userable);
                    
        if(get_class($user->userable) == "App\RegularUser"){
            $posts = Post::orderBy('date','desc')->get();

            return view('pages.feed' , ['is_admin' => false , 'posts' => $posts , 'groups' => Auth::user()->userable->groups , 'events' => Auth::user()->events, 'can_create_events' => Auth::user()->userable->regular_userable_type == 'App\Organization']);
        }
        else if(get_class($user->userable) == "App\Admin"){

            $reports = Report::whereNull('approval')->orderBy('report_id','desc')->get();

            return view('pages.report_feed' , ['reports' => $reports, 'is_admin' => true, 'can_create_events' => Auth::user()->userable->regular_userable_type == 'App\Organization']);
        }

    }
}