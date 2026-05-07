<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6; font-family: Arial, Helvetica, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f3f4f6; padding:40px 0;">
    <tr>
        <td align="center">

            <!-- MAIN CARD -->
            <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px; background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,0.08);">

                <!-- HEADER -->
                <tr>
                    <td style="background:#318069; padding:20px 30px; text-align:center;">
                        <h1 style="margin:0; font-size:20px; color:#ffffff; font-weight:600;">
                            DoctorsProfile Notification
                        </h1>
                    </td>
                </tr>

                <!-- BODY -->
                <tr>
                    <td style="padding:30px; color:#374151;">
                        <h2 style="margin-top:0; font-size:18px; color:#111827;">
                             Status Updated
                        </h2>

                        <p style="font-size:14px; line-height:1.6; margin-bottom:15px;">
                            Hello,
                        </p>

                        <p style="font-size:14px; line-height:1.6; margin-bottom:15px;">
                            We would like to inform you that the status of the tenant account associated with
                            <strong>{{ $email }}</strong> has been updated.
                        </p>

                        <!-- STATUS BOX -->
                        <div style="background:#f9fafb; border-left:4px solid #318069; padding:15px; border-radius:6px; margin:20px 0;">
                            <p style="margin:0; font-size:14px;">
                                <strong>Current Status:</strong>
                                <span style="color:#318069; font-weight:600;">
                                    {{ ucfirst($status) }}
                                </span>
                            </p>
                        </div>

                        <!-- LINKS -->
                        <p style="font-size:14px; line-height:1.6; margin-bottom:20px;">
                            You can access your website and admin panel using the links below:
                        </p>
                        @if($status === 'Active')
                        <p style="margin:0 0 10px;">
                            🌐 <strong>Website:</strong>
                            <a href="{{ $site_url }}" style="color:#318069; text-decoration:none;">
                                {{ $site_url }}
                            </a>
                        </p>

                        <p style="margin:0 0 20px;">
                            🔐 <strong>Admin Login:</strong>
                            <a href="{{ $adminlogin }}" style="color:#318069; text-decoration:none;">
                                {{ $adminlogin }}
                            </a>
                        </p>
                        @endif

                        <p style="font-size:14px; line-height:1.6;">
                            If you have any questions or need assistance, feel free to contact our support team.
                        </p>

                        <p style="font-size:14px; line-height:1.6; margin-top:25px;">
                            Best regards,<br>
                            <strong>DoctorsProfile Support Team</strong>
                        </p>
                    </td>
                </tr>

                <!-- FOOTER -->
                <tr>
                    <td style="background:#f9fafb; padding:15px 30px; text-align:center; font-size:12px; color:#6b7280;">
                        © {{ date('Y') }} DoctorsProfile. All rights reserved.
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>
