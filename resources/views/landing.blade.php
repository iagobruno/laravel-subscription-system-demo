@extends('layouts.main')

@section('content')
<header class="intro py-5 my-3 text-center">
    <h1 class="mb-5">Obtenha acesso ao conteúdo</h1>
    <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Assinar</a>
</header>

<section class="features d-flex justify-evenly align-items-start gap-3 w-75 m-auto mt-5">
    <div class="col">
        <h5 class="mb-3">Assista em qualquer dispositivo</h5>
        <p>Assista em Smart TVs, PlayStation, Xbox, Chromecast, Apple TV, aparelhos de Blu-ray e outros dispositivos.</p>
    </div>
    <div class="col">
        <h5 class="mb-3">Baixe para assistir offline</h5>
        <p>Salve seus títulos favoritos e sempre tenha algo para assistir.</p>
    </div>
    <div class="col">
        <h5 class="mb-3">Cancele quando quiser</h5>
        <p>Você pode cancelar a sua conta com apenas dois cliques. Não há taxa de cancelamento – você pode começar ou encerrar a sua assinatura a qualquer momento.</p>
    </div>
</section>
@endsection
