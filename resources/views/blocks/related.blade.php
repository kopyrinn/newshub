
@php
    $posts = $category
        ->posts()
        ->where('id', '<', $id)
        ->where('status', 1)
        ->where('created_at', '<', \Carbon\Carbon::now())
        ->latest('created_at')
        ->groupBy('id')
        ->take(10)
        ->get();
@endphp

@if ($posts->count())
    <div class="d-flex justify-content-between align-items-center rounded py-2 px-3 mb-2">
        <h5 class="fw-bold fs-4 mb-0">{{ __('Newslist') }}</h5>
    </div>

    {{-- <div class="row"> --}}
        {{-- @foreach($posts as $post)
            <div class="col-sm-12">
                @include('blocks.post-mini', ['post' => $post])
            </div>
        @endforeach --}}
    {{-- </div> --}}

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
        <p class="mb-2">
            <span class="fw-semibold me-2">{{ $lastPost->created_at->format('H:i') }}</span>
            <a href="{{ url("post/{$lastPost->slug}") }}" class="@if ($lastPost->pageviews > 100) fw-bold @else fw-normal @endif">{{ $lastPost->title }}</a>
        </p>
        @if (!$loop->last) <hr class="my-2"/> @endif
    @endforeach
@endif