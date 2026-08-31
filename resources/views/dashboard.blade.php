<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaaSForge Dashboard</title>
</head>
<body>
    <h1>Welcome to SaaSForge</h1>

    <p>
        Welcome, {{ auth()->user()->name ?? auth()->user()->email }}
    </p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>