<?php

namespace App\Http\Controllers\Author;

use App\Category;
use App\Helpers\StoreImage;
use App\Http\Controllers\Controller;
use App\Post;
use App\Tag;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $posts = Auth::user()->posts()->latest()->get();
        return view('author.post.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $tags = Tag::all();
        $categories = Category::all();

        return view('author.post.create', compact('tags', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $request->validate([
            "post_title" => 'required|unique:posts,title',
            "post_image" => 'mimes:jpg,jpeg,png',
            "categories" => 'required',
            "tags"       => 'required',
            "post_body"  => 'required',
        ]);

        $post = new Post();

        $image = $request->file('post_image');
        $post_title = $request->post_title;
        $slug = str_slug($post_title);

        $post->title = $post_title;
        $post->slug = $slug;
        $post->user_id = Auth::id();
        $post->body = $request->post_body;
        $post->status = isset($request->status);
        $post->is_approved = false;

        if (isset($image)) {
            $storeImage = new StoreImage(
                'post', $image, 1600, 1066, $post_title
            );

            $unique_image_name = $storeImage->storeImage();

            $post->image = $unique_image_name;
        }

        $post->save();

        $post->categories()->attach($request->categories);
        $post->tags()->attach($request->tags);

        Toastr::success('Post Inserted Successfully', 'Success');

        return redirect(route('author.post.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show(Post $post)
    {
        if ($post->user_id !== Auth::id())
            return redirect()->back();

        return view('author.post.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit(Post $post)
    {
        if ($post->user_id !== Auth::id())
            return redirect()->back();

        $categories = Category::all();
        $tags = Tag::all();

        return view('author.post.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== Auth::id())
            return redirect()->back();

        $request->validate([
            'post_title' => 'required|unique:posts,title,'.$post->id,
            'post_image' => 'mimes:jpg,jpeg,png',
            'categories' => 'required',
            'tags' => 'required',
            'post_body' => 'required'
        ]);

        $post_title = $request->post_title;
        $slug = str_slug($post_title);

        $post->title = $post_title;
        $post->slug = $slug;
        $post->user_id = Auth::id();
        $post->body = $request->post_body;
        $post->status = isset($request->status);
        $post->is_approved = false;

        $image = $request->file('post_image');

        if (isset($image)) {
            $storeImage = new StoreImage(
                'post', $image, 1600, 1066, $post_title, $post->image
            );

            $unique_image_name = $storeImage->storeImage();

            $post->image = $unique_image_name;
        }

        $post->save();
        $post->categories()->sync($request->categories);
        $post->tags()->sync($request->tags);

        Toastr::success('Author Post updated successfully');

        return redirect(route('author.post.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id())
            return redirect()->back();

        StoreImage::deleteExistingImage('post', $post->image);

        $post->categories()->detach();
        $post->tags()->detach();
        $post->delete();

        Toastr::success('Author Post deleted successfully');

        return redirect(route('author.post.index'));
    }
}
