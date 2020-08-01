<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

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

  private function deleteExistingImage($path, $old_image)
  {
    $old_image_path = "{$path}/{$old_image}";
    if (Storage::disk('public')->exists($old_image_path)) {
      Storage::disk('public')->delete($old_image_path);
    }
  }

  private function storeImage($path, $image, $image_name, $width, $height, $old_image)
  {
    // Check category Dir exists otherwise create it
    if (!Storage::disk('public')->exists($path)) {
      Storage::disk('public')->makeDirectory($path);
    }

    if (!empty($old_image)) {
      // Delete existing image
      $this->deleteExistingImage($path, $old_image);
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
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function store(Request $request)
  {
    $request->validate(
      [
        'category_name' => 'required|unique:categories,name',
        'category_image' => 'mimes:jpg,jpeg,png'
      ]
    );

    $image = $request->file('category_image');
    $category_name = $request->category_name;
    $slug = str_slug($category_name);

    $category = new Category();
    $category->name = $category_name;
    $category->slug = $slug;

    if (isset($image)) {
      //Make an unique image name
      $image_name = $this->createUniqueImageName($image, $slug);

      //Store image in category Dir
      $this->storeImage('category', $image, $image_name, 1600, 479, null);

      // store image in category/slider Dir
      $this->storeImage('category/slider', $image, $image_name, 500, 333, null);

      $category->image = $image_name;
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
   * @return \Illuminate\Http\RedirectResponse
   */
  public function update(Request $request, Category $category)
  {
    $request->validate(
      [
        'category_name' => 'required|unique:categories,name,'.$category->id,
        'category_image' => 'mimes:jpg,jpeg,png'
      ]
    );

    $category_name = $request->category_name;
    $slug = str_slug($category_name);
    $category->name = $category_name;
    $category->slug = $slug;

    $image = $request->file('category_image');

    if (isset($image)) {
      $date = Carbon::now()->toDateString();
      $unique_id = uniqid();
      $extension = $image->getClientOriginalExtension();
      $image_name = "{$slug}-{$date}-{$unique_id}.${extension}";

      $this->storeImage('category', $image, $image_name, 1600, 479, $category->image);

      $this->storeImage('category/slider', $image, $image_name, 500, 333, $category->image);

      $category->image = $image_name;
    }

    $category->save();

    return redirect()
      ->route('admin.category.index')
      ->with('successMsg', 'Category Updated Successfully');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param \App\Category $category
   * @return \Illuminate\Http\RedirectResponse
   */
  public function destroy(Category $category)
  {
    $category->delete();

    $this->deleteExistingImage('category', $category->image);
    $this->deleteExistingImage('category/slider', $category->image);

    return redirect()
      ->route('admin.category.index')
      ->with('successMsg', 'Category Deleted Successfully');
  }
}
