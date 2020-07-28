<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $tags = Tag::latest()->get();
    return view('admin.tag.index', compact('tags'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('admin.tag.create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $this->validate($request, [
      'tag_name' => 'required',
    ]);

    $tag = new Tag();
    $tag->name = $request->tag_name;
    $tag->slug = str_slug($request->tag_name);
    $tag->save();

    return redirect(route('admin.tag.index'))->with('successMsg', 'Tag created successfully');
  }

  /**
   * Display the specified resource.
   *
   * @param \App\Tag $tag
   * @return \Illuminate\Http\Response
   */
  public function show(Tag $tag)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param \App\Tag $tag
   * @return \Illuminate\Http\Response
   */
  public function edit(Tag $tag)
  {
    return view('admin.tag.edit', compact('tag'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param \App\Tag $tag
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Tag $tag)
  {
    $tag->name = $request->tag_name;
    $tag->slug = str_slug($request->name);
    $tag->save();

    return redirect()->route('admin.tag.index')->with('successMsg', 'Tag updated successfully');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param \App\Tag $tag
   * @return \Illuminate\Http\Response
   */
  public function destroy(Tag $tag)
  {
    $tag->delete();
    return redirect()->route('admin.tag.index')->with('successMsg', 'Tag deleted successfully');
  }
}
