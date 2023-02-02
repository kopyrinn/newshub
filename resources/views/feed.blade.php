@extends('layouts.app')

@section('title', __("My Feed"))
@section('description', __("My Feed"))

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
                        {{ __("My Feed") }}
                    </li>
                </ol>
            </nav>
        </div> -->

        <h5>{{ __("News Feed") }}</h5>
        @foreach($posts as $post)
            @include('blocks.post-classic', ['post' => $post])
        @endforeach

        @if ($posts->hasPages())
            <div class="d-flex align-items-center justify-content-center mt-4">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
    @include('sidebar')
</div>
@endsection
