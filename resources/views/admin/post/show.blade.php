@extends('layouts.backend.app')

@section('title', "Show Post")

@push('css')

@endpush

@section('content')
  <div class="container-fluid">
    <div class="m-b-15">
      <a href="{{route('admin.post.index')}}" class="btn btn-danger waves-effect">
        <i class="material-icons">reply</i>
        <span>Back</span>
      </a>

      <button
        class="btn btn-success pull-right waves-effect {{$post->is_approved ? 'disabled' : ''}}"
        onclick="approvePost({{$post->id}})"
      >
        <i class="material-icons">done</i>
        <span>Approve</span>
      </button>

      <form
        action="{{route('admin.post.approve', $post->id)}}"
        method="POST"
        class="d-none"
        id="approval-form"
      >
        @csrf
        @method('PUT')
      </form>

    </div>

    <!-- Vertical Layout | With Floating Label -->
    <div class="row clearfix">
      <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
          <div class="header">
            <h2>{{$post->title}}</h2>
            <small>
              Posted By <strong><a href="">{{$post->user->name}}</a></strong> on
              {{$post->created_at->toFormattedDateString()}}
            </small>

          </div>
          <div class="body">
            {!! $post->body !!}
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
          <div class="header bg-cyan">
            <h2>
              CATEGORIES
            </h2>

          </div>
          <div class="body">
            @foreach ($post->categories as $catogory)
              <span class="label bg-cyan">{{$catogory->name}}</span>
            @endforeach

          </div>
        </div>

        <div class="card">
          <div class="header bg-green">
            <h2>
              TAGS
            </h2>

          </div>
          <div class="body">
            @foreach ($post->tags as $tag)
              <span class="label bg-green">{{$tag->name}}</span>
            @endforeach

          </div>
        </div>

        <div class="card">
          <div class="header bg-amber">
            <h2>
              FEATURED IMAGE
            </h2>

          </div>
          <div class="body">
            <img
              src="{{asset("storage/post/$post->image")}}"
              alt="{{$post->slug}}"
              class="img-responsive thumbnail">
          </div>
        </div>
      </div>
    </div>
    <!-- Vertical Layout | With Floating Label -->

  </div>
@stop

@push('js')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
  <script>
      function approvePost(id) {
          const swalWithBootstrapButtons = Swal.mixin({
              customClass: {
                  confirmButton: 'btn btn-success waves-effect ml-2',
                  cancelButton: 'btn btn-danger waves-effect'
              },
              buttonsStyling: true
          })

          swalWithBootstrapButtons.fire({
              title: 'Are you sure?',
              text: "Do you want to approve this post?",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonText: 'Yes, approve it!',
              cancelButtonText: 'No, cancel!',
              reverseButtons: true
          }).then((result) => {
              if (result.value) {
                  event.preventDefault();
                  document.getElementById(`approval-form`).submit();
              } else if (
                  /* Read more about handling dismissals below */
                  result.dismiss === Swal.DismissReason.cancel
              ) {
                  swalWithBootstrapButtons.fire(
                      'Cancelled',
                      'The post is not approved',
                      'info'
                  )
              }
          })
      }
  </script>
@endpush
