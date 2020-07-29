@extends('layouts.backend.app')

@section('title', "Create Category")

@section('content')
  <div class="container-fluid">
    <!-- Vertical Layout | With Floating Label -->
    <div class="row clearfix">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
          <div class="header">
            <h2>
              ADD NEW CATEGORY
            </h2>

            @if ($errors->any())
              @foreach ($errors->all() as $error)
                <div class="alert alert-danger">
                  {{$error}}
                </div>
              @endforeach
            @endif

          </div>
          <div class="body">
            <form
              action="{{route('admin.category.store')}}"
              method="POST"
              enctype="multipart/form-data"
            >
              @csrf
              <div class="form-group form-float">
                <div class="form-line">
                  <input
                    type="text"
                    id="category_name"
                    class="form-control"
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

              <a
                href="{{route('admin.category.index')}}"
                class="btn btn-danger m-t-15 m-r-10 waves-effect"
              >
                BACK
              </a>
              <button type="submit" class="btn btn-primary m-t-15 waves-effect">
                SUBMIT
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- Vertical Layout | With Floating Label -->
  </div>
@stop
