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
    <script type="text/javascript" src="{{asset('DataTables/buttons.html5.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('DataTables/buttons.print.min.js')}}"></script>

    <style>
        .approved_visit {
            background-color: green;
        }
    </style>


    @extends('admin.ocr.view_extend')

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
        <div class="row justify-content-right" style="direction:rtl"><a href="indexar.php">عربى</a>|<a>English</a></div>
        <div class="row justify-content-center">
            <div class="col-md-6 text-center mb-5" style="margin-bottom:0px !important">
                <h2 class="heading-section">Manage Visits</h2>
            </div>
        </div>
        <br/>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="wrapper">
                    <div class="row no-gutters">
                        <div class="col-md-12">
                            <div class="contact-wrap w-100 p-lg-5 p-4">
                                <label style="color:#fff;font-size:26px;font-weight:bold">Filter By Date Period:</label>

                                <div class="row" style="text-align:center !important;padding-left:27%">
                                    <div class="text pl-3" style="margin-left:3%">
                                        <p><span style="color:#fff">Check in Date:</span> <input type="text"
                                                                                                 class="v2date form-control"
                                                                                                 id="v2date"
                                                                                                 style="color:#000 !important"/>
                                        </p>
                                    </div>
                                    <div class="text pl-3">
                                        <p><span style="color:#fff">Check out Date:</span> <input type="text"
                                                                                                  class="v3date form-control"
                                                                                                  id="v3date"
                                                                                                  style="color:#000 !important"/>
                                        </p>

                                    </div>
                                    <div style="width:100%;float:left"></div>


                                    <button class="btn btn-sm btn-icon mr-2  float-left btn-success find"
                                            data-toggle="tooltip" data-placement="top" title="Search"><i
                                            class="fa fa-search"></i> Search
                                    </button>
                                    <button class="btn btn-sm btn-icon mr-2  float-left btn-success clr"
                                            data-toggle="tooltip" data-placement="top" title="clear"><i
                                            class="fa fa-refresh"></i> Clear Search
                                    </button>
                                    <button class="btn btn-sm btn-icon mr-2  float-left btn-success newscan"
                                            data-toggle="tooltip" data-placement="top" title="New Scan"><i
                                            class="far fa-check-circle"></i> New Scan
                                    </button>
                                </div>

                            </div>
                        </div>

                        <div class="col-md-12 d-flex align-items-stretch"
                             style="text-align:center;padding-top:25px;padding-bottom:25px">
                            <table id="view_visitor" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Visit Date</th>
                                    <th>Visit Time</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($visits) && count($visits) > 0)
                                    @foreach($visits as $visit)
                                        {{-- <tr class="approved_visit" > --}}
                                        <tr style="@if($visit->employee->level == $visit->approval_status) background-color: #24ba64 @endif">
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
