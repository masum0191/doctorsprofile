@component('mail::message')
# Flight Booking Confirmation

Dear {{ $booking->user->name ?? 'Valued Passenger' }},

Your flight booking has been successfully confirmed.

@component('mail::panel')
## Flight Details
- **Flight Number:** {{ $booking->flight->flight_number ?? 'N/A' }}
- **Route:** {{ $booking->flight->origin->city ?? 'N/A' }} ({{ $booking->flight->origin->code ?? '' }}) 
  to 
  {{ $booking->flight->destination->city ?? 'N/A' }} ({{ $booking->flight->destination->code ?? '' }})
- **Departure:** {{ $booking->flight->formatted_departure_date ?? 'N/A' }}
- **Passengers:** {{ $booking->passengers_count ?? '1' }}
- **Total Amount:** ${{ number_format($booking->total_price, 2) }}
@endcomponent

@component('mail::button', ['url' => url('bookings/show')])
View Booking Details
@endcomponent

Thank you for choosing {{ config('app.name') }}!

@component('mail::subcopy')
If you have any questions, please contact our support team at support@example.com.
@endcomponent
@endcomponent