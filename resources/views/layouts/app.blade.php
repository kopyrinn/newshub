@extends('layouts.general')

@section('page-content')
    <div class="content py-0 px-0 px-sm-4">
        <div class="mb-0">
            <div class="block-content">
                @include('blocks.alerts')
                @yield('breadcrumb')
                @yield('content')
            </div>
        </div>
    </div>
@endsection