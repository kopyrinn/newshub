@extends('mobile.layouts.full')

@section('content')
<div class="content overflow-hidden pt-3 px-0 px-sm-4">
    @include('blocks.alerts')
</div>

<div class="section full mt-2 mb-3">
            <div class="section-title mb-1">Related Items</div>


        <div class="carousel-full splide">
            <div class="splide__track">
                <ul class="splide__list">
                    @foreach($slider as $post)
                    <li class="splide__slide">
                        <img src="{{ Format::thumb($post->image, 636, 442) }}" alt="alt" class="imaged w-100 square">
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        
        

            <!-- carousel multiple -->
            <div class="carousel-multiple splide">
                <div class="splide__track">
                    <ul class="splide__list">
                        @foreach($featured as $post)
                        <li class="splide__slide">
                            <div class="card product-card">
                                <div class="card-body">
                                    <img src="{{ Format::thumb($post->image, 314, 217) }}" class="image" alt="product image">
                                    <a href="{{ url("post/{$post->slug}") }}"><h2 class="title">{{ Str::limit($post->title, 85) }}</h2></a>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <!-- * carousel multiple -->

        </div>