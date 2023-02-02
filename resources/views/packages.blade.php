@extends('layouts.app')

@section('title', __("Packages"))
@section('description', __("Packages"))

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <!-- <div class="justify-content-start d-flex">
            <nav class="flex-shrink-1 mb-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt flex-nowrap">
                    <li class="breadcrumb-item d-flex text-nowrap">
                        <a class="link-fx" href="{{ url('/') }}">{{ __("Home") }}</a>
                    </li>
                    <li class="breadcrumb-item text-truncate" aria-current="page">
                        {{ __("Packages") }}
                    </li>
                </ol>
            </nav>
        </div> -->

        <h3 class="mb-4">
            {{ __("Packages") }}
        </h4>

        <div>
            <p>{{ __("NewsHub.kz offers its users a wide range of tariffs and discounts. At the same time, we provide one month of free use of the \"Standard\" package.") }}</p>
            <p>{{ __("This will allow you to evaluate all the benefits of using NewsHub.kz and choose the appropriate set of services that meet the requirements and financial capabilities of a company or organization. With paid service, the client can choose a different package. Each package includes discounts when paying for several months.") }}</p>

            <h3 class="text-center">{{ __("CHOOSE PACKAGE") }}</h3>
            <h5 class="text-center">{{ __("services for work in the Information Hub \"NewsHub.kz\"") }}</h5>

            <form action="{{ route("packages") }}" id="packageForm">
                <div class="col-12">
                    <div class="row items-push">
                        @foreach(\App\Models\Package::all() as $package)
                            <div class="col-md-4">
                                <div class="form-check form-block">
                                    <input type="radio" class="form-check-input" id="radio-block{{ $loop->iteration }}" name="package" value="{{ $package->slug }}" @if ($loop->iteration == 1) checked="" @endif>
                                    <label class="form-check-label" for="radio-block{{ $loop->iteration }}">
                                        <span class="d-block fw-normal my-3">
                                            <p class="fs-4 fw-semibold text-center">{{ strtoupper($package->name) }}</p>
                                            {!! $package->content !!}
                                        </span>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn w-100 btn-alt-primary">
                        {{ __('Continue') }}
                    </button>
                </div>
            </form>
        </div>

        @include('blocks.banner', ['location' => 'packages.view'])
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(document).on('submit', '#packageForm', function(e) {
            e.preventDefault();

            var package = $('[name="package"]:checked').val();
            window.location.href = `{{ url('package') }}/${package}`;
        });
    </script>
@endpush