@extends('layouts.app')

@section('title', __('Select your account type'))
@section('description', __('Select your account type'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-8 col-xl-6">
        <div class="p-sm-3 px-lg-3 py-lg-4">
            <h1 class="h2 mb-3">{{ __('Select your account type') }}</h1>
           

            <form action="{{ route("register.role") }}" method="POST">
                @csrf

                <div class="row items-push">
                    <div class="col-lg-6">
                        <div class="form-check form-block">
                            <input type="radio" class="form-check-input" id="radio-block1" name="role" value="journalist" checked="">
                            <label class="form-check-label" for="radio-block1">
                                <span class="d-block fw-normal my-3">
                                    <p class="fs-4 fw-semibold text-center">{{ __('Journalist') }}</p>
                                    <img class="mw-100" src="{{ asset("assets/media/journalist.jpg") }}">
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-check form-block">
                            <input type="radio" class="form-check-input" id="radio-block2" name="role" value="press">
                            <label class="form-check-label" for="radio-block2">
                                <span class="d-block fw-normal my-3">
                                    <p class="fs-4 fw-semibold text-center">{{ __('Press Center') }}</p>
                                    <img class="mw-100" src="{{ asset("assets/media/press.jpg") }}">
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn w-100 btn-alt-primary">
                        {{ __('Continue') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection