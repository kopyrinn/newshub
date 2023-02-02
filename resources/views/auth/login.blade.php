@extends('layouts.app')

@section('title', __('Authorization'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-8 col-xl-6">
        <div class="block block-rounded block-themed">
            <div class="p-sm-3 px-lg-3 py-lg-4">
                <h1 class="h2 mb-1">{{ __('Login') }}</h1>
                <p class="text-muted">
                    {{ __('Welcome Back!') }}
                </p>
                <form class="js-validation-signin" method="POST" action="{{ route('login') }}" novalidate="novalidate">
                    @csrf

                    <div class="py-3">
                        <div class="mb-4">
                            <input type="email" class="form-control form-control-alt form-control-lg @error('email') is-invalid @enderror" id="login-email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="{{ __('Email') }}">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <input type="password" class="form-control form-control-alt form-control-lg @error('password') is-invalid @enderror" id="login-password" name="password" required autocomplete="current-password" placeholder="{{ __('Password') }}">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="login-remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label font-w400" for="login-remember">{{ __('Remember Me') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4 row">
                        <div class="col-md-6 col-xl-5">
                            <button type="submit" class="btn btn-block btn-alt-primary">
                                <i class="fa fa-fw fa-sign-in-alt me-1"></i> {{ __('Login') }}
                            </button>
                        </div>
                    </div>
                    <div class="mb-4 row">
                        <div class="col-12">
                            <a href="{{ url('register') }}">
                                {{ __('Register') }}
                            </a>
                        </div>
                        <div class="col-12">
                            <a href="{{ url('password/reset') }}">{{ __('Forgot Your Password?') }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
