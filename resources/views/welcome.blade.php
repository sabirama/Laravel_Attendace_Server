<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Page</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />

    @auth('admin')
        <script>
            window.location.href = "{{ route('dashboard') }}";
        </script>
    @endauth
</head>

<body>
    @include('components.header')

    <section class="home">
        <h1>Welcome to the App</h1>
        <p>Please log in or register to continue.</p>

        <div class="button-container">
            <a href="{{ route('login') }}" class="button">Login</a>
            <a href="{{ route('register') }}" class="button">Register</a>
        </div>
    </section>
</body>

</html>
