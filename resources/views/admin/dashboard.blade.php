@php
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

$totalStudents = DB::table('users')->count();

$today = Carbon::today()->toDateString();

$currentAttendance = DB::table('attendance_logs')
    ->whereDate('created_at', $today)
    ->distinct('uid')
    ->count('uid');
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}" />
</head>

<body>

    <div class="navbar">
        <h1>Admin Dashboard</h1>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>

    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
           <ul>
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('user') }}">Students</a></li>
                <li><a href="{{ route('user.create') }}">Add Students</a></li>
                <li><a href="{{ route('attendance.index') }}">Daily Attendance</a></li>
                <li><a href="{{ route('api_tokens.index') }}" class="active">Log Keys</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main">
            <h2>Welcome, {{ Auth::guard('admin')->user()->name ?? 'Admin' }}!</h2>

            <div class="cards">
                <div class="card">
                    <h3>Total Students</h3>
                    <p>{{ $totalStudents }}</p>
                </div>
                <div class="card">
                    <h3>Current Attendance</h3>
                    <p>{{ $currentAttendance }}</p>
                </div>
            </div>
        </main>
    </div>

</body>

</html>
