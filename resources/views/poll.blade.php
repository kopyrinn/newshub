@extends('layouts.app')

@section('title', __("Poll") . ': ' . $poll->question)
@section('description', $poll->question)
@section('image', $poll->image)


@section('content')
<style>
    .block-content .block, .block-content .items-push>div, .block-content .push, .block-content p {
        margin-bottom: 0.3rem !important;
    }
</style>
<div class="row mb-4">
    <div class="col-lg-8">
        <h3 class="mb-4">
            {!! str_replace('<p>&nbsp;</p>', '', trim($poll->question)) !!}
        </h3>
        

        <div class="mb-4">
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
        @if ($poll->image)
        <div class="mb-4 d-flex justify-content-center flex-column">
                                <img itemprop="image" class="rounded m-auto mw-100" src="{{ Storage::url($poll->image) }}" alt="" importance"high">
                            </div>
        @endif                    
        
        

        <div class="mw-100 overflow-hidden d-print-inline" id="post-content">
            {!! str_replace('<p>&nbsp;</p>', '', trim($poll->description)) !!}
        </div>

        <div class="mb-4">
            <div class="mb-4 d-flex align-items-center justify-content-between">
                <h4 class="mb-0 d-flex align-items-center">{{ __('Participants') }} <span class="badge bg-secondary rounded-3 fs-xs ms-2">{{ $poll->participants->count() }}</span></h4>
                {{-- @if (!$poll->participants->where('user_id', auth()->user()->id)) --}}
                    @if (!auth()->guest() && auth()->user()->isPress())
                        <button type="button" class="btn btn-sm fs-xs btn-primary" data-bs-toggle="modal" data-bs-target="#modal-request">{{ __('Request for participation') }}</button>
                    @endif
                {{-- @endif --}}
            </div>

            @if (auth()->guest() || ($poll->start_at && $poll->start_at >= \Carbon\Carbon::now()) || !auth()->user()->isUser() || $poll->votes()->where('user_id', auth()->user()->id)->exists())
                @foreach($poll->participants as $participant)
                    <div class="d-flex justify-content-between mb-2">
                        <div class="d-flex align-items-center">
                            <img class="img-avatar img-avatar128" src="{{ Storage::url($participant->photo) }}" alt="">
                            <span class="ms-2">
                                <span class="fw-bold">{{ $participant->name }} @if (!auth()->guest() && $poll->votes()->where('user_id', auth()->user()->id)->where('poll_request_id', $participant->id)->exists())<i class="fa text-primary ms-1 fa-check fs-sm"></i>@endif</span>
                                <span class="d-block fs-sm text-muted">{{ $participant->position }}</span>
                            </span>
                        </div>
                        <div class="d-flex align-items-end justify-content-end">
                            <span class="rounded-3 btn btn-alt-secondary px-2 py-1 d-flex fs-xs">{{ $participant->votes_count }}</span>
                        </div>
                    </div>
                    <div class="progress push" style="height: 10px;">
                        <div class="progress-bar" role="progressbar" style="width: {{ $poll->total_votes && $participant->votes_count? round($participant->votes_count / $poll->total_votes * 100, 2): 0 }}%;" aria-valuenow="{{ $poll->total_votes && $participant->votes_count? round($participant->votes_count / $poll->total_votes * 100, 2): 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                @endforeach

                @if (!$poll->start_at || $poll->start_at <= \Carbon\Carbon::now())
                    <p class="fw-bold">{{ __('Votes') }}: {{ $poll->total_votes }}</p>
                @endif
            @else
                <form class="form" method="POST" action="{{ route('polls.vote', ['slug' => $poll->slug]) }}">
                    @csrf

                    @foreach($poll->participants as $participant)
                        <div class="form-check d-flex align-items-center mb-2">
                            <div class="me-1">
                                <input class="form-check-input mt-0" type="radio" value="{{ $participant->id }}" id="participant{{ $participant->id }}" name="participant" required>
                            </div>
                            <label class="form-check-label" for="participant{{ $participant->id }}">
                                <span class="d-flex align-items-center">
                                    <img class="img-avatar img-avatar128" src="{{ Storage::url($participant->photo) }}" alt="">
                                    <span class="ms-2">
                                        <span class="fw-bold">{{ $participant->name }}</span>
                                        <span class="d-block fs-sm text-muted">{{ $participant->position }}</span>
                                    </span>
                                </span>
                            </label>
                        </div>
                    @endforeach

                    @if ($poll->participants->count())
                        <div class="mt-3">
                            <button type="submit" class="btn btn-sm btn-primary">{{ __('Vote') }}</button>
                        </div>
                    @endif
                </form>
            @endif
        </div>

        @include('blocks.banner', ['location' => 'polls.view'])
    </div>
    @include('sidebar')
</div>

<div class="modal" id="modal-request" tabindex="-1" role="dialog" aria-labelledby="modal-request" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="block block-rounded block-transparent mb-0">
                <div class="block-header block-header-default">
                    <h3 class="block-title">{{ __('Submit a request for participation') }}</h3>
                    <div class="block-options">
                        
                        <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa fa-fw fa-times"></i>
                        </button>

                    </div>
                </div>
                <div class="block-content block-content-full fs-sm">
                    <div class="mb-3">
                        <label class="form-label" for="request-name">{{ __('Full name') }}</label>
                        <input id="request-name" type="text" class="form-control form-control-alt w-100 mb-3" placeholder="{{ __('Full name') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="request-position">{{ __('Position') }}</label>
                        <input id="request-position" type="text" class="form-control form-control-alt w-100 mb-3" placeholder="{{ __('Position') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="request-phone">{{ __('Phone') }}</label>
                        <input id="request-phone" type="text" class="form-control form-control-alt w-100 mb-3" placeholder="{{ __('Phone') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="request-email">{{ __('Email') }}</label>
                        <input id="request-email" type="email" class="form-control form-control-alt w-100 mb-3" placeholder="{{ __('Email') }}">
                    </div>
                    <div>
                        <label class="form-label">
                            {{ __("Photo") }}
                            <a id="lfm" data-input="request-photo" data-preview="holder" class="ms-2 btn btn-sm btn-primary text-white">
                                <i class="far fa-image"></i> {{ __("Choose") }}
                            </a>
                        </label>
                        <div class="input-group">
                            <input id="request-photo" type="hidden" name="image" value="">
                        </div>
                        <div id="holder" class="mt-1"></div>
                    </div>

                    <div id="request-errors" class="invalid-feedback mt-2" style="display: none;">
                        <strong></strong>
                    </div>
                </div>
                <div class="block-content block-content-full text-end bg-body">
                    <button type="button" class="btn btn-sm btn-alt-secondary me-1" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" class="btn btn-sm btn-primary" id="request-send">{{ __('Send') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/plugins/jquery.maskedinput/jquery.maskedinput.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#request-phone').mask("+7 (999) 999 99 99");
        });

        $(document).on('click', '#request-send', function(e) {
            $('#request-errors').hide();

            $.ajax({
                url: '{{ route('polls.request', ['slug' => $poll->slug]) }}',
                method: 'post',
                dataType: 'json',
                data: {
                    name: $('#request-name').val(),
                    position: $('#request-position').val(),
                    phone: $('#request-phone').val(),
                    email: $('#request-email').val(),
                    photo: $('#request-photo').val(),
                }
            }).then(function(response) {
                if (response.ok) {
                    $('#modal-request').modal('hide');

                    sw.fire({
                        title: "{{ __('Your request has been successfully submitted for moderation.') }}",
                        icon: 'success',
                    });
                } else {
                    $('#request-errors strong').text(response.message);
                    $('#request-errors').show();
                }
            }).catch(function(e) {
                if (e.status == 422) {
                    $('#request-errors strong').text(e.responseJSON.message);
                    $('#request-errors').show();
                }
            })
        });

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
    </script>
@endpush