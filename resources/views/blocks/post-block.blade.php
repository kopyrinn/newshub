<div class="block-rounded overflow-hidden">
    <div itemprop="image" class="bg-image lazy" data-bg="{{ Format::thumb($post->image, 408, 217) }}" style="height: 217px;"></div>
    <div class="block-content bg-{{ $post->is_styled? $post->style_color: '' }}">
        <h1 class="mb-1">
            <a class="{{ $post->is_styled? $post->style_color: 'text-dark' }}" href="{{ url("post/{$post->slug}") }}" itemprop="headline name">{{ Str::limit($post->title, 85) }}</a>
        </h1>
        <p class="fs-sm fw-medium mb-2">
            <a href="{{ url("user/{$post->user_id}") }}" class="author-link">{{ $post->user->name }}</a> · <span class="d-none" itemprop="datePublished">{{ $post->created_at}}</span>{{ Format::date($post->created_at) }} · <span class="{{ $post->is_styled? $post->style_color: 'text-muted' }} fs-xs"> </span> 
        </p>
        <p class="fs-sm {{ $post->is_styled? $post->style_color: 'text-muted' }}" itemprop="description">
            {{ $post->getSummary(150) }}
        </p>
    </div>
</div>