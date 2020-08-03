<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Helpers\StoreImage;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $categories = Category::latest()->get();
        return view('admin.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
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
            $unique_image_name = StoreImage::createUniqueImageName($image, $category_name);

            $storeCategoryImage = new StoreImage(
                'category', $image, 1600, 479
            );
            $storeCategoryImage->storeImage($unique_image_name);

            $storeCategorySliderImage = new StoreImage(
                'category/slider', $image, 500, 333
            );
            $storeCategorySliderImage->storeImage($unique_image_name);

            $category->image = $unique_image_name;
        }

        $category->save();

        Toastr::success('Category Created Successfully');

        return redirect(route('admin.category.index'));
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
                'category_name' => 'required|unique:categories,name,' . $category->id,
                'category_image' => 'mimes:jpg,jpeg,png'
            ]
        );

        $category_name = $request->category_name;
        $slug = str_slug($category_name);
        $category->name = $category_name;
        $category->slug = $slug;

        $image = $request->file('category_image');

        if (isset($image)) {
            /*$date = Carbon::now()->toDateString();
            $unique_id = uniqid();
            $extension = $image->getClientOriginalExtension();
            $image_name = "{$slug}-{$date}-{$unique_id}.${extension}";

            $this->deleteExistingImage('category', $category->image);
            $this->storeImage('category', $image, $image_name, 1600, 479);

            $this->deleteExistingImage('category/slider', $category->image);
            $this->storeImage('category/slider', $image, $image_name, 500, 333);*/

            $unique_image_name = StoreImage::createUniqueImageName($image, $category_name);

            $storeCategoryImage = new StoreImage(
                'category', $image, 1600, 479, '', $category->image
            );
            $storeCategoryImage->storeImage($unique_image_name);

            $storeCategorySliderImage = new StoreImage(
                'category/slider', $image, 500, 333, '', $category->image
            );
            $storeCategorySliderImage->storeImage($unique_image_name);

            $category->image = $unique_image_name;
        }

        $category->save();

        Toastr::success('Category Updated Successfully');

        return redirect(route('admin.category.index'));
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

        /*$this->deleteExistingImage('category', $category->image);
        $this->deleteExistingImage('category/slider', $category->image);*/

        StoreImage::deleteExistingImage('category', $category->image);
        StoreImage::deleteExistingImage('category/slider', $category->image);

        Toastr::success('Category Deleted Successfully');

        return redirect(route('admin.category.index'));
    }
}
