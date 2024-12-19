<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zendo Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .error {
            color: red;
        }

        .success {
            color: green;
        }

        .container {
            max-width: 500px;
            margin-top: 50px;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-group input {
            border-radius: 0.5rem;
        }

        #responseMessage {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center">Zendo Login</h1>
        <form id="loginForm">
            <div class="form-group">
                <label for="login">Mobile/Email:</label>
                <input type="text" id="login" name="login" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>

        <div id="responseMessage"></div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
  document.getElementById('loginForm').addEventListener('submit', async (event) => {
    event.preventDefault();

    const login = document.getElementById('login').value;
    const password = document.getElementById('password').value;
    const responseMessage = document.getElementById('responseMessage');

    try {
        console.log('Sending request with:', { login, password });

        const response = await fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ login, password }),
        });

        const data = await response.json();
        console.log('Server Response:', data); // Check the entire response

        if (response.ok) {
            // Handle the success message correctly
            responseMessage.innerHTML = `<p class="success">${data.Message || 'Login successful'}</p>`;
            console.log('Token:', data.Data?.token || 'No token provided');
        } else {
            // Handle the error message correctly
            responseMessage.innerHTML = `<p class="error">${data.Message || 'Login failed'}</p>`;
        }
    } catch (error) {
        console.error('Fetch Error:', error);
        responseMessage.innerHTML = `<p class="error">Unable to connect to the server. Please try again later.</p>`;
    }
});


    </script>
</body>

</html>
