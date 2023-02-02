@extends('layouts.app')

@section('title', __('Search') . (request()->q? ' "' . request()->q . '" ': ''))
@section('description', __('Search'))

@section('content')
<div class="row mb-4">
    <div class="col-lg-8">
        <div class="justify-content-start d-flex">
            <nav class="flex-shrink-1 mb-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt flex-nowrap">
                    <li class="breadcrumb-item d-flex text-nowrap">
                        <a class="link-fx" href="{{ url('/') }}">{{ __("Home") }}</a>
                    </li>
                    <li class="breadcrumb-item text-truncate" aria-current="page">
                        {{ __('Search') }} @if (request()->q) "{{ request()->q }}" @endif
                    </li>
                </ol>
            </nav>
        </div>

        <h5 class="mb-2">{{ __('Search') }}</h5>

        <form class="mb-4">
            <div class="input-group input-group-sm">
                <input name="q" type="search" class="form-control form-control-alt" placeholder="{{ __('Search') }}.." value="{{ request()->q }}">
                <span class="input-group-text border-0">
                    <i class="fa fa-fw fa-search"></i>
                </span>
            </div>
        </form>

        @if ($posts->count())
            @foreach($posts as $post)
                @include('blocks.post-classic', ['post' => $post])
            @endforeach
        @else
            <p>{{ __("No Results Found.") }}</p>
        @endif

        @if ($posts->hasPages())
            <div class="d-flex align-items-center justify-content-center mt-4">
                {{ $posts->links() }}
            </div>
        @endif

        @include('blocks.banner', ['location' => 'category.view'])
    </div>

    @include('sidebar')
</div>
@endsection
