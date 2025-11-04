<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Password Reset</title>
</head>
<body>
    <h1>Hello {{ $user->name }}</h1>
    <p>You have requested for password reset</p>
    <p>Please click the link bellow to reset your password</p>
    <a href="{{ url('/password/password_reset?token='.$token) }}">Password Reset Link</a>

    <p>Remember ! the token is valid for 60 minutes</p>
    <p>If You did not request a password reset, then just ignore the email</p>

    <h2>Thank You</h2>
</body>
</html>