@extends('layouts.main')

@section('page_title', 'Dashboard')

@section('content')
<div class="m-auto" style="max-width: 700px">
    <section class="py-5 text-center">
        <h1>Obrigado por assinar! 💓</h1>
    </section>
    <hr>

    <section class="current-subscription py-3">
        <h4 class="mb-3">Plano atual</h4>

        <div class="card">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <strong class="h4 mb-3">{{ $currentPlanInfos->name }}</strong>
                    @if($currentSubscription->cancelled())<span class="badge bg-danger badge-sm">CANCELADO</span>@endif
                    <p class="m-0 mb-2">{{ $currentPlanInfos->description }}</p>
                    <small class="m-0 text-secondary">Assinado em {{ $currentSubscription->created_at->format('d/m/Y') }}</small>
                    <br>
                    @if($currentSubscription->upcomingInvoice())
                    <small class="m-0 text-secondary">Próxima cobrança em {{ $currentSubscription->upcomingInvoice()->date()->format('d/m/Y') }}</small>
                    @endif
                </div>
                <div>
                    @if($currentSubscription->onGracePeriod())
                    <form action="{{ route('subscribe.resume')}}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">Retomar</button>
                    </form>
                    @else
                    <form action="{{ route('subscribe.cancel')}}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">Cancelar</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section class="invoices py-3">
        <h4 class="mb-3">Recibos</h4>

        <div class="border rounded-2 px-2">
            <table class="table table-borderless m-0">
                <thead>
                    <tr>
                        <th scope="col">Data</th>
                        <th scope="col">Preço</th>
                        <th scope="col">Referência</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->date()->format('d/m/Y') }}</td>
                        <td>{{ $invoice->total() }}</td>
                        <td>{{ $invoice->id }}</td>
                        <td></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
