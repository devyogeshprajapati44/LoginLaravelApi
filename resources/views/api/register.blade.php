<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Zendo-Register</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        <h1 class="text-center">Zendo-Register</h1>
        <form  action="{{ route('api.register') }}"  method="POST" id="registerForm">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="mobile">Mobile:</label>
                <input type="text" id="mobile" name="mobile" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirm Password:</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Register</button>
        </form>

        <div id="responseMessage"></div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    document.getElementById('registerForm').addEventListener('submit', async (event) => {
        event.preventDefault();

        const username = document.getElementById('username').value;
        const email = document.getElementById('email').value;
        const mobile = document.getElementById('mobile').value;
        const password = document.getElementById('password').value;
        const password_confirmation = document.getElementById('password_confirmation').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        try {
            const response = await fetch('/api/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,  // Include the CSRF token here
                },
                body: JSON.stringify({ username, email, mobile, password, password_confirmation }),
            });

            const data = await response.json();
            const responseMessage = document.getElementById('responseMessage');

            if (response.ok) {
                const message = data.Message || data.message || "User registered successfully!";
                responseMessage.innerHTML = `<p class="success">${message}</p>`;
                console.log('Registration Successful:', data);
            } else {
                const errorMessages = data.errors || {};
                let errorMessageHTML = "<ul>";
                for (const [field, messages] of Object.entries(errorMessages)) {
                    messages.forEach(message => {
                        errorMessageHTML += `<li>${message}</li>`;
                    });
                }
                errorMessageHTML += "</ul>";
                responseMessage.innerHTML = `<p class="error">${errorMessageHTML}</p>`;
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
