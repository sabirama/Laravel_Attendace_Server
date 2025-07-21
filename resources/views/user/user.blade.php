<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Students List</title>
    <link rel="stylesheet" href="{{ asset('css/user/user.css') }}">
</head>

<body>

    <div class="navbar">
        <h1>Students Management</h1>
        <a href="{{ route('user.create') }}">
            <button>Add Students</button>
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
            <h2>Students List</h2>

            <form method="GET" action="{{ route('user') }}">
                <label for="group">Filter by Group:</label>
                <select name="group" id="group" onchange="this.form.submit()">
                    <option value="all" {{ (!isset($groupFilter) || $groupFilter === 'all') ? 'selected' : '' }}>All Groups</option>
                    @foreach($groups as $group)
                        <option value="{{ $group }}" {{ (isset($groupFilter) && $groupFilter == $group) ? 'selected' : '' }}>
                            {{ ucfirst($group) }}
                        </option>
                    @endforeach
                </select>

                <label for="sortBy">Sort by:</label>
                <select name="sortBy" id="sortBy" onchange="this.form.submit()">
                    <option value="uid" {{ (isset($sortBy) && $sortBy == 'uid') ? 'selected' : '' }}>UID</option>
                    <option value="name" {{ (isset($sortBy) && $sortBy == 'name') ? 'selected' : '' }}>Name</option>
                    <option value="contact" {{ (isset($sortBy) && $sortBy == 'contact') ? 'selected' : '' }}>Contact</option>
                    <option value="group" {{ (isset($sortBy) && $sortBy == 'group') ? 'selected' : '' }}>Group</option>
                </select>
            </form>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>UID</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Group</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->uid }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->contact }}</td>
                                <td>{{ ucfirst($user->group) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align:center;">No students found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>

</body>

</html>
