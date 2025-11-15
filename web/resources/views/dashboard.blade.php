<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome, {{ session('user_name', 'User') }}</h1>
    <p>You are logged in.</p>

    <br>
    <a href="{{ route('pets.index') }}">My Pets</a>
    <br>
    <br>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>