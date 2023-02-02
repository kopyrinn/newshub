@extends('layouts.app')

@section('title', __("Edit Post"))
@section('description', __("Edit Post"))

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="justify-content-start d-flex">
            <nav class="flex-shrink-1 mb-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt flex-nowrap">
                    <li class="breadcrumb-item d-flex text-nowrap">
                        <a class="link-fx" href="{{ url('/') }}">{{ __("Home") }}</a>
                    </li>
                    <li class="breadcrumb-item d-flex text-nowrap">
                        <a class="link-fx" href="{{ url('workspace') }}">{{ __("Workspace") }}</a>
                    </li>
                    <li class="breadcrumb-item text-truncate" aria-current="page">
                        {{ __("Edit Post") }}
                    </li>
                </ol>
            </nav>
        </div>

        <form action="{{ route("workspace.post", ['slug' => $post->slug]) }}" method="POST" id="newSave">
            @csrf

            <div class="row mb-4">
                {{-- <div class="col-lg-12 push">
                </div> --}}
                <div class="col-lg-8">
                    <input type="hidden" name="id" value="{{ $post->id }}">

                    <div class="d-flex mb-4 align-items-center">
                        <div><a class="btn btn-alt-secondary btn-sm me-3 text-nowrap" href="{{ url("workspace") }}"><i class="fa fa-angle-left me-2"></i>{{ __('Back') }}</a></div>
                        <div class="fs-4 fw-bold">{{ __("Edit Post") }}: {{ $post->title }}</div>
                    </div>
                    {{-- <div class="mb-0">
                        <label class="form-label">{{ __("Post Translations") }}</label>
                    </div> --}}

                    <div class="block block-rounded block-themed row g-0">
                        <ul class="nav nav-tabs nav-tabs-block flex-md-column col-md-2" role="tablist">
                            <li class="nav-item d-md-flex flex-md-column">
                                <button type="button" class="nav-link text-md-start text-capitalize text-nowrap active" id="btabs-ru-tab" data-bs-toggle="tab" data-bs-target="#btabs-ru" role="tab" aria-controls="btabs-ru" aria-selected="true">русский</button>
                            </li>
                            <li class="nav-item d-md-flex flex-md-column">
                                <button type="button" class="nav-link text-md-start text-capitalize text-nowrap" id="btabs-kk-tab" data-bs-toggle="tab" data-bs-target="#btabs-kk" role="tab" aria-controls="btabs-kk" aria-selected="false">қазақ тілі</button>
                            </li>
                            <li class="nav-item d-md-flex flex-md-column">
                                <button type="button" class="nav-link text-md-start text-capitalize text-nowrap" id="btabs-en-tab" data-bs-toggle="tab" data-bs-target="#btabs-en" role="tab" aria-controls="btabs-en" aria-selected="false">english</button>
                            </li>
                        </ul>
                        <div class="tab-content col-md-10">
                            <div class="tab-pane ps-4 active" id="btabs-ru" role="tabpanel" aria-labelledby="btabs-ru-tab">
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Title") }} (русский)</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" name="title[ru]" value="{{ !empty($post->getTranslations('title')['ru'])? $post->getTranslations('title')['ru']: '' }}">

                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Summary') }} (русский)</label>
                                    <textarea name="summary[ru]" class="form-control @error('summary') is-invalid @enderror" rows="3">{{ !empty($post->getTranslations('summary')['ru'])? $post->getTranslations('summary')['ru']: '' }}</textarea>

                                    @error('summary')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div> 

                                <div>
                                    <label class="form-label">{{ __("Content") }} (русский)</label>
                                    <div class="@error('content') is-invalid @enderror">
                                        <div id="content[ru]" class="editor">{!! !empty($post->getTranslations('content')['ru'])? $post->getTranslations('content')['ru']: '' !!}</div>
                                        {{-- <input type="hidden" name="content[ru]"> --}}
                                    </div>

                                    @error('content')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="tab-pane ps-4" id="btabs-kk" role="tabpanel" aria-labelledby="btabs-kk-tab">
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Title") }} (қазақ тілі)</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" name="title[kk]" value="{{ !empty($post->getTranslations('title')['kk'])? $post->getTranslations('title')['kk']: '' }}">

                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Summary') }} (қазақ тілі)</label>
                                    <textarea name="summary[kk]" class="form-control @error('summary') is-invalid @enderror" rows="3">{{ !empty($post->getTranslations('summary')['kk'])? $post->getTranslations('summary')['kk']: '' }}</textarea>

                                    @error('summary')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div> 

                                <div>
                                    <label class="form-label">{{ __("Content") }} (қазақ тілі)</label>
                                    <div class="@error('content') is-invalid @enderror">
                                        <div id="content[kk]" class="editor">{!! !empty($post->getTranslations('content')['kk'])? $post->getTranslations('content')['kk']: '' !!}</div>
                                        {{-- <input type="hidden" name="content[kk]"> --}}
                                    </div>

                                    @error('content')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="tab-pane ps-4" id="btabs-en" role="tabpanel" aria-labelledby="btabs-en-tab">
                                <div class="mb-3">
                                    <label class="form-label">{{ __("Title") }} (english)</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" name="title[en]" value="{{ !empty($post->getTranslations('title')['en'])? $post->getTranslations('title')['en']: '' }}">

                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Summary') }} (english)</label>
                                    <textarea name="summary[en]" class="form-control @error('summary') is-invalid @enderror" rows="3">{{ !empty($post->getTranslations('summary')['en'])? $post->getTranslations('summary')['en']: '' }}</textarea>

                                    @error('summary')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div> 

                                <div>
                                    <label class="form-label">{{ __("Content") }} (english)</label>
                                    <div class="@error('content') is-invalid @enderror">
                                        <div id="content[en]" class="editor">{!! !empty($post->getTranslations('content')['en'])? $post->getTranslations('content')['en']: '' !!}</div>
                                        {{-- <input type="hidden" name="content[en]"> --}}
                                    </div>

                                    @error('content')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-lg btn-alt-success">
                            {{ __('Save') }}
                        </button>
                    </div>
                </div>
                <div class="col-lg-4">

                    <div class="mb-3">
                        <label class="form-label">{{ __("Category") }}</label>
                        <select class="form-control @error('category_id') is-invalid @enderror" name="category_id" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @if ($post->categories()->first()->id == $category->id) selected="" @endif>{{ __($category->name) }}</option>
                            @endforeach
                        </select>

                        @error('category_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __("Image") }}<a id="lfm" data-input="thumbnail" data-preview="holder" class="ms-2 btn btn-sm btn-primary text-white">
                            <i class="far fa-image"></i> {{ __("Choose") }}
                        </a></label>
                        <div class="input-group">
                            <input id="thumbnail" type="hidden" name="image" value="{{ $post->image }}">
                        </div>
                        <div id="holder" class="mt-1" @error('image') is-invalid @enderror">
                            @if ($post->image)
                                <img class="w-100 rounded" src="{{ asset("storage/{$post->image}") }}">
                            @endif
                        </div>

                        @error('image')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __("Image Caption") }}</label>
                        <input type="text" class="form-control @error('image_caption') is-invalid @enderror" name="image_caption" value="{{ $post->image_caption }}">

                        @error('image_caption')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-2">
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="not-add-to-slider" name="add_to_slider" value="" @if (!$post->is_featured && !$post->is_slider) checked="" @endif>
                            <label class="form-check-label font-w400" for="not-add-to-slider">{{ __("No publication in the slider") }}</label>
                        </div>
                    </div>

                    <div class="mb-2">
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="add-to-big-slider" name="add_to_slider" value="big" @if ($post->is_slider) checked="" @endif>
                            <label class="form-check-label font-w400" for="add-to-big-slider">{{ __("Add To Big Slider") }}<span class="bg-primary rounded px-2 py-1 text-white fs-xs fw-semibold ms-2 text-nowrap">{{ __("Price") }}: {{ nova_get_setting('big_slider_price') }} тг.</span></label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="add-to-small-slider" name="add_to_slider" value="small" @if ($post->is_featured) checked="" @endif>
                            <label class="form-check-label font-w400 d-flex align-items-center" for="add-to-small-slider">{{ __("Add To Small Slider") }}<span class="bg-primary rounded px-2 py-1 text-white fs-xs fw-semibold ms-2 text-nowrap">{{ __("Price") }}: {{ nova_get_setting('small_slider_price') }} тг.</span></label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __("Scheduled Post") }}</label>
                        <input type="datetime-local" class="form-control @error('created_at') is-invalid @enderror" name="created_at" value="{{ $post->created_at->format('Y-m-d\TH:i') }}">

                        @error('created_at')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __("Keywords") }}</label>
                        <input type="text" class="form-control @error('keywords') is-invalid @enderror" name="keywords" value="{{ $post->keywords }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            {{ __("Attachments") }}
                            <a id="lfm2" data-input="thumbnail2" data-preview="holder2" class="ms-2 btn btn-sm btn-primary text-white">
                                <i class="far fa-file"></i> {{ __("Choose") }}
                            </a>
                        </label>
                        <div class="input-group">
                            <input id="thumbnail2" type="hidden">
                        </div>
                        <div id="holder2" class="d-flex bg-body-light rounded px-3 pt-3 flex-wrap @error('files') is-invalid @enderror">
                            @if ($post->getFiles())
                                @foreach($post->getFiles() as $file)
                                    <div class="btn-group btn-group-sm mb-3 me-3 text-nowrap">
                                        <button type="button" class="btn btn-alt-primary btn-sm fw-bold">
                                            <i class="fa fa-fw fa-cloud-download-alt me-1"></i>
                                            {{ $file->name }}
                                        </button>
                                        <button type="button" class="btn btn-alt-danger btn-sm btn-remove-file"><i class="fa fa-trash fa-fw"></i></button>
                                        <input type="hidden" name="files[]" value="{{ $file->url }}">
                                    </div>
                                @endforeach
                            @else
                                <p class="mb-3 fs-sm no-data">{{ __("No Data") }}</p>
                            @endif
                        </div>
                        @error('files')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                </div>
            </div>
        </form>

        @include('blocks.banner', ['location' => 'post.edit'])
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.tiny.cloud/1/155v3k2qljq46vtnp00o2zp3mdhw6lcckpbwl64vk06t33lc/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        {!! \File::get(base_path('vendor/unisharp/laravel-filemanager/public/js/stand-alone-button.js')) !!}
    </script>
    <script>
        $(document).ready(function() {
            $(this).on('click', '.btn-remove-file', function() {
                $(this).closest('.btn-group').remove();
            //}).on('submit', '#newSave', function() {
            });

            tinymce.remove();
            tinymce.init({
                //mode: "specific_textareas",
                selector: '.editor',
                language: 'ru',
                menubar: false,
                statusbar: false,
                plugins: 'print preview paste importcss searchreplace autolink save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor insertdatetime advlist lists wordcount textpattern noneditable help charmap quickbars emoticons',
                imagetools_cors_hosts: ['picsum.photos'],
                toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
                toolbar_sticky: true,
                automatic_uploads: true,
                file_picker_callback: function(callback, value, meta) {
                    var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                    var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

                    var cmsURL = '/laravel-filemanager?editor=' + meta.fieldname;
                    if (meta.filetype == 'image') {
                        cmsURL = cmsURL + "&type=Images";
                    } else {
                        cmsURL = cmsURL + "&type=Files";
                    }

                    tinyMCE.activeEditor.windowManager.openUrl({
                        url : cmsURL,
                        title : 'Filemanager',
                        width : x * 0.8,
                        height : y * 0.8,
                        resizable : "yes",
                        close_previous : "no",
                        onMessage: (api, message) => {
                            callback(message.content);
                        }
                    });
                },
                images_upload_handler: function (blobInfo, success, failure) {
                    var xhr, formData;
                    xhr = new XMLHttpRequest();
                    xhr.withCredentials = false;
                    xhr.open('POST', '{{ url("upload/image") }}');
                    var token = '{{ csrf_token() }}';
                    xhr.setRequestHeader("X-CSRF-Token", token);
                    xhr.onload = function() {
                        var json;
                        if (xhr.status != 200) {
                            failure('HTTP Error: ' + xhr.status);
                            return;
                        }
                        json = JSON.parse(xhr.responseText);

                        if (!json || typeof json.url != 'string') {
                            failure('Invalid JSON: ' + xhr.responseText);
                            return;
                        }
                        success(`/storage/${json.url}`);
                    };
                    formData = new FormData();
                    formData.append('upload', blobInfo.blob(), blobInfo.filename());
                    xhr.send(formData);
                },
                image_advtab: true,
                relative_urls: false,
                remove_script_host : false,
                convert_urls : true,
                importcss_append: true,
                template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
                template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
                height: '50vh',
                block_unsupported_drop: false,
                image_caption: true,
                quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
                noneditable_noneditable_class: 'mceNonEditable',
                toolbar_mode: 'sliding',
                contextmenu: 'link image imagetools table',
                skin: 'oxide',
                content_css: 'default',
                content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
            });
        })
    </script>

    <script>
        var lfm = function(id, type, options) {
            let button = document.getElementById(id);

            button.addEventListener('click', function () {
                var route_prefix = (options && options.prefix) ? options.prefix : '/filemanager';
                var target_input = document.getElementById(button.getAttribute('data-input'));
                var target_preview = document.getElementById(button.getAttribute('data-preview'));

                window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
                window.SetUrl = function (items) {
                    if (type == 'image') {
                        var file_path = items[0].url;
                    } else {
                        var file_path = items.map(function (item) {
                            return item.url;
                        }).join(',');
                    }

                    // set the value of the desired input to image url
                    target_input.value = file_path;
                    target_input.dispatchEvent(new Event('change'));

                    // clear previous preview
                    target_preview.innerHtml = '';

                    if (type == 'image') {
                        file_path.split(',').forEach(function (item) {
                            let img = document.createElement('img')
                            img.setAttribute('style', 'height: 5rem')
                            img.setAttribute('src', item)
                            $(target_preview).html($(img));
                        });
                    } else {
                        file_path.split(',').forEach(function (item) {
                            var match = item.match(/([^/]*)$/);

                            var filePreview = $(`<div class="btn-group btn-group-sm mb-3 me-3 text-nowrap">
                                <button type="button" class="btn btn-alt-primary btn-sm fw-bold">
                                    <i class="fa fa-fw fa-cloud-download-alt me-1"></i>
                                    ${match[0]}
                                </button>
                                <button type="button" class="btn btn-alt-danger btn-sm btn-remove-file"><i class="fa fa-trash fa-fw"></i></button>
                                <input type="hidden" name="files[]" value="${item}">
                            </div>`);

                            if ($(target_preview).find('.btn-group').length) {
                                $(target_preview).append(filePreview);
                            } else {
                                $(target_preview).html(filePreview);
                            }
                        });
                    }

                    // trigger change event
                    target_preview.dispatchEvent(new Event('change'));
                };
            });
        };

        lfm('lfm', 'image', {
            prefix: "/filemanager",
            type: "image",
        });
        lfm('lfm2', 'files', {
            prefix: "/filemanager",
            type: "file",
        });
    </script>
@endpush