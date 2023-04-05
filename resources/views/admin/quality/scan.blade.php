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
                        <div id="reader" width="600px" style="width: 500px;" class="mx-auto"></div>


                        <div class="mx-auto" style="display: none;" id="visit-control">
                            {{-- Visit Data --}}

                            <div class="card" style="width: 18rem;">
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
                                            <span style="font-weight: bold">  {{__('files.Visit Type')}} </span>
                                            <span id="visit-type"></span>
                                        @endif


                                    </li>
                                    {{--  <li class="list-group-item" id="visitor-id">A third item</li>  --}}

                                </ul>
                                <div class="row mx-auto mt-3">
                                    <a href="#" class="btn btn-primary" style="margin-right: 10px; width: 80px;"
                                       id="accept-visit"> {{__('files.Accept')}}</a>
                                    <a href="#" class="btn btn-danger" style="margin-left: 10px; width: 80px;"
                                       id="reject-visit">{{__('files.Reject')}}</a>
                                </div>


                            </div>

                            {{--                            <button class="accept btn btn-primary" id="accept-visit"--}}
                            {{--                                    style="height: 38px;">{{__('files.Accept')}}</button>--}}
                            {{--                            <button class="reject btn btn-danger" id="reject-visit"--}}
                            {{--                                    style="height: 38px;">{{__('files.Reject')}}</button>--}}


                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
            integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
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
                            console.log(response);
                            if(response.status == 200) {
                                console.log(response);
                            $('#visit-control').css({"display":""});

                            visit_id = response.data;


                            $('#visit-reg_no').text(response.visit.reg_no);
                            $('#visitor-name').text(response.visit.visitor.first_name +  " " + response.visit.visitor.last_name);
                            $('#visitor-identification-number').text(  response.visit.visitor.national_identification_no);

                            if( response.visit.car_type == 'T' ) {
                                $('#visit-type').text('Truck');
                            }

                            } else {
                                alert(response);
                                location.reload();
                                // alert('gagal');
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