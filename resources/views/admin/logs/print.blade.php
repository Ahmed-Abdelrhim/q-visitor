@extends('admin.layouts.master')
@section('main-content')
    <section class="section">

        <div class="section-body">
            <div class="row">
                <div class="col-12">


                    @if(isset($show_data))
                        <div class="card">

                            <div class="card-header">
                                <a href="#" id="print" class="btn btn-icon icon-left btn-primary"><i
                                            class="fas fa-print"></i> {{ __('files.Print Data') }}</a>
                            </div>


                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped"  id="printidcard">
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
    <script>
        $(document).ready(function() {
            window.print();
            // $('#printidcard');
        });
        </script>
@endsection
