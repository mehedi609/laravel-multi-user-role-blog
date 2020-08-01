@extends('layouts.backend.app')

@section('title', "Create Category")

@push('css')
  <link
    href="{{asset('assets/backend/plugins/bootstrap-select/css/bootstrap-select.css')}}"
    rel="stylesheet"
  />
@endpush

@section('content')
  @if ($errors->any())
    @foreach ($errors->all() as $error)
      <div class="alert alert-danger">
        {{$error}}
      </div>
    @endforeach
  @endif
  <div class="container-fluid">
    <form
      action="{{route('admin.post.store')}}"
      method="POST"
      enctype="multipart/form-data"
    >
      @csrf
      <!-- Vertical Layout | With Floating Label -->
      <div class="row clearfix">
        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
          <div class="card">
            <div class="header">
              <h2>
                ADD NEW POST
              </h2>

            </div>
            <div class="body">
              <div class="form-group form-float">
                <div class="form-line">
                  <input
                    type="text"
                    id="post_title"
                    class="form-control"
                    name="post_title"
                  >
                  <label class="form-label">Post Title</label>
                </div>
              </div>

              <div class="form-group">
                <input
                  type="file"
                  id="category_image"
                  class="form-control"
                  name="category_image"
                >
              </div>

              <div class="form-group">
                <input type="checkbox" id="post_published" name="post_published" class="filled-in" value="1">
                <label for="post_published">Published</label>
              </div>

            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
          <div class="card">
            <div class="header">
              <h2>
                CATEGORIES AND TAGS
              </h2>

            </div>
            <div class="body">
              <div class="form-group form-float">
                <div class="form-line {{$errors->has('categories') ? 'focused error' : ''}}">
                  <label for="categories">Select Category</label>
                  <select
                    class="form-control show-tick"
                    name="categories[]"
                    id="categories"
                    data-live-search="true"
                    multiple
                  >
                    @foreach ($categories as $category)
                      <option value="{{$category->id}}">{{$category->name}}</option>
                    @endforeach
                  </select>
                </div>

                <div class="form-line {{$errors->has('tags') ? 'focused error' : ''}}">
                  <label for="tags">Select Tag</label>
                  <select
                    class="form-control show-tick"
                    name="tags[]"
                    id="tags"
                    data-live-search="true"
                    multiple
                  >
                    @foreach ($tags as $tag)
                      <option value="{{$tag->id}}">{{$tag->name}}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="form-group">
                <input
                  type="file"
                  id="category_image"
                  class="form-control"
                  name="category_image"
                >
              </div>

              <a
                href="{{route('admin.post.index')}}"
                class="btn btn-danger m-t-15 m-r-10 waves-effect"
              >
                BACK
              </a>
              <button type="submit" class="btn btn-primary m-t-15 waves-effect">
                SUBMIT
              </button>

            </div>
          </div>
        </div>
      </div>
      <!-- Vertical Layout | With Floating Label -->

      <!-- Vertical Layout | With Floating Label -->
      <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <div class="card">
            <div class="header">
              <h2>
                ADD NEW POST
              </h2>

            </div>
            <div class="body">

              @csrf
              <div class="form-group form-float">
                <div class="form-line">
                  <input
                    type="text"
                    id="category_name"
                    class="form-control selectpicker"
                    name="category_name"
                  >
                  <label class="form-label">Category Name</label>
                </div>
              </div>

              <div class="form-group">
                <input
                  type="file"
                  id="category_image"
                  class="form-control"
                  name="category_image"
                >
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Vertical Layout | With Floating Label -->
    </form>
  </div>
@stop

@push('js')
  <script src="{{asset('assets/backend/plugins/bootstrap-select/js/bootstrap-select.js')}}"></script>
@endpush
