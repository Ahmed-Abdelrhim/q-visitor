<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<link rel="stylesheet" href="{{ asset('assets/modules/select2/dist/css/select2.min.css') }}">


@include('admin.layouts.components.head')
@include('admin.layouts.components.custm_styles')


@include('admin.ocr.index_style')
@include('admin.ocr.script')


<body>
<div id="app">
    <div class="main-wrapper">
        {{--        @include('admin.layouts.components.navigation')--}}
        <!-- Main Content -->
        <div class="main-contentt">
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
                            <div class="col-md-14">
                                <div class="wrapper">
                                    <div class="row no-gutters">
                                        {{--    Scan Data   --}}
                                        <div class="col-md-12">
                                            <div class="contact-wrap w-100 p-lg-5 p-4">
                                                @if(!empty($car_type))
                                                    @if($car_type == 'T' || $car_type == 'C' || $car_type == 'TWIN_TRUCK')
                                                        <div class="row" style="text-align:center">
                                                            <h5>{{__('files.Car Plate')}} :</h5><input type="text"
                                                                                                       disabled
                                                                                                       class="form-control plate_no"
                                                                                                       id="plate_number_input"
                                                                                                       name="car_plate_number"/>

                                                            @if($car_type == 'TWIN_TRUCK')
                                                                <h5>{{__('files.Twin Truck Number')}} :</h5>
                                                                <input type="text" class="form-control plate_no"
                                                                       name="twin_truck_number"
                                                                       style="width: 150px; height: 35px; margin-left: 10px; "/>
                                                            @endif

                                                            <a class="btn btn-primary dashboard"
                                                               href="{{ route('admin.dashboard.index') }}"
                                                               style="height: 35px; padding: 7px 14px;margin-left: 7%">
                                                                {{__('files.Dashboard')}}
                                                            </a>

                                                        </div>
                                                    @endif
                                                @endif


                                                <br/>
                                                <div class="row" style="text-align:center">
                                                    <div class="col-md-3">
                                                        <div class="form-group"
                                                             style="text-align:center;float:left;margin-right:20px">
                                                            <img id="pic" src="{{asset('images/personal.png')}}"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <img id="white_picture" src="{{asset('images/id.jpg')}}"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <img id="ocr_head" src="{{asset('images/id.jpg')}}"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <img id="chip_head" src="{{asset('images/id.jpg')}}"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div style="margin-top : 20px; margin-bottom: 20px; width: 100%;">
                                            @if(isset($car_plates))
                                                <h5 class="h5 text-center">{{__('files.Car Plate Number')}}</h5>
                                                <select class="js-example-templating form-control"
                                                        id="car_plate_number">
                                                    <option disabled>-- Select Car Plate Number --</option>
                                                    @foreach($car_plates as $plate)
                                                        <option value="{{$plate->plate_number}}">{{$plate->plate_number}}</option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </div>
                                        {{-- End Car Plates--}}


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
                                                        <p><span>{{__('files.Name')}} :</span> <a id="name"
                                                                                                  class="txt"></a></p>
                                                    </div>
                                                </div>
                                                <div class="dbox w-25 d-flex align-items-center">
                                                    <div class="icon d-flex align-items-center justify-content-center">
                                                        <span class="fa fa-calendar"></span>
                                                    </div>
                                                    <div class="text pl-3">
                                                        <p><span>{{__('files.Date Of Birth')}} :</span> <a id="dob"
                                                                                                           class="txt"></a>
                                                        </p>
                                                    </div>
                                                </div>


                                                <div class="dbox w-25 d-flex align-items-center">
                                                    <div class="icon d-flex align-items-center justify-content-center">
                                                        <span class="fa fa-id-card"></span>
                                                    </div>
                                                    <div class="text pl-3">
                                                        <p><span>{{__('files.ID Number')}} :</span> <a id="mrz"
                                                                                                       class="txt"></a>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="dbox w-25 d-flex align-items-center">
                                                    <div class="icon d-flex align-items-center justify-content-center">
                                                        <span class="fa fa-intersex"></span>
                                                    </div>
                                                    <div class="text pl-3">
                                                        <p><span>{{__('files.Gender')}} :</span> <a id="sex"
                                                                                                    class="txt"></a></p>
                                                    </div>
                                                </div>


                                                <div class="dbox w-25 d-flex align-items-center">
                                                    <div class="icon d-flex align-items-center justify-content-center">
                                                        <span class="fa fa-exclamation-triangle"></span>
                                                    </div>
                                                    <div class="text pl-3">
                                                        <p id="expiration_date">
                                                            <span>{{__('files.Expiry Date')}} :</span> <a
                                                                    id="exdate" class="txt"></a></p>
                                                    </div>
                                                </div>
                                                <div class="dbox w-25 d-flex align-items-center">
                                                    <div class="icon d-flex align-items-center justify-content-center">
                                                        <span class="fa fa-tasks"></span>
                                                    </div>
                                                    <div class="text pl-3">
                                                        <p><span>{{__('files.Job')}} :</span> <a id="job"
                                                                                                 class="txt"></a></p>
                                                    </div>
                                                </div>
                                                <div class="dbox w-25 d-flex align-items-center">
                                                    <div class="icon d-flex align-items-center justify-content-center">
                                                        <span class="fa fa-user"></span>
                                                    </div>
                                                    <div class="text pl-3">
                                                        <p><span>{{__('files.Marital status')}} :</span> <a id="mstat"
                                                                                                            class="txt"></a>
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="dbox w-25 d-flex align-items-center">
                                                    <div class="icon d-flex align-items-center justify-content-center">
                                                        <span class="fa fa-calendar"></span>
                                                    </div>
                                                    <div class="text pl-3">
                                                        <p><span>{{__('files.Issuing Date')}} :</span> <a id="isdate"
                                                                                                          class="txt"></a>
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="dbox w-25 d-flex align-items-center">
                                                    <div class="icon d-flex align-items-center justify-content-center">
                                                        <span class="fa fa-calendar"></span>
                                                    </div>
                                                    <div class="text pl-3">
                                                        <p><label for="vdate">{{__('files.Visit Date')}} :</label>
                                                            <input type="text"
                                                                   value="<?php echo date('d-m-Y'); ?>"
                                                                   class="vdate form-control"
                                                                   id="vdate"
                                                                   disabled="disabled"/>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="dbox w-25 d-flex align-items-center">
                                                    <div class="icon d-flex align-items-center justify-content-center">
                                                        <span class="fa fa-calendar"></span>
                                                    </div>
                                                    <div class="text pl-3">
                                                        <p><label for="vtime">{{__('files.Visit Time')}} :</label>
                                                            <input type="text"
                                                                   value="<?php echo date('h:i:s a'); ?>"
                                                                   class="vtime form-control"
                                                                   id="vtime"
                                                                   disabled="disabled"/>
                                                        </p>

                                                    </div>
                                                </div>
                                                <div class="dbox w-25 d-flex align-items-center">
                                                    <div class="icon d-flex align-items-center justify-content-center">
                                                        <span class="fa fa-flag"></span>
                                                    </div>
                                                    <div class="text pl-3">
                                                        <p><span>{{__('files.Country Code')}} :</span> <a id="icc"
                                                                                                          class="txt"></a>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="dbox w-25 d-flex align-items-center"
                                                     style="display:none !important">
                                                    <div class="icon d-flex align-items-center justify-content-center">
                                                        <span class="fa fa-book"></span>
                                                    </div>
                                                    <div class="text pl-3">
                                                        <p><span>{{__('files.Religion')}} :</span> <a id="relg"
                                                                                                      class="txt"></a>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="dbox w-25 d-flex align-items-center">
                                                    <div class="icon d-flex align-items-center justify-content-center">
                                                        <span class="fa fa-address-card"></span>
                                                    </div>
                                                    <div class="text pl-3">
                                                        <p><span>{{__('files.Address')}} :</span> <a id="address"
                                                                                                     class="txt"></a>
                                                        </p>
                                                    </div>
                                                </div>


                                                <div class="input-group mb-3 " style="margin-top: 10px;">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text"
                                                               for="employee">{{__('files.Employee')}}</label>
                                                    </div>
                                                    <select class="form-control select2" id="employee">
                                                        <option selected value="0">{{__('files.Choose Employee')}}...
                                                        </option>

                                                        @foreach($employees as $employee)
                                                            <option value="{{$employee->id}}">{{$employee->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>


                                                <div class="dbox w-100 d-flex align-items-center"
                                                     style="margin-bottom:0px !important;height: 50px !important;margin-top:55px;text-align:center;padding-left: 35%;">
                                                    <div class="form-group">
                                                        <input type="button" value="{{__('files.New Scan')}}"
                                                               class="btn btn-danger new_page">
                                                        <input type="button" value="{{__('files.Scan')}}"
                                                               class="btn btn-danger scan"
                                                               onclick="connect();">
                                                        <input type="button" value="{{__('files.Save Data')}}"
                                                               class="btn btn-success newscan"
                                                               style="background-color: #0a71db !important;">


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

        </div>
    </div>
</div>
@include('admin.layouts.components.script')
@include('admin.ocr.index_footer_scripts')

<script src="{{ asset('assets/modules/select2/dist/js/select2.full.min.js') }}"></script>
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
        $(document).ready(function() {
            // console.log( $('.select2-search__field').val() );
            // $('.select2-search__field').on('change' , function () {
            //     console.log('dklasjhdklaisjd');
            // });
            
            $(".js-example-templating").select2({});
            $("#employee").select2({});
            $(document).on('keyup', '.select2-search__field:first', function (e) {
                value = $('.select2-search__field')[0].value;
                console.log( value );
                $.ajax({
                    url: '{{ route('admin.search.car.plate') }}',
                    type:"GET",
                    data : {value},
                    success: function(data) {
                        console.log(data);
                        // console.log(data[0].plate_number);
                        $('#car_plate_number').empty();
                        for (var i = 0; i < data.length; i++) {
                            value = data[i].plate_number;
                            $('#car_plate_number').append('<option value="'+value+'">'+value+'</option>')
                        }
                    }
                });
            });
        });
        // var isInitialLoad = true; // Flag to track initial load
        // $('.search-select').select2({
        //     placeholder: '...جاري البحث',
        //     ajax: {
        //         url: '{{ route('admin.search.car.plate') }}',
        //         dataType: 'json',
        //         type:"GET",
        //         delay: 250,
        //         processResults: function (data) {
        //             // console.log(data);
        //             return {
        //                 results: data.map(function (item) {
        //                     return {
        //                         text: item.plate_number,
        //                         id: item.id
        //                     }
        //                 })
        //                 // End Of Search Is Here
        //             };
        //         },
        //         cache: true
        //     }
        // }).on('select2:open', function() {
        //         console.log('opned');
        //         console.log( $('.select2-search__field') );
        //         var searchInput = $('.select2-search__field').val();
        //         if ( searchInput == '') {
        //             console.log('Yes Empty');
        //         }
        //         if (searchInput) {
        //             console.log('Search input value:', searchInput);
        //             // Perform desired logic with the search input value
        //         }
        //     }).on('select2:close', function() {
        //         // Clear the search input
        //         $('.select2-search__field').val('');
        //     });





</script>
</body>
</html>
