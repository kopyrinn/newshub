@php
    $banner = \App\Models\Ad::where('location', $location)->where('expired_at', '>', date('Y-m-d H:i:s'))->inRandomOrder()->first();
@endphp
@if ($banner)
<div class="d-flex align-items-center justify-content-center mt-4">
        <a href="{{ route('goto', ['id' => $banner->id]) }}">
            <img class="mw-100" src="{{ asset("storage/{$banner->image}") }}" alt="">
        </a>
</div>
@endif