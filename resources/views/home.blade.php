@extends('layouts.full')

@section('content')
<div class="content overflow-hidden pt-3 px-0 px-sm-4">
    @include('blocks.alerts')
</div>

<div class="content overflow-hidden py-0 px-0 px-sm-4">
    <div class="mb-0">
        <div class="block-content">
            <!--<div class="d-flex align-items-center mb-3 overflow-hidden" style="height: 2rem;">
                <span class="fs-xs fw-bold d-inline-block py-1 px-3 bg-info rounded text-nowrap text-white text-uppercase me-3">{{ __("Breaking News") }}</span>
                <div class="js-slider w-100 overflow-hidden" data-dots="false" data-autoplay="true" data-arrows="false" data-autoplay-speed="3000">
                    @foreach($breaking as $post)
                        <div><a href="{{ url("post/{$post->slug}") }}" class="fw-semibold">{{ Str::limit($post->title, 100) }}</a></div>
                    @endforeach
                </div>
            </div> -->
            <div class="row gx-2 mb-4">
                <div class="col-md-6">
                    <div class="js-slider slick-nav-white" data-dots="false" data-autoplay="true" data-arrows="true" data-autoplay-speed="5000">
                        @foreach($slider as $post)
                            <div class="block-rounded mb-2" style="height: 442px;">
                                <div class="bg-image bg-image-center lazy" data-bg="{{ Format::thumb($post->image, 636, 442) }}" style="height: 442px;">
                                    <div class="bg-default-dark-op-60 rounded h-100">
                                        <div class="content p-3 mx-0 h-100 w-100 d-flex align-items-start flex-column">
                                            <a href="{{ url("category/{$post->categories()->first()->slug}") }}" class="badge bg-info text-white mb-auto fs-sm">{{ $post->categories()->first()->name }}</a>
                                            <a href="{{ url("post/{$post->slug}") }}">
                                                <h3 class="text-white mb-2">{{ $post->title }}</h3></a>
                                            <p class="bg-light py-1 px-2 rounded mb-0 fs-xs fw-semibold"><a href="{{ url("user/{$post->user_id}") }}">{{ $post->user->name }}</a> · {{ Format::date($post->created_at) }} · <!-- <span class="fs-xs"><i class="fa fa-eye"></i> {{ $post->pageviews }}</span> --> </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row g-2">
                        @foreach($featured as $post)
                            <div class="col-md-6 rounded" style="height: 217px;">
                                <div class="bg-image bg-image-center lazy" data-bg="{{ Format::thumb($post->image, 314, 217) }}" style="height: 217px;">
                                    <div class="bg-default-dark-op-60 rounded h-100">
                                        <div class="content p-3 mx-0 h-100 w-100 d-flex align-items-start flex-column">
                                            <a href="{{ url("category/{$post->categories()->first()->slug}") }}" class="badge bg-info text-white mb-auto fs-sm">{{ $post->categories()->first()->name }}</a>
                                            <a href="{{ url("post/{$post->slug}") }}"><p class="text-white mb-2 fs-sm">{{ Str::limit($post->title, 85) }}</p></a>
                                            <p class="bg-light py-1 px-2 rounded mb-0 fs-xs fw-semibold"><a href="{{ url("user/{$post->user_id}") }}">{{ $post->user->name }}</a> · {{ Format::date($post->created_at) }} · <!-- <span class="fs-xs"><i class="fa fa-eye"></i> {{ $post->pageviews }}</span> --> </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <!-- <div class="col-lg-12">
                    @foreach($widgets->where('location', 'home_with_sidebar')->skip(0)->take(1) as $widget)
                        @if (!in_array($widget->view, ['simple']))
                            <div class="d-flex justify-content-between align-items-center rounded py-2 px-3 mb-2">
                                <h5 class="fw-bold fs-4 mb-0">{{ $widget->getName() }}</h5>
                                <a href="{{ $widget->getUrl() }}" class="fs-sm">{{ __("View all") }}</a>
                            </div>
                        @endif
                        <div class="row mb-4">
                            @php
                                if ($widget->view == 'small') {
                                    $limit = 9;
                                } else if ($widget->view == 'medium') {
                                    $limit = 33;
                                } else if ($widget->view == 'large') {
                                    $limit = 3;
                                } else if ($widget->view == 'simple') {
                                    $limit = 3;
                                }

                                $query = $widget->rubric()->exists()? $widget->rubric->posts(): $widget->category->posts();
                                $news = $query
                                    // ->where('is_featured', 0)
                                    // ->where('is_slider', 0)
                                    ->where('status', 1)
                                    ->where('created_at', '<', \Carbon\Carbon::now())
                                    ->latest('created_at')
                                    ->groupBy('id')
                                    ->limit($limit)
                                    ->get();
                            @endphp

                            @if (in_array($widget->view, ['medium', 'large']))
                                @foreach($news->skip(0)->take(3) as $post)
                                    <div class="col-md-4">
                                        @include('blocks.post-block', [
                                            'post' => $post
                                        ])
                                    </div>
                                @endforeach
                            @endif

                            {{-- @if (in_array($widget->view, ['medium', 'small']))
                                @foreach($news->skip($widget->view == 'medium'? 3: 0)->take(9) as $post)
                                    <div class="col-md-4 mb-2">
                                        @include('blocks.post-mini', [
                                            'post' => $post
                                        ])
                                    </div>
                                @endforeach
                            @endif --}}

                            @if (in_array($widget->view, ['simple']))
                                <div class="col-md-12">
                                    <div class="bg-success-light rounded-2 pt-3 pb-1">
                                        <div class="d-flex justify-content-between px-3 mb-3">
                                            <span class="fs-4">{{ __('Events') }}</span>
                                            @if (!auth()->guest() && auth()->user()->roles()->whereIn('slug', ['moderator', 'admin', 'press'])->exists())
                                                <a href="{{ url('new') }}" class="btn btn-sm btn-light">{{ __('Publish') }}</a>
                                            @endif
                                        </div>
                                        @foreach($news->skip(0)->take(3) as $post)
                                            @include('blocks.post-simple', [
                                                'post' => $post
                                            ])
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @include('blocks.banner', ['location' => "home.{$widget->category->slug}"])
                        </div>
                    @endforeach
                </div> -->
                <div class="col-lg-4">
                    <div class="d-flex justify-content-between align-items-center  rounded py-2 px-3 mb-2">
                        <h5 class="fw-bold fs-4 mb-0">{{ __("Last News") }} <small></small></h5>
                        <a href="{{ $widget->getUrl() }}" class="fs-sm">{{ __("View all") }}</a>
                    </div>

                    @php
                        $lastPostDates = [];
                    @endphp
                    <div class="px-2 mb-3 overflow-y-auto p-scroll d-none d-lg-block" style="max-height: 1200px;">
                        @foreach($news->skip(0)->take(30) as $lastPost)
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
                    </div>
                    <div class="px-2 mb-3 d-flex d-lg-none justify-content-center flex-column">
                        <div id="more-posts">
                            @php
                                $lastPostDates = [];
                            @endphp
                            @foreach($news->skip(3)->take(10) as $lastPost)
                                @if (!$lastPost->created_at->isToday() && !in_array($lastPost->created_at->format('Y-m-d'), $lastPostDates))
                                    @php
                                        $lastPostDates[] = $lastPost->created_at->format('Y-m-d');
                                    @endphp
                                    <h5 class="mb-2">
                                        <span class="fw-bold me-2">{{ \Date::parse($lastPost->created_at)->format('j F') }}</span>
                                    </h5>
                                    <hr class="my-2"/>
                                @endif
                                <p class="mb-2"><span class="fw-semibold me-2">{{ $lastPost->created_at->format('H:i') }}</span><a href="{{ url("post/{$lastPost->slug}") }}" class="@if ($lastPost->pageviews > 100) fw-bold @else fw-normal @endif">{{ $lastPost->title }}</a></p>
                                {{-- @if (!$loop->last) <hr class="my-2"/> @endif --}}
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-alt-secondary" data-offset="13">{{ __('Load More') }}<i class="fa fa-angle-down ms-2"></i></button>
                    </div>
                </div>
                <div class="col-lg-4">
                    @php
                        $category = App\Models\Category::where('slug', 'articles')->first();
                        $post = $category? $category->posts()->latest('created_at')->first(): false;
                    @endphp

                    @if ($post)
                        <div class="d-flex justify-content-between align-items-center  rounded py-2 px-3 mb-2">
                            <h5 class="fw-bold fs-4 mb-0"><a href="/category/articles" style="color: #000;">{{ $category->name }}</a></h5>
                        </div>

                        @include('blocks.post-block', [
                            'post' => $post
                        ])
                    @endif

                    @foreach($widgets->where('location', 'home_with_sidebar')->skip(1)->take(1) as $widget)
                        @if (!in_array($widget->view, ['simple']))
                            <div class="d-flex justify-content-between align-items-center  rounded py-2 px-3 mb-2">
                                <h5 class="fw-bold fs-4 mb-0"><a href="{{ $widget->getUrl() }}" style="color: #000;">{{ $widget->getName() }}</a></h5>
                                <a href="{{ $widget->getUrl() }}" class="fs-sm">{{ __("View all") }}</a>
                            </div>
                        @endif
                        <div class="row mb-4">
                            @php
                                if ($widget->view == 'small') {
                                    $limit = 10;
                                } else if ($widget->view == 'medium') { 
                                    $limit = 12;
                                } else if ($widget->view == 'large') {
                                    $limit = 2;
                                } else if ($widget->view == 'simple') {
                                    $limit = 3;
                                }

                                $query = $widget->rubric()->exists()? $widget->rubric->posts(): $widget->category->posts();
                                $news = $query
                                    // ->where('is_featured', 0)
                                    // ->where('is_slider', 0)
                                    ->where('status', 1)
                                    ->where('created_at', '<', \Carbon\Carbon::now())
                                    ->latest('created_at')
                                    ->groupBy('id')
                                    ->limit($limit)
                                    ->get();
                            @endphp

                            @if (in_array($widget->view, ['medium', 'large']))
                                @foreach($news->skip(0)->take(2) as $post)
                                    <div class="col-md-6">
                                        @include('blocks.post-block', [
                                            'post' => $post
                                        ])
                                    </div>
                                @endforeach
                            @endif

                            @if (in_array($widget->view, ['medium', 'small']))
                                @foreach($news->skip($widget->view == 'medium'? 2: 0)->take(10) as $post)
                                    <div class="col-md-6 mb-2">
                                        @include('blocks.post-mini', [
                                            'post' => $post
                                        ])
                                    </div>
                                @endforeach
                            @endif

                            @if (in_array($widget->view, ['simple']))
                                <div class="col-md-12">
                                    <div class="bg-success-light rounded-2 pt-3 pb-1">
                                        <div class="d-flex justify-content-between px-3 mb-3">
                                            <span class="fs-4"><a href="{{ $widget->getUrl() }}">{{ __('Events') }}</a></span>
                                            @if (!auth()->guest() && auth()->user()->roles()->whereIn('slug', ['moderator', 'admin', 'press'])->exists())
                                                <a href="{{ url('new') }}" class="btn btn-sm btn-light">{{ __('Publish') }}</a>
                                            @endif
                                        </div>
                                        @foreach($news->skip(0)->take(3) as $post)
                                            @include('blocks.post-simple', [
                                                'post' => $post
                                            ])
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @include('blocks.banner', ['location' => "home.{$widget->category->slug}"])
                        </div>
                    @endforeach

                   

                    {{-- @if (!auth()->guest()) --}}
                        <div class="d-flex justify-content-between align-items-center  rounded py-2 px-3 mb-2">
                            <h5 class="fw-bold fs-4 mb-0">{{ __("Recommendations") }} <small></small></h5>
                        </div>

                        <div class="block-rounded overflow-hidden">
                            <div class="block-content">
                                @foreach(Util::getRecommendations() as $user)
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            @if ($user->avatar)
                                                <a href="{{ url("user/{$user->id}") }}">
                                                    <div class="bg-image bg-image-center rounded img-avatar img-avatar64" style="background-image: url('{{ $user->getAvatar() }}'); " alt="{{ $user->name }}"></div>
                                                </a>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <a href="{{ url("user/{$user->id}") }}" class="fw-bolder">
                                                    {{ $user->name }}
                                                </a>
                                            </div>
                                            @if (!auth()->guest() && auth()->user()->id != $user->id)
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    @if (auth()->user()->feeds()->where('user_id', $user->id)->exists())
                                                        <a href="{{ url("user/{$user->id}/unfollow") }}" class="btn btn-warning btn-sm">
                                                            <i class="fa fa-times me-1"></i>
                                                            {{ __("Unsubscribe") }}
                                                        </a>
                                                    @else
                                                        <a href="{{ url("user/{$user->id}/follow") }}" class="btn btn-primary btn-sm">
                                                            <i class="fa fa-plus me-1"></i>
                                                            {{ __("Subscribe") }}
                                                        </a>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <span class="fs-xs">
                                            {{ Str::limit($user->description, 200) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    {{-- @endif --}}
                </div>

                @include('sidebar')

                <div class="col-lg-12">
                    @foreach($widgets->where('location', 'home_with_sidebar')->skip(2)->take(1) as $widget)
                        @if (!in_array($widget->view, ['simple']))
                            <div class="d-flex justify-content-between align-items-center  rounded py-2 px-3 mb-2">
                                <h5 class="fw-bold fs-4 mb-0"><a href="{{ $widget->getUrl() }}" style="color: #000;">{{ $widget->getName() }}</a></h5>
                                <a href="{{ $widget->getUrl() }}" class="fs-sm">{{ __("View all") }}</a>
                            </div>
                        @endif
                        <div class="row mb-4">
                            @php
                                if ($widget->view == 'small') {
                                    $limit = 9;
                                } else if ($widget->view == 'medium') {
                                    $limit = 12;
                                } else if ($widget->view == 'large') {
                                    $limit = 3;
                                } else if ($widget->view == 'simple') {
                                    $limit = 3;
                                }

                                $query = $widget->rubric()->exists()? $widget->rubric->posts(): $widget->category->posts();
                                $news = $query
                                    // ->where('is_featured', 0)
                                    // ->where('is_slider', 0)
                                    ->where('status', 1)
                                    ->where('created_at', '<', \Carbon\Carbon::now())
                                    ->latest('created_at')
                                    ->groupBy('id')
                                    ->limit($limit)
                                    ->get();
                            @endphp

                            @if (in_array($widget->view, ['medium', 'large']))
                                @foreach($news->skip(0)->take(3) as $post)
                                    <div class="col-md-4">
                                        @include('blocks.post-block', [
                                            'post' => $post
                                        ])
                                    </div>
                                @endforeach
                            @endif

                            @if (in_array($widget->view, ['medium', 'small']))
                                @foreach($news->skip($widget->view == 'medium'? 3: 0)->take(9) as $post)
                                    <div class="col-md-4 mb-2">
                                        @include('blocks.post-mini', [
                                            'post' => $post
                                        ])
                                    </div>
                                @endforeach
                            @endif

                            @if (in_array($widget->view, ['simple']))
                                <div class="col-md-12">
                                    <div class="bg-success-light rounded-2 pt-3 pb-1">
                                        <div class="d-flex justify-content-between px-3 mb-3">
                                            <span class="fs-4">{{ __('Events') }}</span>
                                            @if (!auth()->guest() && auth()->user()->roles()->whereIn('slug', ['moderator', 'admin', 'press'])->exists())
                                                <a href="{{ url('new') }}" class="btn btn-sm btn-light">{{ __('Publish') }}</a>
                                            @endif
                                        </div>
                                        @foreach($news->skip(0)->take(3) as $post)
                                            @include('blocks.post-simple', [
                                                'post' => $post
                                            ])
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @include('blocks.banner', ['location' => "home.{$widget->category->slug}"])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="bg-gray-darker text-gray-lighter p-4">
            @foreach(\App\Models\Widget::where('location', 'home_full_width')->orderBy('position')->get() as $widget)
                @if ($widget->view == 'medium_alt')
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h2 class="fw-bold text-uppercase"><a href="{{ $widget->getUrl() }}" style="color: #fff;">{{ $widget->getName() }}</a></h2>
                        <a class="text-white" href="{{ $widget->getUrl() }}" class="fs-sm">{{ __("View all") }}</a>
                    </div>
                @else
                    @if (!in_array($widget->view, ['simple']))
                        <div class="d-flex justify-content-between align-items-center rounded py-2 px-3 mb-2">
                            <h5 class="fw-bold fs-4 mb-0">{{ $widget->getName() }}</h5>
                            <a class="text-white" href="{{ $widget->getUrl() }}" class="fs-sm">{{ __("View all") }}</a>
                        </div>
                    @endif
                @endif

                <div class="row mb-4">
                    @php
                        if ($widget->view == 'small') {
                            $limit = 10;
                        } else if ($widget->view == 'medium') {
                            $limit = 12;
                        } else if ($widget->view == 'medium_alt') {
                            $limit = 6;
                        } else if ($widget->view == 'large') {
                            $limit = 2;
                        } else if ($widget->view == 'simple') {
                            $limit = 3;
                        }

                        $query = $widget->rubric()->exists()? $widget->rubric->posts(): $widget->category->posts();
                        $news = $query
                            // ->where('is_featured', 0)
                            // ->where('is_slider', 0)
                            ->where('status', 1)
                            ->where('created_at', '<', \Carbon\Carbon::now())
                            ->latest('created_at')
                            ->groupBy('id')
                            ->limit($limit)
                            ->get();
                    @endphp

                    @if ($widget->view == 'medium_alt')
                        @foreach($news->skip(0)->take(1) as $post)
                            <div class="col-md-6">
                                @include('blocks.post-block-dark', [
                                    'post' => $post
                                ])
                            </div>
                        @endforeach

                        <div class="col-md-6">
                            @foreach($news->skip(1)->take(5) as $post)
                                <div class="mb-2">
                                    @include('blocks.post-mini-dark', [
                                        'post' => $post
                                    ])
                                </div>
                            @endforeach
                        </div>
                    @else
                        @if (in_array($widget->view, ['medium', 'large']))
                            @foreach($news->skip(0)->take(2) as $post)
                                <div class="col-md-6">
                                    @include('blocks.post-block-dark', [
                                        'post' => $post
                                    ])
                                </div>
                            @endforeach
                        @endif

                        @if (in_array($widget->view, ['medium', 'small']))
                            @foreach($news->skip($widget->view == 'medium'? 2: 0)->take(10) as $post)
                                <div class="col-md-6 mb-2">
                                    @include('blocks.post-mini-dark', [
                                        'post' => $post
                                    ])
                                </div>
                            @endforeach
                        @endif

                        @if (in_array($widget->view, ['simple']))
                            <div class="col-md-12">
                                <div class="bg-success-light rounded-2 pt-3 pb-1">
                                    <div class="d-flex justify-content-between px-3 mb-3">
                                        <span class="fs-4">{{ __('Events') }}</span>
                                        @if (!auth()->guest() && auth()->user()->roles()->whereIn('slug', ['moderator', 'admin', 'press'])->exists())
                                            <a href="{{ url('new') }}" class="btn btn-sm btn-light">{{ __('Publish') }}</a>
                                        @endif
                                    </div>
                                    @foreach($news->skip(0)->take(3) as $post)
                                        @include('blocks.post-simple', [
                                            'post' => $post
                                        ])
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif

                    <!-- @include('blocks.banner', ['location' => "home.{$widget->category->slug}"]) -->
                </div>
            @endforeach
        </div>

        <div class="block-content">
            <div class="row mb-4">
                <div class="col-lg-12">
                    @foreach($widgets->where('location', 'home_with_sidebar')->skip(3) as $widget)
                        @if (!in_array($widget->view, ['simple']))
                            <div class="d-flex justify-content-between align-items-center  rounded py-2 px-3 mb-2">
                                <h5 class="fw-bold fs-4 mb-0"><a href="{{ $widget->getUrl() }}" style="color: #000;">{{ $widget->getName() }}</a></h5>
                                <a href="{{ $widget->getUrl() }}" class="fs-sm">{{ __("View all") }}</a>
                            </div>
                        @endif
                        <div class="row mb-4">
                            @php
                                if ($widget->view == 'small') {
                                    $limit = 9;
                                } else if ($widget->view == 'medium') {
                                    $limit = 12;
                                } else if ($widget->view == 'large') {
                                    $limit = 3;
                                } else if ($widget->view == 'simple') {
                                    $limit = 3;
                                }

                                $query = $widget->rubric()->exists()? $widget->rubric->posts(): $widget->category->posts();
                                $news = $query
                                    // ->where('is_featured', 0)
                                    // ->where('is_slider', 0)
                                    ->where('status', 1)
                                    ->where('created_at', '<', \Carbon\Carbon::now())
                                    ->latest('created_at')
                                    ->groupBy('id')
                                    ->limit($limit)
                                    ->get();
                            @endphp

                            @if (in_array($widget->view, ['medium', 'large']))
                                @foreach($news->skip(0)->take(3) as $post)
                                    <div class="col-md-4">
                                        @include('blocks.post-block', [
                                            'post' => $post
                                        ])
                                    </div>
                                @endforeach
                            @endif

                            @if (in_array($widget->view, ['medium', 'small']))
                                @foreach($news->skip($widget->view == 'medium'? 3: 0)->take(9) as $post)
                                    <div class="col-md-4 mb-2">
                                        @include('blocks.post-mini', [
                                            'post' => $post
                                        ])
                                    </div>
                                @endforeach
                            @endif

                            @if (in_array($widget->view, ['simple']))
                                <div class="col-md-12">
                                    <div class="bg-success-light rounded-2 pt-3 pb-1">
                                        <div class="d-flex justify-content-between px-3 mb-3">
                                            <span class="fs-4">{{ __('Events') }}</span>
                                            @if (!auth()->guest() && auth()->user()->roles()->whereIn('slug', ['moderator', 'admin', 'press'])->exists())
                                                <a href="{{ url('new') }}" class="btn btn-sm btn-light">{{ __('Publish') }}</a>
                                            @endif
                                        </div>
                                        @foreach($news->skip(0)->take(3) as $post)
                                            @include('blocks.post-simple', [
                                                'post' => $post
                                            ])
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- @include('blocks.banner', ['location' => "home.{$widget->category->slug}"]) -->
                        </div>
                    @endforeach
                </div>

                @include('sidebar_alt')
            </div>
        </div>
    </div>
</div>

@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/slick-carousel/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/slick-carousel/slick-theme.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/plugins/slick-carousel/slick.min.js') }}"></script>
    <script>One.helpersOnLoad(['jq-slick']);</script>
    <script>
        $(document).on('click', '[data-offset]', function() {
            var morePostsBtn = $(this);
            var offset = morePostsBtn.data('offset');

            $.get(`/more-posts/${offset}`).then(function(response) {
                $('#more-posts').append(response);
                morePostsBtn.prop('disabled', false);
                morePostsBtn.data('offset', parseInt(offset) + 10);
            });
        });
    </script>

    <script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>
    <script>

        var firebaseConfig = {
            apiKey: "AIzaSyD3JY3UVos0Xk1sk6VlTExFjpBXbsFNbW0",
            authDomain: "webpushkz.firebaseapp.com",
            projectId: "webpushkz",
            storageBucket: "webpushkz.appspot.com",
            messagingSenderId: "72631381469",
            appId: "1:72631381469:web:3c299d5e35e39fdac19a34"
        };

        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();

        function initFirebaseMessagingRegistration() {
            messaging
                .requestPermission()
                .then(function () {
                    return messaging.getToken()
                })
                .then(function(token) {
                    console.log(token);

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url: '{{ route("save-token") }}',
                        type: 'POST',
                        data: {
                            token: token
                        },
                        dataType: 'JSON',
                        success: function (response) {
                            alert('Token saved successfully.');
                        },
                        error: function (err) {
                            console.log('User Chat Token Error'+ err);
                        },
                    });

                }).catch(function (err) {
                console.log('User Chat Token Error'+ err);
            });
        }

        messaging.onMessage(function(payload) {
            const noteTitle = payload.notification.title;
            const noteOptions = {
                body: payload.notification.body,
                icon: payload.notification.icon,
            };
            new Notification(noteTitle, noteOptions);
        });

    </script>
@endpush


