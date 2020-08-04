<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Post;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
  public function store(Request $request, Post $post)
  {
    $request->validate([
      'comment' => 'required|min:4'
    ]);
    $comment = new Comment();
    $comment->comment = $request->comment;
    $comment->post_id = $post->id;
    $comment->user_id = Auth::id();
    $comment->save();

    Toastr::success('Thank you for you comment!', 'Comment');

    return redirect()->back();
  }
}
