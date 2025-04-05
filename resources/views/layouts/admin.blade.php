<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>

    <!-- AdminLTE Styles -->
    <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">

    <!-- jQuery (If Required) -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

</head>
<body>

    <nav class="navbar navbar-dark bg-white">
        <div class="container">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}"></a>
        </div>
    </nav>

    <div class="container mt-4">
        @yield('content')
    </div>
     
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
