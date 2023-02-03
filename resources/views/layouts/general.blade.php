@php
    $currentLocale = app()->getLocale();
@endphp
<!doctype html>
<html lang="ru">
<head>
<title>@yield('title', nova_get_setting('title'))@hasSection('title') | {{ nova_get_setting('title') }}@endif</title>

    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-4743295385821060" crossorigin="anonymous"></script>
    <link rel="dns-prefetch" href="https://mc.yandex.ru">
    <link rel="dns-prefetch" href="https://www.google-analytics.com">
    <link rel="dns-prefetch" href="https://newshub.kz">
    <link rel="alternate" type="application/rss+xml" title="Новости Казахстана и пресс служб" href="https://newshub.kz/rss">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <!-- Open Graph Meta -->
    <meta property="og:type" content="website" />
    <meta property="og:title" content="@yield('title', nova_get_setting('title'))">
    <meta property="og:site_name" content="Newshub.kz">
    <meta property="og:description" content="@yield('description', nova_get_setting('description'))">
@hasSection('author')
    <meta name="author" content="@yield('author')">
@else
    <meta name="author" content="{{ config('app.name') }}">
@endif
@hasSection('image')
    <link rel="image_src" href="https://newshub.kz/storage/@yield('image')" />
    <meta property="og:image" content="https://newshub.kz/storage/@yield('image')">
    @else
    <link rel="image_src" href="https://newshub.kz/newshub.png" />
    <meta property="og:image" content="https://newshub.kz/newshub.png">
@endif
<meta property="og:image:width" content="554px" />
<meta property="og:image:height" content="350px" />
<meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image:type" content="image/jpeg" />
@hasSection('date')
    <meta property="article:published_time" content="@yield('date')">
@endif



  <meta name="smartbanner:title" content="NewsHub.kz">
  <meta name="smartbanner:author" content="Newshub.kz">
  <meta name="smartbanner:price" content="Бесплатно">
  <meta name="smartbanner:price-suffix-apple" content=" - в the App Store">
  <meta name="smartbanner:price-suffix-google" content=" - в Google Play">
  <meta name="smartbanner:icon-apple" content="https://newshub.kz/storage/ios.jpg">
  
  <meta name="smartbanner:icon-google" content="https://newshub.kz/storage/android.png">
  <meta name="smartbanner:button" content="Скачать">
  <meta name="smartbanner:button-url-apple" content="https://apps.apple.com/kz/app/newshub-kz/id1604898976">
  <meta name="smartbanner:button-url-google" content="https://play.google.com/store/apps/details?id=kz.newshub.app&utm_source=newshub.kz&utm_medium=organic&utm_content=app_install_header">
  <meta name="smartbanner:enabled-platforms" content="android,ios">
  <meta name="smartbanner:close-label" content="Закрыть">
  <!--<meta name="smartbanner:api" content="true">-->
  <!--<meta name="smartbanner:hide-ttl" content="2629746000">-->
  <!--<meta name="smartbanner:hide-path" content="/">-->
  <!--<meta name="smartbanner:disable-positioning" content="true">-->
  <!-- Enable for all platforms -->
  <!--<meta name="smartbanner:include-user-agent-regex" content=".*">-->
  <!-- iOS -->
  <!--<meta name="smartbanner:enabled-platforms" content="none">-->
  <!--<meta name="smartbanner:include-user-agent-regex" content="Mobile.*Safari">-->
  <!-- /iOS -->
  <!--<meta name="smartbanner:custom-design-modifier" content="ios">-->

    <meta name="description" content="@yield('description', nova_get_setting('description'))">
    <meta name="keywords" content="@yield('keywords', nova_get_setting('description'))">

    <meta property="yandex_recommendations_tag" content="ru_news"/>
    <link rel="canonical" href="{{ url()->current() }}">

    <link rel="alternate" hreflang="ru" href="https://newshub.kz/"/>
    <link rel="alternate" hreflang="kk" href="https://newshub.kz/kk"/>
    <link rel="alternate" hreflang="en" href="https://newshub.kz/en"/>
    
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Icons -->
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon.png') }}">
    <!-- END Icons -->

    <!-- Stylesheets -->
    <!-- Fonts and OneUI framework -->
    <!--<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"> -->
    <link rel="stylesheet" id="css-main" href="{{ asset('assets/css/oneui.min.css') }}">
    <link rel="stylesheet" id="css-sweetalert" href="{{ asset('assets/js/plugins/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" id="css-custom" href="{{ asset('assets/css/custom.css') }}?v=1.0.0.2">
    <!-- END Stylesheets -->
    
    <!-- Smartbanner -->
    <link rel="stylesheet" id="css-smartbanner" href="{{ asset('assets/css/smartbanner.min.css') }}">
    <!-- END Smartbannder -->
    
    
    <meta name="yandex-verification" content="82420371b2d6484f" />
    <meta name='wmail-verification' content='6fa0ea181f239e20342baa42820dd73b' />

    <style>
        .logo {
            background-image: url(/storage/{{ nova_get_setting("logo_{$currentLocale}") }});
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
            display: inline-block;
            height: 61px;
            width: 302px;
        }

        #recaptcha>div {
            width: auto !important;
            margin-top: 15px;
        }
        
        .fs-xs {
            font-size: 0.75rem!important;
        }

    </style>

    @stack('style')
    @stack('header')
    @yield('head')



</head>

<body>
    <!-- Page Container -->
    <section id="page-container"
        class="remember-theme enable-page-overlay side-scroll main-content-boxed @if ((int) request()->cookie("
        darkmode")) dark-mode sidebar-dark page-header-dark @endif">
        <!-- Header -->
        <header id="page-header">
            <!-- Header Content -->
            <div class="content-header">
                <!-- Left Section -->
                <div class="d-flex align-items-center">
                    <!-- Logo -->
                    <ul class="nav-main nav-main-horizontal nav-main-hover d-none d-lg-block me-2">
                        @foreach (\App\Models\Page::where('visibility', 1)->where('show_on_menu', 1)->get() as $page)
                            <li class="nav-main-item">
                                <a class="nav-main-link px-2" href="{{ url("page/{$page->slug}") }}">
                                    <span class="nav-main-link-name fw-bold">{{ $page->title }}</span>
                                </a>
                            </li>
                        @endforeach
                        <li class="nav-main-item">
                            <a class="nav-main-link px-2" href="https://adv.newshub.kz" target="_blank">
                                <span class="nav-main-link-name fw-bold">{{ __('Advertising') }}</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link px-2" href="{{ url('packages') }}">
                                <span class="nav-main-link-name fw-bold">{{ __('Packages') }}</span>
                            </a>
                        </li>
                    </ul>
                    <!-- END Logo -->
                </div>
                <!-- END Left Section -->

                <!-- Right Section -->
                <div class="d-flex align-items-center">
                    <!-- Navigation -->
                    <ul class="nav-main nav-main-horizontal nav-main-hover d-none d-lg-block me-2">
                        @if (!auth()->guest())
                            @if (auth()->user()->roles()->whereIn('slug', ['moderator', 'admin', 'press'])->exists())
                                <li class="nav-main-item">
                                    <a class="nav-main-link px-2 {{ request()->is('new')? "active": "" }}"
                                        href="{{ url('new') }}">
                                        <i class="nav-main-link-icon fa fa-rocket"></i>
                                        <span class="nav-main-link-name fw-bold">{{ __("Add Post") }}</span>
                                    </a>
                                </li>
                            @endif
                            <li class="nav-main-item">
                                <a class="nav-main-link px-2 {{ request()->is('new-vacancy')? "active": "" }}"
                                    href="{{ url('new-vacancy') }}">
                                    <i class="nav-main-link-icon fa fa-id-card-alt"></i>
                                    <span class="nav-main-link-name fw-bold">{{ __("Add Vacancy") }}</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                    <!-- END Navigation -->
                    {{-- <button type="button" class="btn btn-sm btn-alt-secondary me-2" data-toggle="layout"
                        data-action="dark_mode_toggle">
                        @if ((int) request()->cookie('darkmode'))
                        <i class="fa fa-sun"></i>
                        @else
                        <i class="far fa-moon"></i>
                        @endif
                    </button> --}}

                    {{-- <a href="https://t.me/" target="_blank" class="btn btn-sm btn-alt-secondary me-2">
                        <i class="fab fa-telegram-plane"></i>
                    </a>

                    <a href="https://twitter.com/" target="_blank" class="btn btn-sm btn-alt-secondary me-2">
                        <i class="si si-social-twitter"></i>
                    </a> --}}

                    @if (Auth::guest())
                    <a class="btn btn-sm btn-alt-secondary me-2"
                        href="{{ url('/login') }}">{{ __('Login') }}</a>
                    <a class="btn btn-sm btn-alt-secondary me-2"
                        href="{{ url('/register') }}">{{ __('Register') }}</a>
                    @else
                    <div class="dropdown d-inline-block me-2">
                        <button type="button" class="btn btn-sm btn-alt-secondary d-flex align-items-center"
                            id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <div class="bg-image bg-image-center img-avatar img-avatar16 rounded-circle" style="background-image: url({{ auth()->user()->getAvatar() }});"></div>
                            {{-- <img class="rounded-circle"
                                src="{{ auth()->user()->getAvatar() }}" style="width: 21px;"> --}}
                            <span class="d-none d-sm-inline-block ms-2">
                                {{ auth()->user()->getName() }}
                                @if (auth()->user()->isPress())
                                <span class="badge badge-pill bg-primary ms-1"><i class="fa fa-coins"></i>
                                    {{ Format::num(auth()->user()->balance) }}</span>
                                @endif
                            </span>
                            <i class="fa fa-fw fa-angle-down d-none d-sm-inline-block ms-1 mt-1"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-end p-0 border-0"
                            aria-labelledby="page-header-user-dropdown">
                            <div class="p-3 text-center bg-primary-dark rounded-top">
                            
                                <div class="bg-image bg-image-center img-avatar img-avatar48 img-avatar-thumb" style="background-image: url({{ auth()->user()->getAvatar() }});"></div>
                                {{-- <img class="img-avatar img-avatar48 img-avatar-thumb"
                                    src="{{ auth()->user()->getAvatar() }}"> --}}
                                <p class="mt-2 mb-0 text-white font-w500">{{ auth()->user()->getName() }}</p>
                                @if (auth()->user()->isPress())
                                <p class="mt-2 mb-0 text-white font-w500"><span
                                        class="badge badge-pill bg-primary ms-1"><i class="fa fa-coins"></i>
                                        {{ number_format(auth()->user()->balance) }}</span></p>
                                @endif
                            </div>
                            <div class="p-2 fw-sm">
                                @if (!auth()->guest())
                            @if (auth()->user()->roles()->whereIn('slug', ['moderator', 'admin', 'press'])->exists())
                                    <a class="dropdown-item d-flex align-items-center justify-content-between" id="mobile-link"
                                        href="{{ url('new') }}">
                                        <span class="fs-sm font-w500">{{ __("Add Post") }}</span>
                                    </a>
                            @endif
                                <a class="dropdown-item d-flex align-items-center justify-content-between" id="mobile-link"
                                    href="{{ url('new-vacancy') }}">
                                    <span class="fs-sm font-w500">{{ __("Add Vacancy") }}</span>
                                </a>
                        @endif
                        <div role="separator" class="dropdown-divider"></div>
                                @if (auth()->user()->isAdmin() || auth()->user()->isModerator())
                                <a class="dropdown-item d-flex align-items-center justify-content-between"
                                    href="{{ url(config('nova.path')) }}">
                                        <span class="fs-sm font-w500">
                                        {{ __('Admin Panel') }}</span>
                                </a>
                                @endif
                                <a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ url('feed') }}">
                                    <span class="fs-sm font-w500">
                                        {{ __('My Feed') }}
                                    </span>
                                </a>
                                @if (auth()->user()->isPress())
                                    <a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ url('actions') }}">
                                        <span class="fs-sm font-w500">
                                            {{ __('Actions') }}
                                        </span>
                                    </a>
                                    <a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ url('workspace') }}">
                                        <span class="fs-sm font-w500">
                                            {{ __('Workspace') }}
                                        </span>
                                    </a>
                                @endif
                                <a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ url('user/' . auth()->user()->id) }}">
                                    <span class="fs-sm font-w500">
                                        {{ __('Profile') }}
                                    </span>
                                </a>
                                <a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ url('settings') }}">
                                    <span class="fs-sm font-w500">
                                        {{ __('Settings') }}
                                    </span>
                                </a>
                                <div role="separator" class="dropdown-divider"></div>
                                <a class="dropdown-item d-flex align-items-center justify-content-between"
                                    href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <span class="fs-sm font-w500">
                                        {{ __('Logout') }}
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown d-inline-block me-2">
                        <button type="button" class="btn btn-sm btn-alt-secondary" id="page-header-notifications-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-fw fa-bell"></i>
                            @if (auth()->user()->unreadNotifications()->where('created_at', '<=', \Carbon\Carbon::now())->exists())
                                <span class="badge rounded-pill bg-primary">{{ auth()->user()->unreadNotifications()->where('created_at', '<=', \Carbon\Carbon::now())->count() }}</span>
                            @endif
                            {{-- <span class="text-{{ auth()->user()->unreadNotifications()->where('created_at', '<=', \Carbon\Carbon::now())->exists()? 'primary': 'secondary' }}">•</span> --}}
                        </button>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0 border-0 fs-sm"
                            aria-labelledby="page-header-notifications-dropdown" style="">
                            <div class="p-2 bg-body-light border-bottom d-flex align-items-center justify-content-between rounded-top">
                                <h5 class="dropdown-header">{{ __('Notifications') }}</h5>
                                <a href="{{ route('notifications') }}" class="fs-sm fw-medium text-muted">{{ __('View all') }}</a>
                            </div>
                            <ul class="nav-items mb-0">
                                @foreach(auth()->user()->notifications()->where('created_at', '<=', \Carbon\Carbon::now())->limit(5)->get() as $notification)
                                    @if (!empty($notification->data['post_id']))
                                        @php
                                            $notificationPost = App\Models\Post::find($notification->data['post_id']);
                                            if (!$notificationPost) {
                                                $notification->markAsRead();
                                            }
                                        @endphp
                                        @continue(!$notificationPost)
                                        <li>
                                            <a class="text-dark d-flex py-2" href="{{ url("post/{$notificationPost->slug}") }}">
                                                <div class="flex-shrink-0 me-2 ms-3">
                                                    <i class="fa fa-fw fa-check-circle text-success"></i>
                                                </div>
                                                <div class="flex-grow-1 pe-2">
                                                    <div class="fw-semibold">{{ __('New Post') }}: {{ $notificationPost->title }}</div>
                                                    <span class="fw-medium text-muted">{{ $notification->created_at->diffForHumans() }}</span>
                                                </div>
                                            </a>
                                        </li>
                                    @elseif(!empty($notification->data['package']))
                                        <li>
                                            <a class="text-dark d-flex py-2" href="{{ url("package/{$notification->data['package']}") }}">
                                                <div class="flex-shrink-0 me-2 ms-3">
                                                    <i class="fa fa-fw fa-check-circle text-success"></i>
                                                </div>
                                                <div class="flex-grow-1 pe-2">
                                                    <div class="fw-semibold">{{ __('Your service package ends in 30 days. We recommend extending services in advance.') }}</div>
                                                    <span class="fw-medium text-muted">{{ $notification->created_at->diffForHumans() }}</span>
                                                </div>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                            {{-- <div class="p-2 border-top text-center">
                                <a class="d-inline-block fw-medium" href="javascript:void(0)">
                                    <i class="fa fa-fw fa-arrow-down me-1 opacity-50"></i> {{ __('Load More') }}..
                                </a>
                            </div> --}}
                        </div>
                    </div>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    @endif

                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn btn-sm btn-alt-secondary d-flex align-items-center text-capitalize"
                            id="page-locale-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            {{ LaravelLocalization::getCurrentLocaleNative() }}
                            <i class="fa fa-fw fa-angle-down d-none d-sm-inline-block ms-1 mt-1"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-end p-0 border-0"
                            aria-labelledby="page-locale-dropdown">
                            <div class="p-2 fw-sm">
                                @foreach(LaravelLocalization::getSupportedLocales() as $code => $locale)
                                    <a class="dropdown-item d-flex align-items-center justify-content-between" hreflang="{{ $code }}" href="{{ LaravelLocalization::getLocalizedURL($code, null, [], true) }}">
                                        <span class="fs-sm font-w500 text-capitalize">
                                            {{ $locale['native'] }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Right Section -->
            </div>
            <!-- END Header Content -->
        </header>
        <!-- END Header -->

        <!-- Main Container -->
        <main id="main-container">
                       
            <div class="logo-container">
                <div class="content py-2 d-flex flex-md-nowrap flex-wrap align-items-center justify-content-center justify-content-md-between">
                    <div class="d-flex align-items-center me-0 me-md-3">
                        <a href="{{ url('/') }}">
                            <div class="logo"></div>
                        </a>
                    </div>
                    
                    @include('course.money')

                </div>
            </div>

            <div class="bg-body-extra-light py-2 push shadow-sm" style="z-index: 1;">
                <div class="content pt-0">
                    <div class="d-lg-none">
                        <button id="mobile-menu" type="button" class="btn w-100 d-flex justify-content-between align-items-center"
                            data-toggle="class-toggle" data-target="#horizontal-navigation-hover-normal" data-class="d-none">
                            {{ __('Menu') }}
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>
                    <div id="horizontal-navigation-hover-normal" class="d-none d-lg-block mt-2 mt-lg-0">
                        <ul class="nav-main nav-main-horizontal nav-main-hover">
                            <li class="nav-main-item">
                                <a class="nav-main-link px-3" href="{{ url('/') }}">
                                    <span class="nav-main-link-name">{{ __("Home") }}</span>
                                    {{-- <span class="nav-main-link-badge badge rounded-pill bg-primary">5</span> --}}
                                </a>
                            </li>
                           
                            @foreach(\App\Models\Category::where('show_on_menu', 1)->get() as $category)
                                <li class="nav-main-item">
                                    <a class="nav-main-link px-3 @if ($category->hasSub()) nav-main-link-submenu @endif" @if ($category->hasSub()) data-toggle="submenu" @endif aria-haspopup="true"
                                        aria-expanded="false" href="{{ url("category/{$category->slug}") }}">
                                        <span class="nav-main-link-name">{{ $category->name }}</span>
                                    </a>
                                    @if ($category->hasSub())
                                        <ul class="nav-main-submenu">
                                            @foreach($category->getSub() as $subCategory)
                                                <li class="nav-main-item">
                                                    <a class="nav-main-link px-3" href="{{ url("category/{$subCategory->slug}") }}">
                                                        <span class="nav-main-link-name">{{ $subCategory->name }}</span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                            <li class="nav-main-item">
                                <a class="nav-main-link px-3" aria-haspopup="true"
                                    aria-expanded="false" href="{{ url('users') }}">
                                    <span class="nav-main-link-name">{{ __("Press Releases") }}</span>
                                </a>
                                <ul class="nav-main-submenu">
                                    @foreach(\App\Models\UserCategory::all() as $userCategory)
                                        <li class="nav-main-item">
                                            <a class="nav-main-link px-3" href="{{ url("users/{$userCategory->slug}") }}">
                                                <span class="nav-main-link-name">{{ $userCategory->name }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                            @if (!auth()->guest() && auth()->user()->isAdmin())
                            <li class="nav-main-item">
                                <a class="nav-main-link px-3" aria-haspopup="true"
                                    aria-expanded="false" href="{{ url('map') }}">
                                    <span class="nav-main-link-name">{{ __("Media Map") }}</span>
                                </a>
                            </li>
                            @endif
                            <li class="nav-main-item">
                                <a class="nav-main-link px-3" aria-haspopup="true"
                                    aria-expanded="false" href="{{ url('vacancies') }}">
                                    <span class="nav-main-link-name">{{ __("Vacancies") }}</span>
                                </a>
                            </li>
                             @if (!auth()->guest() && auth()->user()->isUser())
                                <li class="nav-main-item">
                                    <a class="nav-main-link px-3" href="{{ url('/feed') }}">
                                        <span class="nav-main-link-name">{{ __("News Feed") }}</span>
                                    </a>
                                </li>
                            @endif
                            <li class="nav-main-item ms-lg-auto d-lg-none">
                                <a class="nav-main-link px-3" aria-haspopup="true"
                                    aria-expanded="false" href="{{ url('search') }}">
                                    <i class="nav-main-link-icon fa fa-search"></i>
                                    <span class="nav-main-link-name">{{ __("Search") }}</span>
                                </a>
                            </li>
                            <li class="nav-main-item ms-lg-auto d-none d-lg-inline-block">
                                <a class="nav-main-link px-3 nav-main-link-submenu" data-toggle="submenu-extra" aria-haspopup="true" aria-expanded="false" href="#">
                                    <i class="nav-main-link-icon fa fa-search"></i>
                                    <span class="nav-main-link-name d-lg-none">{{ __('Search') }}</span>
                                </a>
                                <div class="nav-main-submenu-extra nav-main-submenu-right">
                                    <form class="d-none d-md-inline-block" action="{{ route('search') }}">
                                        <div class="input-group">
                                            <input type="search" class="form-control form-control-alt" placeholder="{{ __('Search') }}.." name="q" value="{{ request()->q }}">
                                            <button class="btn btn-alt-primary border-0"><i class="fa fa-fw fa-search"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            
            @include('blocks.banner', ['location' => 'header']) 
                

            

            <!-- Page Content -->
            @yield('page-content')

            <!-- END Page Content -->
        </main>
        <!-- END Main Container -->

        <!-- Footer -->
        <footer id="page-footer" class="bg-gray-darker text-gray-lighter" style="z-index: 1;">
            <div class="content py-4">
                <div class="row items-push fs-sm border-bottom pt-4">
                    <div class="col-md-6">
                        <div class="logo mb-4"></div>

                       
                        
                        <h4>{{ __("Follow Us") }}</h4>
                        <a class="text-white" href="{{ nova_get_setting('facebook_url') }}" target="_blank"><i class="fab fa-facebook me-2" style="font-size: 24px;"></i></a>
                        <a class="text-white" href="{{ nova_get_setting('twitter_url') }}" target="_blank"><i class="fab fa-twitter me-2" style="font-size: 24px;"></i></a>
                        <a class="text-white" href="{{ nova_get_setting('instagram_url') }}" target="_blank"><i class="fab fa-instagram me-2" style="font-size: 24px;"></i></a>
                        <a class="text-white" href="{{ nova_get_setting('vk_url') }}" target="_blank"><i class="fab fa-vk me-2" style="font-size: 24px;"></i></a>
                        <a class="text-white" href="{{ nova_get_setting('telegram_url') }}" target="_blank"><i class="fab fa-telegram me-2" style="font-size: 24px;"></i></a>
                        <a class="text-white" href="{{ nova_get_setting('youtube_url') }}" target="_blank"><i class="fab fa-youtube me-2" style="font-size: 24px;"></i></a>
                         
                        <!-- <form class="push">
                            <div class="input-group  w-50">
                                <input type="email" class="form-control form-control-alt" id="dm-gs-subscribe-email"
                                    name="dm-gs-subscribe-email" placeholder="{{ __('Email') }}">
                                <button type="submit" class="btn btn-alt-primary">{{ __('Subscribe') }}</button>
                            </div>
                        </form> -->
                    <br>
                    <p style="font-size: 12px;">{{ __("Footer Text") }}</p>  
                        
                    </div>

                    <div class="col-sm-6 col-md-3 footer">
                        <h3>{{ __("Categories") }}</h3>
                        <ul class="list list-simple-mini">
                            @foreach(\App\Models\Category::where('show_on_menu', 1)->get() as $category)
                                <li>
                                    <a class="fw-semibold" href="{{ url("category/{$category->slug}") }}">
                                        <i class="fa fa-fw fa-link text-primary-lighter me-1"></i> {{ $category->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-sm-6 col-md-3 footer">
                        <h3>{{ __("Navigation") }}</h3>
                        <ul class="list list-simple-mini">
                            <li>
                                <a class="fw-semibold" href="{{ url('page/contact') }}">
                                    <i class="fa fa-fw fa-link text-primary-lighter me-1"></i> Контакты
                                </a>
                            </li>
                            <li>
                                <a class="fw-semibold" href="{{ route('journalists') }}">
                                    <i class="fa fa-fw fa-link text-primary-lighter me-1"></i> Журналисты
                                </a>
                            </li>
                            <li>
                                <a class="fw-semibold" href="{{ url('packages') }}">
                                    <i class="fa fa-fw fa-link text-primary-lighter me-1"></i> Тарифы
                                </a>
                            </li>
                            <li>
                                <a class="fw-semibold" href="{{ url('page/about-project') }}">
                                    <i class="fa fa-fw fa-link text-primary-lighter me-1"></i> О проекте
                                </a>
                            </li>
                            <li>
                                <a class="fw-semibold" href="https://adv.newshub.kz/">
                                    <i class="fa fa-fw fa-link text-primary-lighter me-1"></i> Реклама
                                </a>
                            </li>
                            
                            <li>
                                <a class="fw-semibold" href="{{ url('page/terms-conditions') }}">
                                    <i class="fa fa-fw fa-link text-primary-lighter me-1"></i> Пользовательское соглашение
                                </a>
                            </li>
                            
                            
                            
                        </ul>
                    </div>
                </div>
                <div class="row fs-sm pt-4">
                    <div class="col-sm-6 order-sm-2 py-1 text-center text-sm-end">
                        <a href="https://play.google.com/store/apps/details?id=kz.newshub.app&utm_source=newshub.kz&utm_medium=organic"><img alt="Google Play" class="" src="https://newshub.kz/storage/google-play.svg" data-src="https://newshub.kz/storage/google-play.svg"></a>
                        <a href="https://apps.apple.com/kz/app/newshub-kz/id1604898976">
                        <img alt="App Store" class="" src="https://newshub.kz/storage/app-store.svg" data-src="https://newshub.kz/storage/app-store.svg">
                        </a>
                        

                    </div>
                    <div class="col-sm-6 order-sm-1 py-1 text-center text-sm-start">
                        Copyright <span data-toggle="year-copy" class="js-year-copy-enabled">2022</span> NewsHub - All Rights Reserved.
                    </div>
                </div>
            </div>
        </footer>
        <!-- END Footer -->
        @yield('footer')
    </section>
    <!-- END Page Container -->
    
    <!-- Smartbanner JS -->
    <script async src="{{ asset('assets/js/smartbanner.min.js') }}"></script>

    <!-- OneUI JS -->
    <script src="{{ asset('assets/js/oneui.app.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.cookie.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/jquery.maskedinput/jquery.maskedinput.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/magnific-popup/jquery.magnific-popup.min.js') }}"></script>
    <script async src="//cdn.jsdelivr.net/npm/vanilla-lazyload@17.5.0/dist/lazyload.min.js"></script>


    
    
    <!-- Custom JS -->
    <script async src="{{ asset('assets/js/custom.js') }}"></script>


    <script>
        let onLoadHandlerForConflictTesting = function() {};
        window.onload = onLoadHandlerForConflictTesting;
        document.addEventListener('smartbanner.view', function() { console.log('smartbanner.view'); });
        document.addEventListener('smartbanner.exit', function() { console.log('smartbanner.exit'); });
        document.addEventListener('smartbanner.clickout', function() { console.log('smartbanner.clickout'); });

        // Manual smartbanner population:
        //   1) set smartbanner:api meta tag to true above for manual smartbanner population
        //   2) Uncomment below
        //let apiHandler = function() { smartbanner.publish(); };
        //window.onload = apiHandler;
    </script>

    <script>
        window.lazyLoadOptions = {};
        window.addEventListener(
            "LazyLoad::Initialized",
            function (event) {
            window.lazyLoadInstance = event.detail.instance;
            },
            false
        );
    </script>

    <script>
    
        (function () {
            {{-- function _uiHandleNav() {
                let links = document.querySelectorAll('[data-toggle="submenu-extra"]');

                // When a submenu link is clicked
                if (links) {
                    links.forEach(link => {
                        link.addEventListener('click', e => {
                            e.preventDefault();

                            // Get main navigation
                            let mainNav =  link.closest('.nav-main');
                            
                            // Check if we are in horizontal navigation, large screen and hover is enabled
                            if (
                                !(
                                (window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth) > 991
                                && mainNav.classList.contains('nav-main-horizontal')
                                && mainNav.classList.contains('nav-main-hover')
                                )
                            ) {
                                // Get link's parent
                                let parentLi = link.closest('li');

                                if (parentLi.classList.contains('open')) {
                                    // If submenu is open, close it..
                                    parentLi.classList.remove('open');

                                    link.setAttribute('aria-expanded', 'false');
                                } else {
                                    // .. else if submenu is closed, close all other (same level) submenus first before open it
                                    link.closest('ul').children.forEach(el => {
                                        el.classList.remove('open');
                                    })

                                    parentLi.classList.add('open');
                                    
                                    link.setAttribute('aria-expanded', 'true');
                                }
                            }

                            return false;
                        });
                    });
                }
            } --}}

            jQuery(document).on('click', '[data-action="dark_mode_toggle"]', async function () {
                var darkmode = $.cookie('darkmode');

                $.cookie('darkmode', parseInt(darkmode) ? 0 : 1, {
                    expires: 365,
                    path: '/'
                });

                if (parseInt(darkmode)) {
                    $(this).html('<i class="far fa-moon"></i>');
                    $('[rel="shortcut icon"]').prop('href', '{{ asset("icon.ico") }}');
                    $('[rel="icon"]').prop('href', '{{ asset("icon.ico") }}');
                    $('[rel="apple-touch-icon"]').prop('href', '{{ asset("icon.ico") }}');
                } else {
                    $(this).html('<i class="fa fa-sun"></i>');
                    $('[rel="shortcut icon"]').prop('href', '{{ asset("icon-dark.ico") }}');
                    $('[rel="icon"]').prop('href', '{{ asset("icon-dark.ico") }}');
                    $('[rel="apple-touch-icon"]').prop('href', '{{ asset("icon-dark.ico") }}');
                }
            }).on('click', '[data-toggle="submenu-extra"]', function(e) {
                e.preventDefault();

                if ($('.nav-main-submenu-extra').css('height') == '0px') {
                    $('.nav-main-submenu-extra').css({'height': 'auto'});
                } else {
                    $('.nav-main-submenu-extra').css({'height': '0'});
                }
            });
        })();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var sw = Swal.mixin({
            buttonsStyling: !1,
            target: "#page-container",
            customClass: {
                confirmButton: "btn btn-success btn-sm m-1",
                cancelButton: "btn btn-danger btn-sm m-1",
                input: "form-control"
            },
            cancelButtonText: "{{ __('Cancel') }}"
        });

        $(document).on('click', '[data-action="dark_mode_toggle"]', function () {
            localStorage.setItem("darkMode", $('#page-container').hasClass('dark-mode') ? 1 : 0);
        });

    </script>

    @stack('scripts')

    {{-- <script src="https://www.google.com/recaptcha/api.js" async defer></script> --}}

 
    <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-209786162-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-209786162-1');
</script>

<!-- Yandex.Metrika counter -->
<script type="text/javascript" async >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(86241754, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true
   });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/86241754" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

<script>
function getMoneyList(jQuery){
	$.get('/money-list', function(data){
		if(data.status == true){
			$('#money_usd').html(data.usd[0]);
			$('#money_euro').html(data.euro[0]);
			$('#money_rub').html(data.rub[0]);
			$('#money_cny').html(data.cny[0]);
		}
	});
}
</script>

<script>
    $(document).ready(function() {
        getMoneyList();
    });
</script>

    <script defer src="https://www.gstatic.com/firebasejs/9.2.0/firebase-app.js"></script>

    <script defer>

        // import { initializeApp } from "https://www.gstatic.com/firebasejs/9.2.0/firebase-app.js";
        // import { getMessaging, onMessage } from "https://www.gstatic.com/firebasejs/9.2.0/firebase-messaging.js";

        // Your web app's Firebase configuration
        firebase.initializeApp({
            apiKey: "AIzaSyD3JY3UVos0Xk1sk6VlTExFjpBXbsFNbW0",
            authDomain: "webpushkz.firebaseapp.com",
            projectId: "webpushkz",
            storageBucket: "webpushkz.appspot.com",
            messagingSenderId: "72631381469",
            appId: "1:72631381469:web:3c299d5e35e39fdac19a34"
        });


        if ('Notification' in window) {
            var messaging = firebase.messaging();
            if (Notification.permission === 'default') {
                initFirebaseMessagingRegistration();
            }
        }


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

</body>

</html>
