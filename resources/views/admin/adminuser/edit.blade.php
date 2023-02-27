@extends('admin.layouts.master')

@section('main-content')

    <section class="section">
        <div class="section-header">
            <h1>{{ __('files.Administrators') }}</h1>
            {{ Breadcrumbs::render('administrators/edit') }}
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 rtl-in-arabic">
                    <form action="{{ route('admin.adminusers.update', $user) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card">
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="form-group col">
                                        <label>{{ __('files.First Name') }}</label> <span class="text-danger">*</span>
                                        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', $user->first_name) }}">
                                        @error('first_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col">
                                        <label>{{ __('files.Last Name') }}</label> <span class="text-danger">*</span>
                                        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', $user->last_name) }}">
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
                                        <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}">
                                        @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col">
                                        <label>{{ __('files.Phone') }}</label> <span class="text-danger">*</span>
                                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}">
                                        @error('phone')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col">
                                        <label>{{ __('files.Username') }}</label>
                                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $user->username) }}">
                                        @error('username')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col">
                                        <label>{{ __('files.Password') }}</label>
                                        <input type="text" name="password" class="form-control @error('password') is-invalid @enderror" value="{{ old('password') }}">
                                        @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col">
                                        <label for="customFile">{{ __('files.Image') }}</label>
                                        <div class="custom-file">
                                            <input name="image" type="file" class="custom-file-input @error('image') is-invalid @enderror" id="customFile" onchange="readURL(this);">
                                            <label class="custom-file-label" for="customFile">{{ __('files.Choose file') }}</label>
                                        </div>
                                        @if ($errors->has('image'))
                                            <div class="help-block text-danger">
                                                {{ $errors->first('image') }}
                                            </div>
                                        @endif

                                        @if($user->getFirstMediaUrl('user'))
                                            <img class="img-thumbnail image-width mt-4 mb-3" id="previewImage" src="{{ asset($user->getFirstMediaUrl('user')) }}" alt="your image"/>
                                        @else
                                            <img class="img-thumbnail image-width mt-4 mb-3" id="previewImage" src="{{ asset('assets/img/default/user.png') }}" alt="your image"/>
                                        @endif
                                    </div>
                                    <div class="form-group col">
                                        <label>{{ __('files.Address') }}</label>
                                        <textarea name="address" class="form-control small-textarea-height" id="address" cols="30" rows="10">{{ old('address', $user->address) }}</textarea>
                                        @error('address')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-primary mr-1" type="submit">{{ __('files.Submit') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
    <script src="{{ asset('js/adminuser/edit.js') }}"></script>
@endsection
