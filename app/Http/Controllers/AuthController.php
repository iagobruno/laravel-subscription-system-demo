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
        $user = User::firstOrCreate([
            'username' => request()->input('username'),
        ]);

        Auth::login($user);

        request()->session()->regenerate();

        // TODO: Redirecionar o usuário para página de assinatura se ele nao for assinante
        return redirect()->intended('/dashboard');
    }
}
