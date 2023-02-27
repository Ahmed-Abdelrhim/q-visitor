@extends('admin.layouts.master')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/id-card-print.css') }}">
@endsection
@section('main-content')

    <section class="section">
        <div class="section-header">
            <h1>{{ __('files.Visit Reservation') }}</h1>
            {{ Breadcrumbs::render('visitors/show') }}
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-4 col-md-4 col-lg-4 ">
                    <div class="card">
                        <div class="card-header">
                            <a href="#" id="print" class="btn btn-icon icon-left btn-primary"><i
                                        class="fas fa-print"></i> {{ __('files.Print ID card') }}</a>
                        </div>
                        <div class="card-body">
                            <div class="img-cards" id="printidcard">
                                <div class="id-card-holder">
                                    <div class="id-card">
                                        <div class="id-card-photo">
                                            <!-- @if($visitingDetails->getFirstMediaUrl('visitor'))
                                                <img src="{{ asset($visitingDetails->getFirstMediaUrl('visitor')) }}" alt="">
                                            @else
                                                <img src="{{ asset('images/'.setting('id_card_logo')) }}" alt="">
                                            @endif-->
                                                    <?php if (str_contains($visitingDetails->visitor->photo, 'base64')) { ?>
                                                <img src="{{$visitingDetails->visitor->photo}}" alt=""
                                                     style="clip-path: circle();">
                                                <?php }else{ ?>
                                                <img src="<?php echo "http://localhost/visitorpass/public/";?>{{$visitingDetails->visitor->photo}}"
                                                     alt="" style="clip-path: circle();">
                                                <?php } ?>
                                        </div>
                                        <h2>{{$visitingDetails->visitor->name}}</h2>
                                        <h3>{{__('Ph: ')}}{{$visitingDetails->visitor->phone}}</h3>
                                        <!--<h3>{{__('ID#')}}{{$visitingDetails->reg_no}}</h3>-->
                                        <h3>{{__('Expiry: ')}}{{date('d-m-Y h:i A', strtotime($visitingDetails->expiry_date))}}</h3>
                                        <h3>{{$visitingDetails->company_name}}</h3>
                                        <h3>{{$visitingDetails->visitor->address}}</h3>
                                        <h2>{{__('VISITED TO')}}</h2>
                                        <h3>{{__('Host:')}} {{$visitingDetails->employee->name}}</h3>
                                        <hr>
                                        <!--<p><strong>{{ setting('site_name') }} </strong></p>
                                        <p><strong>{{ setting('site_address') }} </strong></p>
                                        <p>{{__('Ph:')}} {{ setting('site_phone') }} | {{__('E-mail:')}} {{ setting('site_email') }} </p>
										<img src="{{ asset('images/QRCode/'.$visitingDetails->qrcode.'.png') }}" alt="" style="max-width: 60% !important;">-->
                                            <?php if (str_contains($visitingDetails->qrcode, 'http')) { ?>
                                        <img src="{{ $visitingDetails->qrcode}}" alt=""
                                             style="max-width: 60% !important;">
                                        <?php }else{ ?>
                                        <img src="<?php echo "http://localhost/visitorpass/public/";?>{{ $visitingDetails->qrcode}}"
                                             alt="" style="max-width: 60% !important;">
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>

                <div class="col-8 col-md-8 col-lg-8 id-card-in-arabic">
                    <div class="card">
                        <div class="card-body">
                            <div class="profile-desc">
                                <div class="single-profile">
                                    <p><b>{{ __('files.First Name') }} : </b>
                                        @if(empty($visitingDetails->visitor->first_name))
                                            ---
                                        @else
                                            {{ $visitingDetails->visitor->first_name}}
                                        @endif

                                    </p>
                                </div>
                                <div class="single-profile">
                                    <p><b>{{ __('files.Last Name') }} : </b>
                                        @if(empty($visitingDetails->visitor->last_name))
                                            ---
                                        @else
                                            {{ $visitingDetails->visitor->last_name}}
                                        @endif
                                    </p>
                                </div>
                                <div class="single-profile">
                                    <p><b>{{ __('files.Email') }} : </b>
                                        @if(empty($visitingDetails->visitor->email))
                                            ---
                                        @else
                                            {{ $visitingDetails->visitor->email}}
                                        @endif

                                    </p>
                                </div>
                                <div class="single-profile">
                                    <p><b>{{ __('files.Phone') }} : </b>
                                        @if(empty($visitingDetails->visitor->phone))
                                            ---
                                        @else
                                            {{ $visitingDetails->visitor->phone}}
                                        @endif

                                    </p>
                                </div>
                                <div class="single-profile">
                                    <p><b>{{ __('files.Employee') }} : </b>
                                        @if(empty($visitingDetails->employee->user->name))
                                            ---
                                        @else
                                            {{ $visitingDetails->employee->user->name}}
                                        @endif

                                    </p>
                                </div>
                                <div class="single-profile">
                                    <p><b>{{ __('files.Purpose') }} : </b>
                                        @if(empty($visitingDetails->purpose))
                                            ---
                                        @else
                                            {{ $visitingDetails->purpose}}
                                        @endif

                                    </p>
                                </div>
                                <div class="single-profile">
                                    <p><b>{{ __('files.Company') }} : </b>
                                        @if(empty($visitingDetails->company_name))
                                            ---
                                        @else
                                            {{ $visitingDetails->company_name}}
                                        @endif

                                    </p>
                                </div>
                                <div class="single-profile">
                                    <p><b>{{ __('files.National Identification') }}
                                            : </b>
                                        @if(empty($visitingDetails->national_identification_no))
                                            ---
                                        @else
                                            {{ $visitingDetails->national_identification_no}}
                                        @endif
                                    </p>
                                </div>
                                <div class="single-profile">
                                    <p><b>{{ __('files.Date') }}
                                            : </b>
                                        @if(empty($visitingDetails->created_at))
                                            ---
                                        @else
                                            {{date('d-m-Y', strtotime($visitingDetails->created_at))}}
                                        @endif
                                    </p>
                                </div>
                                <div class="single-profile">
                                    <p><b>{{ __('files.Checkin') }}
                                            : </b>
                                        @if(empty($visitingDetails->checkin_at))
                                            ---
                                        @else
                                            {{date('d-m-Y h:i A', strtotime($visitingDetails->checkin_at))}}
                                        @endif
                                    </p>
                                </div>
                                @if($visitingDetails->checkout_at)
                                    <div class="single-profile">
                                        <p><b>{{ __('files.Checkout') }}
                                                : </b>
                                            @if(empty($visitingDetails->checkout_at))
                                                ---
                                            @else
                                                {{date('d-m-Y h:i A', strtotime($visitingDetails->checkout_at))}}
                                            @endif


                                        </p>
                                    </div>
                                @endif
                                <div class="single-profile">
                                    <p><b>{{ __('files.Address') }} : </b>
                                        @if(empty($visitingDetails->visitor->address))
                                            ---
                                        @else
                                            {{ $visitingDetails->visitor->address}}
                                        @endif
                                    </p>
                                </div>
                                <div class="single-profile">
                                    <p><b>{{ __('files.Status') }} : </b>

                                        @if(empty( $visitingDetails->my_status))
                                            ---
                                        @else
                                            {{  $visitingDetails->my_status}}
                                        @endif

                                    </p>
                                </div>
                                <div class="single-profile">
                                    <p><b>{{ __('files.QRCode') }} : </b> <a
                                                href="{{$visitingDetails->qrcode}}">{!! str_replace('https://www.qudratech-eg.net/qrcode/temp/', ' ', $visitingDetails->qrcode) !!}</a>
                                    </p>
                                </div>
                                <div class="single-profile">
                                    <p><b>{{ __('files.Expiry Date') }}
                                            : </b>
                                        @if(empty($visitingDetails->expiry_date))
                                            ---
                                        @else
                                            {{date('d-m-Y h:i A', strtotime($visitingDetails->expiry_date))}}
                                        @endif

                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('assets/modules/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/modules/datatables.net-select-bs4/js/select.bootstrap4.min.js') }}"></script>

    <script>
        var idCardCss = "{{ asset('css/id-card-print.css') }}";
    </script>

    <script src="{{ asset('js/visitor/view.js') }}"></script>
@endsection
