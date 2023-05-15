@extends('admin.layouts.master')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/modules/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap-social/bootstrap-social.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}">
@endsection
@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>{{ __('files.Visit Reservation') }}</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        <form action="#" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
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
    <script>
        $(document).ready(function () {
            // Write Js Here
        });

    </script>
@endsection