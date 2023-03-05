<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
    <!-- Start Arabic Language -->
    @if(app()->getLocale() == 'ar')
        <ul class="navbar-nav navbar-right">
            <!-- Profile & Logout In Arabic -->
            <li class="dropdown">
                <a href="{{ route('admin.profile') }}" data-toggle="dropdown"
                   class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                    <img alt="image" src="{{ auth()->user()->images }}" class="rounded-circle">
                    <div class="d-sm-none d-lg-inline-block">{{ __('files.Hi') }}, {{ auth()->user()->name }}</div>
                </a>
                <div class="dropdown-menu dropdown-menu-right">

{{--                    @if(auth()->user()->hasPermissionTo('profile'))--}}

                        <a href="{{ route('admin.profile') }}" class="dropdown-item has-icon">
                            <i class="far fa-user"></i> {{ __('Profile') }}
                        </a>
{{--                    @endif--}}


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
            <!-- Languages In Arabic -->
            @if(setting('front_end_enable_disable') == 1)
                <li class="dropdown">
                    <a href="#" data-toggle="dropdown"
                       class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                        <div class="d-sm-none d-lg-inline-block">
                            <i class="fa fa-globe"></i>
                        </div>
                    </a>
                    @foreach($languages as $lang)
                        @if(app()->getLocale() != $lang->iso)
                            <div class="dropdown-menu menue-flags dropdown-menu-right " style="width: 20px;">
                                <a href="{{ route('change_locale',$lang->iso) }}" class="dropdown-item flags">
                                    @if(app()->getLocale() == 'ar')
                                        <img src="{{asset('united-states.png')}}"
                                             class="flag-icon"> {{strtoupper($lang->iso)}}
                                    @else
                                        <img src="{{asset('egypt.png')}}" class="flag-icon">{{strtoupper($lang->iso)}}
                                    @endif
                                </a>

                            </div>
                        @endif
                    @endforeach

                </li>
            @endif
            <!-- Languages In Arabic -->
        </ul>


        <!-- Sidebar Icon -->
        <div class="form-inline " style="margin-left: auto !important;">
            <ul class="navbar-nav mr-3">
                <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            </ul>
        </div>
        <!-- Sidebar Icon  -->

        <!-- End Arabic Language -->
    @else
        <!-- Sidebar Icon -->
        <div class="form-inline mr-auto">
            <ul class="navbar-nav mr-3">
                <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            </ul>
        </div>
        <!-- Sidebar Icon -->

        <!-- Name & Lang -->
        <ul class="navbar-nav navbar-right">
            @if(setting('front_end_enable_disable') == 1)
                <!-- Name & Logout -->
                <li class="dropdown">
                    <a href="#" data-toggle="dropdown"
                       class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                        <div class="d-sm-none d-lg-inline-block">
                            <i class="fa fa-globe"></i>
                        </div>
                    </a>
                    @foreach($languages as $lang)
                        @if(app()->getLocale() != $lang->iso)
                            <div class="dropdown-menu menue-flags dropdown-menu-right " style="width: 20px;">
                                <a href="{{ route('change_locale',$lang->iso) }}" class="dropdown-item flags">
                                    @if(app()->getLocale() == 'ar')
                                        <img src="{{asset('united-states.png')}}"
                                             class="flag-icon"> {{strtoupper($lang->iso)}}
                                    @else
                                        <img src="{{asset('egypt.png')}}" class="flag-icon">{{strtoupper($lang->iso)}}
                                    @endif
                                </a>

                            </div>
                        @endif
                    @endforeach

                </li>
                <!-- Name & Logout -->
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

    @endif


</nav>
