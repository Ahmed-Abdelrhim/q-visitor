<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
    <div class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
        </ul>
    </div>
    <ul class="navbar-nav navbar-right">
        @if(setting('front_end_enable_disable') == 1)

            {{--                        <a data-toggle="tooltip" data-placement="bottom" title="Go to Frontend" href="{{ route('/') }}" class="nav-link nav-link-lg beep" target="_blank">--}}
            {{--                            <i class="fa fa-globe"></i>--}}
            {{--                        </a>--}}

            {{--            <li class="dropdown">--}}
            {{--                <button class="nav-link nav-link-lg beep" title="languages" data-placement="bottom">--}}
            {{--                    <i class="fa fa-globe"></i>--}}
            {{--                    {{app()->getLocale()}}--}}
            {{--                </button>--}}

            {{--                <div class="dropdown-menu dropdown-menu-right">--}}
            {{--                    @foreach($languages as $lang)--}}
            {{--                        @if(app()->getLocale() != $lang->iso)--}}
            {{--                            <a class="dropdown-item" href="{{route('change_locale', $lang->iso )}}">--}}
            {{--                                {{$lang->iso}}--}}
            {{--                            </a>--}}
            {{--                        @endif--}}
            {{--                    @endforeach--}}
            {{--                </div>--}}
            {{--            </li>--}}

            <!-- Test Here-->
            <li class="dropdown">
                <a href="#" data-toggle="dropdown"
                   class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                    <div class="d-sm-none d-lg-inline-block">
                        <i class="fa fa-globe"></i>
                    </div>
                </a>
                @foreach($languages as $lang)
                    @if(app()->getLocale() != $lang->iso)
                        <div class="dropdown-menu dropdown-menu-right " style="width: 20px;">
                            <a href="{{ route('change_locale',$lang->iso) }}" class="dropdown-item has-icon">
                                <i class="far fa-userr"></i> {{$lang->iso}}
                            </a>
                            <div class="dropdown-divider"></div>
                        </div>
                    @endif
                @endforeach

            </li>
            <!-- Test Here-->
        @endif
        <li class="dropdown">
            <a href="{{ route('admin.profile') }}" data-toggle="dropdown"
               class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img alt="image" src="{{ auth()->user()->images }}" class="rounded-circle">
                <div class="d-sm-none d-lg-inline-block">{{ __('files.Hi') }}, {{ auth()->user()->name }}</div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="{{ route('admin.profile') }}" class="dropdown-item has-icon">
                    <i class="far fa-user"></i> {{ __('Profile') }}
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                   class="dropdown-item has-icon text-danger">
                    <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" class="display-none">
                    @csrf
                </form>
            </div>
        </li>
    </ul>
</nav>
{{--

            <li class="nav-item dropdown text-uppercase">
                <button class="nav-link nav-link-lg beep" title="languages" data-placement="bottom">
                    <i class="fa fa-globe"></i>
                    {{app()->getLocale()}}
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="min-width: 0px">
                    @foreach($languages as $lang)
                        @if(app()->getLocale() != $lang->iso)
                            <a class="dropdown-item" href="{{route('change_locale', $lang->iso )}}">
                                {{$lang->iso}}
                            </a>
                        @endif
                    @endforeach
                </div>
            </li>

            </li>
--}}