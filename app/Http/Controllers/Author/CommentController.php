<?php

namespace App\Http\Controllers\Author;

use App\Comment;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
  public function index()
  {
    $comments = [];
    foreach (Auth::user()->posts as $post) {
      if ($post->comments->count()) {
        foreach ($post->comments as $comment) {
          $comments[] = $comment;
        }
      }
    }

    return view('author.comments.index', compact('comments'));
  }

  public function destroy(Comment $comment)
  {
    $comment->delete();
    Toastr::success('Comment deleted successfully', 'Comment Deleted');
    return redirect()->back();
  }
}
