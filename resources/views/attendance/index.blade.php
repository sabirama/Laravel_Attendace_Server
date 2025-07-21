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
        <div>
            <button onclick="downloadCSV()">Download Current View as CSV</button>
            <a href="{{ route('logout') }}" style="margin-right: 10px;">
                <button>Logout</button>
            </a>
        </div>
    </div>

    <div class="container">
        <aside class="sidebar">
            <ul>
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('user') }}">Students</a></li>
                <li><a href="{{ route('user.create') }}">Add Students</a></li>
                <li><a href="{{ route('attendance.index') }}" class="active">Daily Attendance</a></li>
                <li><a href="{{ route('api_tokens.index') }}">Log Keys</a></li>
            </ul>
        </aside>

        <main class="main">
            <h2>Attendance for {{ \Carbon\Carbon::parse($date)->format('F j, Y') }}</h2>

            <form method="GET" action="{{ route('attendance.index') }}" style="margin-bottom: 1rem;">
                <label for="date">Select date:</label>
                <input type="date" id="date" name="date" value="{{ $date }}" onchange="this.form.submit()" />

                <label for="group" style="margin-left: 1rem;">Filter by group:</label>
                <select name="group" id="group" onchange="this.form.submit()">
                    <option value="">All Groups</option>
                    @php
                        $groupOptions = $users->pluck('group')->filter()->unique()->sort();
                    @endphp
                    @foreach ($groupOptions as $groupName)
                        <option value="{{ $groupName }}" {{ $groupName == ($group ?? '') ? 'selected' : '' }}>
                            {{ ucfirst($groupName) }}
                        </option>
                    @endforeach
                </select>
            </form>

            <div class="table-container" style="overflow-x: auto;">
                <table id="attendanceTable">
                    <thead>
                        <tr>
                            <th>UID</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Group</th>
                            <th>Status</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $groupedLogs = $attendanceLogs->groupBy('uid');
                        @endphp

                        @forelse($groupedLogs as $uid => $logs)
                            @php
                                $user = $users->get($uid);
                                $firstLog = $logs->sortBy('created_at')->first();
                                $lastLog = $logs->sortByDesc('created_at')->first();
                            @endphp
                            <tr>
                                <td>{{ $uid }}</td>
                                <td>
                                    @if($user)
                                        <a href="{{ route('user') }}?group={{ $user->group }}">{{ $user->name }}</a>
                                    @else
                                        Unknown User
                                    @endif
                                </td>
                                <td>{{ $user->contact ?? '-' }}</td>
                                <td>{{ ucfirst($user->group ?? '-') }}</td>
                                <td>IN</td>
                                <td>{{ \Carbon\Carbon::parse($firstLog->created_at)->format('H:i:s') }}</td>
                            </tr>
                            @if($firstLog->id != $lastLog->id)
                                <tr>
                                    <td>{{ $uid }}</td>
                                    <td>
                                        @if($user)
                                            <a href="{{ route('user') }}?group={{ $user->group }}">{{ $user->name }}</a>
                                        @else
                                            Unknown User
                                        @endif
                                    </td>
                                    <td>{{ $user->contact ?? '-' }}</td>
                                    <td>{{ ucfirst($user->group ?? '-') }}</td>
                                    <td>OUT</td>
                                    <td>{{ \Carbon\Carbon::parse($lastLog->created_at)->format('H:i:s') }}</td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center;">No attendance records for this date.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script>
        function downloadCSV() {
            const rows = document.querySelectorAll('#attendanceTable tr');
            let csvContent = "data:text/csv;charset=utf-8,";

            const headers = [];
            document.querySelectorAll('#attendanceTable th').forEach(header => {
                headers.push(header.innerText);
            });
            csvContent += headers.join(",") + "\n";

            rows.forEach(row => {
                const cols = row.querySelectorAll('td');
                if (cols.length > 0) {
                    let rowData = [];
                    cols.forEach(col => {
                        rowData.push(col.innerText);
                    });
                    csvContent += rowData.join(",") + "\n";
                }
            });

            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "attendance_{{ \Carbon\Carbon::parse($date)->format('Y-m-d') }}.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>

</body>

</html>
