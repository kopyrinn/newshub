@extends('layouts.app')

@section('title', __("Buy Package") . " {$package->name}")
@section('description', __("Buy Package") . " {$package->name}")

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="justify-content-start d-flex">
            <nav class="flex-shrink-1 mb-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt flex-nowrap">
                    <li class="breadcrumb-item d-flex text-nowrap">
                        <a class="link-fx" href="{{ url('/') }}">{{ __("Home") }}</a>
                    </li>
                    <li class="breadcrumb-item text-truncate" aria-current="page">
                        {{ __("Buy Package") . " {$package->name}" }}
                    </li>
                </ol>
            </nav>
        </div>

        <div class="col-lg-4 offset-lg-4">
            <h3 class="mb-4">
                {{ __("Buy Package") . " {$package->name}" }}
            </h4>

            {{-- <p>NewsHub.kz предлагает своим пользователям широкий выбор тарифов и скидок. При этом мы предоставляем один месяц бесплатного использования пакета «Стандарт».</p> --}}

            <form method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">{{ __('Select Period') }}</label>
                    <select class="form-control form-control-alt" name="period">
                        <option data-price="{{ number_format($package->price_1) }}" value="1">{{ __("1 Month") }}</option>
                        <option data-price="{{ number_format($package->price_3) }}" value="3">{{ __("3 Month") }}</option>
                        <option data-price="{{ number_format($package->price_6) }}" value="6">{{ __("6 Month") }}</option>
                        <option data-price="{{ number_format($package->price_12) }}" value="12">{{ __("12 Month") }}</option>
                    </select>
                </div>

                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <div class="fw-bold">
                        {{ __('Total') }}:
                    </div>
                    <div class="fw-semibold bg-primary rounded px-2 py-1 text-white fs-sm">
                        <span id="price">0</span> тг.
                    </div>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn w-100 btn-alt-primary">
                        {{ __('Pay') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(document).on('change', '[name="period"]', function(e) {
            var price = $('[name="period"] option:checked').data('price');
            $('#price').text(price);
        });

        $('[name="period"]').trigger('change');
    </script>
@endpush