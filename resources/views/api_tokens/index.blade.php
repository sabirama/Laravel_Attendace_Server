<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>API Keys Management</title>
    <link rel="stylesheet" href="{{ asset('css/user/user.css') }}">
</head>

<body>

    <div class="navbar">
        <h1>API Keys Management</h1>
        <!-- You can add a button here if you want navigation or extra actions -->
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
            <h2>Manage API Keys</h2>

            <form method="POST" action="{{ route('api_tokens.store') }}" style="margin-bottom: 1.5rem;">
                @csrf
                <label for="name">API Key Name:</label>
                <input type="text" id="name" name="name" required placeholder="Enter a descriptive name" style="margin-left: 0.5rem; padding: 0.3rem 0.5rem; font-size: 1rem;">
                <button type="submit" style="margin-left: 1rem; padding: 0.4rem 1rem;">Generate New Key</button>
            </form>

            @if(session('status'))
                <div class="alert" style="margin-bottom: 1rem; color: green;">
                    {{ session('status') }}
                </div>
            @endif

            @if($apiTokens->isEmpty())
                <p>No API keys found.</p>
            @else
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Token (hidden after creation)</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($apiTokens as $token)
                                <tr>
                                    <td>{{ $token->name }}</td>
                                    <td>
                                        <button type="button" onclick="toggleToken({{ $token->id }})" class="token-toggle-btn"
                                            style="background:none; border:none; color:blue; cursor:pointer; text-decoration:underline;">Show</button>
                                    </td>
                                    <td>{{ $token->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('api_tokens.destroy', $token) }}" onsubmit="return confirm('Are you sure you want to delete this API key?');"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="delete-btn" style="color: red; background:none; border:none; cursor:pointer; text-decoration: underline;">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </main>
    </div>

    <script>
        function toggleToken(id) {
            const button = event.target;
            if (button.textContent === "Show") {
                button.textContent = "{{ $token->token }}";
            } else {
                button.textContent = "Show";
            }
        }
    </script>

</body>

</html>
