@php
    $locale = \LaravelLocalization::getCurrentLocale();
@endphp

@extends('layouts.app')

@section('title', __("Media Map"))
@section('description', __("Media Map"))

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
                        {{ __("Media Map") }}
                    </li>
                </ol>
            </nav>
        </div> -->

        <h3 class="mb-4">
            {{ __("Media Map") }}
        </h4>

        <p>{{ __("The map shows the actual number of people registered on the NewsHUB.kz portal. The use of data is prohibited.") }}</p>

        <p class="medium">
            {{ __("Total number of registered journalists") }} - @if (!auth()->guest()) {{ $usersCount }} @else <b>{{ __("to receive data, registration is required") }}</b> @endif
            
        </p>


        <div class="row">
            <div class="col-sm-8">
                <figure id="imapc">
                    <object data="{{ asset("assets/media/kazakhstan.svg") }}" type="image/svg+xml" id="imap">
                        <p>{{ __("Sorry, you are using an outdated browser version that does not support the interactive map.") }}</p>
                    </object>
                </figure>
            </div>

            <div class="col-sm-4">
                {{-- <div class="form-check">
                    <input type="radio" class="form-check-input" id="resetswitch" name="tabledata" checked>
                    <label for="resetswitch" class="form-check-label font-w400">{{ __("Show no values") }}</label>
                </div> --}}
                <table id="areas" class="table table-sm table-hover table-vcenter">
                    <thead>
                        <tr>
                            <th></th>
                            <th>{{ __("Regions") }}</th>
                            <th id="journalists">{{ __("Journalists") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($regions as $region)
                            <tr id='pathKZ-{{ $region->code }}'>
                                <td class="">
                                    <div class="form-check ms-2">
                                        <input type="checkbox" id="c{{ $region->code }}" class="form-check-input">
                                    </div>
                                </td>
                                <td><label for="c{{ $region->code }}">{{ $region->{"region_name_{$locale}"} }}<label></td>
                                <td><label for="c{{ $region->code }}">{{ $region->getUsersCount(["journalist"]) }}<label></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
    <link href="{{ asset("assets/css/style_map.css") }}" rel="stylesheet" />
    <style>
        table {
            border-collapse: separate;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset("assets/js/script_map.js") }}"></script>
    <script>
        $(document).on('submit', '#packageForm', function(e) {
            e.preventDefault();

            var package = $('[name="package"]:checked').val();
            window.location.href = `{{ url('package') }}/${package}`;
        });
    </script>
@endpush