@extends('layouts.main')

@section('page_title', 'Planos')

@section('content')
<form method="POST" action="{{ route('subscribe.post') }}" id="subscribe-form">
    @csrf

    <header class="pt-5 pb-4 text-center">
        <h1 class="">Escolha um plano</h1>
    </header>

    <section class="plans d-flex justify-content-center align-items-start gap-3 m-auto">
        @foreach($plans as $plan)
            <input type="radio" id="plan-{{ $plan->id }}" name="plan" value="{{ $plan->id }}" required>

            <label for="plan-{{ $plan->id }}" class="col plan">
                <div class="h4">R$ {{ $plan->amount/100 }}</div>
                <strong class="h4 mb-3">{{ $plan->product->name }}</strong>
                <p>{{ $plan->product->description }}</p>
            </label>
        @endforeach
    </section>

    {{-- @if (!Auth::user()->hasPaymentMethod('card')) --}}
    <section class="card">
        <p>
            Try the successful test card: <span>4242424242424242</span>.<br>
            Use any <i>future</i> expiry date, CVC, and 5 digit postal code.
        </p>

        <label for="card-holder-name">Card Holder Name</label>
        <input id="card-holder-name" type="text" value="Iago Bruno">


        <label for="card-element">Credit or debit card</label>
        <div id="card-element" class="form-control"></div>
        <div id="card-errors" role="alert"></div>

        <div class="stripe-errors"></div>

        <div id="client-secret" data-secret="{{ $intent->client_secret }}"></div>

        <button id="submit-button" class="btn btn-primary btn-lg btn-block">SUBMIT</button>
    </section>
</form>
@endsection

@push('extra_body')
    <script src="https://js.stripe.com/v3/"></script>

    <script>
        const stripe = Stripe('{{ env('STRIPE_KEY') }}')

        const form = document.getElementById('subscribe-form')
        const cardHolderName = document.getElementById('card-holder-name')
        const cardErrors = document.getElementById('card-errors')
        const button = document.getElementById('submit-button')
        const clientSecret = document.getElementById('client-secret').dataset.secret;

        const card = stripe.elements().create('card', { hidePostalCode: true })
        card.mount('#card-element')

        card.addEventListener('change', function(event) {
            if (event.error) {
                displayError(event.error.message)
            } else {
                displayError('')
            }
        })

        form.addEventListener('submit', async function (event) {
            event.preventDefault();
            button.setAttribute('disabled', true)

            const { setupIntent, error } = await stripe.confirmCardSetup(
                clientSecret,
                {
                    payment_method: {
                        card,
                        billing_details: {
                            name: cardHolderName.value
                        }
                    }
                }
            );

            if (error) {
                displayError(`Payment failed: ${error.message}`)
                button.setAttribute('disabled', false)
                return
            }

            submitForm(setupIntent.payment_method)
        })

        function submitForm(payment_method) {
            const hiddenInput = document.createElement('input')
            hiddenInput.setAttribute('type', 'hidden')
            hiddenInput.setAttribute('name', 'payment_method')
            hiddenInput.setAttribute('value', payment_method)
            form.appendChild(hiddenInput)

            form.submit()
        }

        function displayError(msg) {
            cardErrors.textContent = msg ? msg : ''
        }
    </script>

    <style>
        input[type=radio] {
            display: none;
        }

        input[type=radio]:checked + .plan {
            box-shadow: 0 0 0 4px var(--bs-primary);
        }

        .plan {
            border: 1px solid #BBB;
            border-radius: 4px;
            padding: 16px;
            cursor: pointer;
            max-width: 300px;
        }
    </style>
@endpush
