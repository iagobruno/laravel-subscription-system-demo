@extends('layouts.main', [
    'show_header' => false
])

@section('page_title', 'Login')

@section('content')
<div class="m-auto my-5" style="width: 340px;">
    <header class="mb-4 text-center">
        <h2 class="mb-2">Criar conta</h2>
        <p>Digite um nome de usuário qualquer abaixo para se autenticar.</p>
    </header>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                <x-input
                    name="username"
                    label="Nome de usuário"
                    required
                    minlength="4"
                    autocomplete="off"
                    autocapitalize="off"
                    autocorrect="off"
                />

                <button type="submit" class="btn btn-primary mt-3">Entrar</button>
            </form>
        </div>
    </div>
</div>
@endsection
