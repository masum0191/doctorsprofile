<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Flight Booking Confirmation') }}</title>
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --text-color: #1f2937;
            --light-text: #6b7280;
            --border-color: #e5e7eb;
            --bg-light: #f9fafb;
            --white: #ffffff;
        }
        
        body {
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            max-width: 700px;
            margin: 0 auto;
            padding: 0;
            background-color: var(--bg-light);
        }
        
        .container {
            background-color: var(--white);
            padding: 2rem;
            margin: 1rem auto;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .header h1 {
            color: var(--primary-color);
            margin-bottom: 0.5rem;
            font-size: 1.75rem;
            font-weight: 600;
        }
        
        .header p {
            color: var(--light-text);
            margin-top: 0;
        }
        
        .booking-info {
            background: var(--bg-light);
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 0.75rem;
            flex-wrap: wrap;
        }
        
        .info-label {
            font-weight: 600;
            min-width: 150px;
            color: var(--light-text);
        }
        
        .flight-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 2rem 0;
            position: relative;
        }
        
        .flight-segment {
            text-align: center;
            flex: 1;
            padding: 0 1rem;
        }
        
        .flight-segment .city {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 0.25rem;
        }
        
        .flight-segment .code {
            color: var(--light-text);
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }
        
        .flight-segment .time {
            font-size: 1.125rem;
            font-weight: 500;
            margin-bottom: 0.25rem;
        }
        
        .flight-segment .date {
            color: var(--light-text);
            font-size: 0.875rem;
        }
        
        .flight-arrow {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            color: var(--light-text);
            font-size: 1.5rem;
        }
        
        .divider {
            border-top: 1px dashed var(--border-color);
            margin: 2rem 0;
        }
        
        .footer {
            text-align: center;
            margin-top: 2rem;
            color: var(--light-text);
            font-size: 0.875rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
        }
        
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: var(--primary-color);
            color: var(--white);
            text-decoration: none;
            border-radius: 0.375rem;
            margin: 1.5rem 0;
            font-weight: 500;
            transition: background-color 0.2s;
        }
        
        .btn:hover {
            background-color: var(--secondary-color);
        }
        
        .text-center {
            text-align: center;
        }
        
        .mt-1 { margin-top: 0.25rem; }
        .mt-2 { margin-top: 0.5rem; }
        .mt-4 { margin-top: 1rem; }
        
        @media (max-width: 640px) {
            .flight-details {
                flex-direction: column;
            }
            
            .flight-segment {
                margin-bottom: 1.5rem;
                padding: 0;
            }
            
            .flight-arrow {
                position: relative;
                transform: none;
                left: auto;
                margin: 1rem 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ __('Flight Booking Confirmed') }}</h1>
            <p>{{ __('Your booking #:booking_id has been confirmed', ['booking_id' => $booking->id]) }}</p>
        </div>

        <div class="booking-info">
            <div class="info-row">
                <div class="info-label">{{ __('Passenger') }}:</div>
                <div>{{ $booking->user->name ?? __('N/A') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">{{ __('Booking Date') }}:</div>
                <div>{{ now()->isoFormat('LLL') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">{{ __('Trip Type') }}:</div>
                <div>{{ ucfirst(__($booking->trip_type)) }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">{{ __('Total Paid') }}:</div>
                <div>{{ $booking->flight->currency }} {{ number_format($booking->total_price, 2) }}</div>
            </div>
            @if($booking->flight->flight_number)
            <div class="info-row">
                <div class="info-label">{{ __('Flight Number') }}:</div>
                <div>{{ $booking->flight->flight_number }}</div>
            </div>
            @endif
        </div>

        <div class="flight-details">
            <div class="flight-segment">
                <div class="city">{{ $booking->flight->origin->city }}</div>
                <div class="code">{{ $booking->flight->origin->code }}</div>
                <div class="time">{{ \Carbon\Carbon::parse($booking->flight->departure_start_time)->isoFormat('LT') }}</div>
                <div class="date">{{ \Carbon\Carbon::parse($booking->flight->departure_date)->isoFormat('LL') }}</div>
            </div>
            
            <div class="flight-arrow">→</div>
            
            <div class="flight-segment">
                <div class="city">{{ $booking->flight->destination->city }}</div>
                <div class="code">{{ $booking->flight->destination->code }}</div>
                <div class="time">{{ \Carbon\Carbon::parse($booking->flight->departure_land_time)->isoFormat('LT') }}</div>
                <div class="date">{{ \Carbon\Carbon::parse($booking->flight->departure_date)->isoFormat('LL') }}</div>
            </div>
        </div>

        @if($booking->flight->duration)
        <div class="text-center mt-4">
            <p>{{ __('Flight Duration: :duration', ['duration' => $booking->flight->duration]) }}</p>
        </div>
        @endif

        <div class="divider"></div>

        <div class="text-center">
            <p>{{ __('Your flight ticket is attached to this email. You can also download it using the button below:') }}</p>
            <a href="{{ route('bookings.download', $booking->id) }}" class="btn">{{ __('Download Ticket') }}</a>
        </div>

        <div class="footer">
            <p>{{ __('Thank you for choosing :airline', ['airline' => config('app.name')]) }}</p>
            <p class="mt-1">{{ __('For any questions, please contact our support team at :email', ['email' => config('mail.support_email')]) }}</p>
            <p class="mt-2">{{ __('Safe travels!') }}</p>
        </div>
    </div>
</body>
</html>