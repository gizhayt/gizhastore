<?php

namespace App\Http\Controllers\Auth;

use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\Lockout;

class ClientAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:client')->except('logout');
    }

    public function showLoginForm()
    {
        return view('client.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ]);

        $this->ensureIsNotRateLimited($request);

        $client = Client::where('email', $request->email)->first();

        if (!$client || !Hash::check($request->password, $client->password)) {
            RateLimiter::hit($this->throttleKey($request));
            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }

        Auth::guard('client')->login($client, $request->boolean('remember'));
        RateLimiter::clear($this->throttleKey($request));

        $request->session()->regenerate();

        return redirect()->intended(route('client.dashboard'))
            ->with('success', 'Login berhasil!');
    }

    public function showRegisterForm()
    {
        return view('client.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:clients,email', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:6'],
        ]);

        DB::beginTransaction();

        try {
            $hashedPassword = Hash::make($request->password);

            $client = Client::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $hashedPassword,
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $hashedPassword,
                'client_id' => $client->id,
                'role' => 'client',
            ]);

            $client->update(['user_id' => $user->id]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors([
                'registration' => 'Pendaftaran gagal: ' . $e->getMessage(),
            ]);
        }

        Auth::guard('client')->login($client);
        $request->session()->regenerate();

        return redirect()->route('client.dashboard')->with('success', 'Pendaftaran berhasil!');
    }

    public function logout(Request $request)
    {
        Auth::guard('client')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('client.login')->with('success', 'Anda telah logout.');
    }

    protected function ensureIsNotRateLimited(Request $request)
    {
        if (RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            event(new Lockout($request));

            throw ValidationException::withMessages([
                'email' => 'Terlalu banyak percobaan login. Silakan coba lagi nanti.',
            ]);
        }
    }

    protected function throttleKey(Request $request)
    {
        return strtolower($request->input('email')) . '|' . $request->ip();
    }
}
