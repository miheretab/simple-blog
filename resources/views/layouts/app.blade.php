<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Blogs') }}</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ __('Blogs') }}
            </a>


            @auth
                <a class="navbar-brand" href="{{ url('/blogs/mine') }}">
                    {{ __('My Blogs') }}
                </a>
                @if (Auth::user()->role === 'admin')
                    <a class="navbar-brand" href="{{ url('/blogs-all') }}">
                        {{ __('All Blogs') }}
                    </a>
                @endif

                <div>
                    <span>{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link">Logout</button>
                    </form>
                </div>
            @else
                <div>
                    <a class="btn btn-primary" href="{{ route('login') }}">Login</a>
                    <a class="btn btn-secondary" href="{{ route('register') }}">Register</a>
                </div>
            @endauth
        </div>
    </nav>

    <main class="py-4 container">
        @yield('content')
    </main>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
