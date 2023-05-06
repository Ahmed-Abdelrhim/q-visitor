@extends('admin.layouts.master')
@section('main-content')


    <section class="section">
        <div class="section-header">
            <h1>{{ __('files.Quality Scan') }}</h1>
            {{ Breadcrumbs::render('visitors') }}
        </div>


        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">

{{--                        <div id="reader" style="width: 500px;" class="mx-auto"></div>--}}
                        <div id="reader"  class="mx-auto scan-visit"></div>

                        <div class="mx-auto" style="display: none;" id="visit-control">
                             Visit Data

                            <div class="card-body" style="width: 18rem;">
                                <div class="card-header " style="font-weight: bold">
                                    {{__('files.Visit Data')}}
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <span style="font-weight: bold">  {{__('files.Visit Register No :')}} </span>
                                        <span id="visit-reg_no"></span>
                                    </li>

                                    <li class="list-group-item">
                                        @if(app()->getLocale() == 'ar' )
                                            <span id="visitor-name"></span>
                                            <span style="font-weight: bold"> : {{__('files.Visitor Name')}}   </span>
                                        @else
                                            <span style="font-weight: bold"> {{__('files.Visitor Name')}} :  </span>
                                            <span id="visitor-name"></span>
                                        @endif
                                    </li>


                                    <li class="list-group-item">
                                        @if(app()->getLocale() == 'ar' )
                                            <span id="visitor-identification-number"></span>
                                            <span style="font-weight: bold"> : {{__('files.Visitor ID Number')}}   </span>
                                        @else
                                            <span style="font-weight: bold"> {{__('files.Visitor ID Number')}} :  </span>
                                            <span id="visitor-identification-number"></span>
                                        @endif
                                    </li>


                                    <li class="list-group-item">
                                        @if(app()->getLocale() == 'ar' )
                                            <span id="visit-type"></span>
                                            <span style="font-weight: bold">  : {{__('files.Visit Type')}} </span>
                                        @else
                                            <span style="font-weight: bold">  {{__('files.Visit Type')}} : </span>
                                            <span id="visit-type"></span>
                                        @endif
                                    </li>


                                    <li class="list-group-item">
                                        @if(app()->getLocale() == 'ar' )
                                            <span id="visit-date"></span>
                                            <span style="font-weight: bold">  : {{__('files.Visit Date Start')}} </span>
                                        @else
                                            <span style="font-weight: bold">  {{__('files.Visit Date Start')}} : </span>
                                            <span id="visit-date"></span>
                                        @endif
                                    </li>


                                      <li class="list-group-item" id="visitor-id">A third item</li>

                                </ul>

                                <div class="row mx-auto mt-3" id="visit_control_buttons">
                                    <a href="#" class="btn btn-primary" style="margin-right: 10px; width: 80px;"
                                       id="accept-visit"> {{__('files.Accept')}}</a>
                                    <a href="#" class="btn btn-danger" style="margin-left: 10px; width: 80px;"
                                       id="reject-visit">{{__('files.Reject')}}</a>
                                </div>


                            </div>

                                                        <button class="accept btn btn-primary" id="accept-visit"
                                                                style="height: 38px;">{{__('files.Accept')}}</button>
                                                        <button class="reject btn btn-danger" id="reject-visit"
                                                                style="height: 38px;">{{__('files.Reject')}}</button>


                        </div>
                        <h3 class="mx-auto" style="display: none; font-weight: bold" id="no_need_for_quality_check">{{__('files.Visit Approved From Quality')}}</h3>
                    </div>
                </div>
            </div>
        </div>


    </section>
@endsection

@section('scripts')
    <script src="{{ asset('assets/modules/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/modules/select2/dist/js/select2.full.min.js') }}"></script>


    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"--}}
{{--            integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="--}}
{{--            crossorigin="anonymous" referrerpolicy="no-referrer">--}}
{{--    </script>--}}

    <script>
        // var time = $.datepicker.formatDate('dd M yy', new Date());

        var visit_id = 0;
        function onScanSuccess(decodedText, decodedResult) {
            $('#result').val(decodedText);
            let id = decodedText;
            html5QrcodeScanner.clear().then(_ => {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                        url: "{{ route('admin.scan.qr') }}",
                        type: 'POST',
                        data: {
                            _methode : "POST",
                            _token: CSRF_TOKEN,
                            qr_code : id
                        },
                        success: function (response) {
                            // console.log(response);

                            if(response.status == 200) {
                                console.log(response);

                                if(response.quality_check == 'Yes') {
                                    $('#visit-control').css({"display":""});
                                } else {
                                    $('#visit-control').css({"display":""});
                                    $('#visit_control_buttons').css({"display":"none"});
                                    $('#no_need_for_quality_check').css({"display":""});
                                }



                            visit_id = response.data;


                            $('#visit-reg_no').text(response.visit.reg_no);
                            $('#visitor-name').text(response.visit.visitor.first_name +  " " + response.visit.visitor.last_name);
                            $('#visitor-identification-number').text(  response.visit.visitor.national_identification_no);

                            if( response.visit.car_type == 'T' ) {
                                // $('#visit-type').text('Truck');
                                // console.log(response.visit.shipment);
                                $('#visit-type').text(response.visit.shipment.name);
                            }

                            // var date = Date.parse(  response.visit.checkin_at ).toString('yyyy-MM-dd');
                            var date = response.visit.checkin_at;
                            // var date = $.datepicker.formatDate( "D dd-M-yy", response.visit.checkin_at ) // Output "Fri 08-Sep-2017"
                            var date =  date.toString('yyyy-M-d')
                            $('#visit-date').text(date);

                            } else {
                                alert(response);
                                location.reload();
                                // console.log(response);
                            }
                        }
                    });
                }).catch(error => {
                    alert('something wrong');
                });




        // handle the scanned code as you like, for example:


        // console.log(`Code matched = ${decodedText}`, decodedResult);
        }

    function onScanFailure(error) {
      // handle scan failure, usually better to ignore and keep scanning.
      // for example:


        // console.warn(`Code scan error = ${error}`);
        }

    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader",
        { fps: 10, qrbox: {width: 250, height: 250} },
        /* verbose= */ false);
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);

        $('#accept-visit').on('click',function() {
            $('#reject-visit').prop('disabled', true);

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: "{{ route('admin.accept.visit.from.quality') }}",
                        type: 'POST',
                        data: {
                            _methode : "POST",
                            _token: CSRF_TOKEN,
                            visit_id : visit_id,
                        },
                        success: function (response) {
                            console.log(response);
                            if(response.status == 200) {
                                // izi fire

                                iziToast.success({
                                    title: 'Success',
                                    message: "{{__('files.Visit Approved Successfully')}}",
                                    position: 'topRight',
                                });

                                setTimeout(reload, 2000);

                                function reload () {
                                    location.reload();
                                }

                            } else {
                                    iziToast.info({
                                        title: 'info',
                                        message: "{{__('files.Something Went Wrong Because Visit ID Was Not Found')}}",
                                        position: 'topRight',
                                    });
                                }
                        }
                    }); // end of function accept visit

        }); // end of accept visit


        $('#reject-visit').on('click',function() {
            $('#accept-visit').prop('disabled', true);

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: "{{ route('admin.reject.visit.from.quality') }}",
                        type: 'POST',
                        data: {
                            _methode : "POST",
                            _token: CSRF_TOKEN,
                            visit_id : visit_id,
                        },
                        success: function (response) {
                            console.log(response);
                            if(response.status == 200) {
                                // izi fire

                                iziToast.success({
                                    title: 'Success',
                                    message: "{{__('files.Visit Rejected Successfully')}}",
                                    position: 'topRight',
                                });

                                setTimeout(reload, 2000);

                                function reload() {
                                    location.reload();
                                    // location.replace('https://qudratech-eg.net/visitorpass/public/index.php/admin/Qr/Index');
                                }

                            } else {
                                    iziToast.info({
                                        title: 'info',
                                        message: "{{__('files.Something Went Wrong Becausr Visit ID Was Not Found')}}",
                                        position: 'topRight',
                                    });
                                }
                        }
                    }); // end of function accept visit
        }); // end reject visit














    </script>
@endsection