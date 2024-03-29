<head>
    {{--    <meta charset="UTF-8">  --}}

    <meta charset="utf-8">

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>{{ isset($sitetitle) ? ucfirst($sitetitle) : "Visitor-Pass" }}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    {{--    <meta name="csrf-token" content="{{ csrf_token() }}" >--}}

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/visitor.ico') }}">
    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/@fortawesome/fontawesome-free/css/all.min.css') }}">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('assets/modules/izitoast/dist/css/iziToast.min.css') }}">
    @yield('css')
    <link rel="stylesheet" href="{{ asset('assets/css/dropzone.css') }}">

    <!-- Template CSS -->
    @if(app()->getLocale() == 'ar')
        <link rel="stylesheet" href="{{ asset('assets/css/style-ar.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @endif
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


</head>
