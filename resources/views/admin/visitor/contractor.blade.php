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
            {{ Breadcrumbs::render('visitors/edit') }}
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        <form method="POST" action="{{route('admin.contractor.store', $contractor_id)}}">
                            @csrf
                            <div class="card-body">
                                <div class="form-row" id="form-row" attribute="clone">
                                    <div class="form-group col">
                                        <label for="name">{{ __('files.Name') }}</label> <span
                                                class="text-danger">*</span>
                                        <input id="name" type="text" name="name" required min="3" max="100"
                                               class="form-control {{ $errors->has('name') ? " is-invalid " : '' }}">
                                        @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="form-group col">
                                        <label for="nat_id">{{ __('files.National Number') }}</label> <span
                                                class="text-danger">*</span>
                                        <input id="nat_id" type="number" name="nat" required
                                               class="form-control {{ $errors->has('nat_id') ? " is-invalid " : '' }}">
                                        @error('nat')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group col">
                                    <a class="btn btn-primary"
                                       @if(app()->getLocale() == 'ar') @endif style="direction: rtl; cursor: pointer"
                                       id="add">
                                        <i class="fa fa-plus" style="color: white"></i>
                                    </a>
                                </div>
                                <br/>
                            </div>

                            <div class="card-footer mx-auto text-center">
                                <button class="btn btn-primary btn-lg mr-1"
                                        type="submit">{{ __('files.Submit') }}</button>
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
    <script src="{{ asset('assets/modules/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // var counter = 1;
            $('#add').on('click',function() {
                // counter++;
                clone = $('#form-row').clone().insertAfter('.form-row:last');

                name_length = $("input[id='name']").length;
                national_length = $("input[id='nat_id']").length;

                clone.find('#name').prop('name' , 'name' + name_length);
                clone.find('#nat_id').prop('name' , 'nat' + national_length);

                clone.find('#name').val("");
                clone.find('#nat_id').val("");
            });
        });
    </script>
@endsection
