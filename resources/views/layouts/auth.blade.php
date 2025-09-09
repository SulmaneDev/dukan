@extends('layouts.master')
@section('content')

<body class="account-page bg-white">
    <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div>

    <div class="main-wrapper">
        <div class="account-content">
            @yield('main')
        </div>
    </div>
</body>
@endsection