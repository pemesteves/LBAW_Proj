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

      $request->validate([
        'body' => "required|string|regex:/^[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]+[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@ ]*[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]$/i|max:255",
      ]);

      $comment->body = $request->input('body');
      $comment->post_id = $post_id;
      $comment->user_id = Auth::user()->userable->regular_user_id;
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

      $request->validate([
        'body' => "required|string|regex:/^[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]+[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@ ]*[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]$/i|max:255",
      ]);

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

      $request->validate([
        'title' => 'required|string|regex:/^[a-z0-9áàãâéêíóõôú]+[a-z0-9áàãâéêíóõôú ]*[a-z0-9áàãâéêíóõôú]$/i|max:255',
        'description' => "required|string|regex:/^[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]+[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@ ]*[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]$/i|max:255",
      ]);

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