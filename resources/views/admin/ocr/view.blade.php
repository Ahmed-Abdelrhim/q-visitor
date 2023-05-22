<!doctype html>
<html lang="en">
<head>
    <title>View Visitors</title>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <link rel="stylesheet" href="{{ asset('assets/modules/izitoast/dist/css/iziToast.min.css') }}">

    <script src="{{asset('DataTables/other/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('DataTables/other/jquery.redirect.js')}}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"
          integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" href="{{ asset('css/ocr_styles/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('DataTables/datatables.min.css')}}"/>
    {{--    <link rel="stylesheet" type="text/css" href="css/dataTables.bootstrap4.min.css"/>--}}

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">


    <link rel="stylesheet" type="text/css" href="{{asset('DataTables/other/bootstrap.css')}}"/>
    <script type="text/javascript" src="{{asset('DataTables/datatables.min.js')}}"></script>
    <link href="{{asset('DataTables/other/buttons.dataTables.min.css')}}"/>

    <script type="text/javascript" src="{{asset('DataTables/dataTables.buttons.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('DataTables/jszip.min.js')}}"></script>
    {{--    <script type="text/javascript" src="{{asset('DataTables/buttons.html5.min.js')}}"></script>--}}
    {{--    <script type="text/javascript" src="{{asset('DataTables/buttons.print.min.js')}}"></script>--}}


    @if(app()->getLocale() == 'ar')
        @extends('admin.ocr.ocr_styles_ar')
    @else
        @extends('admin.ocr.ocr_styles')
    @endif
    @extends('admin.ocr.view_extend')
    <style>
        .loading {
            z-index: 20;
            position: absolute;
            top: 0;
            left: 5px;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0, 4);
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
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

    </style>

</head>


<?php
$filter = '';
if (isset($_POST['v2date']) and isset($_POST['v3date'])) {

    $filter = "and vd.checkin_at between '" . date("Y-m-d", strtotime($_POST['v2date'])) . " 00:00:00" . "' and '" . date("Y-m-d", strtotime($_POST['v3date'])) . " 23:59:59" . "'";
}

?>
<body>
<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-right" style="direction:rtl">
            <a href="{{route('change_locale','ar')}}">
                عربى
            </a>
            |
            <a href="{{route('change_locale','en')}}">
                English
            </a>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6 text-center mb-5" style="margin-bottom:0px !important">
                <h2 class="heading-section">{{__('files.Manage Visits')}}</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="wrapper">
                    <div class="row no-gutters">
                        <div class="col-md-12">
                            <div class="contact-wrap w-100 p-lg-5 p-4">


                                <div class="row">
                                    <label class="col-12"
                                           style="color:#fff;font-size:26px;font-weight:bold; padding-bootom:10px;">{{__('files.Filter By Date Period')}}
                                        : </label>


                                    <div class="text pl-3" style="margin-left:3%">
                                        <p><span style="color:#fff">{{__('files.Check in Date')}} :</span> <input
                                                    type="text"
                                                    class="v2date form-control"
                                                    id="v2date"
                                                    style="color:#000 !important"/>
                                        </p>
                                    </div>


                                    <div class="text pl-3">
                                        <p><span style="color:#fff">{{__('files.Check out Date')}} :</span> <input
                                                    type="text"
                                                    class="v3date form-control"
                                                    id="v3date"
                                                    style="color:#000 !important"/>
                                        </p>
                                    </div>


                                    <div class="actions-btns">
                                        <button class="btn btn-sm btn-icon mr-2  float-left btn-success find"
                                                data-toggle="tooltip" data-placement="top" title="Search">
                                            <i
                                                    class="fa fa-search"></i> {{__('files.Search')}}
                                        </button>

                                        <button class="btn btn-sm btn-icon mr-2  float-left btn-success clr"
                                                data-toggle="tooltip" data-placement="top" title="clear"><i
                                                    class="fa fa-refresh"></i> {{__('files.Clear Search')}}
                                        </button>
                                    </div>


                                    <!-- <button class="btn btn-sm btn-icon mr-2  float-left btn-success newscan"
                                                data-toggle="tooltip" data-placement="top" title="New Scan"><i
                                                class="far fa-check-circle"></i> {{__('files.New Scan')}}
                                    </button> -->


                                </div>


                                <!-- New Row  -->
                                <div class="row">
                                    <label class="col-12"
                                           style="color:#fff;font-size:26px;font-weight:bold; padding-bootom:10px;">
                                        {{__('files.New Visit')}}
                                        : </label>

                                    <div class="visits-btns">
                                        <button class="btn btn-sm btn-icon float-left btn-success  twwin_truck"
                                                id="twin_truck"
                                                data-toggle="tooltip" data-placement="top" title="twin truck"
                                                style="width: 125px;height: 54px;margin-right: 30px !important;">
                                            <i class="fa-solid fa-truck-moving" style="font-size: 25px;"></i>
                                        </button>

                                        <button class="btn btn-sm btn-icon float-left btn-success  truck" id="truck"
                                                data-toggle="tooltip" data-placement="top" title="truck"
                                                style="width: 125px;height: 54px;margin-right: 30px !important;">
                                            <i class="fa-solid fa-truck" style="font-size: 25px;"></i>
                                        </button>
                                        <button class="btn btn-sm btn-icon float-left btn-success  car" id="car"
                                                data-toggle="tooltip" data-placement="top" title="car" style="width: 125px;
    height: 54px;margin-right: 30px!important;"><i class="fa-solid fa-car" style="font-size: 25px;"></i>
                                        </button>

                                        <button class="btn btn-sm btn-icon float-left btn-success  person" id="person"
                                                data-toggle="tooltip" data-placement="top" title="person"
                                                style="width: 125px; height: 54px;margin-right: 30px!important;"><i
                                                    class="fa-solid fa-person-walking" style="font-size: 25px;"></i>
                                        </button>

                                        <button class="btn btn-sm btn-icon float-left btn-success  contractor" id="contractor"
                                                data-toggle="tooltip" data-placement="top" title="Contractor"
                                                style="width: 125px; height: 54px;margin-right: 30px!important;">
                                            <i class="fa fa-building" style="font-size: 25px;"></i>
                                        </button>

                                    </div>


                                </div>
                                <!-- End New Row -->

                            </div>


                            <div class="col-md-12 d-flex align-items-stretch"
                                 style="text-align:center;padding-top:25px;padding-bottom:25px">
                                <table id="view_visitor" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>{{__('files.ID')}}</th>
                                        <th>{{__('files.Name')}}</th>
                                        <th>{{__('files.Phone')}}</th>
                                        <th>{{__('files.Visit Date')}}</th>
                                        <th>{{__('files.Visit Time')}}</th>
                                        <th>{{__('files.Action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($visits) && count($visits) > 0)
                                        @foreach($visits as $visit)
                                            {{-- <tr class="approved_visit" > --}}
                                            <tr style="@if($visit->approval_status == 2) background-color: #24ba64 @endif">
                                                {{--                                            <tr style="@if($visit->employee->level == $visit->approval_status) background-color: #24ba64 @endif">--}}
                                                <td>{{$visit->id}}</td>
                                                <td>{{$visit->visitor->name}}</td>
                                                <td>{{$visit->visitor->phone}}</td>
                                                <td>{{\Illuminate\Support\Carbon::parse($visit->checkin_at)->format('Y-m-d')}}</td>
                                                <td>{{\Illuminate\Support\Carbon::parse($visit->checkin_at)->format('H:i:s')}}</td>
                                                <td>
                                                    <a class="btn btn-sm btn-icon mr-2 float-left btn-success scan mx-auto"
                                                       id="scan" title="scan" style="width: 50px;"
                                                       href="{{route('admin.view.scan.page',encrypt($visit->id))}}">
                                                        <i class="fa-solid fa-binoculars"></i>
                                                    </a>

                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif


                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{--@extends('admin.ocr.ocr_new_visit')--}}
</body>
</html>
{{--                                                    <button--}}
{{--                                                        class="btn btn-sm btn-icon mr-2 float-left btn-danger delete"--}}
{{--                                                        type="submit"--}}
{{--                                                        title="Delete" id="{{$visit->id}}" data-toggle="tooltip"--}}
{{--                                                        data-placement="top">--}}
{{--                                                        <i class="fa fa-trash"></i>--}}
{{--                                                    </button>--}}


{{--                                                <a class="btn btn-sm btn-icon mr-2 float-left btn-primary edit"--}}
{{--                                                   data-toggle="tooltip" data-placement="top"--}}
{{--                                                   title="Edit" id="{{$visit->id}}">--}}
{{--                                                    <i class="far fa-edit"></i>--}}
{{--                                                </a>--}}


{{--                                                <a class="btn btn-sm btn-icon mr-2 float-left btn-danger delete"--}}
{{--                                                   data-toggle="tooltip" data-placement="top" href="{{ route('admin.ocr.destroy',encrypt($visit->id)) }}"--}}
{{--                                                   title="Delete" id="{{$visit->id}}"--}}
{{--                                                   oncancel="event.preventDefault();document.getElementById('deleteVisit').submit();">--}}
{{--                                                    <i class="fa fa-trash"></i>--}}
{{--                                                </a>--}}


{{--                                <?php--}}
{{--                                if ($result->num_rows > 0) {--}}
{{--                                    // output data of each row--}}
{{--                                    while ($row = $result->fetch_assoc()) {--}}
{{--                                        $date = explode(" ", $row["checkin_at"]);--}}
{{--                                        echo "<tr><td>" . $row["visitor_id"] . "</td><td>" . $row["first_name"] . " " . $row["last_name"] . "</td><td>" . $row["phone"] . "</td>--}}
{{--												<td>" . $date[0] . "</td><td>" . $date[1] . "</td>";--}}
{{--                                        echo '<td>--}}

{{--											<a class="btn btn-sm btn-icon mr-2 float-left btn-primary edit" data-toggle="tooltip" data-placement="top" title="Edit" id="' . $row["visitor_id"] . '"> <i class="far fa-edit"></i></a>--}}
{{--											<a class="btn btn-sm btn-icon mr-2 float-left btn-danger delete" data-toggle="tooltip" data-placement="top" title="Delete" id="' . $row["visitor_id"] . '"> <i class="fa fa-trash"></i></a>--}}

{{--											</td></tr>';--}}
{{--                                    }--}}
{{--                                }--}}
{{--                                ?>--}}
