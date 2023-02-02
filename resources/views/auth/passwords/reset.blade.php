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
                    <form class="js-validation-reminder" action="{{ route('password.update') }}" method="POST" novalidate="novalidate">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-4">
                            <input id="email" type="email" class="form-control form-control-lg form-control-alt @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" placeholder="{{ __('E-Mail Address') }}" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <input type="password" class="form-control form-control-lg form-control-alt @error('password') is-invalid @enderror" id="signup-password" name="password" placeholder="{{ __('Password') }}" required autocomplete="new-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <input type="password" class="form-control form-control-lg form-control-alt" id="signup-password-confirm" placeholder="{{ __('Confirm Password') }}" name="password_confirmation" required autocomplete="new-password">
                        </div>

                        <div class="mb-4 row">
                            <div class="col-md-6 col-xl-5">
                                <button type="submit" class="btn btn-block btn-alt-primary">
                                    {{ __('Reset Password') }}
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
