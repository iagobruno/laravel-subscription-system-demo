<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function show()
    {
        /** @var \App\Models\User */
        $user = Auth::user();

        // Don't show this page for subscribed users
        if ($user->subscribed()) {
            return redirect()->intended(route('dashboard'));
        }

        $plans = \Stripe\Price::all(['type' => 'recurring'])->data;
        foreach ($plans as $plan) {
            $plan->product_id = $plan->product;
            $plan->product = \Stripe\Product::retrieve($plan->product_id);
        }

        return view('subscribe', [
            'plans' => $plans,
            'intent' => $user->createSetupIntent(),
        ]);
    }

    public function handle()
    {
        $data = request()->validate([
            'plan' => ['required'],
            'payment_method' => ['required', 'string']
        ], [
            'plan.required' => 'Escolha um plano',
            'payment_method.required' => 'Ocorreu um erro ao validar seu cartão',
        ]);

        /** @var \App\Models\User */
        $user = Auth::user();

        try {
            $user->createOrGetStripeCustomer();
            $user->updateDefaultPaymentMethod($data['payment_method']);
            $user->newSubscription('default', $data['plan'])->add();
        } catch (\Exception $e) {
            return back()->withErrors([
                'generic-error' => 'Ocorreu um problema ao tentar iniciar sua assinatura. ' . $e->getMessage()
            ]);
        }

        return redirect()->intended(route('dashboard'))
            ->with(['success' => 'Assinatura iniciada com sucesso!']);
    }

    public function cancel()
    {
        /** @var \App\Models\User */
        $user = Auth::user();

        $subscription = $user->subscription('default');
        $subscription->cancel();

        return back()->with('info', 'Assinatura cancelada com sucesso. Você ainda pode utilizar nosso serviço até o dia ' . $subscription->ends_at->format('d/m/Y') . '.');
    }

    public function resume()
    {
        /** @var \App\Models\User */
        $user = Auth::user();

        $subscription = $user->subscription('default');
        $subscription->resume();

        return back()->with('success', 'Assinatura retomada com sucesso!');
    }
}
