<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PostController extends Controller
{
    public function details($slug)
    {
        $post = Post::where('slug',$slug)
          ->where('is_approved', true)
          ->where('status', true)
          ->first();
        $random_posts = Post::all()->random(3);

        $blog_key = "blog_{$post->id}";
        if (!Session::has($blog_key)) {
          $post->increment('view_count');
          Session::put($blog_key, 1);
        }

      $key = Session::get($blog_key);

        return view('post-details', compact('post', 'random_posts'));
    }
}