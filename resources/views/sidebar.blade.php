@php
    $category = App\Models\Category::where('slug', 'intervyu')->first();
    $post = $category? $category->posts()->latest('created_at')->first(): false;
@endphp

<div class="col-lg-4 d-print-none mb-3 sidebar-posts">

    @if (empty($related))
        <div class="d-flex justify-content-between align-items-center rounded py-2 px-3 mb-2">
            <h5 class="fw-bold fs-4 mb-0"><a href="/category/intervyu" style="color: #000;">{{ __("Interview") }}</a> <small></small></h5>
        </div>

        @if ($post)
            @include('blocks.post-block', [
                'post' => $post
            ])
        @endif


        @include('blocks.banner', ['location' => 'sidebar.view'])

        <div class="d-flex justify-content-between align-items-center rounded py-2 px-3 mb-2">
            <h5 class="fw-bold fs-4 mb-0">{{ __("Popular Posts") }} <small></small></h5>
        </div>

        <div class="block-rounded overflow-hidden">
            <ul class="nav nav-tabs nav-tabs-alt" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="btabs-alt-week-tab" data-bs-toggle="tab" data-bs-target="#btabs-alt-week" role="tab" aria-controls="btabs-alt-week" aria-selected="true">{{ __('This week') }}</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="btabs-alt-month-tab" data-bs-toggle="tab" data-bs-target="#btabs-alt-month" role="tab" aria-controls="btabs-alt-month" aria-selected="false">{{ __('This month') }}</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="btabs-alt-all-tab" data-bs-toggle="tab" data-bs-target="#btabs-alt-all" role="tab" aria-controls="btabs-alt-all" aria-selected="false">{{ __('All time') }}</button>
                </li>
            </ul>
            <div class="block-content tab-content">
                <div class="tab-pane active" id="btabs-alt-week" role="tabpanel" aria-labelledby="btabs-alt-week-tab">
                    @foreach(\App\Models\Post::where('status', 1)->where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-1 week')))->orderBy('pageviews', 'DESC')->limit(5)->get() as $post)
                        @include('blocks.post-mini', [
                            'post' => $post
                        ])
                    @endforeach
                </div>
                <div class="tab-pane" id="btabs-alt-month" role="tabpanel" aria-labelledby="btabs-alt-month-tab">
                    @foreach(\App\Models\Post::where('status', 1)->where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-1 month')))->orderBy('pageviews', 'DESC')->limit(5)->get() as $post)
                        @include('blocks.post-mini', [
                            'post' => $post
                        ])
                    @endforeach
                </div>
                <div class="tab-pane" id="btabs-alt-all" role="tabpanel" aria-labelledby="btabs-alt-all-tab">
                    @foreach(\App\Models\Post::where('status', 1)->orderBy('pageviews', 'DESC')->limit(5)->get() as $post)
                        @include('blocks.post-mini', [
                            'post' => $post
                        ])
                    @endforeach
                </div>
            </div>
        </div>
        

                    
                    
    @endif

    @if (!empty($related))

        @if ($related->categories()->exists())
            <div class="related-posts">
                <div class="d-print-none">
                    @include('blocks.related', [
                        'category' => $related->categories()->first(),
                        'id' => $related->id,
                    ])
                </div>
            </div>
        @endif
    @endif
    
</div>