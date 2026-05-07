<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GeoController extends Controller
{
    public function reverse(Request $r)
    {
        $lat = (float) $r->query('lat');
        $lng = (float) $r->query('lng');
        abort_unless($lat && $lng, 422);

        // Nominatim (OpenStreetMap). Respect usage policy: add UA + email.
        $res = Http::timeout(7)
            ->withHeaders([
                'User-Agent' => config('app.name').' ('.config('mail.from.address').')'
            ])
            ->get('https://nominatim.openstreetmap.org/reverse', [
                'lat' => $lat,
                'lon' => $lng,
                'format' => 'json',
                'addressdetails' => 1,
                'zoom' => 10,
            ]);

        if (!$res->ok()) {
            return response()->json(['city' => null], 200);
        }

        $data = $res->json();
        $a = $data['address'] ?? [];
        // Best-effort: city > town > municipality > suburb > state_district
        $city = $a['city'] ?? $a['town'] ?? $a['municipality'] ?? $a['suburb'] ?? $a['state_district'] ?? null;
        $state = $a['state'] ?? null;
        $country = $a['country'] ?? null;

        return response()->json([
            'city'    => $city,
            'state'   => $state,
            'country' => $country,
            'display' => $data['display_name'] ?? null,
        ]);
    }
}
