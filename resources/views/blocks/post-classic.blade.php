
@if ($post->isEvent())
    <div class="row gx-0 push bg-light">
        <div class="col-md-3 col-sm-3">
            <a href="{{ url("post/{$post->slug}") }}">
                <div itemprop="image" class="bg-image rounded w-100 h-100 mb-2 lazy" data-bg="{{ Format::thumb($post->image, 466, 262) }}"></div>
            </a>
        </div>
        <div class="col-md-7 col-sm-6 gx-4 py-2">
            <h6 itemprop="headline name" class="mb-1 fw-bold">
                <a class="text-dark" href="{{ url("post/{$post->slug}") }}">{{ Str::limit($post->title, 130) }}</a>
            </h6>
            {{-- <div class="fs-sm mb-1">
                <i class="fa fa-fw fa-1x fa-map-marker-alt me-2"></i>{{ $post->place?: '-' }}
            </div>
            <div class="fs-sm fw-semibold mb-1">
                <i class="fa fa-fw fa-1x fa-ticket-alt me-2"></i> <span class="text-warning">{{ round($post->price, 2)? number_format($post->price) . " тг.": __('Free') }}</span>
            </div> --}}
            <div class="fs-sm fw-semibold mb-1">
                
            </div>
            <p itemprop="articleBody" class="fs-sm mb-2">
                {{ $post->getSummary() }}
            </p>
        </div>
        <div class="col-md-2 col-sm-3">
            <div class="block block-rounded mb-0 h-100">
                <div class="block-content block-content-full h-100 ribbon ribbon-primary ribbon-modern ribbon-glass bg-primary text-light d-flex align-items-center justify-content-center">
                    <div class="ribbon-box">
                        <i class="fa fa-bullhorn"></i>
                    </div>
                    <div class="text-center fw-bold py-4">
                        @php
                            $date = explode(" ", Date::parse($post->event_date)->format('j F'));
                        @endphp
                        <h3 class="mb-0">{{ $date[0] }}</h3>
                        <p class="mb-0 text-capitalize">{{ $date[1] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="row push">
        <div class="col-12">
            <div itemscope itemtype="http://schema.org/Article" class="row g-0 bg-{{ $post->is_styled? $post->style_color: '' }} rounded-2">
                <div class="col-md-4 col-sm-5">
                    <a class="img-link img-link-simple" href="{{ url("post/{$post->slug}") }}">
                        <img itemprop="image" class="rounded mw-100 mb-2 mb-sm-0 lazy w-100" src="{{ Format::thumb($post->image, 466, 262) }}" alt="{{ $post->title }}" loading="lazy">
                    </a>
                </div>
                <div class="col-md-8 col-sm-7 ps-0 ps-sm-3">
                    <h4  class="mb-1">
                        <a class="text-dark" href="{{ url("post/{$post->slug}") }}" itemprop="headline name">{{ $post->title }}</a>
                    </h4>
                    <div class="fs-sm fw-semibold mb-1">
                        <a href="{{ url("user/{$post->user_id}") }}" class="author-link">{{ $post->user->name }}</a> · <span class="d-none" itemprop="datePublished">{{ $post->created_at}}</span> {{ Format::date($post->created_at) }} <!-- · <span class="fs-xs"><i class="fa fa-eye"></i> {{ $post->pageviews }}</span> -->
                    </div>
                    <p itemprop="description" class="fs-sm fw-medium mb-2">
                        {{ $post->getSummary(175) }}
                        
                    </p>
                </div>
            </div>
        </div>
    </div>
@endif