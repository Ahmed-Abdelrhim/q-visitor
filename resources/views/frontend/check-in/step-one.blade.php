@extends('frontend.layouts.frontend')

@section('content')
    <section id="pm-banner-1" class="custom-css-step">
        <div class="container">
            <div class="card" style="margin-top:40px;">
                <div class="card-header" id="Details" align="center">
                    <h4 style="color: #111570;font-weight: bold">{{__('Visitor Details')}}</h4>
                </div>
                <div class="card-body">
                    <div style="margin: 10px;">
                        {!! Form::open(['route' => 'check-in.step-one.next', 'class' => 'form-horizontal', 'files' => true]) !!}
                        <div class="save">
                            <div class="visitor" id="visitor">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div
                                                class="form-group {{ $errors->has('first_name') ? 'has-error' : ''}}">
                                            {!! Html::decode(Form::label('first_name', 'First Name <span class="text-danger">*</span>', ['class' => 'control-label'])) !!}
                                            {!! Form::text('first_name', isset($visitor->first_name) ? $visitor->first_name : null, ('' == 'required') ? ['class' => 'form-control input','id '=>'first_name'] : ['class' => 'form-control input','id '=>'first_name']) !!}
                                            {!! $errors->first('first_name', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div
                                                class="form-group {{ $errors->has('last_name') ? 'has-error' : ''}}">
                                            {!! Html::decode(Form::label('last_name', 'Last Name <span class="text-danger">*</span>', ['class' => 'control-label'])) !!}
                                            {!! Form::text('last_name', isset($visitor->last_name) ? $visitor->last_name : null, ('' == 'required') ? ['class' => 'form-control input', 'id '=>'last_name'] : ['class' => 'form-control input','id '=>'last_name']) !!}
                                            {!! $errors->first('last_name', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
                                            {!! Html::decode(Form::label('email', 'Email <span class="text-danger">*</span>', ['class' => 'control-label'])) !!}
                                            {!! Form::email('email', isset($visitor->email) ? $visitor->email : null, ('required' == 'required') ? ['class' => 'form-control input', 'id '=>'email'] : ['class' => 'form-control input','id '=>'email']) !!}
                                            {!! $errors->first('email', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group {{ $errors->has('phone') ? 'has-error' : ''}}">
                                            {!! Html::decode(Form::label('phone', 'Phone <span class="text-danger">*</span>', ['class' => 'control-label'])) !!}
                                            {!! Form::text('phone', isset($visitor->phone) ? $visitor->phone : null, ('required' == 'required') ? ['class' => 'form-control input', 'id '=>'phone'] : ['class' => 'form-control input','id '=>'phone']) !!}
                                            {!! $errors->first('phone', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group {{ $errors->has('employee_id') ? 'has-error' : ''}}">
                                            <label for="employee_id">{{ __('Select Employee') }}</label> <span
                                                    class="text-danger">*</span>
                                            <select id="employee_id" name="employee_id"
                                                    class="form-control select2 @error('employee_id') is-invalid @enderror">
                                                <option value="">{{ __('Select Employee') }}</option>
                                                @foreach($employees as $key => $employee)
                                                    <option value="{{ $employee->id }}"
                                                            value="{{ $employee->id }}" {{ isset($visitor->invitation->employee_id) && $visitor->invitation->employee_id == $employee->id ? "selected" : '' }}>{{ $employee->name }}
                                                        ( {{$employee->department->name}} )
                                                    </option>
                                                @endforeach
                                            </select>
                                            {!! $errors->first('employee_id', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group {{ $errors->has('gender') ? 'has-error' : ''}}">
                                            <label for="gender">{{ __('Gender') }}</label> <span
                                                    class="text-danger">*</span>
                                            <select id="gender" name="gender"
                                                    class="form-control @error('gender') is-invalid @enderror">
                                                @foreach(trans('genders') as $key => $gender)
                                                    <option value="{{ $key }}" {{ (old('gender') == $key) ? 'selected' : '' }}>{{ $gender }}</option>
                                                @endforeach
                                            </select>
                                            @error('gender')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group {{ $errors->has('company_name') ? 'has-error' : ''}}">
                                            {!! Form::label('company_name', 'Company Name', ['class' => 'control-label']) !!}
                                            {!! Form::text('company_name', isset($visitor->company_name) ? $visitor->company_name : null, ('' == 'required') ? ['class' => 'form-control input', 'id '=>'company_name'] : ['class' => 'form-control input','id '=>'company_name']) !!}
                                            {!! $errors->first('company_name', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group {{ $errors->has('national_identification_no') ? 'has-error' : ''}}">
                                            {!! Form::label('national_identification_no', 'National ID', ['class' => 'control-label']) !!}
                                            {!! Form::text('national_identification_no', isset($visitor->national_identification_no) ? $visitor->national_identification_no : null, ('' == 'required') ? ['class' => 'form-control input', 'id '=>'national_identification_no'] : ['class' => 'form-control input','id '=>'national_identification_no']) !!}
                                            {!! $errors->first('national_identification_no', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group {{ $errors->has('purpose') ? 'has-error' : ''}}">
                                            <label for="purpose">{{ __('Purpose') }}</label> <span
                                                    class="text-danger">*</span>
                                            <textarea name="purpose"
                                                      class="summernote-simple form-control height-textarea-css @error('purpose')is-invalid @enderror"
                                                      id="purpose">{{old('purpose')}}</textarea>
                                            @error('purpose')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group {{ $errors->has('address') ? 'has-error' : ''}}">
                                            <label for="address">{{ __('Address') }}</label>
                                            <textarea name="address"
                                                      class="summernote-simple form-control height-textarea-css @error('address') is-invalid @enderror"
                                                      id="address">{{ old('address') }}</textarea>
                                            @error('address')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <a href="{{ route('check-in') }}"
                                           class="btn btn-danger float-left text-white">
                                            <i class="fa fa-arrow-left" aria-hidden="true"></i> {{__('Cancel')}}
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-success float-right" id="continue">
                                            <i class="fa fa-arrow-right" aria-hidden="true"></i> {{__('Continue')}}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $('.select2').select2();
        })
    </script>
@endsection

{{--

<form action="{{ route('admin.pre-registers.store') }}" method="POST"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="form-group col">
                                        <label for="first_name">{{ __('First Name ') }}</label> <span
                                                class="text-danger">*</span>
                                        <input id="first_name" type="text" name="first_name"
                                               class="form-control {{ $errors->has('first_name') ? " is-invalid " : '' }}"
                                               value="{{ old('first_name') }}">
                                        @error('first_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col">
                                        <label for="last_name">{{ __('Last Name') }}</label> <span
                                                class="text-danger">*</span>
                                        <input id="last_name" type="text" name="last_name"
                                               class="form-control {{ $errors->has('last_name') ? " is-invalid " : '' }}"
                                               value="{{ old('last_name') }}">
                                        @error('last_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                </div>
                                <div class="form-row">
                                    <div class="form-group col">
                                        <label>{{ __('E-Mail Address') }}</label> <span class="text-danger">*</span>
                                        <input type="text" name="email"
                                               class="form-control @error('email') is-invalid @enderror"
                                               value="{{ old('email') }}">
                                        @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col">
                                        <label>{{ __('Phone') }}</label> <span class="text-danger">*</span>
                                        <input type="text" name="phone"
                                               class="form-control @error('phone') is-invalid @enderror"
                                               value="{{ old('phone') }}">
                                        @error('phone')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col">
                                        <label for="gender">{{ __('Gender') }}</label> <span
                                                class="text-danger">*</span>
                                        <select id="gender" name="gender"
                                                class="form-control @error('gender') is-invalid @enderror">
                                            @foreach(trans('genders') as $key => $gender)
                                                <option value="{{ $key }}" {{ (old('gender') == $key) ? 'selected' : '' }}>{{ $gender }}</option>
                                            @endforeach
                                        </select>
                                        @error('gender')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col">
                                        <label for="employee_id">{{ __('Select Employee') }}</label> <span
                                                class="text-danger">*</span>
                                        <select id="employee_id" name="employee_id"
                                                class="form-control select2 @error('employee_id') is-invalid @enderror">
                                            @foreach($employees as $key => $employee)
                                                <option value="{{ $employee->id }}" {{ (old('employee_id') == $employee->id) ? 'selected' : '' }}>{{ $employee->name }}
                                                    ( {{$employee->department->name}} )
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('employee_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col">
                                        <label>{{ __('Enterance Date') }}</label> <span class="text-danger">*</span>
                                        <input type="date" autocomplete="off" id="date-picker" name="expected_date"
                                               class="form-control @error('expected_date') is-invalid @enderror"
                                               value="{{ old('expected_date') }}">
                                        @error('expected_date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col">
                                        <label for="expected_time">{{ __('Enterance Time') }}</label> <span
                                                class="text-danger">*</span>
                                        <input id="expected_time" type="time" name="expected_time"
                                               class="form-control  timepicker @error('expected_time') is-invalid @enderror"
                                               value="{{ old('expected_time') }}">
                                        @error('expected_time')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col">
                                        <label>{{ __('Exit Date') }}</label> <span class="text-danger">*</span>
                                        <input type="date" autocomplete="off" id="date-picker2" name="exit_date"
                                               class="form-control @error('exit_date') is-invalid @enderror"
                                               value="{{ old('exit_date') }}">
                                        @error('exit_date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col">
                                        <label for="expected_time">{{ __('Exit Time') }}</label> <span
                                                class="text-danger">*</span>
                                        <input id="exit_time" type="time" name="exit_time"
                                               class="form-control  timepicker @error('exit_time') is-invalid @enderror"
                                               value="{{ old('exit_time') }}">
                                        @error('exit_time')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col">
                                        <label for="comment">{{ __('Comment') }}</label>
                                        <textarea name="comment"
                                                  class="summernote-simple form-control height-textarea @error('comment')
                                                      is-invalid @enderror"
                                                  id="comment">
                                            {{ old('comment') }}
                                        </textarea>
                                        @error('comment')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col">
                                        <label for="address">{{ __('Address') }}</label>
                                        <textarea name="address"
                                                  class="summernote-simple form-control height-textarea @error('address')
                                                      is-invalid @enderror"
                                                  id="address">
                                            {{ old('address') }}
                                        </textarea>
                                        @error('about')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                            </div>

                            <div class="card-footer ">
                                <button class="btn btn-primary mr-1" type="submit">{{ __('Submit') }}</button>
                            </div>
                        </form>



--}}