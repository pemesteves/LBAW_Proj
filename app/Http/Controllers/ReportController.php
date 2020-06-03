<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Report;
use App\Post;
use App\Comment;
use App\Group;
use App\Event;
use App\RegularUser;

class ReportController extends Controller{
    /**
     * Accept report
     */
    function accept(Request $request, $id){
        $report = Report::find($id);
        $this->authorize('approve',$report);
        $report->approval = true;

        switch($report->referenceTo()){
            case 'Post':
                $post = Post::find($report->reported_post_id);
                $post->type = "blocked";
                $post->save();
            break;
            case 'Comment':
                $comment = Comment::find($report->reported_comment_id);
                $comment->type = "blocked";
                $comment->save();
            break;
            case 'Event':
                $event = Event::find($report->reported_event_id);
                $event->type = "blocked";
                $event->save();
            break;
            case 'Group':
                $group = Group::find($report->reported_group_id);
                $group->type = "blocked";
                $group->save();
            break;
            case 'User':
                $user = RegularUser::find($report->reported_user_id);
                $user->type = "blocked";
                $user->save();
            break;
        }
        
        $report->save();

        return ['id' => $id];
    }
    /**
     * Ignore report
     */
    function decline(Request $request, $id){
        $report = Report::find($id);
        $this->authorize('approve',$report);
        $report->approval = false;

        switch($report->referenceTo()){
            case 'Post':
                $post = Post::find($report->reported_post_id);
                $post->type = "normal";
                $post->save();
            break;
            case 'Comment':
                $comment = Comment::find($report->reported_comment_id);
                $comment->type = "normal";
                $comment->save();
            break;
            case 'Event':
                $event = Event::find($report->reported_event_id);
                $event->type = "normal";
                $event->save();
            break;
            case 'Group':
                $group = Group::find($report->reported_group_id);
                $group->type = "normal";
                $group->save();
            break;
            case 'User':
                $user = RegularUser::find($report->reported_user_id);
                $user->type = "normal";
                $user->save();
            break;
        }

        $report->save();
        return ['id' => $id];

    }

}