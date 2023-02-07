@extends('layouts.app')

@section('title', __("Add Post"))
@section('description', __("Add Post"))

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="justify-content-start d-flex">
            <nav class="flex-shrink-1 mb-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt flex-nowrap">
                    <li class="breadcrumb-item d-flex text-nowrap">
                        <a class="link-fx" href="{{ url('/') }}">{{ __("Home") }}</a>
                    </li>
                    <li class="breadcrumb-item text-truncate" aria-current="page">
                        {{ __("Add Post") }}
                    </li>
                </ol>
            </nav>
        </div>

        <form action="{{ route("new.save") }}" method="POST" id="newSave">
            <div class="row mb-4">
                @if (!auth()->user()->packageActive())
                    <div class="col-lg-8 offset-lg-2">
                        <div class="alert alert-warning" role="alert">
                            <p class="mb-0">{{ __('To be able to publish the material, you must purchase a package of services.') }} </p>
                        </div>

                        <a href="{{ url('packages') }}" class="btn btn-alt-primary">{{ __('Select') }}</a>
                    </div>
                @else
                    <div class="col-lg-8">
                        @csrf

                        <div class="block block-rounded block-themed row g-0">
                            <ul class="nav nav-tabs nav-tabs-block flex-md-column col-md-2" role="tablist">
                                <li class="nav-item d-md-flex flex-md-column">
                                    <button type="button" class="nav-link text-md-start text-capitalize text-nowrap active" id="btabs-ru-tab" data-bs-toggle="tab" data-bs-target="#btabs-ru" role="tab" aria-controls="btabs-ru" aria-selected="true"><img src="https://newshub.kz/assets/ru.png">  русский</button>
                                </li>
                                <li class="nav-item d-md-flex flex-md-column">
                                    <button type="button" class="nav-link text-md-start text-capitalize text-nowrap" id="btabs-kk-tab" data-bs-toggle="tab" data-bs-target="#btabs-kk" role="tab" aria-controls="btabs-kk" aria-selected="false"><img src="https://newshub.kz/assets/kz.png">  қазақ тілі</button>
                                </li>
                                <li class="nav-item d-md-flex flex-md-column">
                                    <button type="button" class="nav-link text-md-start text-capitalize text-nowrap" id="btabs-en-tab" data-bs-toggle="tab" data-bs-target="#btabs-en" role="tab" aria-controls="btabs-en" aria-selected="false"><img src="https://newshub.kz/assets/us.png">  english</button>
                                </li>
                            </ul>
                            <div class="tab-content col-md-10">
                                <div class="tab-pane ps-4 active" id="btabs-ru" role="tabpanel" aria-labelledby="btabs-ru-tab">
                                    <div class="mb-3">
                                        <label class="form-label">{{ __("Title") }} (русский)</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title[ru]" value="{{ old('title.ru') }}">

                                        @error('title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">{{ __('Summary') }} (русский)</label>
                                        <textarea name="summary[ru]" class="form-control @error('summary') is-invalid @enderror" rows="3">{{ old('summary.ru') }}</textarea>

                                        @error('summary')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div> 

                                    <div>
                                        <label class="form-label">{{ __("Content") }} (русский)</label>
                                        <div class="@error('content') is-invalid @enderror">
                                            <div id="content[ru]" class="editor">{!! old('content.ru') !!}</div>
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
                                        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title[kk]" value="{{ old('title.kk') }}">

                                        @error('title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">{{ __('Summary') }} (қазақ тілі)</label>
                                        <textarea name="summary[kk]" class="form-control @error('summary') is-invalid @enderror" rows="3">{{ old('summary.kk') }}</textarea>

                                        @error('summary')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div> 

                                    <div>
                                        <label class="form-label">{{ __("Content") }} (қазақ тілі)</label>
                                        <div class="@error('content') is-invalid @enderror">
                                            <div id="content[kk]" class="editor">{!! old('content.kk') !!}</div>
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
                                        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title[en]" value="{{ old('title.en') }}">

                                        @error('title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">{{ __('Summary') }} (english)</label>
                                        <textarea name="summary[en]" class="form-control @error('summary') is-invalid @enderror" rows="3">{{ old('summary.en') }}</textarea>

                                        @error('summary')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div> 

                                    <div>
                                        <label class="form-label">{{ __("Content") }} (english)</label>
                                        <div class="@error('content') is-invalid @enderror">
                                            <div id="content[en]" class="editor">{!! old('content.en') !!}</div>
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
                            <label class="form-label">{{ __("Category") }}</label>  <i class="nav-main-link-icon fa fa-info-circle" data-bs-toggle="popover" data-bs-placement="right" title="" data-bs-content="{{ __("Category info desc") }}" data-bs-original-title="{{ __("Category info") }}"></i>
                            <select class="form-control @error('category_id') is-invalid @enderror" name="category_id" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ __($category->name) }}</option>
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
                                <input id="thumbnail" type="hidden" name="image" value="">
                            </div>
                            <div id="holder" class="mt-1 @error('image') is-invalid @enderror"></div>

                            @error('image')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __("Image Caption") }}</label>  <i class="nav-main-link-icon fa fa-info-circle" data-bs-toggle="popover" data-bs-placement="right" title="" data-bs-content="{{ __("Image Caption info") }}" data-bs-original-title="{{ __("Image Caption title") }}"></i>
                            <input type="text" class="form-control @error('image_caption') is-invalid @enderror" name="image_caption" value="{{ old("image_caption") }}" placeholder="{{ __("Image Caption placeholder") }}">

                            @error('image_caption')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3 event-field" style="display: none;">
                            <label class="form-label">{{ __("Event Date") }}</label> <i class="nav-main-link-icon fa fa-info-circle" data-bs-toggle="popover" data-bs-placement="right" title="" data-bs-content="{{ __("Event Date info") }}" data-bs-original-title="{{ __("Event Date title") }}"></i>
                            <input type="datetime-local" class="form-control @error('event_date') is-invalid @enderror" name="event_date" value="{{ old("event_date") }}">

                            @error('event_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __("Keywords") }}</label>
                            <input type="text" class="form-control @error('keywords') is-invalid @enderror" name="keywords" value="{{ old("keywords") }}" placeholder="{{ __("Keywords placeholder") }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                {{ __("Attachments") }}
                                <a id="lfm2" data-input="thumbnail2" data-preview="holder2" class="ms-2 btn btn-sm btn-primary text-white">
                                    <i class="far fa-file"></i> {{ __("Choose") }}
                                </a>
                            </label>
                            <div class="input-group">
                                <input id="thumbnail2" type="hidden" name="files">
                            </div>
                            <div id="holder2" class="d-flex bg-body-light rounded px-3 pt-3 flex-wrap @error('files') is-invalid @enderror">
                                <p class="mb-3 fs-sm no-data">{{ __("No Data") }}</p>
                            </div>
                            @error('files')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3 hidden">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="not-add-to-slider" name="add_to_slider" value="" checked>
                                <label class="form-check-label font-w400" for="not-add-to-slider">{{ __("No publication in the slider") }}</label>
                            </div>
                        </div> 

                        <div class="mb-2">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="add-to-big-slider" name="add_to_slider" value="big">
                                <label class="form-check-label font-w400" data-bs-toggle="popover" data-bs-placement="right" title="" data-bs-content="{{ __("Add To Big Slider desc") }}" data-bs-original-title="{{ __("Add To Big Slider title") }}" for="add-to-big-slider">{{ __("Add To Big Slider") }}<span class="bg-primary rounded px-2 py-1 text-white fs-xs fw-semibold ms-2 text-nowrap">{{ __("Price") }}: {{ nova_get_setting('big_slider_price') }} тг.</span></label>
                            </div>
                        </div>

                        <div class="mb-2">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="add-to-small-slider" name="add_to_slider" value="small">
                                <label class="form-check-label font-w400" data-bs-toggle="popover" data-bs-placement="right" title="" data-bs-content="{{ __("Add To Small Slider desc") }}" data-bs-original-title="{{ __("Add To Small Slider title") }}" for="add-to-small-slider">{{ __("Add To Small Slider") }}<span class="bg-primary rounded px-2 py-1 text-white fs-xs fw-semibold ms-2 text-nowrap">{{ __("Price") }}: {{ nova_get_setting('small_slider_price') }} тг.</span></label>
                            </div>
                        </div>

                        @if (auth()->user()->packageActive() && in_array(auth()->user()->package->slug, ['standart-plus', 'standart-maximum']))
                            <div class="mb-2">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="send-notifications" name="to_fcm" value="1">
                                    <label class="form-check-label font-w400" for="send-notifications">{{ __("Send Notifications To Fcm") }}<span class="bg-primary rounded px-2 py-1 text-white fs-xs fw-semibold text-nowrap d-block">{{ __("Notification info") }}</span></label>
                                </div>
                            </div>

                            <div class="mb-2">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="send-notifications-telegram" name="to_telegram" value="1">
                                    <label class="form-check-label font-w400" for="send-notifications-telegram">{{ __("Send Notifications To Telegram") }}</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="special-block-style" name="is_styled" value="1">
                                    <label class="form-check-label font-w400" for="special-block-style">{{ __("Special Block Style") }}</label>
                                </div>
                                <div class="mt-2" id="design-options" style="display: none;">
                                    <div class="d-flex flex-wrap">
                                        @php
                                            $colors = [
                                                'success-light text-success',
                                                'info-light text-info',
                                                'warning-light text-warning',
                                                'danger-light text-danger',
                                                'gray-light text-gray-darker',
                                                'success text-white',
                                                'info text-white',
                                                'warning text-white',
                                                'danger text-white',
                                                'muted text-white',
                                                'primary-darker text-white',
                                                'primary-dark text-white',
                                                'primary text-white',
                                                'primary-light text-white',
                                                'primary-lighter text-white',
                                            ];
                                        @endphp

                                        @foreach($colors as $color)
                                            <input id="btn-{{ $loop->index }}" type="radio" class="btn-check" name="color" value="{{ $color }}">
                                            <label for="btn-{{ $loop->index }}" type="button" class="btn shadow-sm me-2 mb-2 btn-icon rounded-pill bg-{{ $color }}"><span class="design-text"> A </span></label>
                                        @endforeach
                                    </div>
                                    <span class="form-text text-muted">{{ __("Choose from the ready-made options the design in which your block will be displayed..") }}</span>
                                </div>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label">{{ __("Scheduled Post") }}</label>  <i class="nav-main-link-icon fa fa-info-circle" data-bs-toggle="popover" data-bs-placement="right" title="" data-bs-content="{{ __("Scheduled Post desc") }}" data-bs-original-title="{{ __("Scheduled Post title") }}"></i>
                            <input type="datetime-local" class="form-control @error('created_at') is-invalid @enderror" name="created_at" value="{{ old("created_at") }}">

                            @error('created_at')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- <div class="mb-3 event-field" style="display: none;">
                            <label class="form-label">{{ __("Event Place") }}</label>
                            <input type="text" class="form-control @error('place') is-invalid @enderror" name="place" value="{{ old("place") }}" required>

                            @error('place')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3 event-field" style="display: none;">
                            <label class="form-label">{{ __("Event Price") }}</label>
                            <input type="text" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old("price") }}" required>

                            @error('price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div> --}}

                        {{-- <div class="block block-rounded">
                            <ul class="nav nav-tabs nav-tabs-alt" role="tablist">
                                <li class="nav-item">
                                    <button type="button" class="nav-link text-capitalize active" id="btabs-ru-tab" data-bs-toggle="tab" data-bs-target="#btabs-ru" role="tab" aria-controls="btabs-ru" aria-selected="true">русский</button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link text-capitalize" id="btabs-kk-tab" data-bs-toggle="tab" data-bs-target="#btabs-kk" role="tab" aria-controls="btabs-kk" aria-selected="false">қазақ тілі</button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link text-capitalize" id="btabs-en-tab" data-bs-toggle="tab" data-bs-target="#btabs-en" role="tab" aria-controls="btabs-en" aria-selected="false">english</button>
                                </li>
                            </ul>
                            <div class="pt-3 tab-content">
                                <div class="tab-pane active" id="btabs-ru" role="tabpanel" aria-labelledby="btabs-ru-tab">

                                </div>
                                <div class="tab-pane" id="btabs-kk" role="tabpanel" aria-labelledby="btabs-kk-tab">
                                    <div class="mb-3">
                                        <label class="form-label">{{ __("Title") }} (қазақ тілі)</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title[kk]" value="{{ old("title.kk") }}" placeholder="{{ __("Title placeholder") }}">

                                        @error('title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">{{ __('Summary') }} (қазақ тілі)</label>
                                        <textarea name="summary[kk]" class="form-control @error('summary') is-invalid @enderror" rows="3" placeholder="{{ __("Summary placeholder") }}">{{ old("summary.kk") }}</textarea>

                                        @error('summary')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">{{ __("Content") }} (қазақ тілі)</label>
                                        <div class="@error('content') is-invalid @enderror">
                                            <div id="content[kk]" class="editor">{!! old("content.kk") !!}</div>
                                        </div>

                                        @error('content')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="tab-pane" id="btabs-en" role="tabpanel" aria-labelledby="btabs-en-tab">
                                    <div class="mb-3">
                                        <label class="form-label">{{ __("Title") }} (english)</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title[en]" value="{{ old("title.en") }}" placeholder="{{ __("Title placeholder") }}">

                                        @error('title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">{{ __('Summary') }} (english)</label>
                                        <textarea name="summary[en]" class="form-control @error('summary') is-invalid @enderror" rows="3" placeholder="{{ __("Summary placeholder") }}">{{ old("summary.en") }}</textarea>

                                        @error('summary')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">{{ __("Content") }} (english)</label>
                                        <div class="@error('content') is-invalid @enderror">
                                            <div id="content[en]" class="editor">{!! old("content.en") !!}</div>
                                        </div>

                                        @error('content')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                @endif 
            </div>
        </form>

        @include('blocks.banner', ['location' => 'post.new'])
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
            $(this).on('change', '[name="color"]', function() {
                $('.design-text').text(`A`);
                $(this).next().find('.design-text').html(`<i class="fa fs-xs fa-check"></i>`);
            }).on('change', '#special-block-style', function() {
                if ($(this).is(':checked')) {
                    $('#design-options').show();
                } else {
                    $('#design-options').hide();
                }
            }).on('click', '[type="datetime-local"]', function() {
                $(this).focus();
            }).on('click', '.btn-remove-file', function() {
                $(this).closest('.btn-group').remove();
            }).on('change', '[name="category_id"]', function() {
                if ($(this).val() == 8) {
                    $('.event-field').show();
                } else {
                    $('.event-field').hide();
                }
            //}).on('submit', '#newSave', function() {
            //    $('[name="content"]').val(tinyMCE.activeEditor.getContent({format : 'raw'}));
            });

            var initTinyMCE = function() {
                tinymce.remove();
                tinymce.init({
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
                            success(`{{ url('/') }}/storage/${json.url}`);
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
                    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px } img {max-width: 100%;height: auto;}'
                });
            };

            initTinyMCE();
        })
    </script>

    <script>
        var lfm = function(id, type, options) {
            var button = document.getElementById(id);

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
                            var img = document.createElement('img')
                            img.setAttribute('class', 'w-100 rounded')
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

        if ($("#lfm").length) {
            lfm('lfm', 'image', {
                prefix: "/filemanager",
                type: "image",
            });
        }
        if ($("#lfm2").length) {
            lfm('lfm2', 'files', {
                prefix: "/filemanager",
                type: "file",
            });
        }
    </script>
@endpush
