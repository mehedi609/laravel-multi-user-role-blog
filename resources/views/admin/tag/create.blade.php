@extends('layouts.backend.app')

@section('title', "Create Tag")

@section('content')
  <div class="container-fluid">
    <!-- Vertical Layout | With Floating Label -->
    <div class="row clearfix">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
          <div class="header">
            <h2>
              ADD NEW TAG
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
            <form action="{{route('admin.tag.store')}}" method="POST">
              @csrf
              <div class="form-group form-float">
                <div class="form-line">
                  <input type="text" id="tag_name" class="form-control" name="tag_name">
                  <label class="form-label">Tag Name</label>
                </div>
              </div>

              <a href="{{route('admin.tag.index')}}" class="btn btn-danger m-t-15 m-r-10 waves-effect">
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
