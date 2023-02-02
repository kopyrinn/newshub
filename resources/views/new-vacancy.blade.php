@extends('layouts.app')

@section('title', __("Add Vacancy"))
@section('description', __("Add Vacancy"))

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
                        {{ __("Add Vacancy") }}
                    </li>
                </ol>
            </nav>
        </div>

        <div class="row mb-4">
            <div class="col-lg-8 offset-lg-2">
                <h2>{{ __("Add Vacancy") }}</h2>

                <div class="d-flex align-items-center mb-4">
                    <h6 class="mb-0">{{ __("Price") }}</h6><span class="bg-primary rounded px-2 py-1 text-white fs-xs fw-semibold ms-2">{{ nova_get_setting('vacancy_price') }} тг.</span>
                </div>

                <form action="{{ route("new.vacancy") }}" method="POST" id="newVacancySave">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">{{ __("Title") }}</label>
                        <input type="text" class="form-control @error('job_title') is-invalid @enderror" name="job_title" value="{{ old("job_title") }}" required>

                        @error('job_title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('Requirements') }}</label>
                        <textarea name="requiremets" class="form-control @error('requiremets') is-invalid @enderror" rows="3" required>{{ old("requiremets") }}</textarea>

                        @error('requiremets')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('Task') }}</label>
                        <textarea name="task" class="form-control @error('task') is-invalid @enderror" rows="3" required>{{ old("task") }}</textarea>

                        @error('task')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('Conditions') }}</label>
                        <textarea name="conditionsm" class="form-control @error('conditionsm') is-invalid @enderror" rows="3" required>{{ old("conditionsm") }}</textarea>

                        @error('conditionsm')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __("Email") }}</label>
                        <input type="text" class="form-control @error('email_jobseeker') is-invalid @enderror" name="email_jobseeker" value="{{ old("email_jobseeker") }}" required>

                        @error('email_jobseeker')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-alt-primary">
                            {{ __('Save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @include('blocks.banner', ['location' => 'vacancy.new'])
    </div>
</div>
@endsection