@extends('layouts.app')

@section('title', $tag)
@section('description', $tag)

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
                        {{ $tag }}
                    </li>
                </ol>
            </nav>
        </div>

        <h5 class="mb-2">{{ $tag }}</h5>

        <div class="mt-4">
            @if ($posts->count())
            @foreach($posts as $post)
                @include('blocks.post-classic', ['post' => $post])
            @endforeach
            @else
                    <h5 style="text-align:center;">{{ __("No Data") }}</h5>
            @endif
        </div>

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
