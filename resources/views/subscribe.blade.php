@extends('layouts.main')

@section('page_title', 'Planos')

@section('content')
<form method="POST" action="{{ route('subscribe.post') }}" id="subscribe-form">
    @csrf

    @if ($errors->any())
        <div class="m-auto mt-5" style="max-width: 620px">
            @foreach ($errors->all() as $error)
                <x-alert-flash
                    message="{{ $error }}"
                    type="danger"
                    dismissible="true"
                    class="mb-2"
                />
            @endforeach
        </div>
    @endif

    <header class="pt-5 pb-4 text-center">
        <h2 class="h1">Escolha um plano</h2>
    </header>

    <section class="plans row gap-4 m-auto">
        @foreach($plans as $plan)
            <input type="radio" id="plan-{{ $plan->id }}" name="plan" value="{{ $plan->id }}">

            <label for="plan-{{ $plan->id }}" class="col plan">
                <strong class="h3 mb-2 d-block">{{ $plan->product->name }}</strong>
                <div class="h4">R$ {{ $plan->unit_amount / 100 }} <small class="text-muted fs-6 fw-normal">/por mês</small></div>
                <p class="m-0">{{ $plan->product->description }}</p>
            </label>
        @endforeach
    </section>

    {{-- @if (!Auth::user()->hasPaymentMethod('card')) --}}
    <section class="card-infos mt-5 m-auto hide">
        <header class="py-3 text-center">
            <h2 class="h1">Informe os dados de pagamento</h2>
            <p class="text-muted">Sua assinatura começa assim que você configurar o pagamento.</p>
        </header>

        <div class="card card-body">
            <x-input
                name="card-holder-name"
                text="text"
                value="Fake User"
                label="Nome do titular do cartão"
            />

            <label for="card-element" class="mt-3">Cartão de crédito ou débito</label>
            <div id="card-element" class="form-control py-2"></div>
            <div id="card-errors" role="alert" class="text-danger"></div>

            <p class="text-muted mb-0 mt-2">
                Try the successful test card: <span>4242424242424242</span>.<br>
                Use any <i>future</i> expiry date, CVC, and 5 digit postal code.
            </p>

            <div class="stripe-errors"></div>

            <div id="client-secret" data-secret="{{ $intent->client_secret }}"></div>
        </div>

        <button id="submit-button" class="btn btn-primary btn-lg w-100 mt-4">ASSINAR</button>

        <small class="d-block mt-3 text-muted text-center">Ao assinar você concorda com nossos termos de serviço.</small>
    </section>
</form>
@endsection

@push('extra_body')
    <script src="https://js.stripe.com/v3/"></script>

    <script>
        const plansSection = document.querySelector('section.plans')
        const paymentSection = document.querySelector('.card-infos')

        plansSection.addEventListener('click', function () {
            paymentSection.classList.remove('hide')
            paymentSection.scrollIntoView()
        }, { once: true })

        const stripe = Stripe('{{ env('STRIPE_KEY') }}')

        const form = document.getElementById('subscribe-form')
        const cardHolderName = document.getElementById('card-holder-name-field')
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
            if (msg) button.setAttribute('disabled', 'true')
            else button.removeAttribute('disabled')
        }
    </script>

    <style>
        input[type=radio] {
            display: none;
        }

        input[type=radio]:checked + .plan {
            box-shadow: 0 0 0 4px var(--bs-primary);
        }

        section.plans {
            width: fit-content;
        }

        .plan {
            border: 1px solid #BBB;
            border-radius: 4px;
            padding: 16px;
            cursor: pointer;
            width: 300px;
        }

        section.card-infos {
            max-width: 620px;
            transition: opacity 500ms ease;
        }
        section.card-infos.hide {
            opacity: 0;
            display: none;
        }
    </style>
@endpush
