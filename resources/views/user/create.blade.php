<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Add Students</title>
    <link rel="stylesheet" href="{{ asset('css/user/user.css') }}">
</head>

<body>

    <div class="navbar">
        <h1>Add Students</h1>
        <a href="{{ route('logout') }}">
            <button>Logout</button>
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

            @if(session('status'))
                <div class="status-message">{{ session('status') }}</div>
            @endif

            <div class="instructions">
                Paste your students here as tab-separated values:<br />
                <strong>UID[TAB]Name[TAB]Contact[TAB]Group</strong><br />
                Example:<br />
                <code>1011[TAB]Elena Cruz[TAB]09171231231[TAB]section-d</code><br /><br />
                Each user on a new line.
            </div>

            <form method="POST" action="{{ route('user.store') }}">
                @csrf
                <label for="bulk_input">Paste students list (UID[TAB]Name[TAB]Contact[TAB]Group):</label>
                <textarea id="bulk_input" name="bulk_input" rows="10" placeholder="1011[TAB]Elena Cruz[TAB]09171231231[TAB]section-d"></textarea>

                <fieldset>
                    <legend>Duplicate Handling:</legend>
                    <label>
                        <input type="radio" name="duplicate_action" value="skip" checked>
                        Skip duplicates
                    </label>
                    <label>
                        <input type="radio" name="duplicate_action" value="overwrite">
                        Overwrite duplicates
                    </label>
                </fieldset>

                <button type="submit">Add Users</button>
            </form>
        </main>
    </div>

</body>

</html>
