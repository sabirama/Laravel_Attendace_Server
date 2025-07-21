<!-- header -->
<header>
    <link rel="stylesheet" href="{{ asset('css/components/header.css') }}" />
    <nav>
        <h1><a href="/">Attendance Logs</a></h1>
        <ul>
            <li>
                @php
                    $currentPath = request()->path();
                @endphp

                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                @endauth

                @guest
                    @if ($currentPath === 'login')
                        <a href="{{ route('register') }}">Register</a>
                    @elseif ($currentPath === 'register')
                        <a href="{{ route('login') }}">Login</a>
                    @else
                        <a href="{{ route('login') }}">Login</a> {{-- Or Register, pick one --}}
                    @endif
                @endguest

            </li>
        </ul>
    </nav>
</header>
