<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Symfony\Component\Console\Input\Input;

class CategoryController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $categories = Category::latest()->get();
    return view('admin.category.index', compact('categories'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('admin.category.create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function store(Request $request)
  {
    $this->validate($request, [
      'category_name' => 'required|unique:categories',
      'category_image' => 'mimes:jpg,jpeg,png'
    ]);


    $image = $request->category_image;
    $category_name = $request->category_name;
    $slug = str_slug($category_name);

    $category = new Category();
    $category->name = $category_name;
    $category->slug = $slug;

    if (isset($image)) {
      //Make an unique image name
      $currentDate = Carbon::now()->toDateString();
      $uniqId = uniqid();
      $extension = $image->getClientOriginalExtension();
      $imageName = "{$slug}-{$currentDate}-{$uniqId}.{$extension}";

      // Check category Dir exists otherwise create it
      if (!Storage::disk('public')->exists('category')) {
        Storage::disk('public')->makeDirectory('category');
      }

      // Resize image and upload
      $resized_image = Image::make($image)->resize(1600, 479)->stream();
      Storage::disk('public')->put("category/{$imageName}", $resized_image);

      // Check category slider Dir exists otherwise create it
      if (!Storage::disk('public')->exists('category/slider')) {
        Storage::disk('public')->makeDirectory('category/slider');
      }

      // Resize image and upload
      $resized_slider_image = Image::make($image)->resize(500, 333)->stream();
      Storage::disk('public')->put("category/slider/{$imageName}", $resized_slider_image);

      $category->image = $imageName;
    }

    $category->save();

    return redirect()
      ->route('admin.category.index')
      ->with('successMsg', 'Category Created Successfully');

  }

  /**
   * Display the specified resource.
   *
   * @param \App\Category $category
   * @return \Illuminate\Http\Response
   */
  public function show(Category $category)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param \App\Category $category
   * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function edit(Category $category)
  {
    return view('admin.category.edit', compact('category'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param \App\Category $category
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Category $category)
  {

  }

  /**
   * Remove the specified resource from storage.
   *
   * @param \App\Category $category
   * @return \Illuminate\Http\Response
   */
  public function destroy(Category $category)
  {
    //
  }
}
