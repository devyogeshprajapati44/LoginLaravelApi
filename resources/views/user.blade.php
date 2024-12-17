<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Info</title>
</head>
<body>
    <h1>User Info</h1>
    @if ($user)
        <p>Name: {{ $user->name }}</p>
        <p>Email: {{ $user->email }}</p>
    @else
        <p>No user is logged in.</p>
    @endif
</body>
</html>
