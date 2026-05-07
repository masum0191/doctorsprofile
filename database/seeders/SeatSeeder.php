<?php
namespace Database\Seeders;
use App\Models\Flight;
use App\Models\Seat;
use Illuminate\Database\Seeder;

class SeatSeeder extends Seeder
{
    public function run()
    {
        $seatLabels = ['A', 'B', 'C', 'D'];
        $seatNumbers = range(1, 4); // A1 to D4 = 16 seats

        foreach (Flight::all() as $flight) {
            foreach ($seatLabels as $row) {
                foreach ($seatNumbers as $num) {
                    Seat::create([
                        'flight_id' => $flight->id,
                        'seat_number' => $row . $num,
                        'is_booked' => false,
                    ]);
                }
            }
        }
    }
}
