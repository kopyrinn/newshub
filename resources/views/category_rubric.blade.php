@extends('layouts.app')

@section('title', $rubric->name)
@section('description', $category->description)

@section('content')
<div class="row mb-4">
    <div class="col-lg-8">
        <div class="justify-content-start d-flex">
            <nav class="flex-shrink-1 mb-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt flex-nowrap">
                    <li class="breadcrumb-item d-flex text-nowrap">
                        <a class="link-fx" href="{{ url('/') }}">{{ __("Home") }}</a>
                    </li>
                    <li class="breadcrumb-item d-flex text-nowrap" aria-current="page">
                        <a class="link-fx" href="{{ url("category/{$category->slug}") }}">{{ $category->name }}</a>
                    </li>
                    <li class="breadcrumb-item text-truncate" aria-current="page">
                        {{ $rubric->name }}
                    </li>
                </ol>
            </nav>
        </div>

        <h5 class="mb-2">{{ $rubric->name }}</h5>
        @if ($rubric->description)
            <p>{{ $rubric->description }}</p>
        @endif

        <div class="mt-4">
            @foreach($posts as $post)
                @include('blocks.post-classic', ['post' => $post])
            @endforeach
        </div>

        @if ($posts->hasPages())
            <div class="d-flex align-items-center justify-content-center mt-4">
                {{ $posts->links() }}
            </div>
        @endif

        @include('blocks.banner', ['location' => 'rubric.view'])
    </div>

    @include('sidebar')
</div>
@endsection
