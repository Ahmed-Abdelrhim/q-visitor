@extends('admin.layouts.master')
@section('main-content')
<section class="section">
    <div class="section-header">
        <h1>{{ __('files.Visitors') }}</h1>
        {{ Breadcrumbs::render('visitors') }}
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    @can('visitors_create')
                        <div class="card-header">
                            <a href="{{ route('admin.visitors.create') }}" class="btn btn-icon icon-left btn-primary"><i
                                    class="fas fa-plus"></i> {{ __('files.Add Visitor') }}</a>
                        </div>
                    @endcan

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="maintable"
                                data-url="{{ route('admin.visitors.get-visitors') }}"
                                data-status="{{ \App\Enums\Status::ACTIVE }}"
                                   data-hidecolumn=
                                           "{{ auth()->user()->can('visitors_show') || auth()->user()->can('visitors_edit') || auth()->user()->can('visitors_delete') }}">
                                <thead>
                                    <tr>
                                        <th>{{ __('files.ID') }}</th>
                                        <th>{{ __('files.Image') }}</th>
                                        <th>{{ __('files.VisitorID') }}</th>
                                        <th>{{ __('files.Name') }}</th>
                                        <th>{{ __('files.Email') }}</th>
                                        <th>{{ __('files.Phone') }}</th>
                                        <th>{{ __('files.Employee') }}</th>
                                        <th>{{ __('files.Checkin') }}</th>
										<!--<th>{{ __('files.QRCode') }}</th>-->
                                        <th>{{ __('files.Status') }}</th>

                                        <th style="width: 190px !important;min-width: 190px !important;">{{ __('files.Action') }}</th>
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


<div class="aaaa">
    <p class="p" id="pp"></p>
</div>

@endsection



@section('css')
<link rel="stylesheet" href="{{ asset('assets/modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/modules/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}">
@endsection

@section('scripts') 
<script src="{{ asset('assets/modules/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/modules/datatables.net-select-bs4/js/select.bootstrap4.min.js') }}"></script>
<script src="{{ asset('js/visitor/index.js') }}"></script><!---->
@endsection
