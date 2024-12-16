<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP</title>
</head>
<body>
    <form method="POST" action="/otp-verify">
        @csrf
        <label>Mobile Number:</label>
        <input type="text" name="mobile" required>
        <label>OTP:</label>
        <input type="text" name="otp" required>
        <button type="submit">Verify OTP</button>
    </form>
</body>
</html>
