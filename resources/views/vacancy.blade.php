@extends('layouts.app')

@section('title', $vacancy->job_title)
@section('description', strip_tags(html_entity_decode($vacancy->task)))

@section('content')
<div class="row mb-4">
    <div class="col-lg-8">
        <div class="justify-content-start d-flex">
            <nav class="flex-shrink-1 mb-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt flex-nowrap">
                    <li class="breadcrumb-item d-flex text-nowrap">
                        <a class="link-fx" href="{{ url('/') }}">{{ __("Home") }}</a>
                    </li>
                    <li class="breadcrumb-item text-truncate" aria-current="page">
                        <a class="link-fx" href="{{ url("vacancies") }}">
                            {{ __("Vacancies") }}
                        </a>
                    </li>
                    <li class="breadcrumb-item text-truncate" aria-current="page">
                        {{ Str::limit($vacancy->job_title, 50) }}
                    </li>
                </ol>
            </nav>
        </div>

        <div class="d-md-flex align-items-center">
            <div>
                <h3 class="mb-1">
                    {{ $vacancy->job_title }}
                </h3>
                <div class="fs-sm fw-medium mb-3">
                    <a href="{{ url("user/{$vacancy->user_id}") }}">{{ $vacancy->user->name }}</a> · {{ Date::parse($vacancy->created_at)->format('j F Y') }} · <span class="text-muted fs-xs"><i class="fa fa-eye"></i> {{ $vacancy->vacancy_view }}</span>
                </div>
            </div>
        </div>

        <h4 class="mb-0">{{ __("Requirements") }}</h4>
        <p>{!! $vacancy->requiremets !!}</p>

        <h4 class="mb-0">{{ __("Tasks") }}</h4>
        <p>{!! $vacancy->task !!}</p>

        <h4 class="mb-0">{{ __("Conditions") }}</h4>
        <p>{!! $vacancy->conditionsm !!}</p>

        <h4 class="mb-0">{{ __("Contacts") }}</h4>
        <p>Email: <a href="mailto:{{ $vacancy->email_jobseeker }}">{{ $vacancy->email_jobseeker }}</a></p>

        @include('blocks.banner', ['location' => 'vacancy.view'])
    </div>

    @include('sidebar')
</div>
@endsection
