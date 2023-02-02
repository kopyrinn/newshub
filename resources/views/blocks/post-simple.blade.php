<div class="d-flex align-items-center">
    <div class="flex-grow-1 ms-3 fw-semibold">
        <a class="text-dark fs-sm" itemprop="headline name" href="{{ url("post/{$post->slug}") }}">{{ $post->title }}</a>
        <p class="text-muted mb-2 fs-xs"><a href="{{ url("user/{$post->user_id}") }}" class="author-link">{{ $post->user->name }}</a> · {{ Format::date($post->created_at) }} <!-- · <span class="text-muted fs-xs"><i class="fa fa-eye"></i> {{ $post->pageviews }}</span> --> </p>
    </div>
</div>