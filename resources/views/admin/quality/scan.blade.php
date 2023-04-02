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
                            <a class="accept btn btn-primary" id="accept-visit">{{__('files.Accept Visit')}}</a>
                            <a class="reject btn btn-danger" id="reject-visit">{{__('files.Reject Visit')}}</a>
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
                                // alert('berhasil');
                                console.log(response);
                            $('#visit-control').css({"display":""});

                            visit_id = response.data;


                            } else {
                                alert(response);
                                // alert('gagal');
                                console.log(response);
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

                                console.log(response.data);

                            } else {
                                    iziToast.success({
                                        title: 'Error',
                                        message: ".response.",
                                        position: 'topRight',
                                    });
                                }
                        }
                    }); // end of function accept visit

        }); // end of accept visit


        $('#reject-visit').on('click',function() {
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

                            } else {
                                    iziToast.success({
                                        title: 'Error',
                                        message: "{{__('files.Something Went Wrong')}}",
                                        position: 'topRight',
                                    });
                                }
                        }
                    }); // end of function accept visit
        }); // end reject visit

    </script>
@endsection