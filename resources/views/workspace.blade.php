@extends('layouts.app')

@section('title', __("Workspace"))
@section('description', __("Workspace"))

@section('content')
<!-- <div class="justify-content-start d-flex">
    <nav class="flex-shrink-1 mb-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt flex-nowrap">
            <li class="breadcrumb-item d-flex text-nowrap">
                <a class="link-fx" href="{{ url('/') }}">{{ __("Home") }}</a>
            </li>
            <li class="breadcrumb-item d-flex text-nowrap">
                <a class="link-fx" href="{{ url('profile') }}">{{ __("Profile") }}</a>
            </li>
            <li class="breadcrumb-item text-truncate" aria-current="page">
                {{ __("Workspace") }}
            </li>
        </ol>
    </nav>
</div> -->

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between">
            <h3 class="mb-3">{{ __("My Posts") }}</h3>
            <div>
                @if ($posts->hasPages())
                    <div class="d-flex align-items-center justify-content-end mt-4">
                        {{ $posts->links() }}
                    </div>
                @endif
            </div>
        </div>

        <table class="table table-hover table-vcenter">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">#</th>
                    <th>{{ __('Title') }}</th>
                    <th>{{ __('Created At') }}</th>
                    <th>{{ __('Category') }}</th>
                    <th>{{ __('Rubric') }}</th>
                    <th>{{ __('Statistic') }}</th>
                    <th class="text-end" style="width: 100px;">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @if ($posts->count())
                    @foreach($posts as $post)
                        <tr>
                            <th class="text-center" scope="row">{{ $post->id }}</th>
                            <td class="fw-semibold fs-sm" style="width: 35%;">
                                @if (!$post->reject && !$post->status)<span class="fs-xs fw-semibold d-inline-block py-1 px-2 rounded bg-warning text-white">{{ __('Moderation')  }}</span>@endif
                                <a href="{{ url("post/{$post->slug}") }}">{{ $post->title }}</a>
                                @if ($post->reject)<div class="text-danger fs-xs text-start">{{ __('Rejected')  }}: {{ $post->reason }}</div>@endif
                            </td>
                            <td class="fw-semibold fs-sm">
                                {{ Format::date($post->created_at) }}
                            </td>
                            <td>
                                <span class="fs-xs fw-semibold d-inline-block py-1 px-2 rounded bg-primary text-white">{{ $post->categories()->first()->name }}</span>
                            </td>
                            <td>
                                <span class="fs-xs fw-semibold d-inline-block py-1 px-2 rounded bg-primary text-white">{{ $post->rubrics()->pluck('name')->join(', ')?: '-' }}</span>
                            </td>
                            <td>
                                <span class="fs-xs fw-semibold d-inline-block py-1 px-2 rounded bg-light text-dark" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Views') }}"><i class="fa fa-eye"></i> {{ $post->pageviews }}</span>
                                <span class="fs-xs fw-semibold d-inline-block py-1 px-2 rounded bg-light text-dark" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Downloads') }}"><i class="fa fa-download"></i> {{ $post->files_downloaded }}</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{ route("workspace.post", ['slug' => $post->slug]) }}" class="btn btn-sm btn-alt-secondary">
                                        <i class="fa fa-fw fa-pencil-alt"></i>
                                    </a>
                                    <a href="javascript://" class="btn btn-sm btn-alt-danger delete-post" data-slug="{{ $post->slug }}">
                                        <i class="fa fa-fw fa-times"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="4">{{ __("No Data") }}</td>
                    </tr>
                @endif
            </tbody>
        </table>

        @if ($posts->hasPages())
            <div class="d-flex align-items-center justify-content-end mt-4">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(document).on('click', '.delete-post', function(e) {
            var slug = $(this).data('slug');

            sw.fire({
                title: "{{ __('Are you sure you want to delete this resource?') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "{{ __('Yes') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `workspace/delete/${slug}`;
                }
            })
        });
    </script>
@endpush