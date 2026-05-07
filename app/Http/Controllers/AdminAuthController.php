<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class AdminAuthController extends Controller
{
    // Show the admin login form
    public function showLoginForm()
    {
        return view('admin.login');
    }

    // Handle the admin login request
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->intended('/superadmin/dashboard');
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    // Handle the admin logout request
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }
    // Show the admin login form
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email'); // Blade below
    }

    /**
     * Send reset link to email
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // If you use a custom guard/broker, pass broker('admins') etc.
        $status = Password::broker() // default 'users' table
            ->sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Show the "reset password" form (with token)
     */
    public function showResetForm(Request $request, string $token)
    {
        return view('auth.passwords.reset', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    /**
     * Handle resetting the password
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()
                ->route('admin.login')
                ->with('success', __('Your password has been reset. Please log in.'));
        }

        return back()->withErrors(['email' => __($status)]);
    }
    public function getNsRecords(string $domain): array
{
    try {
        $records = dns_get_record($domain, DNS_NS);

        if (empty($records)) {
            return ['error' => 'No NS records found'];
        }

        return collect($records)->pluck('target')->all();
    } catch (\Throwable $e) {
        //Log::error('DNS lookup failed', ['domain' => $domain, 'error' => $e->getMessage()]);
        return ['error' => $e->getMessage()];
    }
}

   public function dashboard(Request $request)
{
    //return auth()->user()->role;
    if (auth()->user()->role == 'tenant') {
        $tenant = \App\Models\Tenant::with('domains')
            ->where('id', auth()->user()->tenant_id)
            ->first();

        if ($tenant && $tenant->domains->isNotEmpty()) {
            $ns = $this->getNsRecords($tenant->domains->first()->domain);

        } else {
            $ns = ['error' => 'No domain found for this tenant'];
        }

// dd($tenant);
        return view('admin.dashboard', [
            'tenants' => collect([$tenant]), // Wrap single tenant in a collection
            'ns' => $ns,
            'type'=> $tenant?->domains->first()?->type ?? 'N/A',
        ]);
    } else {
        $query = \App\Models\User::where('role', 'tenant')->where('tenant_id', '!=', null);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%");
                 // ->orWhere('c', 'like', "%{$search}%")
               //   ->orWhere('mobile', 'like', "%{$search}%")
                //  ->orWhere('mobile', 'like', "%{$search}%")
                //  ->orWhere('status', 'like', "%{$search}%");
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $doctors = $query->orderBy('created_at', 'desc')->get();

        $ns = null;

        return view('admin.admindashboard', compact('doctors', 'ns'))
            ->with([
                'filters' => [
                    'search' => $request->search,
                    'status' => $request->status,
                    'domain' => $request->domain,
                ],
            ]);
    }
}


}
