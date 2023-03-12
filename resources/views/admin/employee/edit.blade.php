@extends('admin.layouts.master')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/modules/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap-social/bootstrap-social.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('main-content')

    <section class="section">
        <div class="section-header">
            <h1>{{ __('files.Employees') }}</h1>
            {{ Breadcrumbs::render('employees/edit') }}
        </div>

        <div class="section-body">
            <div class="row">

                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        <form action="{{ route('admin.employees.update', $employee) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="form-group col">
                                        <label for="first_name">{{ __('files.First Name') }}</label> <span class="text-danger">*</span>
                                        <input id="first_name" type="text" name="first_name" class="form-control {{ $errors->has('first_name') ? " is-invalid " : '' }}" value="{{ old('first_name',$employee->user->first_name) }}">
                                        @error('first_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col">
                                        <label for="last_name">{{ __('files.Last Name') }}</label> <span class="text-danger">*</span>
                                        <input id="last_name" type="text" name="last_name" class="form-control {{ $errors->has('last_name') ? " is-invalid " : '' }}" value="{{ old('last_name',$employee->user->last_name) }}">
                                        @error('last_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                </div>
                                <div class="form-row">
                                    <div class="form-group col">
                                        <label>{{ __('files.Email') }}</label> <span class="text-danger">*</span>
                                        <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email',$employee->user->email) }}">
                                        @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col">
                                        <label>{{ __('files.Phone') }}</label> <span class="text-danger">*</span>
                                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone',$employee->user->phone) }}">
                                        @error('phone')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col">
                                        <label>{{ __('files.Joining Date') }}</label> <span class="text-danger">*</span>
                                        <input type="text" autocomplete="off" id="vdate" name="date_of_joining"
                                               class="vdate form-control @error('date_of_joining') is-invalid @enderror"
                                               value="{{ old('date_of_joining',$employee->date_of_joining) }}">
                                        @error('date_of_joining')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col">
                                        <label for="gender">{{ __('files.Gender') }}</label> <span class="text-danger">*</span>
                                        <select id="gender" name="gender" class="form-control @error('gender') is-invalid @enderror">
                                            @foreach(trans('genders') as $key => $gender)
                                                <option value="{{ $key }}" {{ (old('gender',$employee->gender) == $key) ? 'selected' : '' }}>{{ $gender }}</option>
                                            @endforeach
                                        </select>
                                        @error('gender')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>



                                    <!-- Level -->
                                    <div class="form-group col">
                                        <label>{{ __('files.Approval Levels') }}</label> <span class="text-danger">*</span>
                                        <select name="level" id="approval_level"
                                                class="form-control @error('level') is-invalid @enderror">
                                            <option value="0" @if($employee->level == 0) selected @endif>0</option>
                                            <option value="1" @if($employee->level == 1) selected @endif>1</option>
                                            <option value="2" @if($employee->level == 2) selected @endif>2</option>
                                        </select>
                                        @error('level')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <!-- End Level -->




                                    <!-- Employee One -->
                                    @if(isset($roles) && count($roles) > 0)
                                        <div class="form-group col">
                                            <label>{{ __('files.Employee One') }}</label> <span class="text-danger">*</span>
                                            <select name="emp_one" id="emp_one"
                                                    class="form-control @error('emp_one') is-invalid @enderror" disabled>
                                                @if(empty($emp->emp_one) )
                                                    <option value="0" selected id="none_one">NONE</option>
                                                @endif
                                                @foreach($employees as $emp)
                                                    <option value="{{ $emp->id }}"
                                                    @if(!empty($employee->emp_one) && $employee->emp_one == $emp->id) selected @endif>
                                                        {{ $emp->first_name }} {{$emp->last_name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('emp_one')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <!-- End Employee One  -->


                                        <!-- Employee Two -->
                                        <div class="form-group col">
                                            <label>{{ __('files.Employee Two') }}</label> <span class="text-danger">*</span>
                                            <select name="emp_two" id="emp_two"
                                                    class="form-control @error('emp_two') is-invalid @enderror" disabled>
                                                @if(empty($emp->emp_two))
                                                    <option value="0" selected id="none_two">NONE</option>
                                                @endif
                                                @foreach($employees as $emp)
                                                    <option value="{{ $emp->id }}"
                                                    @if(!empty($employee->emp_two) && $employee->emp_two == $emp->id) selected @endif>
                                                        {{ $emp->first_name }} {{$emp->last_name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('emp_two')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    @endif
                                    <!-- End  Employee Two  -->






                                    <!-- Roles -->
                                    <div class="form-group col">
                                        <label for="gender">{{ __('files.Roles') }}</label> <span class="text-danger">*</span>
                                        <select id="gender" name="role" class="form-control @error('role') is-invalid @enderror">
                                            <option selected value="0">{{__('files.choose..')}}</option>
                                            @foreach($roles as $role)
                                                <option value="{{$role->id}}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('role')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <!-- End Roles -->

                                </div>
                                <div class="form-row">
                                    <div class="form-group col">
                                        <label for="department_id">{{ __('files.Department') }}</label> <span class="text-danger">*</span>
                                        <select id="department_id" name="department_id" class="form-control @error('department_id') is-invalid @enderror">
                                            @foreach($departments as $key => $department)
                                                <option value="{{ $department->id }}" {{ (old('department_id',$employee->department_id) == $department->id) ? 'selected' : '' }}>{{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('department_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col">
                                        <label for="designation_id">{{ __('files.Positions') }}</label> <span class="text-danger">*</span>
                                        <select id="designation_id" name="designation_id" class="form-control @error('designation_id') is-invalid @enderror">
                                            @foreach($designations as $key => $designation)
                                                <option value="{{ $designation->id }}" {{ (old('designation_id',$employee->designation_id) == $designation->id) ? 'selected' : '' }}>{{ $designation->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('designation_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col">
                                        <label  class="purbose-label col-md-12" for="about">{{ __('files.About') }}</label>
                                        <textarea name="about"
                                                  class="summernote-simple form-control height-textarea @error('about')
                                                      is-invalid @enderror"
                                                  id="about" >
                                            {{ old('about',$employee->about) }}
                                        </textarea>
                                        @error('about')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col">
                                        <label for="customFile">{{ __('files.Image') }}</label>
                                        <div class="custom-file">
                                            <input name="image" type="file" class="custom-file-input @error('image') is-invalid @enderror" id="customFile" onchange="readURL(this);">
                                            <label  class="custom-file-label" for="customFile">{{ __('files.Choose file') }}</label>
                                        </div>
                                        @if ($errors->has('image'))
                                            <div class="help-block text-danger">
                                                {{ $errors->first('image') }}
                                            </div>
                                        @endif
                                        @if($employee->user->getFirstMediaUrl('user'))
                                            <img class="img-thumbnail image-width mt-4 mb-3" id="previewImage" src="{{ asset($employee->user->getFirstMediaUrl('user')) }}" alt="your image"/>
                                        @else
                                            <img class="img-thumbnail image-width mt-4 mb-3" id="previewImage" src="{{ asset('assets/img/default/user.png') }}" alt="your image"/>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer ">
                                <button class="btn btn-primary mr-1" type="submit">{{ __('files.Update') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
    <script src="{{ asset('assets/modules/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/modules/summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('assets/modules/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('js/employee/edit.js') }}"></script>




    <script>
        $(document).ready(function () {
            let none_one = $('#none_one').val();
            let none_two = $('#none_two').val();

            let value = $('#approval_level').val();
            if (value == 1) {
                $('#emp_one').removeAttr('disabled');

                $('#emp_two').val(none_two);
                $('#emp_two').attr('disabled', true);
            }

            if (value == 2) {
                $('#emp_one').removeAttr('disabled');
                $('#emp_two').removeAttr('disabled');
            }

            if (value == 0) {
                $('#emp_one').val(none_one);
                $('#emp_two').val(none_two);

                $('#emp_one').attr('disabled', true);
                $('#emp_two').attr('disabled', true);
            }


            $('#approval_level').on('change', function () {
                console.log('changed');
                let value = $(this).val();
                console.log('Value=> ' + value);
                if (value == 1) {
                    $('#emp_one').removeAttr('disabled');

                    $('#emp_two').val(none_two);
                    $('#emp_two').attr('disabled', true);
                }

                if (value == 2) {
                    $('#emp_one').removeAttr('disabled');
                    $('#emp_two').removeAttr('disabled');
                }

                if (value == 0) {
                    $('#emp_one').val(none_one);
                    $('#emp_two').val(none_two);

                    $('#emp_one').attr('disabled', true);
                    $('#emp_two').attr('disabled', true);
                }
            });
        });
    </script>
@endsection
