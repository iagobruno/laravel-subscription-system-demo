<?php

namespace App\Http\Controllers;

use App\Helpers\Stripe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function show()
    {
        // Don't show this page for subscribed users
        if (Auth::user()->subscribed()) {
            return redirect()->intended(route('dashboard'));
        }

        return view('subscribe', [
            'plans' => Stripe::getPlans(),
            'intent' => Auth::user()->createSetupIntent(),
        ]);
    }

    public function handle()
    {
        // dd(request()->all());

        $paymentMethod = request()->input('payment_method');
        $plan = request()->input('plan');
        $user = Auth::user();

        try {
            $user->createOrGetStripeCustomer();
            $user->addPaymentMethod($paymentMethod);
            $user->newSubscription('default', $plan)->create($paymentMethod);
        } catch (\Exception $e) {
            dd($e->getMessage());

            // TODO: Show error inside view
            return back()->withErrors([
                'generic-error' => 'Error creating subscription. ' . $e->getMessage()
            ]);
        }

        return redirect()->intended(route('dashboard'));
    }

    public function cancel()
    {
        $subscription = Auth::user()->subscription('default');
        $subscription->cancel();

        return back()->with('info', 'Assinatura cancelada com sucesso. Você ainda pode retomar até o dia ' . $subscription->ends_at->format('d/m/Y') . '.');
    }

    public function resume()
    {
        $subscription = Auth::user()->subscription('default');
        $subscription->resume();

        return back()->with('success', 'Assinatura retomada com sucesso!');
    }
}
