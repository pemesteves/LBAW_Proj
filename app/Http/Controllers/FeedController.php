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
            //$posts = Post::orderBy('date','desc')->get();
            $myPosts = Post::where('author_id', Auth::user()->userable->regular_user_id)
                            ->whereNull('post.event_id')
                            ->whereNull('post.group_id')
                            ->leftJoin("report" , "post.post_id","report.reported_post_id")
                            ->whereNull('report.approval')
                            ->select("post.*");
            $myGroupsPosts = Post::join('user_in_group' , "post.group_id" , "user_in_group.group_id")
                                ->where('user_in_group.user_id', Auth::user()->userable->regular_user_id)
                                ->leftJoin("report" , "post.post_id","report.reported_post_id")
                                ->whereNull('report.approval')
                                ->select("post.*");
            $myEventsPosts = Post::join('user_interested_in_event' , "post.event_id" , "user_interested_in_event.event_id")
                                ->where('user_interested_in_event.user_id', Auth::user()->userable->regular_user_id)
                                ->leftJoin("report" , "post.post_id","report.reported_post_id")
                                ->whereNull('report.approval')
                                ->select("post.*");

            $posts = Post::join('friend' , "author_id" , "friend_id2")
                    ->where( [["friend_id1" , "=" , Auth::user()->userable->regular_user_id] ,
                            ['friend.type','accepted']])
                            ->whereNull('post.event_id')
                            ->whereNull('post.group_id')
                    ->leftJoin("report" , "post.post_id","report.reported_post_id")
                    ->whereNull('report.approval')
                    ->select("post.*")
                    ->union($myPosts)
                    ->union($myGroupsPosts)
                    ->union($myEventsPosts)
                    ->orderBy('date','desc')
                    ->get();

            return view('pages.feed' , ['is_admin' => false , 'posts' => $posts , 'groups' => Auth::user()->userable->groups  ,'events' => Auth::user()->events, 'can_create_events' => Auth::user()->userable->regular_userable_type == 'App\Organization']);
        }
        else if(get_class($user->userable) == "App\Admin"){
            return redirect('/admin');
        }

    }

    public function show_admin_feed(){
        $reports = Report::whereNull('approval')->orderBy('report_id','desc')->get();

        return view('pages.report_feed' , ['reports' => $reports, 'is_admin' => true, 'can_create_events' => Auth::user()->userable->regular_userable_type == 'App\Organization']);
    
    }
}