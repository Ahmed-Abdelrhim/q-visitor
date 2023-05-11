<!doctype html>
<html lang="en">

<head>
    <title>Passport Scanner</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('assets/modules/izitoast/dist/css/iziToast.min.css') }}">


    {{--    <link rel="stylesheet" href="css/style.css"> --}}
    <link rel="stylesheet" href="{{ asset('css/ocr_styles/style.css') }}">

    @include('admin.ocr.index_style')
    <style>
        .loading {
            z-index: 20;
            position: absolute;
            top: 0;
            left: 5px;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0,4);
        }

        .loading-content {
            position: absolute;
            border: 16px solid #f3f3f3;
            border-top: 16px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            top: 40%;
            left: 50%;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% {transform: rotate(0deg) ;}
            100% {transform: rotate(360deg);}
        }


        .contact-wrap {
            background-color: #24ba64 !important;
            background-image: linear-gradient(to bottom right, #24ba64, #c6cdc6) !important;
            border-radius: 15px 15px 0 0 !important;
        }

        .btn.btn-primary,
        .btn-success {
            border-color: #fff !important;
            color: #fff !important;
            border: 2px solid #ffffff !important;
            background: transparent !important;
        }

        .btn.btn-primary:hover,
        .btn-success:hover {
            background-color: #353b99 !important;
            border: 2px solid #ffffff !important;
        }

        .heading-section {
            color: #353b99 !important;
            font-weight: bold !important;
        }

        .plate_no {
            border: 2px solid #FFF !important;
        }

        h5 {
            font-weight: bold !important;
        }

        .info-wrap .dbox .icon span {
            color: #353b99 !important;
        }

        .new_page {
            box-shadow: 0 2px 6px #24ba64 !important;
            background-color: #24ba64 !important;
            border-color: #24ba64 !important;
        }

        .scan {
            box-shadow: 0 2px 6px #e79e28 !important;
            background-color: #e79e28 !important;
            border-color: #e79e28 !important;
            color: #fff;
            margin-left: 1% !important;
        }

        .view {
            box-shadow: 0 2px 6px #5c5c5e !important;
            background-color: #5c5c5e !important;
            border-color: #5c5c5e !important;
            margin-left: 1% !important;
        }

        .view:hover {
            box-shadow: 0 2px 6px #919090 !important;
            background-color: #919090 !important;
            border-color: #919090 !important;
        }

        .save {
            box-shadow: 0 2px 6px #353b99 !important;
            background-color: #353b99 !important;
            border-color: #353b99 !important;
            margin-left: 1% !important;
        }

        .save:hover {
            box-shadow: 0 2px 6px #353b99 !important;
            background-color: #353b99 !important;
            border-color: #353b99 !important;
        }

        .wrapper {
            border-radius: 15px !important;
        }

        .form-group {
            width: 70% !important;
            margin: auto !important;
        }

        .info-wrap .dbox:last-child {
            padding-left: 0 !important;
        }

        .dashboard {
            margin-left: 50% !important;
        }
    </style>

</head>
@include('admin.ocr.script')

<?php

if (!file_exists(storage_path('app/public' . '/plate.txt'))) {
    // File Does Not Exist
    $file = \Illuminate\Support\Facades\Storage::disk('public')->put('plate.txt', '');
    $plate = '';
} else {
    // File Already Exists
    if (filesize(storage_path('app/public' . '/plate.txt')) > 0) {
        $myFile = fopen(storage_path('app/public' . '/plate.txt'), 'r');
        $plate = fread($myFile, filesize(storage_path('app/public' . '/plate.txt')));
        fclose($myFile);
    } else {
        $plate = '';
    }
}

//if (\Illuminate\Support\Facades\Storage::disk('public')->exists('plate.txt')) {
//if (file_exists(storage_path('app/public') . '/plate.txt')) {
//    if (filesize(storage_path('app/public/' . 'plate.txt')) > 0) {
//        $myfile = fopen(storage_path('app/public/' . 'plate.txt'), "r");
//        $plate = fread($myfile, filesize(storage_path('app/public/' . 'plate.txt')));
//        fclose($myfile);
//    } else {
//        $plate = '';
//    }
//} else {
//    $plate = '';
//}

?>

<body>

<div id="loading">
    <div id="loading-content"></div>
</div>

<section class="ftco-section">

    <div class="container">
        <div class="row justify-content-right" style="direction:rtl"><a
                href="{{ route('change_locale','ar') }}">
                عربى
            </a>
            |
            <a href="{{route('change_locale','en')}}">
                English
            </a>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6 text-center mb-5" style="margin-bottom:0px !important">
                <h2 class="heading-section">{{__('files.Scanner')}}</h2>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-14">
                <div class="wrapper">
                    <div class="row no-gutters">
                        <div class="col-md-12">
                            <div class="contact-wrap w-100 p-lg-5 p-4">

                                <div id="form-message-warning" class="mb-4"></div>
                                <div id="form-message-success" class="mb-4">
                                    Data was sent, thank you!
                                </div>

                                @if(!empty($car_type))
                                    @if($car_type == 'T' || $car_type == 'C' || $car_type == 'TWIN_TRUCK')
                                        <div class="row" style="text-align:center">
                                            <h5>{{__('files.Car Plate')}} :</h5><input type="text" class="form-control plate_no"
                                                                                       value="<?php echo $plate; ?>" />
                                            <input type="button" value="{{__('files.Last Car plate')}}" class="btn btn-success get_plate"
                                                   style="height: 35px; padding: 7px 14px;margin-left: 7%">

                                            @if($car_type == 'TWIN_TRUCK')
                                                <h5>{{__('files.Twin Truck Number')}} :</h5>
                                                <input type="text" class="form-control plate_no" name="twin_truck_number" style="width: 150px; height: 35px; margin-left: 10px; "/>
                                            @endif

                                            <a class="btn btn-primary dashboard" href="{{ route('admin.dashboard.index') }}"
                                               style="height: 35px; padding: 7px 14px;margin-left: 7%">
                                                {{__('files.Dashboard')}}
                                            </a>

                                        </div>
                                    @endif
                                @endif


                                <br />
                                <div class="row" style="text-align:center">
                                    <div class="col-md-3">
                                        <div class="form-group"
                                             style="text-align:center;float:left;margin-right:20px">
                                            <img id="pic" src="{{asset('images/personal.png')}}" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <img id="white_picture" src="{{asset('images/id.jpg')}}" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <img id="ocr_head" src="{{asset('images/id.jpg')}}" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <img id="chip_head" src="{{asset('images/id.jpg')}}" />
                                        </div>
                                    </div>


                                </div>

                            </div>
                        </div>
                        <div class="div2">
                            <textarea id="msg" cols="75" rows="20"></textarea>
                        </div>
                        <div class="col-md-12 d-flex align-items-stretch">
                            <div class="info-wrap w-100 p-lg-5 p-4 img">
                                {{ csrf_field() }}


                                <div class="dbox w-25 d-flex align-items-start">
                                    <div class="icon d-flex align-items-center justify-content-center">
                                        <span class="fa fa-user"></span>
                                    </div>
                                    <div class="text pl-3">
                                        <p><span>{{__('files.Name')}} :</span> <a id="name" class="txt"></a></p>
                                    </div>
                                </div>
                                <div class="dbox w-25 d-flex align-items-center">
                                    <div class="icon d-flex align-items-center justify-content-center">
                                        <span class="fa fa-calendar"></span>
                                    </div>
                                    <div class="text pl-3">
                                        <p><span>{{__('files.Date Of Birth')}} :</span> <a id="dob" class="txt"></a></p>
                                    </div>
                                </div>


                                <div class="dbox w-25 d-flex align-items-center">
                                    <div class="icon d-flex align-items-center justify-content-center">
                                        <span class="fa fa-id-card"></span>
                                    </div>
                                    <div class="text pl-3">
                                        <p><span>{{__('files.ID Number')}} :</span> <a id="mrz" class="txt"></a></p>
                                    </div>
                                </div>
                                <div class="dbox w-25 d-flex align-items-center">
                                    <div class="icon d-flex align-items-center justify-content-center">
                                        <span class="fa fa-intersex"></span>
                                    </div>
                                    <div class="text pl-3">
                                        <p><span>{{__('files.Gender')}} :</span> <a id="sex" class="txt"></a></p>
                                    </div>
                                </div>


                                <div class="dbox w-25 d-flex align-items-center" >
                                    <div class="icon d-flex align-items-center justify-content-center">
                                        <span class="fa fa-exclamation-triangle"></span>
                                    </div>
                                    <div class="text pl-3">
                                        <p id="expiration_date"><span>{{__('files.Expiry Date')}} :</span> <a id="exdate" class="txt"></a></p>
                                    </div>
                                </div>
                                <div class="dbox w-25 d-flex align-items-center">
                                    <div class="icon d-flex align-items-center justify-content-center">
                                        <span class="fa fa-tasks"></span>
                                    </div>
                                    <div class="text pl-3">
                                        <p><span>{{__('files.Job')}} :</span> <a id="job" class="txt"></a></p>
                                    </div>
                                </div>
                                <div class="dbox w-25 d-flex align-items-center">
                                    <div class="icon d-flex align-items-center justify-content-center">
                                        <span class="fa fa-user"></span>
                                    </div>
                                    <div class="text pl-3">
                                        <p><span>{{__('files.Marital status')}} :</span> <a id="mstat" class="txt"></a></p>
                                    </div>
                                </div>

                                <div class="dbox w-25 d-flex align-items-center">
                                    <div class="icon d-flex align-items-center justify-content-center">
                                        <span class="fa fa-calendar"></span>
                                    </div>
                                    <div class="text pl-3">
                                        <p><span>{{__('files.Issuing Date')}} :</span> <a id="isdate" class="txt"></a></p>
                                    </div>
                                </div>

                                <div class="dbox w-25 d-flex align-items-center">
                                    <div class="icon d-flex align-items-center justify-content-center">
                                        <span class="fa fa-calendar"></span>
                                    </div>
                                    <div class="text pl-3">
                                        <p><label for="vdate">{{__('files.Visit Date')}} :</label> <input type="text"
                                                                                         value="<?php echo date('d-m-Y'); ?>" class="vdate form-control"
                                                                                         id="vdate" disabled="disabled" /></p>
                                    </div>
                                </div>
                                <div class="dbox w-25 d-flex align-items-center">
                                    <div class="icon d-flex align-items-center justify-content-center">
                                        <span class="fa fa-calendar"></span>
                                    </div>
                                    <div class="text pl-3">
                                        <p><label for="vtime">{{__('files.Visit Time')}} :</label> <input type="text"
                                                                                         value="<?php echo date('h:i:s a'); ?>" class="vtime form-control"
                                                                                         id="vtime" disabled="disabled" /></p>

                                    </div>
                                </div>
                                <div class="dbox w-25 d-flex align-items-center">
                                    <div class="icon d-flex align-items-center justify-content-center">
                                        <span class="fa fa-flag"></span>
                                    </div>
                                    <div class="text pl-3">
                                        <p><span>{{__('files.Country Code')}} :</span> <a id="icc" class="txt"></a></p>
                                    </div>
                                </div>
                                <div class="dbox w-25 d-flex align-items-center" style="display:none !important">
                                    <div class="icon d-flex align-items-center justify-content-center">
                                        <span class="fa fa-book"></span>
                                    </div>
                                    <div class="text pl-3">
                                        <p><span>{{__('files.Religion')}} :</span> <a id="relg" class="txt"></a></p>
                                    </div>
                                </div>
                                <div class="dbox w-25 d-flex align-items-center">
                                    <div class="icon d-flex align-items-center justify-content-center">
                                        <span class="fa fa-address-card"></span>
                                    </div>
                                    <div class="text pl-3">
                                        <p><span>{{__('files.Address')}} :</span> <a id="address" class="txt"></a></p>
                                    </div>
                                </div>





                                <div class="input-group mb-3 " style="margin-top: 10px;">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="employee">{{__('files.Employee')}}</label>
                                    </div>
                                    <select class="custom-select " id="employee">
                                        <option selected value="0">{{__('files.Choose Employee')}}...</option>

                                    @foreach($employees as $employee)
                                            <option value="{{$employee->id}}">{{$employee->name}}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="dbox w-100 d-flex align-items-center"
                                     style="margin-bottom:0px !important;height: 50px !important;margin-top:55px;text-align:center;padding-left: 35%;">
                                    <div class="form-group">
                                        <input type="button" value="{{__('files.New Scan')}}" class="btn btn-danger new_page">
                                        <input type="button" value="{{__('files.Scan')}}" class="btn btn-danger scan"
                                               onclick="connect();">
                                        <input type="button" value="{{__('files.Save Data')}}" class="btn btn-success newscan" style="background-color: #0a71db !important;">
                                        {{-- <input type="button" value="View Visitors" class="btn btn-success view" --}}
                                        {{-- onclick="{{route('admin.visitors.index')}};"> --}}
                                        <a type="button" class="btn btn-success view"
                                           href="{{ route('admin.OCR.index') }}">
                                            {{__('files.View Visitors')}}
                                        </a>
                                        <div class="submitting"></div>
                                    </div>
                                </div>
















                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="images" style="display:none"></div>
<div class="perpic" style="display:none"></div>


{{-- @yield('content') --}}
<script src="{{ asset('assets/modules/izitoast/dist/js/iziToast.min.js') }}"></script>
<script>
    @if (Session::has('message'))
    var type = "{{ Session::get('alert-type', 'info') }}"
    switch (type) {
        case 'info':
            iziToast.info({
                title: 'info',
                message: '{{ session('message') }}',
                position: 'topRight'
            });
            break;

        case 'success':
            iziToast.success({
                title: 'Success',
                message: '{{ session('message') }}',
                position: 'topRight'
            });
            break;

        case 'warning':
            iziToast.warning({
                title: 'warning',
                message: '{{ session('message') }}',
                position: 'topRight'
            });
            break;

        case 'error':
            iziToast.error({
                title: 'error',
                message: '{{ session('message') }}',
                position: 'topRight'
            });
            break;
    }
    @endif
</script>
{{--@include('admin.ocr.ocr_new_visit')--}}
{{--@extends('admin.ocr.view_extend')--}}

@include('admin.ocr.index_footer_scripts')
</body>
</html>

{{-- < ?php --}}
{{--    --}}
{{--    --}}
{{-- //if (filesize(asset('storage/plate.txt')) > 0) { --}}
{{-- //if (Illuminate\Support\Facades\Storage::exists(storage_path('plate.txt')) > 0) { --}}


{{-- if (Illuminate\Support\Facades\Storage::exists( storage_path('app/public/'.'plate.txt') )) { --}}
{{--    $myfile = fopen(asset('plate.txt'), "r"); --}}
{{--    // $myfile = File::get(storage_path('app/public/' .'plate.txt'), "r") --}}
{{--    // $plate = File::get($myfile, filesize(storage_path('app/public/'.'plate.txt'))) --}}
{{--    $plate = File::get(filesize(storage_path('app/public/'.'plate.txt'))); --}}
{{--    // File::close($plate) --}}
{{-- } else { --}}
{{--    $plate = ''; --}}
{{-- } --}}
{{-- ?> --}}
