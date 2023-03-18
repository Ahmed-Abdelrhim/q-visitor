@extends('admin.layouts.master')

@section('main-content')

    <section class="section">
        <div class="section-header">
            <h1>{{ __('files.Shipments') }}</h1>
            {{-- {{ Breadcrumbs::render('roles') }} --}}
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">

                        @can('shipment_create')
                            <div class="card-header ">
                                <a href="{{ route('admin.shipment.create') }}" class="btn btn-icon icon-left btn-primary"><i
                                            class="fas fa-plus"></i> {{ __('files.Add Shipment') }}</a>
                            </div>
                        @endcan

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>{{ __('files.Name') }}</th>
{{--                                        <th>{{ __('files.ID') }}</th>--}}
                                        @if (auth()->user()->can('Shipmet_show') || auth()->user()->can('Shipmet_edit') || auth()->user()->can('Shipmet_delete'))
                                            <th>{{ __('files.Actions') }}</th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @if(!empty($shipments))
                                        @foreach($shipments as $shipment)
                                            <tr>
                                                <td>{{ $shipment->name}}</td>
                                                <td>{{ $loop->index+1}}</td>

                                                @if (auth()->user()->can('shipment_show') || auth()->user()->can('shipment_edit') || auth()->user()->can('shipment_delete'))
                                                    <td class="td-style td-actions">
                                                        @if (auth()->user()->can('shipment_show'))
                                                            <a href="{{ route('admin.shipment.show', $shipment->id) }}"
                                                               class="btn btn-sm btn-icon float-left btn-success"
                                                               data-toggle="tooltip" data-placement="top"
                                                               title="{{__('files.Permission')}}"><i
                                                                        class="fas fa-plus"></i></a>
                                                        @endif

                                                        @if (auth()->user()->can('shipment_edit'))
                                                            <a href="{{ route('admin.shipment.edit', $shipment->id) }}"
                                                               class="btn btn-sm btn-icon float-left btn-primary ml-2"
                                                               data-toggle="tooltip" data-placement="top"
                                                               title="{{__('files.Edit')}}"><i class="far fa-edit"></i></a>
                                                        @endif

                                                        @if (auth()->user()->can('shipment_delete'))
                                                            <form class="float-left pl-2"
                                                                  action="{{ route('admin.shipment.destroy', $shipment) }}"
                                                                  method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="btn btn-sm btn-icon btn-danger"
                                                                        data-toggle="tooltip" data-placement="top"
                                                                        title="{{__('files.Delete')}}"><i
                                                                            class="fa fa-trash"></i></button>
                                                            </form>
                                                        @endif
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @endif



                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection