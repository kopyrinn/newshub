@extends('layouts.app')

@section('title', $post->title)
@section('description', $post->getSummary())
@section('image', $post->image)
@section('keywords', $post->keywords)
@section('date', $post->created_at->format('c'))
@section('author', $post->user->name)


@push('header')
    {!! $schema->toScript() !!}
    <link rel="stylesheet" href="{{ asset('assets/css/print.css') . '?' . time() }}" type="text/css" media="print"> 
    <link href="{{ url("amp/post/{$post->slug}") }}" rel="amphtml">
@endpush




@section('content')
<div class="row">
    <div class="col-lg-12 mb-4 post-area">
        <div class="post-content" data-slug="{{ $post->slug }}" data-title="{{ $post->title }}">
            {{-- <div class="justify-content-start d-flex d-print-none">
                <nav class="flex-shrink-1 mb-2 mw-100" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt flex-nowrap">
                        <li class="breadcrumb-item d-flex text-nowrap">
                            <a class="link-fx" href="{{ url('/') }}">{{ __("Home") }}</a>
                        </li>
                        @if ($post->categories()->exists())
                            <li class="breadcrumb-item d-flex text-nowrap" aria-current="page">
                                <a class="link-fx" href="{{ url("category/{$post->categories()->first()->slug}") }}">
                                    {{ $post->categories()->first()->name }}
                                </a>
                            </li>
                        @endif
                        <li class="breadcrumb-item text-truncate" aria-current="page">
                            {{ $post->title }}
                        </li>
                    </ol>
                </nav>
            </div> --}}
            <div class="row">   
            <div class="col-lg-1">
            <div class="bg-body-light d-inline-block rounded px-2 py-1 me-2 mb-2 social">
                    <button type="button" class="btn text-white me-1 mb-3" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u={{ LaravelLocalization::getLocalizedURL(app()->getLocale()) }}', 'Share This Post', 'width=640,height=450');return false" style="background-color: #3b5998;">
                        <i class="fab fa-facebook"></i>
                    </button>
                    <button type="button" class="btn text-white me-1 mb-3" onclick="window.open('https://twitter.com/share?url={{ LaravelLocalization::getLocalizedURL(app()->getLocale()) }}', 'Share This Post', 'width=640,height=450');return false" style="background-color: #55acee;">
                        <i class="fab fa-twitter"></i>
                    </button>
                    <button type="button" class="btn text-white me-1 mb-3" onclick="window.open('http://vk.com/share.php?url={{ LaravelLocalization::getLocalizedURL(app()->getLocale()) }}', 'Share This Post', 'width=640,height=450');return false" style="background-color: #4D76A1;">
                        <i class="fab fa-vk"></i>
                    </button>
                    <button type="button" class="btn text-white me-1 mb-3" onclick="window.open('https://t.me/share/url?url={{ LaravelLocalization::getLocalizedURL(app()->getLocale()) }}', 'Share This Post', 'width=640,height=450');return false" style="background-color: #08c;">
                        <i class="fab fa-telegram"></i>
                    </button>
                    <button type="button" class="btn text-white me-1 mb-3" onclick="window.open('https://api.whatsapp.com/send?text={{ LaravelLocalization::getLocalizedURL(app()->getLocale()) }}', 'Share This Post', 'width=640,height=450');return false" style="background-color: #3EBE2B;"><i class="fab fa-whatsapp"></i>
                    </button>
                </div>
            </div>
                <div class="col-lg-7">
                    <article class="d-print-inline" itemscope itemtype="http://schema.org/Article">
                        <h2 class="mb-2" itemprop="headline">
                            {{ $post->title }}
                        </h2>
                        <div class="d-flex justify-content-between">
                            <div class="fs-xs fw-semibold d-print-none">
                                <div class="bg-body-light d-inline-block rounded text-nowrap px-2 py-1 me-2 mb-2"><a href="{{ url("user/{$post->user_id}") }}">{{ $post->user->name }}</a></div>
                                <div class="bg-body-light d-inline-block rounded text-nowrap px-2 py-1 me-2 mb-2">{{ Format::date($post->created_at) }}</div>
                                
                                @if ($post->categories()->exists() && $post->rubrics()->exists())
                                    <div class="bg-body-light d-inline-block rounded text-nowrap px-2 py-1 me-2 mb-2"><i class="fa fa-folder-open fa-fw me-1"></i>
                                        @foreach($post->rubrics()->get() as $rubric)
                                            <a href="{{ url("category/{$post->categories()->first()->slug}/{$rubric->slug}") }}">{{ $rubric->name }}</a>{{ !$loop->last? ",": "" }}
                                        @endforeach
                                    </div>
                                @endif
                                
                            </div>
                            <div class="fs-xs fw-semibold d-print-none">
                                @if (count($post->getTranslations('content')) > 1)
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm btn-alt-secondary dropdown-toggle" id="dropdown-default-alt-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="fs-sm font-w500 text-capitalize">{{ \LaravelLocalization::getSupportedLocales()[$post->getLocale()]['native'] }}</span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end fs-sm" aria-labelledby="dropdown-default-alt-primary">
                                            @foreach(LaravelLocalization::getSupportedLocales() as $code => $locale)
                                                @continue(empty($post->getTranslations('content')[$code]))
                                                <a class="dropdown-item" data-lang="{{ $code }}" data-slug="{{ $post->slug }}" href="javascript://"><span class="fs-sm font-w500 text-capitalize">{{ $locale['native'] }}</span></a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <p class="fs-normal d-print-inline" itemprop="articleBody">
                            {!! nl2br($post->getSummary()) !!}
                        </p>

                        @if ($post->image)
                            <div class="mb-4 d-flex justify-content-center flex-column">
                                <img class="rounded m-auto mw-100" src="{{ asset("storage/{$post->image}") }}" alt="" importance"high">
                                @if ($post->image_caption)
                                    <p class="mb-0 text-muted">Фото: {{ $post->image_caption }}</p>
                                @endif
                            </div>
                        @endif

                        <div class="mw-100 overflow-hidden d-print-inline" id="post-content">
                            {!! str_replace('<p>&nbsp;</p>', '', trim($post->content)) !!}
                        </div>
                        
                        
                        @if (auth()->guest() && $post->getFiles())
                            <div class="d-flex bg-body-light rounded px-3 pt-3 flex-wrap mb-4 d-print-none">
                                <p>Для скачивания файлов, необходима регистрация.</p>
                            </div>
                        @endif
                        

                        @if (!auth()->guest() && $post->getFiles())
                            <div class="d-flex bg-body-light rounded px-3 pt-3 flex-wrap mb-4 d-print-none">
                                @foreach ($post->getFiles() as $file)
                                    <a href="{{ route('file', ['slug' => $post->slug, 'name' => md5(basename($file->name))]) }}" class="btn btn-alt-primary btn-sm fw-bold mb-3 me-3 text-nowrap" download>
                                        <i class="fa fa-fw fa-cloud-download-alt me-1"></i>
                                        {{ $file->originalName }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                        
        <div class="block block-rounded block-bordered">
            <div class="block-content block-content-full justify-content-between align-items-center">
              <div>
                <div class="mb-2">
                {{ __("Follow News") }}
                </div>
                <div class="d-flex align-items-center justify-content-center mb-2">
                <a href="https://t.me/NewsHub_Channel" style="margin-right: 25px;" target="_blank"><img class="social-icon" src="/assets/telegram.svg" alt="telegram"> Telegram</a>
                <a href="https://www.facebook.com/newshub.kz" style="margin-right: 25px;" target="_blank"><img class="social-icon" src="/assets/facebook.svg" alt="telegram"> Facebook</a>
                <a href="https://news.google.com/publications/CAAqBwgKMOmarAsw5qXEAw?hl=en-NZ&gl=NZ&ceid=NZ%3Aen" target="_blank"><img class="social-icon" src="/assets/google_news.svg" alt="google news"> Google News</a>
                </div>
                <p class="fs-xs">{{ __("Error in the text? Please let us know. Highlight the error and press Ctrl + Enter") }}</p>
              </div>
            </div>
        </div>

                        
                        <!-- <div class="bg-body-light d-inline-block rounded text-nowrap px-2 py-1 me-2 mb-2"><i class="fa fa-eye fa-fw me-1"></i>{{ $post->pageviews }}</div> -->
<div class="bg-body-light d-inline-block rounded text-nowrap px-2 py-1 me-2 mb-2 tags">
                                    @foreach($post->getTags() as $tag)
                                        <a href="{{ route('tags', ['tag' => $tag]) }}">#{{ $tag }}</a>@if (!$loop->last),@endif
                                    @endforeach
                                </div>
                        
                    </article>

                    <div class="d-print-none">
                        @include('blocks.banner', ['location' => 'post.view'])
                    </div>
                </div>
                
                @include('sidebar', ['related' => $post])
            </div>
        </div>
    </div>

    <div class="modal" id="modal-report" tabindex="-1" role="dialog" aria-labelledby="modal-report" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-transparent mb-0">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">{{ __('Submit a grammar error') }}</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content fs-sm">
                        <input id="grammar-slug" type="hidden" name="post">
                        <p id="grammar-error" class="fw-medium border-start border-2 ps-2"></p>
                        <textarea id="grammar-fix" class="form-control form-control-alt mb-3" placeholder="{{ __('Suggest a fix') }}"></textarea>
                    </div>
                    <div class="block-content block-content-full text-end bg-body">
                        <button type="button" class="btn btn-sm btn-alt-secondary me-1" data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="button" class="btn btn-sm btn-primary" id="grammar-send">{{ __('Send') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        (function() {
            const aboutUsObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if(entry.isIntersecting) {
                        var slug = $(entry.target).data('slug');
                        var title = $(entry.target).data('title');
                        document.title = title;
                        history.pushState(slug, title, `{{ url('/') }}/post/${slug}`);
                    }
                });
            }, {});

            if (!!window.IntersectionObserver) {
                aboutUsObserver.observe($(".post-content")[0]);
            }

            $(document).on('click', '#grammar-send', function(e) {
                $.ajax({
                    url: '{{ url("post/grammar") }}',
                    method: 'post',
                    dataType: 'json',
                    data: {
                        slug: $('#grammar-slug').val(),
                        error: $('#grammar-error').text(),
                        suggestion: $('#grammar-fix').val(),
                    }
                }).then(function(response) {
                    $('#modal-report').modal('hide');
                    sw.fire({
                        title: "{{ __('Thank you for your attention, the error will be corrected in the near future.') }}",
                        icon: 'success',
                    });
                });
            });

            $(document).on('keyup', function(event) {
                if (event.ctrlKey && [10, 13].includes(event.keyCode)) {
                    var selection = window.getSelection();
                    var baseNode = $(selection.baseNode);
                    var slug = baseNode.closest('.post-content').data('slug');
                    $('#grammar-slug').val(slug);
                    $('#grammar-error').text(selection.toString());
                    $('#modal-report').modal('show');
                }
            });
 
            var prevLoading = false;
            var currentSlug = "";
            //var stickyTop = $('.sticky').offset().top;

            $(document).on('click', '[data-lang]', function(e) {
                var postLocale = $(this).data('lang');
                var postSlug = $(this).data('slug');
                var postContent = $(this).closest('.post-content');

                $.getJSON(`{{ url("post/translate") }}/${postLocale}/${postSlug}`, function(response) {
                    if (response.status) {
                        var markup = $(response.markup);
                        postContent.replaceWith(markup);
                        //document.title = title;
                        lazyLoadInstance.update();
                    }
                });
            });

            $(window).scroll(function() {
                //var windowTop = $(window).scrollTop();
                //if ($(window).width() > 974 && stickyTop < windowTop && $(".sidebar-posts").height() + $(".related-posts").offset().top - $(".sticky").height() > //windowTop) {
                //    $('.sticky').css('position', 'fixed');
                //    $('.sticky').css('top', '5vh');
                //    $('.sticky').css('max-width', $(".sidebar-posts").width());
                //} else {
                //    $('.sticky').css('position', 'relative');
                //    $('.sticky').css('top', '0');
                //    $('.sticky').css('max-width', 'unset');
                //}

                if ($(window).scrollTop() >= $('#page-footer').offset().top - $(window).height() - 100 && !prevLoading) {
                    prevLoading = true;
                    var lastArticle = $('.post-content').last();
                    var prevSlug = lastArticle.data('slug');
                    $.getJSON(`{{ url("post/prev") }}/${prevSlug}`, function(response) {
                        if (response.status) {
                            prevLoading = false;
                            var markup = $(response.markup);
                            var slug = markup.data('slug');
                            var title = markup.find('article h2[itemprop="headline"]').text();
                            $('.post-area').append(markup);

                            if (!!window.IntersectionObserver) {
                                aboutUsObserver.observe(markup[0]);
                            }

                            lazyLoadInstance.update();
                        }
                    });
                }
            });
        })();
    </script>
@endpush