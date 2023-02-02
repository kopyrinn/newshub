@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-8 col-xl-6">
        <div class="block block-rounded block-themed">
            <div class="p-sm-3 px-lg-3 py-lg-4">
                <h1 class="h2 mb-1">{{ __('Reset Password') }}</h1>
                <p class="text-muted">
                    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                </p>
                <form class="js-validation-reminder" action="{{ route('password.email') }}" method="POST" novalidate="novalidate">
                    @csrf

                    <div class="mb-4 py-3">
                        <input id="email" type="email" class="form-control form-control-lg form-control-alt @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="{{ __('Email') }}" autofocus>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-4 row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-block btn-alt-primary">
                                <i class="fa fa-fw fa-envelope me-1"></i> {{ __('Send Password Reset Link') }}
                            </button>
                        </div>
                    </div>
                </form>
                <a href="{{ route('login') }}">{{ __('Login') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection
