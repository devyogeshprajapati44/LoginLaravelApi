<!DOCTYPE html>
<html>
<head>
    <title>Generate OTP</title>
</head>
<body>
    <form method="POST" action="/otp-generate">
        @csrf
        <label>Mobile Number:</label>
        <input type="text" name="mobile" required>
        <button type="submit">Generate OTP</button>
    </form>
</body>
</html>
