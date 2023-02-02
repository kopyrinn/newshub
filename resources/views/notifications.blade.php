@extends('layouts.app')

@section('title', __("Notifications"))
@section('description', __("Notifications"))

@section('content')
<!-- <div class="justify-content-start d-flex">
    <nav class="flex-shrink-1 mb-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt flex-nowrap">
            <li class="breadcrumb-item d-flex text-nowrap">
                <a class="link-fx" href="{{ url('/') }}">{{ __("Home") }}</a>
            </li>
            <li class="breadcrumb-item d-flex text-nowrap">
                <a class="link-fx" href="{{ url('users') }}">{{ __("Users") }}</a>
            </li>
            <li class="breadcrumb-item d-flex text-nowrap">
                <a class="link-fx" href="{{ url("user/{$user->id}") }}">{{ $user->name }}</a>
            </li>
            <li class="breadcrumb-item text-truncate" aria-current="page">
                {{ __("Notifications") }}
            </li>
        </ol>
    </nav>
</div> -->

<div class="row mb-4">
    <div class="col-lg-3 col-md-4">
        @include('user_sidebar')
    </div>
    <div class="col-lg-9 col-md-8">
        <div class="d-flex align-items-center justify-content-between">
            <h3 class="mb-3">{{ __("Notifications") }}</h3>
            <div>
                @if ($notifications->hasPages())
                    <div class="d-flex align-items-center justify-content-end mt-4">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>

        <table class="table table-hover table-vcenter">
            <thead>
                <tr>
                    <!-- <th class="text-center" style="width: 50px;">#</th>
                    <th>{{ __('Time') }}</th> -->
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @if ($notifications->count())
                    @foreach($notifications as $notification)
                        <tr>
                            <td class="fw-semibold fs-sm">
                                @if (!empty($notification->data['post_id']))
                                    @php
                                        $notificationPost = App\Models\Post::find($notification->data['post_id']);
                                        if (!$notificationPost) {
                                            $notification->markAsRead();
                                        }
                                    @endphp
                                    @continue(!$notificationPost)
                                    <a class="text-dark d-flex py-2" href="{{ url("post/{$notificationPost->slug}") }}">
                                        <div class="flex-shrink-0 me-2 ms-3">
                                            <i class="fa fa-fw fa-check-circle text-success"></i>
                                        </div>
                                        <div class="flex-grow-1 pe-2">
                                            <div class="fw-semibold">{{ __('New Post') }}: {{ $notificationPost->title }}</div>
                                            <span class="fw-medium text-muted">{{ $notification->created_at->diffForHumans() }}</span>
                                        </div>
                                    </a>
                                @elseif(!empty($notification->data['package']))
                                    <a class="text-dark d-flex py-2" href="{{ url("package/{$notification->data['package']}") }}">
                                        <div class="flex-shrink-0 me-2 ms-3">
                                            <i class="fa fa-fw fa-check-circle text-success"></i>
                                        </div>
                                        <div class="flex-grow-1 pe-2">
                                            <div class="fw-semibold">{{ __('Your service package ends in 30 days. We recommend extending services in advance.') }}</div>
                                            <span class="fw-medium text-muted">{{ $notification->created_at->diffForHumans() }}</span>
                                        </div>
                                    </a>
                                @endif
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

        @if ($notifications->hasPages())
            <div class="d-flex align-items-center justify-content-end mt-4">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection