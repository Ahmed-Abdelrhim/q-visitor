@extends('admin.layouts.master')
@section('main-content')
    <section class="section">
        <div class="section-header">
            <h1>{{ __('files.Logs') }}</h1>

            <form action="{{route('admin.logs.search')}}">
                <div class="form-row">
                    <div class="form-group " style="margin-right: 30px">
                        <label style="font-weight: bold; font-size: 15px;">{{ __('files.Filter Logs') }}</label>
                        <input type="datetime-local" name="logs_date" id="v3date"
                               class="v3date form-control @error('expiry_date') is-invalid @enderror"
                               id="expiry_date">
                    </div>

                    <div class="form-group "
                         style="margin-right: 30px; margin-top: 35px; color: white; cursor: pointer">
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


                    @if(isset($show_data))
                        <div class="card">


                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="maintable">
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

                                        @if(isset($visits) && count($visits)> 0 )
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
                                                            @if( !empty($visit->shipment_number) )
                                                                {{$visit->shipment_number }}
                                                            @else
                                                                ---
                                                            @endif
                                                        @else
                                                            ---
                                                        @endif


                                                        {{$visit->car_type }}
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
                                                        @if(!empty($visit->companions ))
                                                        @else
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
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
        $(document).ready(function() {
            // $('#logs_filter').on();
        });





    </script>
@endsection


{{--                                       data-url="{{ route('admin.visitors.get-visitors') }}"--}}
{{--                                       data-hidecolumn=--}}
{{--                                               "{{ auth()->user()->can('visitors_show') || auth()->user()->can('visitors_edit') || auth()->user()->can('visitors_delete') }}"--}}