<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Post;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavouriteController extends Controller
{
    public function index()
    {
        $posts = Auth::user()->favourite_posts;
        return view('admin.favourite-posts.index', compact('posts'));
    }

    public function removePostFromFavoriteList(Post $post)
    {
        $post->favourite_to_users()->detach(Auth::user());
        Toastr::info('Post remove from your favourite list', 'Remove Post');
        return redirect()->back();
    }
}
