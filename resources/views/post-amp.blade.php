@extends('layouts.amp')

@section('title', $post->title)
@section('description', $post->getSummary())
@section('image', $post->image)
@section('keywords', $post->keywords)
@section('date', $post->created_at->format('c'))
@section('author', $post->user->name)

@push('header')
    {!! $schema->toScript() !!}
@endpush

@section('content')
    <header class="headerbar">
        
        <div class="site-name"><amp-img src="https://newshub.kz/logo.png" width="166" height="36" style="width: 166px; height: 36px;">
        </amp-img></div>
        <div role="button" on="tap:sidebar1.toggle" tabindex="0" class="hamburger">☰</div>
    </header>

    <amp-sidebar id="sidebar1" layout="nodisplay" side="right">
        <div role="button" aria-label="close sidebar" on="tap:sidebar1.toggle" tabindex="0" class="close-sidebar">✕</div>
            <ul class="sidebar">
                <li><a href="https://newshub.kz/category/news">Новости</a></li>
                <li><a href="https://newshub.kz/category/press-release-3">Пресс-релизы</a></li>
                <li><a href="https://newshub.kz/category/intervyu">Интервью</a></li>
                <li><a href="https://newshub.kz/category/sobitiya">События</a></li>
                <li><a href="https://newshub.kz/page/contact">Контакты</a></li>
            </ul>
    </amp-sidebar>
    
<div class="container">

    <div class="post_info">
        <div class="date">{{ Format::date($post->created_at) }}</div>
    </div>
    
        <div class="author">
            <div class="authorInfo">
                <div class="name" style="padding-top: 10px;">Автор: {{ $post->user->name }}</div>
            </div>
        </div>
        
        <h1>{{ $post->title }}</h1>
        
        <div class="tags">
            @foreach($post->getTags() as $tag)
            <a href="{{ route('tags', ['tag' => $tag]) }}">#{{ $tag }}</a>@if (!$loop->last),@endif
            @endforeach
        </div>
    
                @if ($post->image)
                        <amp-img src="{{ asset("storage/{$post->image}") }}" width="800" height="500" layout="responsive" alt=""></amp-img>
                        @if ($post->image_caption)
                            <div class="image_copyright">{{ $post->image_caption }}</div>
                        @endif
                @endif

        <div class="description">{!! nl2br($post->getSummary()) !!}</div>
        
        <div class="social">
                <amp-social-share class="rounded" type="email" width="36" height="36"></amp-social-share>
                <amp-social-share class="rounded" type="facebook" data-param-app_id="254325784911610" width="36" height="36"></amp-social-share>
                <amp-social-share class="rounded" type="twitter" width="36" height="36"></amp-social-share>
                <amp-social-share class="rounded" type="whatsapp" width="36" height="36"></amp-social-share>
        </div>

        <div class="read_more">
            <a href="{{ url("post/{$post->slug}") }}" class="btn">Читать на Newshub.kz</a>
        </div> 
        
        <div class="content" id="post-content">{!! str_replace('<p>&nbsp;</p>', '', trim($post->content)) !!}</div>
        
        <div class="read_more">
            <a href="https://newshub.kz" class="btn">Еще новости</a>
        </div> 

</div>
@endsection
