@extends('layouts.app')

@section('title', $user->name)
@section('description', $user->description)

@section('content')
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
                {{ $user->name }}
            </li>
        </ol>
    </nav>
</div> -->

<div class="row mb-4">
    <div class="col-lg-3 col-md-4">
        @include('user_sidebar')
    </div>
    <div class="col-lg-9 col-md-8">
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
                    <div class="d-flex align-items-center">
                        <a href="{{ url("user/{$user->id}") }}" class="fw-bolder">
                            {{ $user->name }}
                        </a>
                        @foreach($user->roles->pluck('name') as $role)
                            <span class="bg-primary rounded px-2 py-1 text-white fs-xs fw-semibold ms-2">{{ $role }}</span>
                        @endforeach
                    </div>

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
                <p class="mb-0">{{ $user->description }}</p>
            </div>
        </div>

        <h5>{{ __("News Feed") }}</h5>
        @if ($posts->count())
            @foreach($posts as $post)
                @include('blocks.post-classic', ['post' => $post])
            @endforeach
        @else
            <p>{{ __("Not Found Posts") }}</p>
        @endif

        @if ($posts->hasPages())
            <div class="d-flex align-items-center justify-content-center mt-4">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/plugins/chart.js/chart.min.js') }} "></script>
@endpush