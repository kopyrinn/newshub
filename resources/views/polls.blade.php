@extends('layouts.app')

@section('title', __("Polls"))
@section('description', __("Polls"))

@section('content')
<div class="row mb-4">
    <div class="col-lg-8">
        <h3 class="mb-4">
            {{ __("Polls") }}
        </h4>

        <div class="row">
            @if ($polls->count())
                @foreach ($polls as $poll)
                    <div class="col-md-4">
                        <a class="" href="{{ url('polls/' . $poll->slug) }}">
                            <div class="bg-image mb-1 rounded lazy" itemprop="image" data-bg="{{ Storage::url($poll->image) }}" style="height: 217px;"></div>
                        </a>
                        <h4 class="mb-1">
                            <a class="text-truncate d-block" href="{{ url('polls/' . $poll->slug) }}" itemprop="headline name">{{ $poll->question }}</a>
                        </h4>
                        <p class="fs-sm fw-medium mb-2">
                            <span class="d-none" itemprop="datePublished">{{ $poll->created_at }}</span>{{ Format::date($poll->created_at) }}
                        </p>
                        <p class="fs-sm" itemprop="description">
                            {{ Str::limit($poll->description, 150) }}
                        </p>
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