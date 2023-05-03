@extends('admin.layouts.master')
@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>{{ __('files.Logs') }}</h1>

            <form action="{{route('admin.logs.search')}}">
                <div class="form-row">
                    <div class="form-group " @if(app()->getLocale() == 'ar') style="margin-right: 30px"
                         @else style="margin-left: 30px" @endif>
                        <label style="font-weight: bold; font-size: 15px;">{{ __('files.Filter Logs') }}</label>
                        <input type="datetime-local" name="logs_date" id="v3date"
                               class="v3date form-control @error('expiry_date') is-invalid @enderror"
                               id="expiry_date">
                    </div>

                    <div class="form-group "
                         @if(app()->getLocale() == 'ar')  style="margin-right: 30px; margin-top: 35px; color: white; cursor: pointer"
                         @else style="margin-left: 30px; margin-top: 35px; color: white; cursor: pointer"
                            @endif>
                        <button type="submit" class="btn btn-sm btn-icon mr-2  float-left btn-success find"
                                data-toggle="tooltip" data-placement="top">
                            <i class="fa fa-search"></i> {{__('files.Search')}}
                        </button>
                    </div>
                </div>
            </form>

            {{ Breadcrumbs::render('visitors') }}
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">


                    @if(isset($visits) && count($visits)> 0 )
                        <div class="card">

                            <div class="card-header">
                                {{--                                <a href="#" id="print" class="btn btn-icon icon-left btn-primary"><i--}}
                                {{--                                            class="fas fa-print"></i> {{ __('files.Print Data') }}</a>--}}

                                <a href="{{route('admin.logs.download.pdf' , $logs_date)}}"
                                   id="print" class="btn btn-icon icon-left btn-success">
                                    {{--                                    <i class="fa fa-file-pdf"></i>      --}}
                                    <i class="fas fa-file-pdf"></i>
                                    {{ __('files.Export To Pdf') }}
                                </a>


                            </div>


                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="printidcard">
                                        <thead>
                                        <tr>
                                            <th>{{ __('files.Visit ID') }}</th>
                                            <th>{{ __('files.Visitor Name') }}</th>
                                            <th>{{ __('files.Visit Time') }}</th>
                                            <th>{{ __('files.Visit Car Type') }}</th>
                                            <th>{{ __('files.Shipment Type') }}</th>
                                            <th>{{ __('files.Visit Type Number') }}</th>
                                            <th>{{ __('files.Companions') }}</th>

                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach($visits as $visit)
                                            <tr>
                                                <td> {{$visit->id}} </td>
                                                <td> {{$visit->visitor->first_name}}    {{$visit->visitor->last_name}}  </td>
                                                <td> {{ \Illuminate\Support\Carbon::parse($visit->checkin_at)->format('H:m:s') }} </td>

                                                {{-- Visit Type --}}
                                                <td>
                                                    @if($visit->car_type == 'T')
                                                        {{__('files.Truck')}}
                                                    @endif

                                                    @if($visit->car_type == 'C')
                                                        {{__('files.Car')}}
                                                    @endif

                                                    @if($visit->car_type == 'P')
                                                        {{__('files.Person')}}
                                                    @endif
                                                </td>

                                                {{-- Shipment Type --}}
                                                <td>
                                                    @if($visit->car_type == 'T')
                                                        @if( !empty($visit->shipment_id) && $visit->shipment_id != 0)
                                                            {{$visit->shipment->name }}
                                                        @else
                                                            ---
                                                        @endif
                                                    @else
                                                        ---
                                                    @endif

                                                </td>

                                                {{-- Shipment Number --}}
                                                <td>
                                                    @if($visit->car_type == 'T')
                                                        @if( !empty($visit->shipment_number) )
                                                            {{$visit->shipment_number }}
                                                        @else
                                                            ---
                                                        @endif
                                                    @else
                                                        ---
                                                    @endif
                                                </td>

                                                <td>
                                                    @if( !empty($visit->companions))
                                                        <a href="{{route('admin.visitors.companions', encrypt($visit->id)) }}"
                                                           class="btn btn-sm btn-icon float-left btn-info actions"
                                                           title="{{ __('files.Companions') }} "
                                                           style="margin-left: 10px;">
                                                            <i class="far fa-user"></i>
                                                        </a>
                                                    @else
                                                        ---
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>


    </section>
@endsection

@section('scripts')
    <script src="{{ asset('assets/modules/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/modules/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        // var idCardCss = "{{ asset('css/id-card-print.css') }}";
        var idCardCss = "{{ asset('assets/css/style.css') }} ";


    </script>
@endsection