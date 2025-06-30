<!DOCTYPE html>
<html lang="en">
<head>
    @include('plantilla.head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    @include('plantilla.sidebar')
    <div class="pc-header">
        @include('plantilla.header')
    </div>
    <div class="pc-container">
        <div class="pc-content">
            @yield('content')
        </div>
    </div>
    @include('plantilla.footer')
    @include('plantilla.scripts')


</body>

</html>