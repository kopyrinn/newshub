@extends('layouts.app')

@section('title', __("Press Releases"))
@section('description', __("Press Releases"))

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
                        {{ __("Users") }}
                    </li>
                </ol>
            </nav>
        </div> -->

        @foreach(\App\Models\UserCategory::all() as $userCategory)
            <a href="{{ url("users/{$userCategory->slug}") }}" style="color: #000;">
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0">
                        @if ($userCategory->image)
                        <div class="bg-image bg-image-center rounded img-avatar img-avatar64" style="background-image: url('{{ asset("storage/{$userCategory->image}") }}'); " alt="{{ $userCategory->name }}"></div>
                        @endif
                    </div>
                    <div class="flex-grow-1 ms-3">
                        {{ $userCategory->name }}
                    </div>
                </div>
            </a>
        @endforeach
    </div>
    @include('sidebar')
</div>
@endsection
