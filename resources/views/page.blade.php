@extends('layouts.app')

@section('title', $page->title)
@section('description', $page->description)

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <!-- <div class="justify-content-start d-flex">
            <nav class="flex-shrink-1 mb-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt flex-nowrap">
                    <li class="breadcrumb-item d-flex text-nowrap">
                        <a class="link-fx" href="{{ url('/') }}">{{ __("Home") }}</a>
                    </li>
                    <li class="breadcrumb-item text-truncate" aria-current="page">
                        {{ Str::limit($page->title, 50) }}
                    </li>
                </ol>
            </nav>
        </div> -->

        <h2 class="mb-4">{{ $page->title }}</h2>

        {!! $page->page_content !!}

        @include('blocks.banner', ['location' => 'page.view'])
    </div>
</div>

@endsection
