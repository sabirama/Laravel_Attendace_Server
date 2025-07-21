<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Daily Attendance</title>
    <link rel="stylesheet" href="{{ asset('css/user/user.css') }}">
</head>

<body>

    <div class="navbar">
        <h1>Daily Attendance</h1>
        <a href="{{ route('user') }}">
            <button>Back to Students List</button>
        </a>
    </div>

    <div class="container">
        <aside class="sidebar">
            <ul>
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('user') }}">Students</a></li>
                <li><a href="{{ route('user.create') }}">Add Students</a></li>
                <li><a href="{{ route('attendance.index') }}">Daily Attendance</a></li>
                <li><a href="{{ route('api_tokens.index') }}" class="active">Log Keys</a></li>
            </ul>
        </aside>

        <main class="main">
            <h2>Attendance for {{ \Carbon\Carbon::parse($date)->format('F j, Y') }}</h2>

            <form method="GET" action="{{ route('attendance.index') }}" style="margin-bottom: 1rem;">
                <label for="date">Select date:</label>
                <input type="date" id="date" name="date" value="{{ $date }}" onchange="this.form.submit()" />
            </form>

            <div class="table-container" style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>UID</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Group</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Get unique attendance records by UID (showing first occurrence)
                            $uniqueLogs = $attendanceLogs->unique('uid');
                            // Alternatively, to show last occurrence:
                            // $uniqueLogs = $attendanceLogs->sortByDesc('created_at')->unique('uid');
                        @endphp

                        @forelse($uniqueLogs as $log)
                            @php
                                $user = $users->get($log->uid);
                            @endphp
                            <tr>
                                <td>{{ $log->uid }}</td>
                                <td>
                                    @if($user)
                                        <a href="{{ route('user') }}?group={{ $user->group }}">{{ $user->name }}</a>
                                    @else
                                        Unknown User
                                    @endif
                                </td>
                                <td>{{ $user->contact ?? '-' }}</td>
                                <td>{{ ucfirst($user->group ?? '-') }}</td>
                                <td>{{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center;">No attendance records for this date.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>

</body>

</html>
