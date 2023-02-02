@php
    $locale = \LaravelLocalization::getCurrentLocale();
@endphp

@extends('layouts.app')

@section('title', __('Fill in the data about the region '))
@section('description', __('Fill in the data about the region '))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-8 col-xl-6">
        <div class="p-sm-3 px-lg-3 py-lg-4">
            <form action="{{ route("register.place") }}" method="POST">
                @csrf

                <h1 class="h2 mb-3">{{ __("Basic profile settings") }}</h1>

                <div class="mb-3">
                    <label class="form-label" for="name">{{ __('Name') }} <span class="text-danger">*</span></label>

                    <input type="text" class="form-control form-control-lg form-control-alt @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autocomplete="name">

                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="lastname">{{ __('Last Name') }} <span class="text-danger">*</span></label>

                    <input type="text" class="form-control form-control-lg form-control-alt @error('lastname') is-invalid @enderror" id="lastname" name="lastname" value="{{ old('lastname') }}" required autocomplete="lastname">

                    @error('lastname')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <h1 class="h2 mb-3">{{ __('Fill in the data about the region ') }}</h1>

                <div class="mb-3">
                    <label class="form-label" for="signup-password">{{ __('Region') }} <span class="text-danger">*</span></label>

                    <select name="region" class="form-control form-control-lg form-control-alt @error('region') is-invalid @enderror" required>
                        @foreach($regions as $region)
                            <option value="{{ $region->id }}">{{ __($region->{"region_name_{$locale}"}) }}</option>
                        @endforeach
                    </select>

                    @error('region')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="signup-password">{{ __('City') }} <span class="text-danger">*</span></label>

                    <select name="city" class="form-control form-control-lg form-control-alt @error('city') is-invalid @enderror" required>
                        @foreach($cities as $city)
                            <option data-region="{{ $city->region_id }}" value="{{ $city->id }}">{{ __($city->{"city_name_{$locale}"}) }}</option>
                        @endforeach
                    </select>

                    @error('city')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
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

@push('scripts')
    <script>
        $(document).ready(function() {
            $(this).on('input', '#name', function() {
               $(this).val($(this).val().replaceAll(/[^A-ZА-Яa-zа-я\-\ ]/ig, ''))
            }).on('input', '#lastname', function() {
                $(this).val($(this).val().replaceAll(/[^A-ZА-Яa-zа-я\-\ ]/ig, ''))
            });

            $(this).on('change', '[name="region"]', function() {
                var region = $(this).val();

                $(`[name="city"] option`).prop('disabled', true).hide();
                $(`[name="city"] option[data-region="${region}"]`).prop('disabled', false).show();
                $(`[name="city"]`).val($(`[name="city"] option[data-region="${region}"]`).first().val());
            });

            $(`[name="region"]`).trigger('change');
        });
    </script>
@endpush