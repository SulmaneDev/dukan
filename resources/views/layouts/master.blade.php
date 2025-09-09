<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Dukan') }}</title>
    <link rel="shortcut icon" type="image/x-icon" href="/assets/img/favicon.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/img/apple-touch-icon.png">
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="/assets/css/animate.css">
    <link rel="stylesheet" href="/assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/assets/plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="/assets/plugins/tabler-icons/tabler-icons.css">
    <link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="/assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/datatable.css">
    <link rel="stylesheet" href="/assets/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link href="/assets/css/summernote-bs4.min.css" rel="stylesheet">
    <!-- Owl Carousel CSS -->
    <link rel="stylesheet" href="/assets/plugins/owlcarousel/owl.carousel.min.css">
    <link rel="stylesheet" href="/assets/plugins/owlcarousel/owl.theme.default.min.css">
</head>

@yield('content')
<script src="/assets/js/jquery-3.7.1.min.js"></script>
<script src="/assets/js/table.js"></script>
<script src="/assets/js/datatable.js"></script>
<script src="/assets/js/script.js"></script>
<script src="/assets/js/feather.min.js"></script>
<script src="/assets/js/jquery.slimscroll.min.js"></script>
<script src="/assets/js/bootstrap.bundle.min.js"></script>
<script src="/assets/plugins/apexchart/apexcharts.min.js"></script>
<script src="/assets/plugins/apexchart/chart-data.js"></script>
<script src="/assets/plugins/chartjs/chart.min.js"></script>
<script src="/assets/plugins/chartjs/chart-data.js"></script>
<script src="/assets/js/moment.min.js"></script>
<script src="/assets/plugins/daterangepicker/daterangepicker.js"></script>
<script src="/assets/plugins/select2/js/select2.min.js"></script>
<script src="/assets/plugins/%40simonwep/pickr/pickr.es5.min.js"></script>
<script src="/assets/js/theme-colorpicker.js"></script>
<script src="/assets/js/rocket-loader.min.js" data-cf-settings="4444127bcda206d2f95788f6-|49" defer></script>
<script src="/assets/js/dashboard.js"></script>
<script defer src="/assets/js/beacon.js"
    data-cf-beacon='{"rayId":"93f77b316b95d051","version":"2025.4.0-1-g37f21b1","serverTiming":{"name":{"cfExtPri":true,"cfL4":true,"cfSpeedBrain":true,"cfCacheStatus":true}},"token":"3ca157e612a14eccbb30cf6db6691c29","b":1}'
    crossorigin="anonymous"></script>
<script src="/assets/js/bootstrap-datetimepicker.min.js"></script>
<script src="/assets/js/summernote-bs4.min.js"></script>
<script src="/assets/plugins/theia-sticky-sidebar/ResizeSensor.js"></script>
<script src="/assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js"></script>
<script src="/assets/plugins/owlcarousel/owl.carousel.min.js"></script>
<script src="/assets/js/calculator.js"></script>
@include('components.common.toast')
@yield('scripts')

</html>