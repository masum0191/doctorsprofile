<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Flight Ticket #{{ $booking->id }}</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #dbe9f4, #fef9f5);
            margin: 0;
            padding: 30px;
        }

        .ticket {
            max-width: 800px;
            margin: auto;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
            overflow: hidden;
        }

        .ticket-header {
            background: linear-gradient(90deg, #2980b9, #6dd5fa);
            color: white;
            padding: 25px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .ticket-header h1 {
            font-size: 24px;
            margin: 0;
        }

        .ticket-header span {
            font-weight: bold;
        }

        .route {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f6f9fc;
            padding: 25px 30px;
            border-bottom: 2px dashed #ccc;
        }

        .location {
            text-align: center;
        }

        .location-code {
            font-size: 32px;
            font-weight: bold;
            color: #2c3e50;
        }

        .location-name {
            font-size: 14px;
            color: #888;
        }

        .arrow {
            font-size: 28px;
            color: #3498db;
        }

        .info-section {
            padding: 30px;
            background: #fefefe;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .info-card {
            background: linear-gradient(135deg, #e8f8f5, #fef9e7);
            border-radius: 12px;
            padding: 15px 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .info-label {
            font-size: 13px;
            color: #666;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 16px;
            font-weight: bold;
            color: #2d3436;
        }

        .barcode-section {
            text-align: center;
            padding: 20px 30px;
            background: #ecf0f1;
        }

        .barcode-section img {
            margin: 10px;
        }

        .footer {
            text-align: center;
            background: #2980b9;
            color: #fff;
            font-size: 13px;
            padding: 15px;
        }
    </style>
</head>
<body>

<div class="ticket">
    <div class="ticket-header">
        <h1>BOARDING PASS</h1>
        <span>Booking #{{ $booking->id }}</span>
    </div>

    <div class="route">
        <div class="location">
            <div class="location-code">{{ $booking->flight->origin->code }}</div>
            <div class="location-name">{{ $booking->flight->origin->city }}</div>
        </div>
        <div class="arrow">--</div>
        <div class="location">
            <div class="location-code">{{ $booking->flight->destination->code }}</div>
            <div class="location-name">{{ $booking->flight->destination->city }}</div>
        </div>
    </div>

    <div class="info-section">
        <div class="info-grid">
            <div class="info-card">
                <div class="info-label">Passenger</div>
                <div class="info-value">{{ $booking->user->name }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">Flight Number</div>
                <div class="info-value">{{ $booking->flight->id }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">Trip Type</div>
                <div class="info-value">{{ ucfirst($booking->trip_type) }}</div>
            </div>
            <!--<div class="info-card">-->
            <!--    <div class="info-label">Class</div>-->
            <!--    <div class="info-value">{{ ucfirst($booking->flight->class) }}</div>-->
            <!--</div>-->
            <div class="info-card">
                <div class="info-label">Departure Time</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($booking->flight->departure_start_time)->format('h:i A') }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">Arrival Time</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($booking->flight->departure_land_time)->format('h:i A') }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">Date</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($booking->flight->departure_date)->format('d M Y') }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">Seat(s)</div>
                <div class="info-value">{{ $booking->seats->pluck('seat_number')->join(', ') }}</div>
            </div>
        </div>
    </div>

   {{-- <div class="barcode-section">
        <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($booking->id, 'C39') }}" alt="Barcode" height="60">
        <br>
        <img src="data:image/png;base64,{{ QrCode::format('png')->size(90)->generate(route('booking.show', $booking->id)) }}" alt="QR Code">
    </div>--}}

    <div class="footer">
        Please present this boarding pass at check-in. Thank you for flying with {{ config('app.name') }}!
    </div>
</div>

</body>
</html>
