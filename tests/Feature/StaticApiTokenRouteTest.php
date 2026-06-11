<?php

namespace Tests\Feature;

use App\Http\Middleware\StaticApiToken;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class StaticApiTokenRouteTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['services.static_api.token' => 'route-token']);

        Route::middleware(StaticApiToken::class)->get('/testing/static-token', function () {
            return response()->json(['ok' => true]);
        });
    }

    public function test_static_token_route_rejects_missing_token(): void
    {
        $this->getJson('/testing/static-token')
            ->assertUnauthorized()
            ->assertJson([
                'status' => false,
                'message' => 'Unauthorized API request',
            ]);
    }

    public function test_static_token_route_accepts_matching_token(): void
    {
        $this->withHeader('X-API-TOKEN', 'route-token')
            ->getJson('/testing/static-token')
            ->assertOk()
            ->assertJson(['ok' => true]);
    }
}
