@extends('layouts.app')

@section('title', __("Actions"))
@section('description', __("Actions"))

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
                {{ __("Actions") }}
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
            <h3 class="mb-3">{{ __("Actions") }}</h3>
            <div>
                @if ($actions->hasPages())
                    <div class="d-flex align-items-center justify-content-end mt-4">
                        {{ $actions->links() }}
                    </div>
                @endif
            </div>
        </div>

        <table class="table table-hover table-vcenter">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">#</th>
                    <th>{{ __('Time') }}</th>
                    <th>{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @if ($actions->count())
                    @foreach($actions as $action)
                        <tr>
                            <th class="text-center" scope="row">{{ $action->id }}</th>
                            <td class="fw-semibold fs-sm">
                                {{ Format::date($action->created_at) }}
                            </td>
                            <td class="fw-semibold fs-sm">
                                {{ $action->getLabel() }}
                                @if (in_array($action->type, ['create_post', 'update_post']))
                                    @php
                                        $post = $user->posts()->find($action->content['post_id']);
                                    @endphp
                                    @continue(!$post)

                                    &laquo;<a href="{{ url("post/{$post->slug}") }}">{{ $post->title }}</a>&raquo;
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

        @if ($actions->hasPages())
            <div class="d-flex align-items-center justify-content-end mt-4">
                {{ $actions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection