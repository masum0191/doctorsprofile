<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Appointment Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #3b82f6; color: white; padding: 20px; text-align: center; }
        .content { background: #f9fafb; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .detail-item { margin-bottom: 10px; }
        .label { font-weight: bold; color: #374151; }
        .footer { text-align: center; color: #6b7280; font-size: 14px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Appointment Confirmed</h1>
        </div>

        <div class="content">
            <h2>Hello {{ $appointment->patient_first_name }},</h2>
            <p>Your appointment has been successfully booked. Here are your appointment details:</p>

            <div class="detail-item">
                <span class="label">Appointment ID:</span> {{ @$appointment->appointment_number }}
            </div>
            <div class="detail-item">
                <span class="label">Doctor:</span> Dr. {{ @$doctor->name }}
            </div>
            <div class="detail-item">
                <span class="label">Date & Time:</span> {{ @$appointment->appointment_date->format('F j, Y') }} at {{ \Carbon\Carbon::parse(@$appointment->appointment_time)->format('g:i A') }}
            </div>
            <div class="detail-item">
                <span class="label">Chamber:</span> {{ @$chamber->name }}
            </div>
            <div class="detail-item">
                <span class="label">Address:</span> {{ @$chamber->address }}, {{ @$chamber->city }}
            </div>
            <div class="detail-item">
                <span class="label">Consultation Type:</span> {{ ucfirst(@$appointment->consultation_type) }}
            </div>
            <div class="detail-item">
                <span class="label">Service:</span> {{ @$appointment->service_type }}
            </div>
            <div class="detail-item">
                <span class="label">Fees:</span> ৳{{ number_format(@$appointment->amount, 2) }}
            </div>

            @if($appointment->notes)
            <div class="detail-item">
                <span class="label">Notes:</span> {{ @$appointment->notes }}
            </div>
            @endif

            <div style="margin-top: 20px; padding: 15px; background: #dcfce7; border-radius: 6px;">
                <strong>Important Instructions:</strong>
                <ul>
                    <li>Please arrive 15 minutes before your appointment time</li>
                    <li>Bring your ID and any relevant medical reports</li>
                    <li>For online consultations, you'll receive a link 30 minutes before the appointment</li>
                </ul>
            </div>
        </div>

        <div class="footer">
            <p>If you need to reschedule or cancel, please contact us at least 24 hours in advance.</p>
            <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
