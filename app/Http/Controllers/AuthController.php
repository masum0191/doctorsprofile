<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    // Show NID + mobile form
    public function showForgetForm()
    {
        return view('password.forget-password');
    }

    // Handle verification
    public function verifyNidMobile(Request $request)
    {
        $request->validate([
            'nid' => 'required|string',
            'mobile' => 'required|string',
        ]);

        $user = User::where('nid', $request->nid)
                    ->where('mobile', $request->mobile)
                    ->first();

        if ($user) {
            // Store user ID in session for next step
            session(['reset_user_id' => $user->id]);
            // Redirect to reset password form
            return redirect()->route('password.set')->with('success', 'আপনার NID এবং মোবাইল নম্বর সঠিক। এখন আপনি পাসওয়ার্ড রিসেট করতে পারবেন।');
            //return redirect()->route('set.password');
        } else {
            return back()->withErrors(['nid' => 'NID অথবা মোবাইল নম্বর সঠিক নয়।']);
        }
    }

    // Show reset password form
    public function showResetForm()
    {
        if (!session('reset_user_id')) {
            return redirect()->route('forget.password')->withErrors(['msg' => 'আপনার সেশন মেয়াদ উত্তীর্ণ হয়েছে।']);
        }

        return view('password.set-password');
    }

    // Handle password reset
    public function resetPassword(Request $request)
    {
        $request->validate([
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password',
        ]);

        $userId = session('reset_user_id');
        $user = User::find($userId);

        if ($user) {
            $user->password = Hash::make($request->new_password);
            $user->save();

            session()->forget('reset_user_id'); // Clear session
            return redirect()->route('login')->with('success', 'পাসওয়ার্ড সফলভাবে পরিবর্তন হয়েছে।');
        }

        return redirect()->route('forget.password')->withErrors(['msg' => 'ব্যবহারকারী খুঁজে পাওয়া যায়নি।']);
    }
    // login tenant
    public function showLoginForm()
    {
        $setings = \App\Models\Setting::first();
        return view('tenant.login', compact('setings'));
    }
    public function login1(Request $request)
{
    //dd($request->input('nid'));
    $request->validate([
        'nid'      => ['required','string','max:255'], // may contain NID or email
        'password' => ['required','string'],
        'remember' => ['sometimes','boolean'],
    ]);

    $login = $request->input('nid');
    $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'nid';

    if (Auth::attempt([$field => $login, 'password' => $request->password], $request->boolean('remember'))) {
        $request->session()->regenerate(); // session fixation protection
        return redirect()->intended('/admin/dashboard');
    }

    return back()->with([
        'error' => 'NID অথবা পাসওয়ার্ড সঠিক নয়।',
        'nid' => 'NID অথবা পাসওয়ার্ড সঠিক নয়।',


    ])->onlyInput('nid');
}
public function login(Request $request)
{
    $request->validate([
        'nid' => ['required', 'string', 'max:255'],
        'password' => ['required', 'string'],
        'remember' => ['sometimes', 'boolean'],
    ]);

    $login = $request->input('nid');

    // 🔥 Determine login field
    $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'nid';

    // 🔍 First check if user exists
    $user = \App\Models\User::where($field, $login)->first();

    if (!$user) {
        return back()->with('error', 'User not found.')->onlyInput('nid');
    }

    // 🔒 Check status
    if ($user->status != 1) {
        return back()->with('error', 'Your account has been disabled. Talk to the support team.')->onlyInput('nid');
    }

    // 🔑 Attempt login
    if (Auth::attempt([
        $field => $login,
        'password' => $request->password
    ], $request->boolean('remember'))) {

        $request->session()->regenerate();
        return redirect()->intended('/admin/dashboard');
    }

    return back()->with('error', 'Password is incorrect.')->onlyInput('nid');
}


    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
    // register tenant
    public function showRegisterForm()
    {
        $settings = \App\Models\Setting::first();
        return view('tenant.register', compact('settings'));
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nid' => 'required|string|unique:users,nid',
            'mobile' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'nid' => $request->nid,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
        ]);

        auth()->login($user);
        return redirect('/admin/dashboard');
    }
}
