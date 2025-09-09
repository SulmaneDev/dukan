@extends('layouts.master')
@section('content')

<body>

    <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div>
    <div class="main-wrapper">
        @include('components.admin.header')
        @include('components.admin.sidebar')
        <div class="page-wrapper">
            <div class="content">
                @yield('main')
            </div>
        </div>

    </div>
    @include('components.common.delete-modal')
</body>
@endsection