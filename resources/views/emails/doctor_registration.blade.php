<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Doctor Registration Successful</title>
</head>
<body>
    <h1>Doctor Registration Successful</h1>
    <p>Dear {{ $name }},</p>
    {{-- login url --}}
    <p>Your login URL: <a href="{{ url('/admin/login') }}">{{ url('/admin/login') }}</a></p>
    <p>Thank you for registering as a doctor on our platform. Your account has been successfully created.</p>
    <p>Please log in to your account to get started.</p>
    <p>Best regards,</p>
    <p>The Doctorsprofile Team</p>
</body>
</html>
