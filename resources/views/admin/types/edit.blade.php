@extends('admin.layouts.master')

@section('main-content')

    <section class="section">
        <div class="section-header">
            <h1>{{ __('files.Types') }}</h1>
            {{ Breadcrumbs::render('types/edit') }}
        </div>

        <div class="section-body">
            <div class="row">
                                @if(app()->getLocale() == 'ar')
                    <div class="col-md-6"></div>

                @endif
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="card">
                        <form action="{{ route('admin.types.update', $types) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <!-- Name -->
                            <div class="card-body">
                                <div class="form-group">
                                    <label>{{ __('files.Name') }}</label> <span class="text-danger">*</span>
                                    <input type="text" name="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', $types->name) }}">
                                    @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <!-- End Name -->

                                <!--Status -->
                                <div class="form-group">
                                    <label>{{ __('files.Status') }}</label> <span class="text-danger">*</span>
                                    <select name="status" class="form-control @error('status') is-invalid @enderror">
                                        @foreach(trans('statuses') as $key => $status)
                                            <option value="{{ $key }}" {{ (old('status', $types->status) == $key) ? 'selected' : '' }}>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <!-- End Status-->


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
    <script>
        $(document).ready(function () {
            let value =  $('#approval_level').val();
            if (value == 1) {
                $('#role_one').removeAttr('disabled');
                $('#role_two').attr('disabled' , true);
            }

            if (value == 2) {
                $('#role_one').removeAttr('disabled');
                $('#role_two').removeAttr('disabled');
            }

            if(value == 0) {
                $('#role_one').attr('disabled' , true);
                $('#role_two').attr('disabled' , true);
            }


            $('#approval_level').on('change', function () {

                let value = $(this).val();
                console.log('Value=> ' + value);
                if (value == 1) {
                    $('#role_one').removeAttr('disabled');
                    $('#role_two').attr('disabled' , true);
                }

                if (value == 2) {
                    $('#role_one').removeAttr('disabled');
                    $('#role_two').removeAttr('disabled');
                }

                if(value == 0) {
                    $('#role_one').attr('disabled' , true);
                    $('#role_two').attr('disabled' , true);
                }
            });
        });
    </script>
@endsection



{{--<!-- Level -->--}}
{{--<div class="form-group">--}}
{{--    <label>{{ __('files.Approval Levels') }}</label> <span class="text-danger">*</span>--}}
{{--    <select name="level" id="approval_level" class="form-control @error('level') is-invalid @enderror">--}}
{{--        <option value="0" @if($types->level == 0) selected @endif>0</option>--}}
{{--        <option value="1" @if($types->level == 1) selected @endif>1</option>--}}
{{--        <option value="2" @if($types->level == 2) selected @endif>2</option>--}}
{{--    </select>--}}
{{--    @error('level')--}}
{{--    <div class="invalid-feedback">--}}
{{--        {{ $message }}--}}
{{--    </div>--}}
{{--    @enderror--}}
{{--</div>--}}
{{--<!-- End Level -->--}}


{{--<!-- Approval One -->--}}
{{--@if(isset($roles) && count($roles) > 0)--}}
{{--    <div class="form-group">--}}
{{--        <label>{{ __('files.Approval One') }}</label> <span class="text-danger">*</span>--}}
{{--        <select name="role_one" id="role_one"--}}
{{--                class="form-control @error('role_one') is-invalid @enderror" @if(!empty($types->role_two)) @else disabled @endif>--}}
{{--            <option value="0" selected>NONE</option>--}}
{{--            @foreach($roles as $role)--}}
{{--                <option value="{{ $role->id }}"--}}
{{--                        @if($types->role_one == $role->id) selected @endif>--}}
{{--                    {{ $role->name }}--}}
{{--                </option>--}}
{{--            @endforeach--}}
{{--        </select>--}}
{{--        @error('role_one')--}}
{{--        <div class="invalid-feedback">--}}
{{--            {{ $message }}--}}
{{--        </div>--}}
{{--        @enderror--}}
{{--    </div>--}}
{{--    <!-- End Approval One  -->--}}


{{--    <!-- Approval Two -->--}}
{{--    <div class="form-group">--}}
{{--        <label>{{ __('files.Approval Two') }}</label> <span class="text-danger">*</span>--}}
{{--        <select name="role_two" id="role_two"--}}
{{--                class="form-control @error('role_two') is-invalid @enderror" @if(!empty($types->role_two)) @else disabled @endif>--}}
{{--            @if(!empty($types->role_two))--}}
{{--                <option value="0">NONE</option>--}}
{{--            @else--}}
{{--                <option value="0" selected>NONE</option>--}}
{{--            @endif--}}

{{--            @foreach($roles as $role)--}}
{{--                <option value="{{ $role->id }}" @if(!empty($types->role_two))--}}
{{--                    @if($types->role_two == $role->id) selected @endif @endif>--}}
{{--                    {{ $role->name }}--}}
{{--                </option>--}}
{{--            @endforeach--}}
{{--        </select>--}}
{{--        @error('role_two')--}}
{{--        <div class="invalid-feedback">--}}
{{--            {{ $message }}--}}
{{--        </div>--}}
{{--        @enderror--}}
{{--    </div>--}}
{{--@endif--}}
{{--<!-- End  Approval Two  -->--}}
