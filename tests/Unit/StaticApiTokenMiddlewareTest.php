<?php

namespace Tests\Unit;

use App\Http\Middleware\StaticApiToken;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tests\TestCase;

class StaticApiTokenMiddlewareTest extends TestCase
{
    public function test_request_without_matching_static_token_is_rejected(): void
    {
        config(['services.static_api.token' => 'expected-token']);

        $response = (new StaticApiToken())->handle(Request::create('/api/private'), function () {
            return new Response('ok');
        });

        $this->assertSame(401, $response->getStatusCode());
        $this->assertSame([
            'status' => false,
            'message' => 'Unauthorized API request',
        ], $response->getData(true));
    }

    public function test_request_with_matching_static_token_continues(): void
    {
        config(['services.static_api.token' => 'expected-token']);

        $request = Request::create('/api/private', 'GET', [], [], [], [
            'HTTP_X_API_TOKEN' => 'expected-token',
        ]);

        $response = (new StaticApiToken())->handle($request, function () {
            return new Response('ok', 202);
        });

        $this->assertSame(202, $response->getStatusCode());
        $this->assertSame('ok', $response->getContent());
    }
}
