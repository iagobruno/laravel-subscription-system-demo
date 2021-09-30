<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function show()
    {
        return view('dashboard', [
            'currentSubscription' => Auth::user()->subscription('default'),
            'currentPlanInfos' => Auth::user()->getCurrentPlanInfos(),
            'invoices' => Auth::user()->invoices(),
        ]);
    }
}
