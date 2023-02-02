<div class="d-flex align-items-center bg-{{ $post->is_styled? $post->style_color: '' }} rounded-2 mb-2">
    <div class="flex-shrink-0" style="font-size: 0;">
        <a class="img-link img-link-simple" href="{{ url("post/{$post->slug}") }}">
            <div class="bg-image rounded lazy" data-bg="{{ Format::thumb($post->image, 106, 74) }}" itemprop="image" style="height: 74px; width: 106px;"></div>
        </a>
    </div>
    <div class="flex-grow-1 ms-3 fs-xs fw-semibold">
        <a class="{{ $post->is_styled? $post->style_color: 'text-dark' }}" itemprop="headline name" href="{{ url("post/{$post->slug}") }}">{{ $post->title }}</a>
        <p class="{{ $post->is_styled? $post->style_color: 'text-muted' }} mb-0 fs-xs"><a href="{{ url("user/{$post->user_id}") }}" class="author-link">{{ $post->user->name }}</a> · {{ Format::date($post->created_at) }} <!-- ·  <span class="{{ $post->is_styled? $post->style_color: 'text-muted' }} fs-xs text-nowrap">  <i class="fa fa-eye"></i> {{ $post->pageviews }}</span> --> </p>
    </div>
</div>