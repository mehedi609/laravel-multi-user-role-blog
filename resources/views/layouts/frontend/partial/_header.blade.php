<header>
  <div class="container-fluid position-relative no-side-padding">

    <a href="{{route('mainhome')}}" class="logo"><img src="images/logo.png" alt="Logo Image"></a>

    <div class="menu-nav-icon" data-nav-menu="#main-menu"><i class="ion-navicon"></i></div>

    <ul class="main-menu visible-on-click" id="main-menu">
      <li><a href="{{route('mainhome')}}">Home</a></li>
      <li><a href="{{route('post.index')}}">Posts</a></li>

      @guest
        <li><a href="{{route('login')}}">Login</a></li>
      @else
        <li>
          <a href="{{Auth::user()->role->id == 1 ? route('admin.dashboard') : route('author.dashboard')}}">
            DashBoard
          </a>
        </li>
      @endguest

      <li><a href="#">Features</a></li>
    </ul><!-- main-menu -->

    <div class="src-area">
      <form>
        <button class="src-btn" type="submit"><i class="ion-ios-search-strong"></i></button>
        <input class="src-input" type="text" placeholder="Type of search">
      </form>
    </div>

  </div><!-- conatiner -->
</header>
