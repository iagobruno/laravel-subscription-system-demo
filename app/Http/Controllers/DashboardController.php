<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function show()
    {
        /** @var \App\Models\User */
        $user = Auth::user();

        return view('dashboard', [
            'currentSubscription' => $user->subscription('default'),
            'currentPlanInfos' => $user->getCurrentPlanInfos(),
            'invoices' => $user->invoices(),
        ]);
    }
}
