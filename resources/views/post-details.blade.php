@extends('layouts.frontend.app')

@section('title')
  {{$post->title}}
@stop

@push('css')
  <link href="{{asset('assets/frontend/css/single-post/styles.css')}}" rel="stylesheet">
  <link href="{{asset('assets/frontend/css/single-post/responsive.css')}}" rel="stylesheet">

  <style>
    .slider {
      height: 400px;
      width: 100%;
      background-size: cover;
      margin: 0;
      background-image: url({{asset("storage/post/{$post->image}")}});
    }
  </style>
@endpush

@section('content')
  <div class="slider">

  </div><!-- slider -->

  <section class="post-area section">
    <div class="container">

      <div class="row">

        <div class="col-lg-8 col-md-12 no-right-padding">

          <div class="main-post">

            <div class="blog-post-inner">

              <div class="post-info">

                <div class="left-area">
                  <a
                    class="avatar"
                    href="#"
                  >
                    <img
                      src="{{asset("storage/profile/{$post->user->image}")}}"
                      alt="{{$post->user->name}}"
                    >
                  </a>
                </div>

                <div class="middle-area">
                  <a class="name" href="#"><b>{{$post->user->name}}</b></a>
                  {{--                  <h6 class="date">on Sep 29, 2017 at 9:48 am</h6>--}}
                  <h6 class="date">
                    on {{$post->created_at->toFormattedDateString()}} at {{$post->created_at->format('h:i A')}}
                  </h6>
                </div>

              </div><!-- post-info -->

              <h3 class="title">
                <a href="#">
                  <b>{{$post->title}}</b>
                </a>
              </h3>

              <p class="para">{!! $post->body !!}</p>

              <ul class="tags">
                @foreach ($post->tags as $tag)
                  <li><a href="javascript:void(0)">{{$tag->name}}</a></li>
                @endforeach
              </ul>
            </div><!-- blog-post-inner -->

            <div class="post-icons-area">
              <ul class="post-icons">
                <li>
                  @guest
                    <a
                      href="javascript:void(0)"
                      onclick="fav()"
                    >
                      <i class="ion-heart"></i>{{$post->favourite_to_users->count()}}
                    </a>
                  @else
                    <a
                      href="javascript:void(0)"
                      onclick="submitFavouriteForm({{$post->id}})"
                      class="{{$post->favourite_to_users()->where('user_id', Auth::id())->count() ? 'text-primary' : ''}}"
                    >
                      <i class="ion-heart"></i>{{$post->favourite_to_users->count()}}
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
                <li><a href="#"><i class="ion-chatbubble"></i>0</a></li>
                <li>
                  <a href="#">
                    <i class="ion-eye"></i>{{$post->view_count}}
                  </a>
                </li>
              </ul>

              <ul class="icons">
                <li>SHARE :</li>
                <li><a href="#"><i class="ion-social-facebook"></i></a></li>
                <li><a href="#"><i class="ion-social-twitter"></i></a></li>
                <li><a href="#"><i class="ion-social-pinterest"></i></a></li>
              </ul>
            </div>

          </div><!-- main-post -->
        </div><!-- col-lg-8 col-md-12 -->

        <div class="col-lg-4 col-md-12 no-left-padding">

          <div class="single-post info-area">

            <div class="sidebar-area about-area">
              <h4 class="title"><b>ABOUT AUTHOR</b></h4>
              <p align="justify">{{$post->user->about}}</p>
            </div>

            <div class="tag-area">

              <h4 class="title"><b>CATEGORIES</b></h4>
              <ul>
                @foreach ($post->categories as $category)
                  <li>
                    <a href="{{route('posts.by.category', $category->slug)}}">
                      {{$category->name}}
                    </a>
                  </li>
                @endforeach

              </ul>

            </div><!-- subscribe-area -->

          </div><!-- info-area -->

        </div><!-- col-lg-4 col-md-12 -->

      </div><!-- row -->

    </div><!-- container -->
  </section><!-- post-area -->


  <section class="recomended-area section">
    <div class="container">
      <div class="row">
        @foreach ($random_posts as $random_post)
          <div class="col-lg-4 col-md-6">
            <div class="card h-100">
              <div class="single-post post-style-1">

                <div class="blog-image">
                  <img
                    src="{{asset("storage/post/{$random_post->image}")}}"
                    alt="{{$random_post->slug}}">
                </div>

                <a class="avatar" href="javascript:void(0)">
                  <img
                    src="{{asset("storage/profile/{$random_post->user->image}")}}"
                    alt="Profile Image"
                  >
                </a>

                <div class="blog-info">

                  <h4 class="title">
                    <a href="{{route('post.details', $random_post->slug)}}">
                      <b>{{$random_post->title}}</b>
                    </a>
                  </h4>

                  <ul class="post-footer">
                    <li>
                      @guest
                        <a href="#" onclick="fav({{$random_post->id}})">
                          <i class="ion-heart"></i>
                          {{$random_post->favourite_to_users->count()}}
                        </a>
                      @else
                        <a
                          href="javascript:void(0)"
                          onclick="submitFavouriteForm({{$random_post->id}})"
                          class="{{$random_post->favourite_to_users()->where('user_id', Auth::id())->count() ? 'text-primary' : ''}}"
                        >
                          <i class="ion-heart"></i>
                          {{$random_post->favourite_to_users->count()}}
                        </a>

                        <form
                          action="{{route('favourite.post', $random_post->id)}}"
                          method="POST"
                          class="d-none"
                          id="favourite-form-{{$random_post->id}}"
                        >
                          @csrf
                        </form>
                      @endguest
                    </li>
                    <li>
                      <a href="#">
                        <i class="ion-chatbubble"></i>
                        {{$random_post->comments->count()}}
                      </a>
                    </li>
                    <li>
                      <a href="#">
                        <i class="ion-eye"></i>{{$random_post->view_count}}
                      </a>
                    </li>
                  </ul>

                </div><!-- blog-info -->
              </div><!-- single-post -->
            </div><!-- card -->
          </div>
        @endforeach
      </div><!-- row -->

    </div><!-- container -->
  </section>

  <section class="comment-section">
    <div class="container">
      <h4><b>POST COMMENT</b></h4>
      <div class="row">

        <div class="col-lg-8 col-md-12">
          <div class="comment-form">
            @guest
              <p>
                For post a comment you need to <a href="{{route('login')}}"><em>Login</em></a>
              </p>
            @else
              <form method="post" action="{{route('comment.store', $post->id)}}">
                @csrf
                <div class="row">
                  <div class="col-sm-12">
									<textarea
                    name="comment"
                    rows="2"
                    class="text-area-messge form-control"
                    placeholder="Enter your comment"
                  ></textarea>
                  </div><!-- col-sm-12 -->

                  <div class="col-sm-12">
                    <button
                      class="submit-btn"
                      type="submit"
                      id="form-submit"
                    >
                      <b>POST COMMENT</b>
                    </button>
                  </div><!-- col-sm-12 -->
                </div><!-- row -->
              </form>
            @endguest

          </div><!-- comment-form -->

          <h4><b>COMMENTS({{$post->comments->count()}})</b></h4>

          @if ($post->comments->count() > 0)
            @foreach ($post->comments()->orderBy('id', 'DESC')->get() as $comment)
              <div class="commnets-area ">
                <div class="comment">
                  <div class="post-info">
                    <div class="left-area">
                      <a class="avatar" href="#">
                        <img
                          src="{{asset("storage/profile/{$comment->user->image}")}}"
                          alt="{{$comment->user->name}}"
                        >
                      </a>
                    </div>
                    <div class="middle-area">
                      <a class="name" href="#"><b>{{$comment->user->name}}</b></a>
                      <h6 class="date">on {{$comment->created_at->diffForHumans()}}</h6>
                    </div>
                    <div class="right-area">
                      <h5 class="reply-btn"><a href="#"><b>REPLY</b></a></h5>
                    </div>
                  </div><!-- post-info -->
                  <p>{{$comment->comment}}</p>
                </div>
              </div>
            @endforeach
          @else
            <div class="commnets-area">
              <p>No Comments yet. Be the first.</p>
            </div>
          @endif

        </div><!-- col-lg-8 col-md-12 -->

      </div><!-- row -->

    </div><!-- container -->
  </section>
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
