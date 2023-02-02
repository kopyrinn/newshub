@php
    $lastPostDates = [];
@endphp
@foreach($posts as $lastPost)
    @if (!$lastPost->created_at->isToday() && !in_array($lastPost->created_at->format('Y-m-d'), $lastPostDates))
        @php
            $lastPostDates[] = $lastPost->created_at->format('Y-m-d');
        @endphp
        <h5 class="mb-2">
            <span class="fw-bold me-2">{{ \Date::parse($lastPost->created_at)->format('j F') }}</span>
        </h5>
        <hr class="my-2"/>
    @endif
    <p class="mb-2"><span class="fw-semibold me-2">{{ $lastPost->created_at->format('H:i') }}</span><a href="{{ url("post/{$lastPost->slug}") }}" class="@if ($lastPost->pageviews > 100) fw-bold @else fw-normal @endif">{{ $lastPost->title }}</a></p>
@endforeach