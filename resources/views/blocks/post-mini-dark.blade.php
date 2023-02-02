<div class="d-flex align-items-center">
    <div class="flex-shrink-0">
        <a class="img-link img-link-simple" href="{{ url("post/{$post->slug}") }}">
            <div class="bg-image rounded lazy" data-bg="{{ Format::thumb($post->image, 106, 74) }}" style="height: 74px; width: 106px;"></div>
        </a>
    </div>
    <div class="flex-grow-1 ms-3 fs-xs fw-semibold">
        <a class="text-white" href="{{ url("post/{$post->slug}") }}">{{ $post->title }}</a>
        <p class="text-white mb-2 fs-xs"><a class="text-white" href="{{ url("user/{$post->user_id}") }}" class="author-link">{{ $post->user->name }}</a> · {{ Format::date($post->created_at) }} <!-- · <span class="text-white fs-xs"><i class="fa fa-eye"></i> {{ $post->pageviews }}</span> --></p>
    </div>
</div>