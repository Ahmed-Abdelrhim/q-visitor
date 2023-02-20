@extends('admin.layouts.master')

@section('main-content')

  <section class="section">
        <div class="section-header">
            <h1>{{ __('files.Positions') }}</h1>
            {{ Breadcrumbs::render('designations') }}
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        @can('designations_create')
                            <div class="card-header">
                                <a href="{{ route('admin.designations.create') }}" class="btn btn-icon icon-left btn-primary"><i class="fas fa-plus"></i> {{ __('files.Add Positions') }}</a>
                            </div>
                        @endcan
                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table table-striped" id="maintable" data-url="{{ route('admin.designations.get-designations') }}" data-status="{{ \App\Enums\Status::ACTIVE }}" data-hidecolumn="{{ auth()->user()->can('designations_edit') || auth()->user()->can('designations_delete') }}">
                                    <thead>
                                        <tr>
                                            <th>{{ __('files.ID') }}</th>
                                            <th>{{ __('files.Name') }}</th>
                                            <th>{{ __('files.Status') }}</th>
                                            <th>{{ __('files.Actions') }}</th>
                                        </tr>
                                    </thead>
                                </table>
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
    <script src="{{ asset('js/designation/index.js') }}"></script>
@endsection
