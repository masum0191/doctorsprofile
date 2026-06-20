<?php

namespace Tests\Unit;

use App\Http\Middleware\UserTypeMiddleware;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class UserTypeMiddlewareTest extends TestCase
{
    public function test_user_with_any_allowed_role_can_continue(): void
    {
        $user = new User(['role' => 'user']);
        $user->id = 1;

        $this->be($user);

        $response = (new UserTypeMiddleware())->handle(Request::create('/admin'), function () {
            return new Response('ok', 202);
        }, 'admin', 'user');

        $this->assertSame(202, $response->getStatusCode());
        $this->assertSame('ok', $response->getContent());
    }

    public function test_user_without_allowed_role_is_rejected(): void
    {
        $user = new User(['role' => 'patient']);
        $user->id = 2;

        $this->be($user);

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('Unauthorized access');

        (new UserTypeMiddleware())->handle(Request::create('/admin'), function () {
            return new Response('ok');
        }, 'superadmin', 'admin');
    }
}
