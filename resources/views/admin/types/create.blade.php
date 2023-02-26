@extends('admin.layouts.master')

@section('main-content')

    <section class="section">
        <div class="section-header">
            <h1>{{ __('files.Types') }}</h1>
            {{ Breadcrumbs::render('types/add') }}
        </div>

        <div class="section-body">
            <div class="row">
                                @if(app()->getLocale() == 'ar')
                    <div class="col-md-6"></div>

                @endif
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="card">
                        <form action="{{ route('admin.types.store') }}" method="POST">
                            @csrf
                            <div class="card-body">

                                <!-- Name -->
                                <div class="form-group">
                                    <label>{{ __('files.Name') }}</label> <span class="text-danger">*</span>
                                    <input type="text" name="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name') }}">
                                    @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <!-- Level -->
                                <div class="form-group">
                                    <label>{{ __('files.Approval Levels') }}</label> <span class="text-danger">*</span>
                                    <select name="level" class="form-control @error('level') is-invalid @enderror">
                                        <option value="0">0</option>
                                        <option value="1" selected>1</option>
                                        <option value="2">2</option>
                                    </select>
                                    @error('level')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <!-- End Level -->


                                <!-- Approval One -->
                                @if(isset($roles) && count($roles) > 0)
                                    <div class="form-group">
                                        <label>{{ __('files.Approval One') }}</label> <span class="text-danger">*</span>
                                        <select name="role_one"
                                                class="form-control @error('role_one') is-invalid @enderror">
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('role_one')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <!-- End Approval One  -->


                                    <!-- Approval Two -->
                                    <div class="form-group">
                                        <label>{{ __('files.Approval Two') }}</label> <span class="text-danger">*</span>
                                        <select name="role_two"
                                                class="form-control @error('role_two') is-invalid @enderror">
                                            <option value="0" selected>NONE</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('role_two')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                @endif
                                <!-- End  Approval Two  -->


                                <!-- Status -->
                                <div class="form-group">
                                    <label>{{ __('files.Status') }}</label> <span class="text-danger">*</span>
                                    <select name="status" class="form-control @error('status') is-invalid @enderror">
                                        @foreach(trans('statuses') as $key => $status)
                                            <option value="{{ $key }}" {{ (old('status') == $key) ? 'selected' : '' }}>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
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
