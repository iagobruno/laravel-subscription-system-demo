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
        $data = request()->validate([
            'plan' => ['required'],
            'payment_method' => ['required', 'string']
        ], [
            'plan.required' => 'Escolha um plano',
            'payment_method.required' => 'Ocorreu um erro ao validar seu cartão',
        ]);

        $user = Auth::user();

        try {
            $user->createOrGetStripeCustomer();
            $user->addPaymentMethod($data['payment_method']);
            $user->newSubscription('default', $data['plan'])->create($data['payment_method']);
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
        $subscription = Auth::user()->subscription('default');
        $subscription->cancel();

        return back()->with('info', 'Assinatura cancelada com sucesso. Você ainda pode utilizar nosso serviço até o dia ' . $subscription->ends_at->format('d/m/Y') . '.');
    }

    public function resume()
    {
        $subscription = Auth::user()->subscription('default');
        $subscription->resume();

        return back()->with('success', 'Assinatura retomada com sucesso!');
    }
}
