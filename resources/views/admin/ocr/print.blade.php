<!doctype html>
<!doctype html>
<html lang="en">
<head>
    <title>Passport Scanner</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="{{asset('css/ocr_styles/style.css')}}">

    <style>
        .card {
            background-color: #1f1f1f;
            border-radius: 15px;
        }

        .id-card {
            background-color: #fff;
            padding: 10px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 0 1.5px 0px #b9b9b9;
        }

    </style>
</head>
<body>
<div class="col-12 col-md-12 col-lg-12" style="height: fit-content">
    <div class="card" style="width:80%;text-align:center;">
        <!--<div class="card-header">
            <a href="#" id="print" class="btn btn-icon icon-left btn-primary"><i class="fas fa-print"></i> Print</a>
        </div>-->
        <div class="card-body mx-auto">
            <div class="img-cards" id="printidcard">
                <div class="id-card-holder">
                    <div class="id-card">
                        <div class="id-card-photo">
                            {{--                            <!-- @if($visitingDetails->getFirstMediaUrl('visitor'))--}}
                            {{--                                <img src="{{ asset($visitingDetails->getFirstMediaUrl('visitor')) }}" alt="">--}}
                            {{--                             @else--}}
                            {{--                                <img src="{{ asset('images/'.setting('id_card_logo')) }}" alt="">--}}
                            {{--                             @endif-->--}}

                            {{--  <img src="<?php echo 'per_images/' . $reg_no . '.png'; ?>" alt=""--}}
                            @if(isset($data->images))
                                <img src="{{$data->images  }}" alt="not-found" style="clip-path: circle();width:70%">
                            @else
                                <img src="" alt="not-found" style="clip-path: circle();width:50%">
                            @endif

                            {{--                            <img src="{{asset('storage/per_images/'. $data->reg_no.'.png') }} " alt="not-found" style="clip-path: circle();width:50%">--}}
                            {{--                            <img src="{{$data->images}} " alt="not-found"--}}
                            {{--                                 style="clip-path: circle();width:50%">--}}
                        </div>


                        @if(isset($data->visitor->first_name))
                            <h1 style="font-weight: bold;  margin-top: 35px;">{{$data->visitor->first_name}} {{$data->visitor->last_name}} </h1>
                        @else
                            <h2>No Valid Name</h2>
                        @endif

                        {{--                        <!--<h3>Ph:<?php echo $phone ?></h3>--}}
                        {{--						<h3>{{__('ID#')}}{{$visitingDetails->reg_no}}</h3>-->--}}


                        <h1 style="font-weight: bold ;  margin-top: 20px;"> Visit Date:
                            @if(!empty($data->checkin_at))
                                {{$data->checkin_at}}
                            @else
                                Not Specified Date
                            @endif
                            {{--<?php echo $datein ?>--}}
                        </h1>
                        <h3 style="margin-bottom: 20px;">
                            @if(!empty($data->company_name ) )
                                {{$data->company_name}}
                            @else
                                Not Specified Company Name
                            @endif
                        </h3>
                        <hr />

                        <img src="{{ $data->qrcode }}" alt="" style="width: 710px !important; margin-top: 35px;">
                        {{-- <img src="{{ $data->qrcode }}" alt="" style="width: 800px !important; ">    --}}

                    </div>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
</div>


<script src="{{asset('js/ocr_scripts/jquery.min.js')}}"></script>
<script>
    $().ready(function () {
        //window.print();
    });
</script>


</body>

