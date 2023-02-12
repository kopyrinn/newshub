@extends('layouts.app')

@section('title', __("Settings"))
@section('description', __("Settings"))

@section('content')
    <!-- <div class="justify-content-start d-flex">
        <nav class="flex-shrink-1 mb-3" aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-alt flex-nowrap">
                <li class="breadcrumb-item d-flex text-nowrap">
                    <a class="link-fx" href="{{ url('/') }}">{{ __("Home") }}</a>
                </li>
                <li class="breadcrumb-item d-flex text-nowrap">
                    <a class="link-fx" href="{{ url("user/{$user->id}") }}">{{ __("Profile") }}</a>
                </li>
                <li class="breadcrumb-item text-truncate" aria-current="page">
                    {{ __("Settings") }}
                </li>
            </ol>
        </nav>
    </div> -->

    <div class="row">
        <div class="col-lg-12">
            @if ($user->isPress() && (!$user->name || !$user->description || !$user->user_category_id))
                <div class="alert alert-warning" role="alert">
                    <p class="mb-0">{{ __('Basic profile settings are required.') }}</p>
                </div>
            @endif
            <div class="row push">
                <div class="col-lg-2 col-md-3 col-sm-4">
                    <h4><i class="fa fa-fw fa-home opacity-50 me-1 d-none d-sm-inline-block"></i> {{ __("Basic") }}</h4>
                    <p class="fs-sm text-muted">
                        {{ __("Basic profile settings") }}
                    </p>
                </div>
                <div class="col-lg-10 col-md-9 col-sm-8">
                    <form action="{{ route('settingsBasic') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            @if ($user->isPress())
                                <label class="form-label">{{ __('Organization Name') }} <span class="text-danger">*</span></label>
                            @else
                                <label class="form-label">{{ __('Name') }} <span class="text-danger">*</span></label>
                            @endif

                            <input type="text" class="form-control form-control-alt @error('name') is-invalid @enderror" id="name" name="name" value="{{ $user->name }}" autocomplete="name" required>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        @if ($user->isUser())
                            <div class="mb-3">
                                <label class="form-label">{{ __('Last Name') }} <span class="text-danger">*</span></label>

                                <input type="text" class="form-control form-control-alt @error('lastname') is-invalid @enderror" id="lastname" name="lastname" value="{{ $user->lastname }}" autocomplete="lastname" required>

                                @error('lastname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        @endif

                        <div class="mb-3">
                            @if ($user->isPress())
                            <label class="form-label">{{ __("Organization logo") }}
                            @else
                            <label class="form-label">{{ __("Avatar") }}
                            @endif
                                <label for="uploadAvatar" id="uploadAvatarLabel" class="ms-2 btn btn-sm btn-primary text-white">
                                    <i class="far fa-image"></i> {{ __("Choose") }}
                                </label>
                            </label>
                            <div class="input-group">
                                <input id="thumbnail" class="form-control form-control-alt" type="hidden" name="avatar" value="{{ $user->avatar }}">
                            </div>
                            <div class="bg-image bg-image-center img-avatar img-avatar128" id="uploadAvatarPreview" style="background-image: url({{ $user->avatar ? '/storage/'.$user->avatar : 'img/no-avatar.png' }});"></div>
                        </div>

                        @if ($user->isPress())
                            <div class="mb-3">
                                <label class="form-label">{{ __('User Category') }} <span class="text-danger">*</span></label>
                                <select class="form-control form-control-alt @error('user_category_id') is-invalid @enderror" name="user_category_id">
                                    @foreach(\App\Models\UserCategory::all() as $userCategory)
                                        <option value="{{ $userCategory->id }}" @if ($user->user_category_id == $userCategory->id) selected="" @endif>{{ $userCategory->name }}</option>
                                    @endforeach
                                </select>
                                @error('user_category_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('Description') }} <span class="text-danger">*</span></label>
                                <textarea name="description" class="form-control form-control-alt @error('description') is-invalid @enderror" rows="3">{{ $user->description }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            

                        <!--   <h5 class="fw-semibold">{{ __("Requisites") }}</h5>
                            
                            <div class="mb-3">
                                <label class="form-label">{{ __('Bin') }}</label>
                                <input type="text" class="form-control form-control-alt @error('bin') is-invalid @enderror" id="bin" name="bin" value="{{ $user->bin }}" autocomplete="bin">
                                @error('bin')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">{{ __('Iban') }}</label>
                                <input type="text" class="form-control form-control-alt @error('iban') is-invalid @enderror" id="iban" name="iban" value="{{ $user->iban }}" autocomplete="iban">
                                @error('iban')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">{{ __('Bank') }}</label>
                                <input type="text" class="form-control form-control-alt @error('bank') is-invalid @enderror" id="bank" name="bank" value="{{ $user->bank }}" autocomplete="bank">
                                @error('bank')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">{{ __('Bik') }}</label>
                                <input type="text" class="form-control form-control-alt @error('bik') is-invalid @enderror" id="bik" name="bik" value="{{ $user->bik }}" autocomplete="bik">
                                @error('bik')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">{{ __('Kbe') }}</label>
                                <input type="text" class="form-control form-control-alt @error('kbe') is-invalid @enderror" id="kbe" name="kbe" value="{{ $user->kbe }}" autocomplete="kbe">
                                @error('kbe')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>  -->
                        @endif

                        <div class="mb-3">
                            <button type="submit" class="btn btn-alt-primary">
                                {{ __('Save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row push">
                <div class="col-lg-2 col-md-3 col-sm-4">
                    <h4><i class="fa fa-fw fa-user-circle opacity-50 me-1 d-none d-sm-inline-block"></i> {{ __("Contacts") }}</h4>
                    <p class="fs-sm text-muted">
                        {{ __("Add contact information to display on your profile") }}
                    </p>
                </div>
                <div class="col-lg-10 col-md-9 col-sm-8">
                    <form action="{{ route('settingsContacts') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label" for="email">{{ __('Email') }} <span class="text-danger">*</span></label>

                            <input type="email" class="form-control form-control-alt @error('email') is-invalid @enderror" id="email" name="email" value="{{ $user->email }}" required autocomplete="email">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Phone') }}</label>
                            <input type="text" class="form-control form-control-alt" name="phone" value="{{ $user->phone }}" autocomplete="phone">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Address') }}</label>
                            <textarea name="address" class="form-control form-control-alt" rows="2">{{ $user->address }}</textarea>
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-alt-primary">
                                {{ __('Save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row push">
                <div class="col-lg-2 col-md-3 col-sm-4">
                    <h4><i class="fa fa-fw fa-cog opacity-50 me-1 d-none d-sm-inline-block"></i> {{ __("Access") }}</h4>
                    <p class="fs-sm text-muted">
                        {{ __("Changing your login password is an easy way to protect your account.") }}
                    </p>
                </div>
                <div class="col-lg-10 col-md-9 col-sm-8">
                    <h5 class="fw-semibold">{{ __('Change Password') }}</h5>
                    <form action="{{ route('settingsAccess') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label" for="one-profile-edit-password">{{ __('Current Password') }}</label>
                            <input type="password" class="form-control form-control-alt @error('current-password') is-invalid @enderror" id="one-profile-edit-password" name="current-password" required>
                            @error('current-password')
                                <div class="invalid-feedback animated fadeIn">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 row">
                            <div class="col-12">
                                <label class="form-label" for="one-profile-edit-password-new">{{ __('New Password') }}</label>
                                <input type="password" class="form-control form-control-alt @error('new-password') is-invalid @enderror" id="one-profile-edit-password-new" name="new-password" required>
                                @error('new-password')
                                    <div class="invalid-feedback animated fadeIn">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-12">
                                <label class="form-label" for="one-profile-edit-password-new-confirm">{{ __('Confirm Password') }}</label>
                                <input type="password" class="form-control form-control-alt" id="one-profile-edit-password-new-confirm" name="new-password_confirmation" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-alt-primary">
                                {{ __('Save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <form method="POST" action="{{ route('settingsUpload') }}" enctype="multipart/form-data" id="uploadAvatarForm" style="display: none;">
        <input type="file" name="image" id="uploadAvatar" accept="image/jpeg,image/png,image/gif" hidden />
    </form>



@endsection




@push('scripts')
    <script>
        $(document).on('change', '#uploadAvatar', function() {
            $('#uploadAvatarForm').submit();
            $(this).attr("disabled", true);
            $('#uploadAvatarLabel').addClass("disabled");
            $('#uploadAvatarLabel').html(`<i class="fa fa-spinner fa-spin"></i>`);
        }).on('submit', '#uploadAvatarForm', function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                type: $(this).attr('method'),
                url: $(this).attr('action'),
                data: formData,
                cache : false,
                processData: false,
                contentType: false,
            }).done((data) => {
                $('#uploadAvatarPreview').css({'background-image': `url(/storage${data.image})`})
                $('[name="avatar"]').val(data.image);
                this.reset();
            }).fail((xhr, err) => {
                sw.fire({
                    title: "{{ __('Server Error') }}",
                    text: xhr.responseJSON.errors.image.join("; "),
                    icon: 'error',
                })
            }).always(function() {
                $('#uploadAvatar').attr("disabled", false);
                $('#uploadAvatarLabel').removeClass("disabled");
                $('#uploadAvatarLabel').html(`<i class="far fa-image"></i> {{ __("Choose") }}`);
            });
        });
    </script>
@endpush