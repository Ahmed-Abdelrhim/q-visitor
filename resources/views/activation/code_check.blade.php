<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>{{ setting('site_name'). ' - ' . __('Code Activation') }}</title>
    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- CSS Libraries -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/visitor.ico') }}">
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">


    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
            integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</head>

<body>
<div id="app">
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                    <div class="login-brand">
                        @if(setting('site_logo'))
                            <img src="{{ asset('images/'.setting('site_logo')) }}" alt="logo" width="100">
                        @else
                            <b>{{ setting('site_name') }}</b>
                        @endif
                    </div>

                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>{{ __('Activation') }}</h4>
                        </div>

                        @if (Session::has('mac'))
                            <script>
                                swal({
                                    text: " {!! Session::get('mac') !!}",
                                    icon: "error",
                                })
                            </script>
                        @endif

                        {{--                        @if (Session::has('success'))--}}
                        {{--                            <script>--}}
                        {{--                                swal({--}}
                        {{--                                    text: " {!! Session::get('success') !!}",--}}
                        {{--                                    icon: "success",--}}
                        {{--                                })--}}
                        {{--                            </script>--}}
                        {{--                        @endif--}}

                        @if (Session::has('error'))
                            <script>
                                swal({
                                    text: " {!! Session::get('error') !!}",
                                    icon: "error",
                                })
                            </script>
                        @endif

                        {{-- <script>--}}
                        {{-- swal({--}}
                        {{-- text: " {!! Session::get('error') !!}",--}}
                        {{-- icon: "error",--}}
                        {{-- })--}}
                        {{-- </script>--}}

                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.code.activation') }}">
                                @csrf
                                <!-- Code Activation -->
                                <div class="form-group">
                                    <label for="code">{{ __('Code') }}</label><span class="text-danger"> *</span>
                                    <input id="code" type="text"
                                           class="form-control @error('code') is-invalid @enderror" name="code"/>
                                    @error('code')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>


                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                        {{ __('Activate') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="simple-footer">
                        {{ setting('site_footer') }}
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
</body>
</html>