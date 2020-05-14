<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Post;
use App\Group;
use App\Report;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpKernel\Exception\HttpException;

class GroupController extends Controller{

    public function show($id){
      if (!Auth::check()) return redirect('/login');

      $group = Group::find($id);
      if(!isset($group))
        throw new HttpException(404, "group");

      $this->authorize('show', $group);

      $posts = $group->posts;

      $members = $group->members();

      $owner = DB::table('user_in_group')
            ->where('group_id', '=', $id)
            ->where('user_id', '=', Auth::user()->userable->regular_user_id)
            ->select('admin')
            ->limit(1);

      return view('pages.group' , ['is_admin' => false , 'group' => $group, 'posts' => $posts, 'members' => $members, 'can_create_events' => Auth::user()->userable->regular_userable_type == 'App\Organization', 'is_owner' => $owner ]);
    }

    public function showCreateForm(){
      if (!Auth::check()) return redirect('/login');

      $this->authorize('create', 'App\Group');

      return view('pages.create_group', ['is_admin' => false, 'can_create_events' => Auth::user()->userable->regular_userable_type == 'App\Organization']);
    }

    public function create(Request $request){
      if (!Auth::check()) return redirect('/login');

      $this->authorize('create', 'App\Group');
      
      $group = DB::transaction(function(){
        $group = new Group();
        $group->name = Input::get('name');
        $group->information = Input::get('information');
        $group->save();

        DB::table('user_in_group')->insert([
              'user_id' => Auth::user()->userable_id,
              'group_id' => $group->group_id,
              'admin' => true
            ]);
            
        return $group;
      });
    
      return redirect()->route('groups.show', $group);
    }

    public function show_edit($id){
      if(!Auth::check()) return redirect('/login');

      $group = Group::find($id);
      if(!isset($group))
        throw new HttpException(404, "group");

      $this->authorize('edit', $group);
      
      return view('pages.edit_group' , ['is_admin' => false , 'group' => $group, 'can_create_events' => Auth::user()->userable->regular_userable_type == 'App\Organization' ]);
    }

    /**
     * Edits the group
     */
    public function edit(Request $request, $id){
      $group = Group::find($id);

      if(!isset($group))
        throw new HttpException(404, "group");

      $this->authorize('edit', $group);

      $name = $request->input('name');
      $information = $request->input('information');

      $group->update(['name' => $name, 'information' => $information]);

      return GroupController::show($group->group_id);
    }

    /**
     * Report a group.
     *
     * @return Group The group reported.
     */
    public function report(Request $request, $id)
    { 
      $title = $request->input('title');
      $description = $request->input('description');
      $reporter_id = Auth::user()->userable->regular_user_id;

      $report = new Report();
      $report->title = $title;
      $report->reason = $description;
      $report->reporter_id = $reporter_id;
      $report->reported_group_id = $id;

      $report->save();
      return $report;
    }
}