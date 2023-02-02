<div class="bg-image mb-1 rounded lazy" itemprop="image" data-bg="{{ Format::thumb($post->image, 640, 217) }}" style="height: 217px;"></div>
<h4 class="mb-1">
    <a class="text-white" href="{{ url("post/{$post->slug}") }}" itemprop="headline name">{{ Str::limit($post->title, 85) }}</a>
</h4>
<p class="fs-sm fw-medium mb-2">
    <a class="text-white" href="{{ url("user/{$post->user_id}") }}" class="author-link">{{ $post->user->name }}</a> · <span class="d-none" itemprop="datePublished">{{ $post->created_at}}</span>{{ Format::date($post->created_at) }} <!--· <span class="text-white fs-xs"><i class="fa fa-eye"></i> {{ $post->pageviews }}</span> -->
</p>
<p class="fs-sm text-white" itemprop="description">
    {{ $post->getSummary() }}
</p>