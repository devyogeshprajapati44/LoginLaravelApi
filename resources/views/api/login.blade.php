<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zendo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
  <style>
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <h1>Zendo Login</h1>

    <form action="{{ route('login') }}" id="loginForm">
        <div>
            <label for="login">Mobile/Email:</label>
            <input type="text" id="login" name="login" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Login</button>
    </form>

    <div id="responseMessage"></div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async (event) => {
            event.preventDefault();

            const login = document.getElementById('login').value;
            const password = document.getElementById('password').value;

            try {
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ email, password }),
                });

                const data = await response.json();
                const responseMessage = document.getElementById('responseMessage');

                if (response.ok) {
                    responseMessage.innerHTML = `<p class="success">${data.Message}</p>`;
                    console.log('Token:', data.Data.token);
                } else {
                    responseMessage.innerHTML = `<p class="error">${data.Message}</p>`;
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('responseMessage').innerHTML = 
                    `<p class="error">An unexpected error occurred. Please try again later.</p>`;
            }
        });
    </script>
</body>
</html>
