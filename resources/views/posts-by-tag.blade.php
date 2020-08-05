@extends('layouts.frontend.app')

@section('title', 'All Posts')

@push('css')
  <link href="{{asset('assets/frontend/css/category/styles.css')}}" rel="stylesheet">
  <link href="{{asset('assets/frontend/css/category/responsive.css')}}" rel="stylesheet">
@endpush

@section('content')
    <div class="slider display-table center-text">
      <h1 class="title display-table-cell">
        <b>{{$tag->name}}</b>
      </h1>
    </div><!-- slider -->

  <section class="blog-area section">
    <div class="container">

      <div class="row">
        @forelse ($tag->posts as $post)
            <div class="col-lg-4 col-md-6">
              <div class="card h-100">
                <div class="single-post post-style-1">

                  <div class="blog-image">
                    <img
                      src="{{asset("storage/post/{$post->image}")}}"
                      alt="{{$post->slug}}">
                  </div>

                  <a class="avatar" href="javascript:void(0)">
                    <img
                      src="{{asset("storage/profile/{$post->user->image}")}}"
                      alt="{{$post->user->image}}"
                    >
                  </a>

                  <div class="blog-info">

                    <h4 class="title">
                      <a href="{{route('post.details', $post->slug)}}">
                        <b>{{$post->title}}</b>
                      </a>
                    </h4>

                    <ul class="post-footer">
                      <li>
                        @guest
                          <a href="#" onclick="fav({{$post->id}})">
                            <i class="ion-heart"></i>
                            {{$post->favourite_to_users->count()}}
                          </a>
                        @else
                          <a
                            href="javascript:void(0)"
                            onclick="submitFavouriteForm({{$post->id}})"
                            class="{{$post->favourite_to_users()->where('user_id', Auth::id())->count() ? 'text-primary' : ''}}"
                          >
                            <i class="ion-heart"></i>
                            {{$post->favourite_to_users->count()}}
                          </a>

                          <form
                            action="{{route('favourite.post', $post->id)}}"
                            method="POST"
                            class="d-none"
                            id="favourite-form-{{$post->id}}"
                          >
                            @csrf
                          </form>
                        @endguest
                      </li>
                      <li>
                        <a href="#">
                          <i class="ion-chatbubble"></i>
                          {{$post->comments->count()}}
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <i class="ion-eye"></i>{{$post->view_count}}
                        </a>
                      </li>
                    </ul>

                  </div><!-- blog-info -->
                </div><!-- single-post -->
              </div><!-- card -->
            </div>
        @empty
          <div class="col-12">
            <div class="card h-100">
              <div class="single-post post-style-1">
                <h4 class="pt-5">No Posts Found!!</h4>
              </div><!-- single-post -->
            </div><!-- card -->
          </div>
        @endforelse
      </div><!-- row -->

    </div><!-- container -->
  </section><!-- section -->

@stop

@push('js')
  <script src="{{asset('assets/frontend/js/swiper.js')}}"></script>
  <script src="{{asset('assets/frontend/js/scripts.js')}}"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
  <script>
      function fav() {
          Swal.fire({
              position: 'top-end',
              icon: 'info',
              title: 'Oops...',
              text: 'Please login to add as your Favourite!'
          })
      }

      function submitFavouriteForm(id) {
          event.preventDefault();
          $(`#favourite-form-${id}`).submit();
      }
  </script>
@endpush
