<header class="navbar navbar-linght border-bottom" style="height: 70px">
  <div class="container d-flex justify-between">
    <a class="navbar-brand fs-5 fw-bold" href="@yield('header_brand_link', '/')">{{ config('app.name') }}</a>

    <div class="d-flex align-items-center gap-3">
        @auth
            <span>{{ Auth::user()->username }}</span>

            @if (Route::has('logout'))
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-secondary btn-sm">{{ __('Logout') }}</button>
                </form>
            @endif
        @else
            @if (Route::has('login'))
                <a href="{{ route('login') }}" class="btn btn-primary btn-sm">{{ __('Login') }}</a>
            @endif
        @endauth
    </div>
  </div>
</header>
