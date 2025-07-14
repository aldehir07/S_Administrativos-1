<!DOCTYPE html>
<html lang="en">
<head>
    @include('plantilla.head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body class="d-flex align-items-center justify-content-center" style="min-height:100vh; background-color:#f5f7fb;">
    @yield('content')

    @include('plantilla.scripts')
</body>
</html>
