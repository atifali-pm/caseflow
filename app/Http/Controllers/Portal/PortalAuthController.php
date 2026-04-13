<?php

namespace App\Http\Controllers\Portal;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PortalAuthController extends Controller
{
    public function showLogin()
    {
        return view('portal.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials) && Auth::user()->isClient()) {
            $request->session()->regenerate();
            return redirect()->route('portal.dashboard');
        }

        Auth::logout();
        return back()->withErrors(['email' => 'Invalid credentials or not a client account.']);
    }

    public function showRegister(string $token)
    {
        $client = Client::where('invitation_token', $token)->firstOrFail();

        return view('portal.auth.register', compact('client'));
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'token' => 'required|string',
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $client = Client::where('invitation_token', $data['token'])->firstOrFail();

        $user = User::create([
            'name' => $data['name'],
            'email' => $client->email,
            'password' => Hash::make($data['password']),
            'role' => UserRole::Client,
            'provider_id' => $client->provider_id,
            'email_verified_at' => now(),
        ]);

        $client->update([
            'user_id' => $user->id,
            'invitation_token' => null,
        ]);

        Auth::login($user);

        return redirect()->route('portal.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('portal.login');
    }
}
