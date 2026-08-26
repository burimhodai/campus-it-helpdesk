<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Campus IT Help Desk')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="site-header">
        <div class="container header-inner">
            <a class="brand" href="{{ route('home') }}" aria-label="Campus IT Help Desk home">
                <span class="brand-mark">IT</span>
                <span>Campus Help Desk</span>
            </a>

            <nav class="main-nav" aria-label="Main navigation">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Overview</a>
                        <a class="{{ request()->routeIs('admin.tickets.*') ? 'active' : '' }}" href="{{ route('admin.tickets.index') }}">Tickets</a>
                        <a class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">Categories</a>
                    @else
                        <a class="{{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                        <a class="{{ request()->routeIs('tickets.*') ? 'active' : '' }}" href="{{ route('tickets.index') }}">My tickets</a>
                        <a class="button button-small" href="{{ route('tickets.create') }}">New ticket</a>
                    @endif

                    <span class="nav-user">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="nav-button" type="submit">Sign out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Sign in</a>
                    <a class="button button-small" href="{{ route('register') }}">Create account</a>
                @endauth
            </nav>
        </div>
    </header>

    <main>
        @if(session('success'))
            <div class="container flash flash-success" role="status">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="container flash flash-error" role="alert">
                <strong>Please correct the following:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container footer-inner">
            <span>Campus IT Help Desk</span>
            <span>Web Programming final project</span>
        </div>
    </footer>
</body>
</html>
