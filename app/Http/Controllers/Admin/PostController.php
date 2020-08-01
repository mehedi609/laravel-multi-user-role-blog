<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Post;
use App\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::latest()->get();
        return view('admin.post.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = Tag::all();
        $categories = Category::all();
        return view('admin.post.create', compact('tags', 'categories'));
    }

    private function deleteExistingImage($path, $old_image)
    {
        $old_image_path = "{$path}/{$old_image}";
        if (Storage::disk('public')->exists($old_image_path)) {
            Storage::disk('public')->delete($old_image_path);
        }
    }

    private function storeImage($path, $image, $image_name, $width, $height)
    {
        // Check category Dir exists otherwise create it
        if (!Storage::disk('public')->exists($path)) {
            Storage::disk('public')->makeDirectory($path);
        }

        // Resize image and upload
        $resized_image = Image::make($image)->resize($width, $height)->stream();
        Storage::disk('public')->put("{$path}/{$image_name}", $resized_image);
    }

    private function createUniqueImageName($image, $slug)
    {
        $currentDate = Carbon::now()->toDateString();
        $uniqId = uniqid();
        $extension = $image->getClientOriginalExtension();
        return "{$slug}-{$currentDate}-{$uniqId}.{$extension}";
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
        $post->is_approved = true;

        if (isset($image)) {
            //Make an unique image name
            $image_name = $this->createUniqueImageName($image, $slug);

            //Store image in post dir
            $this->storeImage('post', $image, $image_name, 1600, 1066);

            $post->image = $image_name;
        }

        $post->save();

        $post->categories()->attach($request->categories);
        $post->tags()->attach($request->tags);

        return redirect(route('admin.post.index'))
            ->with('successMsg', 'Post Created Successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function show(Post $post)
    {
        $tags = $post->tags;
        $categories = $post->categories;

        return view('admin.post.show', compact('post', 'categories', 'tags'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $categories = Category::all();
        $tags = Tag::all();

        return view('admin.post.edit', compact('post', 'categories', 'tags'));
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
        $post->is_approved = true;

        $image = $request->file('post_image');

        if (isset($image)) {
            $image_name = $this->createUniqueImageName($image, $slug);

            //delete existing image
            $this->deleteExistingImage('post', $post->image);

            //store new image
            $this->storeImage('post', $image, $image_name, 1600, 1066);

            $post->image = $image_name;
        }

        $post->save();
        $post->categories()->sync($request->categories);
        $post->tags()->sync($request->tags);

        return redirect(route('admin.post.index'))
            ->with('successMsg', 'Post updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $this->deleteExistingImage('post', $post->image);

        $post->categories()->detach();
        $post->tags()->detach();
        $post->delete();

        return redirect(route('admin.post.index'))->with('successMsg', 'Post deleted successfully');
    }
}
