@extends('layouts.app')

@section('title', $userCategory->name)
@section('description', $userCategory->name)

@section('content')
<div class="row mb-4">
    <div class="col-lg-8">
        <!-- <div class="justify-content-start d-flex">
            <nav class="flex-shrink-1 mb-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt flex-nowrap">
                    <li class="breadcrumb-item d-flex text-nowrap">
                        <a class="link-fx" href="{{ url('/') }}">{{ __("Home") }}</a>
                    </li>
                    <li class="breadcrumb-item d-flex text-nowrap">
                        <a class="link-fx" href="{{ url('users') }}">{{ __("Users") }}</a>
                    </li>
                    <li class="breadcrumb-item text-truncate" aria-current="page">
                        {{ $userCategory->name }}
                    </li>
                </ol>
            </nav>
        </div> -->
        @if ($users->count())
        @foreach($users as $user)
            <div class="d-flex align-items-center mb-3">
                <div class="flex-shrink-0">
                    @if ($user->avatar)
                        <a href="{{ url("user/{$user->id}") }}">
                            <div class="bg-image bg-image-center rounded img-avatar img-avatar64" style="background-image: url('{{ $user->getAvatar() }}'); " alt="{{ $user->name }}"></div>
                        </a>
                    @endif
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <a href="{{ url("user/{$user->id}") }}" class="fw-bolder">
                            {{ $user->name }}
                        </a>
                        @if (!auth()->guest() && auth()->user()->id != $user->id)
                            @if (auth()->user()->feeds()->where('user_id', $user->id)->exists())
                                <a href="{{ url("user/{$user->id}/unfollow") }}" class="btn btn-warning btn-sm">
                                    <i class="fa fa-times me-1"></i>
                                    {{ __("Unsubscribe") }}
                                </a>
                            @else
                                <a href="{{ url("user/{$user->id}/follow") }}" class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus me-1"></i>
                                    {{ __("Subscribe") }}
                                </a>
                            @endif
                        @endif
                    </div>
                    <p class="mb-0">{{ Str::limit($user->description, 200) }}</p>
                </div>
            </div>
        @endforeach
        @else
                    <h5 style="text-align:center;">{{ __("No Data") }}</h5>
        @endif
    </div>
    @include('sidebar')
</div>
@endsection
