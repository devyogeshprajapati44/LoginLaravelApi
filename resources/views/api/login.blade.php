<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <h1>Login</h1>

    <form id="loginForm">
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Login</button>
    </form>

    <div id="responseMessage"></div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async (event) => {
            event.preventDefault();

            const email = document.getElementById('email').value;
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
