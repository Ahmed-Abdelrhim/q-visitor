<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" >

@include('admin.layouts.components.head')
@include('admin.layouts.components.custm_styles')


<body>
    <div id="app">
        <div class="main-wrapper">
            @include('admin.layouts.components.navigation')
            @include('admin.layouts.components.sidebar')

            <!-- Main Content -->
            <div class="main-content">
                @yield('main-content')
            </div>
            @include('admin.layouts.components.footer')
        </div>
    </div>
    @include('admin.layouts.components.script')

</body>
</html>


