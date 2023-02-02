@extends('layouts.app')

@section('title', __("Vacancies"))
@section('description', __("Vacancies"))

@section('content')
<div class="row mb-4">
    <div class="col-lg-8">
        <!-- <div class="justify-content-start d-flex">
            <nav class="flex-shrink-1 mb-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt flex-nowrap">
                    <li class="breadcrumb-item d-flex text-nowrap">
                        <a class="link-fx" href="{{ url('/') }}">{{ __("Home") }}</a>
                    </li>
                    <li class="breadcrumb-item text-truncate" aria-current="page">
                        {{ __("Vacancies") }}
                    </li>
                </ol>
            </nav>
        </div> -->

        <h3>{{ __("Vacancies") }}</h3>
        <div class="row gx-3">
            @if ($vacancies->count())
                @foreach($vacancies as $vacancy)
                    <div class="col-md-6">
                        @include('blocks.vacancy-block', ['vacancy' => $vacancy])
                    </div>
                @endforeach
            @else
                    <h5 style="text-align:center;">{{ __("No Data") }}</h5>
            @endif
        </div>
           

        @if ($vacancies->hasPages())
            <div class="d-flex align-items-center justify-content-center mt-4">
                {{ $vacancies->links() }}
            </div>
        @endif
    </div>
    @include('sidebar')
</div>
@endsection
