@extends('admin.layouts.master')

@section('main-content')

    <section class="section">
        <div class="section-header">
            <h1>{{ __('shipments') }}</h1>
            {{ Breadcrumbs::render('role/add') }}
        </div>

        <div class="section-body">
            <div class="row">
                @if(app()->getLocale() == 'ar')
                    <div class="col-md-6"></div>

                @endif
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="card">
                        <form action="{{ route('admin.shipment.store') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label>{{ __('files.Name') }}</label> <span class="text-danger">*</span>
                                    <input type="text" name="name"
                                           class="form-control @error('name') is-invalid @enderror">
                                    @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>{{ __('files.Needs Qulaity Control Check') }}</label> <span
                                            class="text-danger">*</span>


                                    <div class="row" style="margin: 0 20px;">
                                        <div class="yey" style="margin: 0 20px !important;">
                                            <input type="checkbox" name="yes" style="width: 20px !important;" id="yes"

                                                   class="form-control @error('yes') is-invalid @enderror">

                                            <label for="yes"> {{__('files.Yes')}} </label>
                                            @error('yes')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="no" style="margin: 0 20px !important;">
                                            <input type="checkbox" name="no" style="width: 20px !important;" id="no"
                                                   class="form-control @error('no') is-invalid @enderror">
                                            <label for="no"> {{__('files.No')}} </label>
                                            @error('no')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                    </div>

                                </div>


                            </div>

                            <div class="card-footer">
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
    <script type="text/javascript">
        $(document).ready(function () {
        $("#yes").on('click',function () {
        var value_yes = document.getElementById("yes").checked;
        if(value_yes == true ) {
            $('#no').prop('checked', false);
        }
        });

        $("#no").on('click',function () {
        var value_no = document.getElementById("no").checked;
        if(value_no == true ) {
            $('#yes').prop('checked', false);
        }
        });

        });
    </script>
@endsection