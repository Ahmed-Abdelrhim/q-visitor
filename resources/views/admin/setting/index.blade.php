@extends('admin.layouts.master')

@section('main-content')

    <section class="section">
        <div class="section-header">
            <h1>{{ __('files.Settings') }}</h1>

            @yield('admin.setting.breadcrumbs')
        </div>
    </section>

    <div class="row">
        <div class="col-md-3">
            <div class="bg-light card">
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.setting.index') }}" class="list-group-item list-group-item-action {{ (request()->is('admin/setting')) ? 'active' : '' }} ">{{ __('files.Site Setting') }}</a>
                    <a href="{{ route('admin.setting.sms') }}" class="list-group-item list-group-item-action {{ (request()->is('admin/setting/sms')) ? 'active' : '' }}">{{ __('files.SMS Setting') }}</a>
                    <a href="{{ route('admin.setting.email') }}" class="list-group-item list-group-item-action {{ (request()->is('admin/setting/email')) ? 'active' : '' }}">{{ __('files.Email Setting') }}</a>
                    <a href="{{ route('admin.setting.notification') }}" class="list-group-item list-group-item-action {{ (request()->is('admin/setting/notification')) ? 'active' : '' }}">{{ __('files.Notification Setting') }}</a>
                    <a href="{{ route('admin.setting.email-template') }}" class="list-group-item list-group-item-action {{ (request()->is('admin/setting/emailtemplate')) ? 'active' : '' }}">{{ __('files.Email & Sms template Setting') }}</a>
                    <a href="{{ route('admin.setting.homepage') }}" class="list-group-item list-group-item-action {{ (request()->is('admin/setting/homepage')) ? 'active' : '' }}">{{ __('files.Front-end Setting') }}</a>
                </div>
            </div>
        </div>

        @yield('admin.setting.layout')
    </div>

@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/modules/summernote/summernote-bs4.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('assets/modules/summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('js/setting/create.js') }}"></script>
@endsection
