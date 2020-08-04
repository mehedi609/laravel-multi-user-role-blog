<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function details($slug)
    {
        $post = Post::where('slug',$slug)
          ->where('is_approved', true)
          ->where('status', true)
          ->first();
        $random_posts = Post::all()->random(3);
        return view('post-details', compact('post', 'random_posts'));
    }
}
