<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function show()
    {
        return view('login');
    }

    public function handle()
    {
        $username = request()->input('username');

        $user = User::firstOrCreate([
            'username' => $username,
        ]);
        $user->createOrGetStripeCustomer([
            'name' => $username,
        ]);

        Auth::login($user);

        request()->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }
}
