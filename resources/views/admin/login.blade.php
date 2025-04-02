<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="d-flex align-items-center justify-content-center vh-100 bg-light">
    <div class="card p-4 shadow" style="width: 350px;">
        <h3 class="text-center mb-4">Admin Login</h3>
        <form id="loginForm">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
            <p class="text-danger mt-2 text-center" id="errorMessage" style="display: none;"></p>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('#loginForm').submit(function(event) {
                event.preventDefault();
                let username = $('#username').val();
                let password = $('#password').val();

                $.ajax({
                    url: '{{ url('http://127.0.0.1:8000/api/admin/login') }}',  // Make sure this matches your API route
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({ username, password }),
                    success: function(response) {
                        localStorage.setItem('token', response.access_token);
                        window.location.href = '{{ url('/admin/dashboard') }}';
                    },
                    error: function(xhr) {
                        $('#errorMessage').text(xhr.responseJSON?.message || 'Invalid credentials').show();
                    }
                });
            });
        });
    </script>
</body>
</html>
