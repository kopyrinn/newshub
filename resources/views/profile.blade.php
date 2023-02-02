@extends('layouts.app')

@section('title', $user->name)
@section('description', $user->description)

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="justify-content-start d-flex">
            <nav class="flex-shrink-1 mb-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt flex-nowrap">
                    <li class="breadcrumb-item d-flex text-nowrap">
                        <a class="link-fx" href="{{ url('/') }}">{{ __("Home") }}</a>
                    </li>
                    <li class="breadcrumb-item d-flex text-nowrap">
                        <a class="link-fx" href="{{ url('users') }}">Кабинеты</a>
                    </li>
                    <li class="breadcrumb-item text-truncate" aria-current="page">
                        {{ $user->name }}
                    </li>
                </ol>
            </nav>
        </div>

        <div class="d-flex align-items-center mb-4">
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
                    @if (auth()->user()->feeds()->where('user_id', $user->id)->exists())
                        <a href="{{ url("user/{$user->id}/unfollow") }}" class="btn btn-warning btn-sm">
                            <i class="fa fa-times me-1"></i>
                            Отписаться
                        </a>
                    @else
                        <a href="{{ url("user/{$user->id}/follow") }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus me-1"></i>
                            Подписаться
                        </a>
                    @endif
                </div>
                <p class="mb-0">{{ $user->description }}</p>
            </div>
        </div>

    </div>
</div>
@endsection
