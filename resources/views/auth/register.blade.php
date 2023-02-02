@extends('layouts.app')
@section('head')
    <script src='https://www.google.com/recaptcha/api.js'></script>
@endsection

@section('title', __('Register'))
@section('description', __('Register'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-8 col-xl-6">
        <div class="p-sm-3 px-lg-3 py-lg-4">
            <h1 class="h2 mb-1">{{ __('Register') }}</h1>
            <p class="text-muted">
                <a href="{{ route('login') }}">{{ __('Already registered?') }}</a>
            </p>
            <form class="js-validation-signup" action="{{ route('register') }}" method="POST" novalidate="novalidate">
                @csrf

                <div class="py-3">
                    {{-- <div class="mb-3">
                        <label class="form-label" for="name">{{ __('Name') }} <span class="text-danger">*</span></label>

                        <input type="text" class="form-control form-control-lg form-control-alt @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autocomplete="name">

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div> --}}

                    <div class="mb-3">
                        <label class="form-label" for="email">{{ __('Email') }} <span class="text-danger">*</span></label>

                        <input type="email" class="form-control form-control-lg form-control-alt @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autocomplete="email">

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="phone">{{ __('Phone') }} <span class="text-danger">*</span></label>

                        <input type="phone" class="form-control form-control-lg form-control-alt @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required autocomplete="phone">

                        @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="signup-password">{{ __('Password') }} <span class="text-danger">*</span></label>

                        <input type="password" class="form-control form-control-lg form-control-alt @error('password') is-invalid @enderror" id="signup-password" name="password" required autocomplete="new-password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="signup-password-confirm">{{ __('Confirm Password') }} <span class="text-danger">*</span></label>

                        <input type="password" class="form-control form-control-lg form-control-alt" id="signup-password-confirm" name="password_confirmation" required autocomplete="new-password">
                    </div>

                    <div class="mb-3">
                        <div class="form-check @error('agree') is-invalid @enderror">
                            <input type="checkbox" class="form-check-input" id="signup-terms" name="agree">
                            <label class="form-label form-check-label fw-normal" for="signup-terms">Я согласен с <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#one-signup-terms">условиями конфиденциальности и обработку персональных данных</a></label>
                        </div>
                        @error('agree')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                    <div class="mb-4 row">
                        <div class="col-md-6 col-xl-5">
                            <div class="g-recaptcha" data-sitekey="{{ env('CAPTCHA_SITE_KEY') }}"></div>
                                @if ($errors->has('g-recaptcha-response'))
                                <span class="invalid-feedback" style="display: block;">
                                    <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                </span>
                                @endif
                            </div>
                    </div>
                <div class="mb-4 row">
                    <div class="col-md-6 col-xl-5">
                        <button type="submit" class="btn btn-block btn-alt-primary">
                            <i class="fa fa-fw fa-plus me-1"></i> {{ __('Register') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('footer')
<div class="modal fade" id="one-signup-terms" tabindex="-1" role="dialog" aria-labelledby="one-signup-terms" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-popout" role="document">
        <div class="modal-content">
            <div class="block block-rounded block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Согласие на обработку персональных данных</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa fa-fw fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    {!! \App\Models\Page::where('slug', 'terms-conditions')->first()->page_content !!}
                </div>
                <div class="block-content block-content-full text-end border-top">
                    <button type="button" class="btn btn-alt-primary me-1" data-bs-dismiss="modal">Закрыть</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="assign-terms">Согласен</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/plugins/jquery.maskedinput/jquery.maskedinput.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('[name="phone"]').mask("+7(999)-999-99-99");
        });
        $(document).on('click', '#assign-terms', function(e) {
            $('#signup-terms').prop('checked', true);
        });
    </script>
@endpush