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

    public function like(Request $request, $id , $val)
    {
      $comment = Comment::find($id);
      if(!isset($comment))
        throw new HttpException(404, "comment");
      //TODO: AUTHORIZE  
      $change = ['comment_id' => $id ,'upvotes' => 0, 'downvotes' => 0];

      $like = $comment->userLikes()->wherePivot('user_id' , Auth::user()->userable->regular_user_id)->first();
      if(!$like){
        DB::table('user_comment_reaction')
              ->insert(['user_id' => Auth::user()->userable->regular_user_id,
                'comment_id' => $id,
                'like_or_dislike' => $val]);
        if($val == 0){
          $comment->increment('downvotes');
          $change['downvotes'] = 1;
        }else{
          $comment->increment('upvotes');
          $change['upvotes'] = 1;
        }
      }else{ //se o like ja existir
        if($like->pivot->like_or_dislike == $val){ //se o valor for igual então tirar o like
          $like->pivot->delete();
          if($val == 0){
            $comment->decrement('downvotes');
            $change['downvotes'] = -1;
          }else{
            $comment->decrement('upvotes');
            $change['upvotes'] = -1;
          }
        }else{ //mudar o like
          $like->pivot->update(['like_or_dislike' => $val]);
          if($val == 0){
            $comment->increment('downvotes'); $comment->decrement('upvotes');
            $change = ['comment_id' => $id ,'upvotes' => -1, 'downvotes' => 1];
          }else{
            $comment->increment('upvotes'); $comment->decrement('downvotes');
            $change = ['comment_id' => $id ,'upvotes' => 1, 'downvotes' => -1];
          }
        }
      }

      return $change;
    }

}