<?php

namespace App\Http\Controllers;

use App\Post;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavouriteController extends Controller
{
    public function add(Post $post)
    {
        $isFavourite = $post->favourite_to_users()->where('user_id', Auth::id())->count();
        if (!$isFavourite) {
            $post->favourite_to_users()->attach(Auth::user());

            Toastr::info('Thank you for like this post', 'Liked Successfully');
            return redirect()->back();
        }

        $post->favourite_to_users()->detach(Auth::user());
        Toastr::warning('Post successfully remove from your favourite list', 'Remove Liked');
        return redirect()->back();


    }
}
