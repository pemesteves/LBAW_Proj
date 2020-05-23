<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Post;
use App\Report;
use App\RegularUser;
use App\OrgApproval;

class FeedController extends Controller{

    public function show(){
        if (!Auth::check()) return redirect('/login');

        $user =  Auth::user();

        //return get_class($user->userable);
                    
        if(get_class($user->userable) == "App\RegularUser"){
            //$posts = Post::orderBy('date','desc')->get();
            $myPosts = Post::where([['author_id', Auth::user()->userable->regular_user_id],['type','<>','blocked']])
                            ->whereNull('post.event_id')
                            ->whereNull('post.group_id')
                            /*->leftJoin("report" , "post.post_id","report.reported_post_id")
                            ->whereNull('report.approval')*/
                            ->select("post.*");
            $myGroupsPosts = Post::join('user_in_group' , "post.group_id" , "user_in_group.group_id")
                                ->where([['user_in_group.user_id', Auth::user()->userable->regular_user_id],['post.type','<>','blocked']])
                                /*->leftJoin("report" , "post.post_id","report.reported_post_id")
                                ->whereNull('report.approval')*/
                                ->select("post.*");
            $myEventsPosts = Post::join('user_interested_in_event' , "post.event_id" , "user_interested_in_event.event_id")
                                ->where([['user_interested_in_event.user_id', Auth::user()->userable->regular_user_id],['post.type','<>','blocked']])
                                /*->leftJoin("report" , "post.post_id","report.reported_post_id")
                                ->whereNull('report.approval')*/
                                ->select("post.*");

            $posts = Post::join('friend' , "author_id" , "friend_id2")
                    ->where( [["friend_id1" , "=" , Auth::user()->userable->regular_user_id] ,
                            ['friend.type','accepted'],['post.type','<>','blocked']])
                            ->whereNull('post.event_id')
                            ->whereNull('post.group_id')
                    /*->leftJoin("report" , "post.post_id","report.reported_post_id")
                    ->whereNull('report.approval')*/
                    ->select("post.*")
                    ->union($myPosts)
                    ->union($myGroupsPosts)
                    ->union($myEventsPosts)
                    ->orderBy('date','desc')
                    ->get();
            //->join('friend as friend_gen2',"friend_id2",)

            $closest = DB::table("regular_user")->join("friend as gen1", "regular_user_id" , '=', "gen1.friend_id2")
                            ->join("friend as gen2" , "gen1.friend_id2", '=' , "gen2.friend_id1")
                            ->where([
                                ["gen1.friend_id1",Auth::user()->userable_id],
                                ['gen1.type','accepted'],
                                ['gen2.friend_id2', '<>' ,Auth::user()->userable_id],
                                ['gen2.type','accepted']])
                            ->groupBy('gen1.friend_id2')
                            ->orderByRaw('N_common DESC')
                            ->select('gen1.friend_id2 as axis' , DB::raw('count(gen2.friend_id2) as N_common' ) )
                            ->limit(5);

            $recommendations_user = DB::table("regular_user")->joinSub($closest,'best',function ($join) {
                                    $join->on('regular_user_id', '=', 'best.axis');
                                })
                                ->join('friend as friendsOfFriends','best.axis','friendsOfFriends.friend_id1') 
                                ->where([
                                    ['friendsOfFriends.type','accepted'],
                                    ['friendsOfFriends.friend_id2', '<>' ,Auth::user()->userable_id],
                                ])
                                ->whereNOTIn('friendsOfFriends.friend_id2',function($query){
                                    $query->select('friend_id2')->from('friend')
                                    ->where([['friend_id1',Auth::user()->userable_id]]);
                                 })
                                 ->whereNOTIn('friendsOfFriends.friend_id2',function($query){
                                    $query->select('friend_id1')->from('friend')
                                    ->where([['friend_id2',Auth::user()->userable_id]]);
                                 })
                                //->groupBy('friendsOfFriends.friend_id2')
                                ->select('friendsOfFriends.friend_id2')
                                
                                ;
            $recommendations_pre =  DB::table("regular_user")->joinSub($recommendations_user , 'rec' , function ($join) {
                                    $join->on('regular_user_id', '=', 'rec.friend_id2');
                                })
                                ->select('rec.friend_id2',DB::raw('count(rec.friend_id2) as N_common' ))
                                ->groupBy('rec.friend_id2')
                                ->orderByRaw('N_common DESC')
                                ->limit(10)
                                 ; 
            $recommendations_t = RegularUser::joinSub($recommendations_pre , 'rec' , function ($join) {
                $join->on('regular_user_id', '=', 'rec.friend_id2');
            })->get();    

            $recommendations = $recommendations_t->shuffle()->random(min(count($recommendations_t),3));

            //return $recommendations->toSql();

            return view('pages.feed' , ['is_admin' => false , 
            'posts' => $posts , 
            'groups' => Auth::user()->userable->groups  ,
            'events' => Auth::user()->userable->events, 
            'can_create_events' => Auth::user()->userable->regular_userable_type == 'App\Organization',
            'recommendations' => $recommendations]);
        }
        else if(get_class($user->userable) == "App\Admin"){
            return redirect('/admin');
        }

    }

    public function show_admin_feed(){
        $reports = Report::whereNull('approval')->orderBy('report_id','desc')->get();
        $requests = OrgApproval::whereNull('approval')->orderBy('request_id','desc')->get();

        return view('pages.admin_feed' , ['reports' => $reports, 'is_admin' => true, 'can_create_events' => Auth::user()->userable->regular_userable_type == 'App\Organization', 'requests' => $requests]);
    
    }
}