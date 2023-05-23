<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<link rel="stylesheet" href="{{ asset('assets/modules/select2/dist/css/select2.min.css') }}">


@include('admin.layouts.components.head')
@include('admin.layouts.components.custm_styles')

@include('admin.ocr.index_style')
@include('admin.ocr.script')

<body>
<div id="app">
    <div class="main-wrapper" >
        <!-- Main Content -->
        <div class="main-contentt" >
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
                            <h2 class="heading-section">{{__('files.Contractors')}}</h2>
                        </div>
                    </div>


                    <div class="row justify-content-center">
                        <div class="col-md-14">
                            <div class="col-md-14">
                                <div class="wrapper">
                                    <div class="row no-gutters">
                                        {{--    Scan Data   --}}
                                        <div class="col-md-12">
                                            <div class="contact-wrap w-20 p-lg-5 p-4" style="height: 250px !important;">
                                                <br/>
                                                <div class="row" style="text-align:center">
                                                    <h2 class="h2 text-center" style="width: 100%;">
                                                        {{__('files.Choose Contractor')}}
                                                    </h2>
                                                    <div class="col-md-12 d-flex  text-center" >
                                                        <div class="info-wrap w-100 p-lg-5 p-4 img">
                                                            <form action="{{route('admin.workers.search')}}" method="POST" enctype="multipart/form-data" style="width: 100%; !important; margin-left: ">
                                                                @csrf
                                                                <div class="input-group mb-3 " style="margin-top: 10px;">
                                                                    <div class="input-group-prepend">
                                                                        <label class="input-group-text"
                                                                               for="contractor">{{__('files.Choose Contractor')}}</label>
                                                                    </div>
                                                                    <select class="form-control select2" id="contractor" name="contractor">
                                                                        <option selected value="0">
                                                                            @if(app()->getLocale() == 'ar')
                                                                                ···
                                                                                {{__('files.Choose Contractor')}}
                                                                            @else
                                                                                {{__('files.Choose Contractor')}}
                                                                                ···
                                                                            @endif


                                                                        </option>

                                                                        @foreach($contractors as $contractor)
                                                                            <option value="{{ $contractor->id }}"> {{ $contractor->visitor->name }}  -- {{ $contractor->visitor->national_identification_no }} </option>
                                                                        @endforeach

                                                                    </select>

                                                                    @error('contractor')
                                                                    <div class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                    @enderror

                                                                    <div class="card-footerr" style="margin-left: 50px; margin-top: 3px">
                                                                        <button class="btn btn-primary mr-1" type="submit">{{ __('files.Search') }}</button>
                                                                    </div>


                                                                </div>

                                                            </form>
                                                        </div>
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
    </div>
</div>
@include('admin.layouts.components.script')
@include('admin.ocr.index_footer_scripts')

<script src="{{ asset('assets/modules/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/modules/izitoast/dist/js/iziToast.min.js') }}"></script>
</body>
</html>







{{--<div class="container" style="border-radius: 30px 30px 0 0 !important; background-color: #6f42c1">--}}
{{--    <div class="row justify-content-right" style="direction:rtl"><a--}}
{{--                href="{{ route('change_locale','ar') }}">--}}
{{--            عربى--}}
{{--        </a>--}}
{{--        |--}}
{{--        <a href="{{route('change_locale','en')}}">--}}
{{--            English--}}
{{--        </a>--}}
{{--    </div>--}}
{{--    <div class="row justify-content-center">--}}
{{--        <div class="col-md-6 text-center mb-5" style="margin-bottom:0px !important">--}}
{{--            <h2 class="heading-section">{{__('files.Scanner')}}</h2>--}}
{{--        </div>--}}
{{--    </div>--}}


{{--    <div class="row justify-content-center">--}}
{{--        <div class="col-md-14">--}}
{{--            <div class="col-md-14">--}}
{{--                <div class="wrapper">--}}
{{--                    <div class="row no-gutters">--}}
{{--                        <div class="div2">--}}
{{--                            <textarea id="msg" cols="75" rows="20"></textarea>--}}
{{--                        </div>--}}

{{--                        <div class="col-md-12 d-flex align-items-stretch">--}}
{{--                            <div class="info-wrap w-100 p-lg-5 p-4 img">--}}
{{--                                <form action="{{route('admin.workers.search')}}" method="POST" enctype="multipart/form-data">--}}
{{--                                    @csrf--}}
{{--                                    <div class="input-group mb-3 " style="margin-top: 10px;">--}}
{{--                                        <div class="input-group-prepend">--}}
{{--                                            <label class="input-group-text"--}}
{{--                                                   for="contractor">{{__('files.Choose Contractor')}}</label>--}}
{{--                                        </div>--}}
{{--                                        <select class="form-control select2" id="contractor" name="contractor">--}}
{{--                                            <option selected value="0">--}}
{{--                                                @if(app()->getLocale() == 'ar')--}}
{{--                                                    ···--}}
{{--                                                    {{__('files.Choose Contractor')}}--}}
{{--                                                @else--}}
{{--                                                    {{__('files.Choose Contractor')}}--}}
{{--                                                    ···--}}
{{--                                                @endif--}}


{{--                                            </option>--}}

{{--                                            @foreach($contractors as $contractor)--}}
{{--                                                <option value="{{ $contractor->id }}"> {{ $contractor->visitor->name }}  -- {{ $contractor->visitor->national_identification_no }} </option>--}}
{{--                                            @endforeach--}}

{{--                                        </select>--}}

{{--                                        @error('contractor')--}}
{{--                                        <div class="invalid-feedback">--}}
{{--                                            {{ $message }}--}}
{{--                                        </div>--}}
{{--                                        @enderror--}}

{{--                                        <div class="card-footerr" style="margin-left: 50px; margin-top: 3px">--}}
{{--                                            <button class="btn btn-primary mr-1" type="submit">{{ __('files.Search') }}</button>--}}
{{--                                        </div>--}}


{{--                                    </div>--}}

{{--                                </form>--}}

{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    </section>--}}
