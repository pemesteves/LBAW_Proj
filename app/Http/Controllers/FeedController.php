<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Post;
use App\Report;
use App\RegularUser;
use App\Event;
use App\Group;
use App\OrgApproval;
use Symfony\Component\HttpKernel\Exception\HttpException;

class FeedController extends Controller{
    /**
     * Show posts of the events user is interested in, shoe posts of groups user is in, shoe posts of users the user is friends with,
     * show user's posts and 
     * compute 3 random users of the 10 users with most friends in commum of the 5 friends the user has most friends in commun with
     */
    public function show(){
        if (!Auth::check()) return redirect('/login');

        $user =  Auth::user();

        //return get_class($user->userable);
                    
        if(get_class($user->userable) == "App\RegularUser"){
            //$posts = Post::orderBy('date','desc')->get();
            $myPosts = Post::where([['author_id', Auth::user()->userable->regular_user_id],['type','normal']])
                                ->whereNull('post.event_id')
                                ->whereNull('post.group_id')
                                ->select("post.*");
            $myGroupsPosts = Post::join('user_in_group' , "post.group_id" , "user_in_group.group_id")
                                        ->where([['user_in_group.user_id', Auth::user()->userable->regular_user_id],['post.type','normal']])
                                        ->select("post.*");
            $myEventsPosts = Post::join('user_interested_in_event' , "post.event_id" , "user_interested_in_event.event_id")
                                        ->where([['user_interested_in_event.user_id', Auth::user()->userable->regular_user_id],['post.type','normal']])
                                        ->select("post.*");

            $posts = Post::join('friend' , "author_id" , "friend_id2")
                    ->where( [["friend_id1" , "=" , Auth::user()->userable->regular_user_id] ,
                            ['friend.type','accepted'],['post.type','normal']])
                            ->whereNull('post.event_id')
                            ->whereNull('post.group_id')
                    ->select("post.*")
                    ->union($myPosts)
                    ->union($myGroupsPosts)
                    ->union($myEventsPosts)
                    ->orderBy('date','desc')
                    ->limit(5)
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
            'css' => ['posts.css','post_form.css','navbar.css','feed.css','menu.css','chat.css'],
            'js' => ['post.js','infinite_scroll.js','friendship.js','general.js', 'uploadImages.js', 'event.js', 'group.js'],
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
    /**
     * Show reports and request of verification for organizations for Admin
     */
    public function show_admin_feed(){
        $reports = Report::whereNull('approval')->orderBy('report_id','desc')->get();
        $reported = Report::where('approval','True')->orderBy('report_id','desc')->get();
        $requests = OrgApproval::where('type', 'pending')->orderBy('request_id','desc')->get();
        $requested = OrgApproval::where('type','accepted')->orderBy('request_id','desc')->get();

        return view('pages.admin_feed' , ['reports' => $reports, 
        'css' => ['navbar.css','admin.css'],
        'js' => ['reports.js'],
        'reported' => $reported, 'is_admin' => true, 'requests' => $requests, 'requested' => $requested]);
    
    }

    public function searchUsers(Request $request ){
        $str = strtolower($request->input('search'));
        $users = RegularUser::select(["*", "regular_user.regular_user_id as regularUserId"])
                              ->join('user','user.user_id','regular_user.user_id')
                              ->whereRaw('lower(name) LIKE \'%'.$str.'%\'')
                              ->leftjoin('image', 'regular_user.regular_user_id', '=','image.regular_user_id')
                              ->leftjoin('file', 'file.file_id', '=', 'image.file_id')
                              ->get();

        return view('pages.search',
        ['css' => ['navbar.css','feed.css','menu.css', 'search.css'],
        'js' => ['general.js'],
         'str' => $str  ,'users' => $users,'events' => null, 'groups' => null, 'posts' => null]);
    }

    public function searchEvents(Request $request ){
        $str = strtolower($request->input('search'));
        $events = Event::select(["*", "event.name as eventName", "event.event_id as eventId"])
                         ->whereRaw('lower(event.name) LIKE \'%'.$str.'%\'')
                         ->rightjoin('organization', 'organization.organization_id', 'event.organization_id')
                         ->rightjoin('regular_user', 'regular_user.regular_user_id', 'organization.regular_user_id')
                         ->rightjoin('user', 'regular_user.user_id', 'user.user_id')
                         ->leftjoin('image', 'event.event_id', '=','image.event_id')
                         ->leftjoin('file', 'file.file_id', '=', 'image.file_id')
                         ->get();

        return view('pages.search',
        ['css' => ['navbar.css','feed.css','menu.css', 'search.css'],
        'js' => ['general.js'],
         'str' => $str  ,'users' => null,'events' => $events, 'groups' => null, 'posts' => null]);
    }

    public function searchGroups(Request $request ){
        $str = strtolower($request->input('search'));
        $groups = Group::select(["*", "group.group_id as groupId"])
                         ->whereRaw('lower(name) LIKE \'%'.$str.'%\'')
                         ->leftjoin('image', 'group.group_id', '=','image.group_id')
                         ->leftjoin('file', 'file.file_id', '=', 'image.file_id')
                         ->join('user_in_group', function($join){
                            $join->on('user_in_group.group_id', '=', 'group.group_id')
                                 ->where('user_in_group.user_id', '=', Auth::user()->userable->regular_user_id);
                         })
                         ->get();

        return view('pages.search',
        ['css' => ['navbar.css','feed.css','menu.css', 'search.css'],
        'js' => ['general.js'],
         'str' => $str  ,'users' => null,'events' => null, 'groups' => $groups, 'posts' => null]);
    }

    public function searchPosts(Request $request){
        $str = strtolower($request->input('search'));

        $all_posts =  DB::table('post')
                          ->select(["*", DB::raw("ts_rank(
                                    setweight(to_tsvector('english', title), 'A') || 
                                    setweight(to_tsvector('english', body), 'B'),
                                    plainto_tsquery('english', '".$str."')
                                ) as rank ")])
                          ->join('regular_user', 'regular_user.regular_user_id', 'post.author_id')
                          ->join('user', 'regular_user.user_id', 'user.user_id')
                          ->orderBy("rank", "desc") 
                          ->get();
            
        $posts = array();
        foreach($all_posts as $post){
            if($post->rank > 0){
                array_push($posts, $post);
            }
        }
        
        return view('pages.search',
        ['css' => ['navbar.css','feed.css','menu.css', 'search.css'],
        'js' => ['general.js'],
        'str' => $str  ,'users' => null,'events' => null, 'groups' => null, 'posts' => $posts]);
    }

    /**
     * Search users, events, groups and posts, or one of thoose.
     */

    public function search(Request $request ){

        $filter = $request->input('filter');

        switch ($filter) {
            case 'Users':
                return $this->searchUsers($request);
            case 'Events':
                return $this->searchEvents($request);
            case 'Groups':
                return $this->searchGroups($request);
            case 'Posts':
                return $this->searchPosts($request);
            case 'All':
                break;
            default: 
                throw new HttpException(404, 'page');
        }

        $str = strtolower($request->input('search'));
        $users = RegularUser::select(["*", "regular_user.regular_user_id as regularUserId"])
                              ->join('user','user.user_id','regular_user.user_id')
                              ->whereRaw('lower(name) LIKE \'%'.$str.'%\'')
                              ->leftjoin('image', 'regular_user.regular_user_id', '=','image.regular_user_id')
                              ->leftjoin('file', 'file.file_id', '=', 'image.file_id')
                              ->limit(5)
                              ->get();
                              
        $events = Event::select(["*", "event.name as eventName", "event.event_id as eventId"])
                         ->whereRaw('lower(event.name) LIKE \'%'.$str.'%\'')
                         ->rightjoin('organization', 'organization.organization_id', 'event.organization_id')
                         ->rightjoin('regular_user', 'regular_user.regular_user_id', 'organization.regular_user_id')
                         ->rightjoin('user', 'regular_user.user_id', 'user.user_id')
                         ->leftjoin('image', 'event.event_id', '=','image.event_id')
                         ->leftjoin('file', 'file.file_id', '=', 'image.file_id')
                         ->limit(5)
                         ->get();

        $groups = Group::select(["*", "group.group_id as groupId"])
                         ->whereRaw('lower(name) LIKE \'%'.$str.'%\'')
                         ->leftjoin('image', 'group.group_id', '=','image.group_id')
                         ->leftjoin('file', 'file.file_id', '=', 'image.file_id')
                         ->join('user_in_group', function($join){
                            $join->on('user_in_group.group_id', '=', 'group.group_id')
                                 ->where('user_in_group.user_id', '=', Auth::user()->userable->regular_user_id);
                         })
                         ->limit(5)
                         ->get();

        $all_posts =  DB::table('post')
                          ->select(["*", DB::raw("ts_rank(
                                    setweight(to_tsvector('english', title), 'A') || 
                                    setweight(to_tsvector('english', body), 'B'),
                                    plainto_tsquery('english', '".$str."')
                                ) as rank ")])
                          ->join('regular_user', 'regular_user.regular_user_id', 'post.author_id')
                          ->join('user', 'regular_user.user_id', 'user.user_id')
                          ->orderBy("rank", "desc") 
                          ->limit(5)->get();
            
        $posts = array();
        foreach($all_posts as $post){
            if($post->rank > 0){
                array_push($posts, $post);
            }
        }

        return view('pages.search',[
        'css' => ['navbar.css','feed.css','menu.css', 'search.css'],
        'js' => ['general.js'], 'str' => $str  ,'users' => $users,'events' => $events, 'groups' => $groups, 'posts' => $posts]);
    }

    /**
     * Get posts, used in infinite scroll
     */

    function getPosts($last_id){
      
        $myPosts = Post::where([['author_id', Auth::user()->userable->regular_user_id],['type','normal']])
                            ->whereNull('post.event_id')
                            ->whereNull('post.group_id')
                            ->where('post_id','<',$last_id)
                            ->select("post.*");
        $myGroupsPosts = Post::join('user_in_group' , "post.group_id" , "user_in_group.group_id")
                                    ->where([['user_in_group.user_id', Auth::user()->userable->regular_user_id],['post.type','normal']])
                                    ->where('post_id','<',$last_id)
                                    ->select("post.*");
        $myEventsPosts = Post::join('user_interested_in_event' , "post.event_id" , "user_interested_in_event.event_id")
                                    ->where([['user_interested_in_event.user_id', Auth::user()->userable->regular_user_id],['post.type','normal']])
                                    ->where('post_id','<',$last_id)
                                    ->select("post.*");

        $posts = Post::join('friend' , "author_id" , "friend_id2")
                ->where( [["friend_id1" , "=" , Auth::user()->userable->regular_user_id] ,
                        ['friend.type','accepted'],['post.type','normal']])
                        ->whereNull('post.event_id')
                        ->whereNull('post.group_id')
                ->select("post.*")
                ->union($myPosts)
                ->union($myGroupsPosts)
                ->union($myEventsPosts)
                ->orderBy('date','desc')
                ->where('post_id','<',$last_id)
                ->limit(3)
                ->get();

        return view('requests.posts',['posts' => $posts]);

    }

}