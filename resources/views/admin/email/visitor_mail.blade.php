<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">

    <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap/dist/css/bootstrap.min.css') }}">

    <!-- Template CSS -->
    @if(app()->getLocale() == 'ar')
        <link rel="stylesheet" href="{{ asset('assets/css/style-ar.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @endif
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <title>Visitor Mail</title>
</head>



<body>
<div>
    <h3>
        Dear {{ $visitor_name}} your visit has been accepted
    </h3>
    <p>
        Qr Code :
        <br />
        <img src="{{ $qr_code }}" alt="Qr Code Was Not Loaded" style="max-width: 60% !important;"/>

        <br />

        your visit date : {{\Illuminate\Support\Carbon::parse($visit_date)->toDateTimeString() }}
    </p>
</div>
</body>
</html>