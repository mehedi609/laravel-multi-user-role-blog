@extends('layouts.frontend.app')

@section('title', 'Home')

@push('css')
  <link href="{{asset('assets/frontend/css/home/styles.css')}}" rel="stylesheet">
  <link href="{{asset('assets/frontend/css/home/responsive.css')}}" rel="stylesheet">
@endpush

@section('content')
  <div class="main-slider">
    <div class="swiper-container position-static" data-slide-effect="slide" data-autoheight="false"
         data-swiper-speed="500" data-swiper-autoplay="10000" data-swiper-margin="0" data-swiper-slides-per-view="4"
         data-swiper-breakpoints="true" data-swiper-loop="true">
      <div class="swiper-wrapper">

        @foreach ($categories as $category)
          <div class="swiper-slide">
            <a class="slider-category" href="{{route('posts.by.category', $category->slug)}}">
              <div class="blog-image">
                <img
                  src="{{asset("storage/category/slider/{$category->image}")}}"
                  alt="{{$category->slug}}"
                >
              </div>

              <div class="category">
                <div class="display-table center-text">
                  <div class="display-table-cell">
                    <h3>
                      <b>{{$category->name}}</b>
                    </h3>
                  </div>
                </div>
              </div>

            </a>
          </div>
        @endforeach

      </div><!-- swiper-wrapper -->

    </div><!-- swiper-container -->

  </div><!-- slider -->

  <section class="blog-area section">
    <div class="container">

      <div class="row">

        @foreach ($posts as $post)
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
                    alt="Profile Image"
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
        @endforeach

      </div><!-- row -->

      <a class="load-more-btn" href="{{route('post.index')}}"><b>LOAD MORE</b></a>

    </div><!-- container -->
  </section><!-- section -->
@stop

@push('js')
  <script src="{{asset('assets/frontend/js/swiper.js')}}"></script>
  <script src="{{asset('assets/frontend/js/scripts.js')}}"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
  <script>
      function fav(id) {
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
