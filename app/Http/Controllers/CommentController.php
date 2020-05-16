<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Comment;
use App\Report;
use App\Events\NewComment;

class CommentController extends Controller{

    /**
     * Creates a new comment.
     *
     * @return Comment The comment created.
     */
    public function create(Request $request, $post_id)
    {
      $comment = new Comment();

      $this->authorize('create', $comment);

      $comment->body = $request->input('body');
      $comment->post_id = $post_id;
      $comment->user_id = Auth::user()->user_id;//Auth::user()->user_id; //Change this to the id of the regular_user
      $comment->save();

      //Gets useful information about the comment
      $new_comment = Comment::take(1)->where("comment_id", '=', $comment["comment_id"])->get();
      
      broadcast(new NewComment($comment))->toOthers();

      return $new_comment[0];
    }

    public function delete(Request $request, $id)
    {
      $comment = Comment::find($id);

      //$this->authorize('delete', $comment);
      $comment->delete();

      return $comment;
    }

    public function update(Request $request, $id)
    {
      $comment = Comment::find($id);


      //TODO::CHECKPOLICE
      $comment->body = $request->input('body');

      $comment->save();

      return $comment;
    }

        /**
     * Report a comment.
     *
     * @return Report The comment created.
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
      $report->reported_comment_id = $id;

      $report->save();
      return $report;
    }

}