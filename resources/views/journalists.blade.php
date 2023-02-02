@extends('layouts.app')

@section('title', __("Journalists"))
@section('description', __("Journalists"))

@section('content')
<!-- <div class="justify-content-start d-flex">
    <nav class="flex-shrink-1 mb-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt flex-nowrap">
            <li class="breadcrumb-item d-flex text-nowrap">
                <a class="link-fx" href="{{ url('/') }}">{{ __("Home") }}</a>
            </li>
            <li class="breadcrumb-item text-truncate" aria-current="page">
                {{ __("Journalists") }}
            </li>
        </ol>
    </nav>
</div> -->

<div class="row mb-4">
    <h3 class="mb-3">{{ __("Journalists") }}</h3>
    <div class="col-lg-3 col-md-4">
        <div class="block block-rounded block-fx-shadow">
            <div class="block-header block-header-default">
                <h3 class="block-title">{{ __("Search") }}</h3>
            </div>
            <div class="block-content">
        <form id="filterForm">
            <div class="input-group input-group-sm mb-3">
                <input type="text" class="form-control form-control-alt" name="q" placeholder="Поиск по содержимому" value="{{ request()->q }}">
            </div>

            <div class="d-flex align-items-center justify-content-between">
                <div class="mb-3 d-flex align-items-center">
                    <div class="me-3">
                        <label class="form-label fs-sm">{{ __('Region') }}</label>
                        <select class="form-control form-control-alt form-control-sm" name="region">
                            <option value="">{{ __("Select") }}</option>
                            @foreach(App\Models\Region::all() as $region)
                                <option value="{{ $region->id }}" @if (request()->region == $region->id) selected="" @endif>{{ $region->region_name_ru }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if (request()->region)
                    <div>
                        <label class="form-label fs-sm">{{ __('City') }}</label>
                        <select class="form-control form-control-alt form-control-sm" name="city" style="min-width: 100px;">
                            <option value="">{{ __("Select") }}</option>
                            @foreach(App\Models\City::where('region_id', request()->region)->get() as $city)
                                <option value="{{ $city->id }}" @if (request()->city == $city->id) selected="" @endif>{{ $city->city_name_ru }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>

                <div class="mb-3">
                    @if ($users->hasPages())
                        <div class="d-flex align-items-center justify-content-end">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            </div>
                <button type="submit" class="btn btn-alt-primary" style="margin-bottom: 20px;">
                    <i class="fa fa-search me-1"></i> Поиск
                </button>
        </form>
        </div>
        </div>
    </div>

    <div class="col-lg-8">

        <div class="table-responsive">
            <table class="table table-hover table-vcenter">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">#</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Region') }}</th>
                        <th>{{ __('City') }}</th>
                        {{-- <th class="text-end" style="width: 100px;">{{ __('Actions') }}</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @if ($users->count())
                        @foreach($users as $user)
                            <tr>
                                <th class="text-center" scope="row">{{ $loop->iteration }}</th>
                                <td class="fw-semibold fs-sm" style="width: 40%;">
                                    <a href="{{ url("user/{$user->id}") }}">{{ $user->getName() }}</a>
                                </td>
                                <td class="fw-semibold fs-sm">
                                    {{ $user->city? $user->city->region->region_name_ru: '-' }}
                                </td>
                                <td class="fw-semibold fs-sm">
                                    {{ $user->city? $user->city->city_name_ru: '-' }}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="text-center" colspan="4">{{ __("No Data") }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="d-flex align-items-center justify-content-end mt-4">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(document).on('change', '[name="region"]', function(e) {
            e.preventDefault();
            $('[name="city"]').val('');
            $('#filterForm').submit();
        }).on('change', '[name="city"]', function(e) {
            e.preventDefault();
            $('#filterForm').submit();
        });
    </script>
@endpush