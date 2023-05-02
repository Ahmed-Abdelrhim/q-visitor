@extends('admin.layouts.master')

@section('main-content')

    <section class="section">
        <div class="section-header">
            <h1>{{ __('files.Companions') }}</h1>
            {{--            {{ Breadcrumbs::render('Companions') }}--}}
            {{--            Companions--}}
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">

                        @can('role_create')
                            <div class="card-header ">
                                <a href="#" class="btn btn-icon icon-left btn-primary"><i
                                        class="fas fa-plus"></i> {{ __('files.Add Role') }}</a>
                            </div>
                        @endcan

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>{{ __('files.Name') }}</th>
                                        <th>{{ __('files.First Photo') }}</th>
                                        <th>{{ __('files.Second Photo') }}</th>
                                        <th>{{ __('files.Third Photo') }}</th>
                                        <th>{{ __('files.Fourth Photo') }}</th>

{{--                                        @if (auth()->user()->can('role_show') || auth()->user()->can('role_edit') || auth()->user()->can('role_delete'))--}}
{{--                                            <th>{{ __('files.Actions') }}</th>--}}
{{--                                        @endif--}}
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!blank($visit))
                                        <tr class="margin-bottom-2">
                                            <td>{{$visit->visitor->name}}</td>
                                            <td>
                                                <img
                                                    src="{{asset('storage/images/'.$visit->reg_no.'/'.$visit->reg_no .'-1.jpg')}}"
                                                    style="width: 150px; height: 100px;" />
                                            </td>


                                            <td>
                                                <img
                                                <img
                                                    src="{{asset('storage/images/'.$visit->reg_no.'/'.$visit->reg_no .'-2.jpg')}}"
                                                    style="width: 150px; height: 100px;" />
                                            </td>

                                            <td>
                                                <img
                                                    src="{{asset('storage/images/'.$visit->reg_no.'/'.$visit->reg_no .'-3.jpg')}}"
                                                    style="width: 150px; height: 100px;" />
                                            </td>

                                            <td>
                                                <img
                                                    src="{{asset('storage/images/'.$visit->reg_no.'/'.$visit->reg_no .'-4.jpg')}}"
                                                    style="width: 150px; height: 100px;" />
                                            </td>

                                            <td>

                                                    {{--                                                <a href="#" class="btn btn-sm btn-icon float-left btn-danger"--}}
                                                    {{--                                                   data-toggle="tooltip" data-placement="top"--}}
                                                    {{--                                                   title="{{__('files.Delete')}}">--}}
                                                    {{--                                                    <i class="fa fa-trash"></i>--}}
                                                    {{--                                                </a>--}}

                                            </td>
                                        </tr>
                                        @foreach($visit->companions as $companion)
                                            <tr class="margin-bottom-2">
                                                <td>{{$companion->name}}</td>

                                                {{-- $visit->reg_no.'/'.$visit->visitor->national_identification_no  --}}
                                                <td>
                                                    <img
                                                        src="{{asset('storage/images/'.$visit->reg_no.'/companions/'. $visit->reg_no .'-' . $companion->id. '-1.jpg')}}"
                                                        style="width: 150px; height: 100px;"/>
                                                </td>


                                                <td>
                                                    <img
                                                        src="{{asset('storage/images/'.$visit->reg_no.'/companions/'. $visit->reg_no .'-' . $companion->id. '-2.jpg')}}"
                                                        style="width: 150px; height: 100px;"/>
                                                </td>


                                                <td>
                                                    <img
                                                        src="{{asset('storage/images/'.$visit->reg_no.'/companions/'. $visit->reg_no.'-'  . $companion->id. '-3.jpg')}}"
                                                        style="width: 150px; height: 100px;"/>
                                                </td>


                                                <td>
                                                    <img
                                                        src="{{asset('storage/images/'.$visit->reg_no.'/companions/'. $visit->reg_no .'-' . $companion->id. '-4.jpg')}}"
                                                        style="width: 150px; height: 100px;"/>

                                                </td>


{{--                                                <td>--}}


{{--                                                    <a href="#" class="btn btn-sm btn-icon float-left btn-danger"--}}
{{--                                                       data-toggle="tooltip" data-placement="top"--}}
{{--                                                       title="{{__('files.Delete')}}" onclick="event.preventDefault();document.getElementById('remove-companion').submit();">--}}
{{--                                                        <i class="fa fa-trash"></i>--}}
{{--                                                    </a>--}}

{{--                                                    <form id="remove-companion" class="d-none" method="POST" action="{{route('admin.remove.companion',encrypt($companion->id))}}">--}}
{{--                                                        @csrf--}}
{{--                                                    </form>--}}


{{--                                                </td>--}}
{{--                                                --}}
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
