<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Subscription Reminder</title>
</head>

<body style="font-family: Arial; background:#f4f6f8; padding:20px;">

    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">

                <table width="600" style="background:#ffffff; padding:20px; border-radius:6px;">

                    <tr>
                        <td style="background:#0b74de; color:#fff; padding:15px; text-align:center;">
                            <h2 style="margin:0;">Subscription Renewal Reminder</h2>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:20px;">

                            <p>Hello <strong>{{ $subscription->doctor->name }}</strong>,</p>

                            @if ($daysLeft > 0)
                                <p>
                                    Your <strong>{{ $subscription->package->name }}</strong> subscription will expire in
                                    <strong>{{ $daysLeft }} day{{ $daysLeft > 1 ? 's' : '' }}</strong>.
                                </p>
                            @else
                                <p>
                                    Your subscription has expired.
                                </p>
                            @endif

                            <div style="background:#f7f9fb; padding:12px; margin:15px 0; border-radius:4px;">
                                <strong>Plan:</strong> {{ $subscription->package->name }} <br>
                                <strong>Expiry Date:</strong>
                                {{ \Carbon\Carbon::parse($subscription->ends_at)->format('d M Y') }} <br>
                                <strong>Billing Cycle:</strong> {{ ucfirst($subscription->billing_cycle) }}
                            </div>

                            <p>
                                To avoid interruption of service, please renew your subscription.
                            </p>

                            <p style="margin-top:20px;">
                                <a href="{{ url('/packages') }}"
                                    style="background:#0b74de; color:#fff; padding:10px 18px; text-decoration:none; border-radius:4px;">
                                    Renew Now
                                </a>
                            </p>

                            <hr style="margin:20px 0;">

                            <p style="font-size:13px; color:#666;">
                                If you have any questions, contact support at
                                <a href="mailto:support@doctorsprofile.xyz">support@doctorsprofile.xyz</a>
                            </p>

                        </td>
                    </tr>

                    <tr>
                        <td style="text-align:center; font-size:12px; color:#999; padding:10px;">
                            © {{ date('Y') }} DoctorsProfile. All rights reserved.
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
