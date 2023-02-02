<div class="post-content mt-4" data-slug="{{ $post->slug }}" data-title="{{ $post->title }}" style="border-top: 3px solid #f6f7f9; padding-top: 25px;">
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
                    <button type="button" class="btn text-white me-1 mb-3" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}', 'Share This Post', 'width=640,height=450');return false" style="background-color: #3b5998;">
                        <i class="fab fa-facebook"></i>
                    </button>
                    <button type="button" class="btn text-white me-1 mb-3" onclick="window.open('https://twitter.com/share?url={{ url()->current() }}', 'Share This Post', 'width=640,height=450');return false" style="background-color: #55acee;">
                        <i class="fab fa-twitter"></i>
                    </button>
                    <button type="button" class="btn text-white me-1 mb-3" onclick="window.open('http://vk.com/share.php?url={{ url()->current() }}', 'Share This Post', 'width=640,height=450');return false" style="background-color: #4D76A1;">
                        <i class="fab fa-vk"></i>
                    </button>
                    <button type="button" class="btn text-white me-1 mb-3" onclick="window.open('https://t.me/share/url?url={{ url()->current() }}', 'Share This Post', 'width=640,height=450');return false" style="background-color: #08c;">
                        <i class="fab fa-telegram"></i>
                    </button>
                    <button type="button" class="btn text-white me-1 mb-3" onclick="window.open('https://api.whatsapp.com/send?text={{ url()->current() }}', 'Share This Post', 'width=640,height=450');return false" style="background-color: #3EBE2B;"><i class="fab fa-whatsapp"></i>
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
                <a href="https://www.facebook.com/newshub.kz" style="margin-right: 25px;" target="_blank"><img class="social-icon" src="/assets/facebook.svg" alt="facebook"> Facebook</a>
                <a href="https://news.google.com/publications/CAAqBwgKMOmarAsw5qXEAw?hl=en-NZ&gl=NZ&ceid=NZ%3Aen" target="_blank"><img class="social-icon" src="/assets/google_news.svg" alt="google news"> Google News</a> 
                </div>
                <p class="fs-xs">{{ __("Error in the text? Please let us know. Highlight the error and press Ctrl + Enter") }}</p>
              </div>
            </div>
        </div>

                
                {{-- <div class="bg-body-light d-inline-block rounded text-nowrap px-2 py-1 me-2 mb-2"><i class="fa fa-eye fa-fw me-1"></i>{{ $post->pageviews }}</div> --}}
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