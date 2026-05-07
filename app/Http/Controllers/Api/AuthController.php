<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // 📝 Register
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = $user->createToken('user-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    // 🔐 Login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->where('role', 'tenant')->first();
        $tenantId = $user ? $user->tenant_id : null;

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        // 🔒 Check status
    if ($user->status != 1) {
        return response()->json(['error' => 'Your account has been disabled. Talk to the support team.'], 403);
    }
        $token = $user->createToken('user-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    // 🚪 Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }
    // 🔑 Change Password

public function changePassword(Request $request)
{
    Log::info('========== CHANGE PASSWORD STARTED ==========');

    $user = auth()->user();

    if (!$user) {
        return response()->json([
            'message' => 'Unauthenticated'
        ], 401);
    }

    // ✅ Validation
    $request->validate([
        'current_password' => ['required'],
        'new_password'     => ['required', 'min:8', 'confirmed'],
    ], [
        'current_password.required' => 'Current password is required',
        'new_password.confirmed'    => 'New password confirmation does not match',
    ]);

    // ✅ Check current password
    if (!Hash::check($request->current_password, $user->password)) {
        throw ValidationException::withMessages([
            'current_password' => ['Current password is incorrect'],
        ]);
    }

    // ✅ Prevent same password reuse
    if (Hash::check($request->new_password, $user->password)) {
        throw ValidationException::withMessages([
            'new_password' => ['New password must be different from current password'],
        ]);
    }

    // ✅ Update password
    $user->password = Hash::make($request->new_password);
    $user->save();

    $tenantId = $user->tenant_id ?? null;

    if (!$tenantId) {
        return response()->json([
            'success' => false,
            'message' => 'Tenant not resolved.',
        ], 422);
    }

    $tenant = \App\Models\Tenant::find($tenantId);

    if (!$tenant) {
        return response()->json([
            'success' => false,
            'message' => 'Tenant not found.',
        ], 404);
    }

    tenancy()->initialize($tenant);
   try {
        $tenantUser = User::where('role', 'admin')->first();
        if ($tenantUser) {
            $tenantUser->password = Hash::make($request->new_password);
            $tenantUser->save();
        } else {
            Log::warning("User with ID {$user->id} not found in tenant database.");
        }
    } catch (\Exception $e) {
        Log::error('Error updating password in tenant database: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to update password in tenant database.',
        ], 500);
    } finally {
        tenancy()->end();
    }

}

}
