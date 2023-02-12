@extends('layouts.app')

@section('title', __("Polls"))
@section('description', __("Polls"))

@section('content')

<div class="row mb-4">
    <div class="col-lg-8">
        <h5 class="mb-2"> {{ __("Polls") }}</h5>

        <div class="mt-4">
            @if ($polls->count())
                @foreach ($polls as $poll)
        <div class="row push">
        <div class="col-12">
            <div class="row g-0 rounded-2">
                <div class="col-md-4 col-sm-5">
                    <a class="img-link img-link-simple" href="{{ url('polls/' . $poll->slug) }}">
                        <img itemprop="image" class="rounded mw-100 mb-2 mb-sm-0 lazy w-100" src="{{ Storage::url($poll->image) }}" alt="{{ $poll->question }}" loading="lazy">
                    </a>
                </div>
                <div class="col-md-8 col-sm-7 ps-0 ps-sm-3">
                    <h4  class="mb-1">
                        <a class="text-dark" href="{{ url('polls/' . $poll->slug) }}">{{ $poll->question }}</a>
                    </h4>
                    <div class="fs-sm fw-semibold mb-1">
                        · <span class="d-none" itemprop="datePublished">{{ $poll->created_at}}</span> {{ Format::date($poll->created_at) }} 
                    </div>
                    <p itemprop="description" class="fs-sm fw-medium mb-2">
                        {{ Str::limit(strip_tags($poll->description), 180) }}
                        
                    </p>
                </div>
            </div>
        </div>
        </div>

                @endforeach
            @else
                <p>{{ __("No Results Found.") }}</p>
            @endif

            @if ($polls->hasPages())
                <div class="d-flex align-items-center justify-content-center mt-4">
                    {{ $polls->links() }}
                </div>
            @endif
        </div>

        @include('blocks.banner', ['location' => 'polls.index'])
    </div>

    @include('sidebar')
</div>
@endsection