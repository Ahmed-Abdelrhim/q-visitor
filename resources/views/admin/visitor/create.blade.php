@extends('admin.layouts.master')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/modules/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap-social/bootstrap-social.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('main-content')
    <style>
        .required:after {
            content: " *";
            color: red;
        }
    </style>
    <section class="section">
        <div class="section-header">
            <h1>{{ __('files.Visit Reservation') }}</h1>
            {{ Breadcrumbs::render('visitors/add') }}
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        <form action="{{ route('admin.visitors.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="form-group col">
                                        <label for="first_name">{{ __('files.First Name') }}</label> <span
                                            class="text-danger">*</span>
                                        <input id="first_name" type="text" name="first_name"
                                               class="form-control {{ $errors->has('first_name') ? " is-invalid " : '' }}"
                                               value="{{ old('first_name') }}">
                                        @error('first_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col">
                                        <label for="last_name">{{ __('files.Last Name') }}</label> <span
                                            class="text-danger">*</span>
                                        <input id="last_name" type="text" name="last_name"
                                               class="form-control {{ $errors->has('last_name') ? " is-invalid " : '' }}"
                                               value="{{ old('last_name') }}">
                                        @error('last_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                </div>
                                <div class="form-row">
                                    <div class="form-group col">
                                        <label>{{ __('files.Email') }}</label> <span class="text-danger">*</span>
                                        <input type="text" name="email"
                                               class="form-control @error('email') is-invalid @enderror"
                                               value="{{ old('email') }}">
                                        @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col">
                                        <label>{{ __('files.Phone') }}</label> <span class="text-danger">*</span>
                                        <input type="text" name="phone"
                                               class="form-control @error('phone') is-invalid @enderror"
                                               value="{{ old('phone') }}">
                                        @error('phone')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col">
                                        <label for="gender">{{ __('files.Gender') }}</label> <span
                                            class="text-danger">*</span>
                                        <select id="gender" name="gender"
                                                class="form-control @error('gender') is-invalid @enderror">
                                            @foreach(trans('genders') as $key => $gender)
                                                <option
                                                    value="{{ $key }}" {{ (old('gender') == $key) ? 'selected' : '' }}>{{ $gender }}</option>
                                            @endforeach
                                        </select>
                                        @error('gender')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col">
                                        <label>{{ __('Company Name') }}</label>
                                        <input type="text" name="company_name"
                                               class="form-control @error('company_name') is-invalid @enderror"
                                               value="{{ old('company_name') }}">
                                        @error('company_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col">
                                        <label>{{ __('files.National Identification No') }}</label>
                                        <input type="text" name="national_identification_no"
                                               class="form-control @error('national_identification_no') is-invalid @enderror"
                                               value="{{ old('national_identification_no') }}">
                                        @error('national_identification_no')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col">
                                        <label for="employee_id">{{ __('files.Select Employee') }}</label> <span
                                            class="text-danger">*</span>
                                        <select id="employee_id" name="employee_id"
                                                class="form-control select2 @error('employee_id') is-invalid @enderror">
                                            @foreach($employees as $key => $employee)
                                                <option
                                                    value="{{ $employee->id }}" {{ (old('employee_id') == $employee->id) ? 'selected' : '' }}>{{ $employee->name }}
                                                    ( {{$employee->department->name}} )
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('employee_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>




                                <div class="form-row">
                                    <div class="form-group col">
                                        <label>{{ __('files.From Date') }}</label>
                                        <input type="datetime-local" name="from_date" id="v2date"
                                               class="v2date form-control @error('from_date') is-invalid @enderror"
                                               value="{{ old('from_date') }}" id="from_date">
                                        @error('from_date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col">
                                        <label>{{ __('files.Expiry Date') }}</label>
                                        <input type="datetime-local" name="expiry_date" id="v3date"
                                               class="v3date form-control @error('expiry_date') is-invalid @enderror"
                                               value="{{ old('expiry_date') }}" id="expiry_date">
                                        @error('expiry_date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row">

                                    <div class="form-group col">
                                        <label>{{ __('files.TypeApproval') }}</label>
                                        <!--<input type="text" name="type" class="form-control @error('type') is-invalid @enderror" value="{{ old('type') }}" id="type" >-->
                                        <select id="type" name="type"
                                                class="form-control select2 @error('type') is-invalid @enderror">
                                            @foreach($types as $key => $type)
                                                <option
                                                    value="{{ $type->id }}" {{ (old('type_id') == $type->id) ? 'selected' : '' }}>{{ $type->name }} </option>
                                            @endforeach
                                        </select>
                                        @error('type')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>


                                    <div class="form-group col">
                                        <label>{{ __('files.Car Type') }}</label>
                                        <select id="car_type" name="car_type"
                                                class="form-control select2 @error('car_type') is-invalid @enderror">
                                            <option value="N" selected>{{__('files.None')}}</option>
                                            <option value="T">{{__('files.Truck')}}</option>
                                            <option value="C">{{__('files.Car')}}</option>
                                            <option value="P">{{__('files.Person')}}</option>
                                        </select>
                                        @error('car_type')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>


                                    {{-- <div class="form-group col"> --}}
                                    {{-- </div> --}}
                                </div>


                                <div class="view_car_type"  style="display: none">
                                    @if(auth()->user()->hasRole(15) )
                                        <!-- Start Logisitcs -->
                                        <div class="form-row">
                                            <div class="form-group col">
                                                <label id="shipment_number">{{ __('files.Shipment Number') }}</label>
                                                <input type="text" name="shipment_number" id="shipment_number"
                                                       class="form-control @error('shipment_number') is-invalid @enderror">
                                                @error('shipment_number')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>

                                            <div class="form-group col">
                                                <label for="shipment_id">{{ __('files.Select Shipment') }}</label> <span
                                                        class="text-danger">*</span>
                                                <select id="shipment_id" name="shipment_id"
                                                        class="form-control select2 @error('shipment_id') is-invalid @enderror">
                                                    @foreach($shipments as $key => $shipment)
                                                        <option value="{{ $shipment->id }}">
                                                            {{ $shipment->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('shipment_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <!-- End Logistics-->
                                    @endif
                                </div>

                                <!-- حجز زيارة-->
                                <div class="form-row">
                                    <div class="form-group col">

                                        <label class="purbose-label required col-md-12"
                                               for="purpose">{{ __('files.Purpose') }}</label>


                                        <textarea name="purpose"
                                                  class="summernote-simple  summernote form-control height-textarea @error('purpose')
                                                      is-invalid @enderror"
                                                  id="purpose">
                                            {{ old('purpose') }}
                                        </textarea>
                                        @error('purpose')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>


                                    <div class="form-group col">
                                        <label class="address-label required col-md-12"
                                               for="address">{{ __('files.Address') }}</label>
                                        <textarea name="address"
                                                  class="summernote-simple summernote form-control height-textarea @error('address')
                                                      is-invalid @enderror"
                                                  id="address">
                                            {{ old('address') }}
                                        </textarea>
                                        @error('address')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>


                                <div class="form-row">
                                    <div class="form-group col-6">
                                        <label for="customFile">{{ __('files.Image') }}</label>
                                        <div class="custom-file">
                                            <input name="image" type="file"
                                                   class="custom-file-input @error('image') is-invalid @enderror"
                                                   id="customFile" onchange="readURL(this);">
                                            <label class="custom-file-label"
                                                   for="customFile">{{ __('files.Choose file') }}</label>
                                        </div>
                                        @if ($errors->has('image'))
                                            <div class="help-block text-danger">
                                                {{ $errors->first('image') }}
                                            </div>
                                        @endif
                                        <img class="img-thumbnail image-width mt-4 mb-3" id="previewImage"
                                             src="{{ asset('assets/img/default/user.png') }}" alt="your image"/>
                                    </div>

                                </div>
                            </div>

                            <div class="card-footer ">
                                <button class="btn btn-primary mr-1" type="submit">{{ __('files.Submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
    <script src="{{ asset('assets/modules/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/modules/summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('assets/modules/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('js/pre-register/create.js') }}"></script>
    <script>
        $(document).ready(function () {


        $('#car_type').on('change', function () {
        var car_type = $("#car_type").val();
        if(car_type == 'T') {
            console.log('Add Class View');
           //  $('.view_car_type').removeClass('hidden_car_type');
            $('.view_car_type').css({"display":"block"});
            } else {
                        console.log('Remove Class View');
                        // $('.view_car_type').addClass('hidden_car_type');
                        $('.view_car_type').css({"display":"none"});

            }
        });


{{--        $('#car_type').on('change', function () {--}}
{{--        --}}
{{--        });--}}



    });
    </script>
@endsection
