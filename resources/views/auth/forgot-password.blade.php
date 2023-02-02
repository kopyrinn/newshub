@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-8 col-xl-6">
        <div class="block block-rounded block-themed">
            <div class="block-header bg-primary-dark">
                <h3 class="block-title">{{ __('Reset Password') }}</h3>
                <div class="block-options">
                    <a class="btn-block-option js-tooltip-enabled" href="{{ route('login') }}" data-toggle="tooltip" data-placement="left" title="" data-original-title="Авторизация">
                        <i class="fa fa-sign-in-alt"></i>
                    </a>
                </div>
            </div>
            <div class="block-content">
                <div class="p-sm-3 px-lg-3 py-lg-4">
                    <h1 class="h2 mb-1">{{ config('app.name') }}</h1>
                    <p class="text-muted">
                        Enter your account email address and we will send you a link to reset your password. 
                    </p>
                    <form class="js-validation-reminder" action="{{ route('password.email') }}" method="POST" novalidate="novalidate">
                        @csrf

                        <div class="mb-4 py-3">
                            <input id="email" type="email" class="form-control form-control-lg form-control-alt @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="{{ __('E-Mail Address') }}" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 row">
                            <div class="col-md-6 col-xl-5">
                                <button type="submit" class="btn btn-block btn-alt-primary">
                                    <i class="fa fa-fw fa-envelope me-1"></i> Send Email
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
