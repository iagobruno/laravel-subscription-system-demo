@php
  $sub = $currentSubscription ?? Auth::user()?->subscription();
@endphp

@if (
    Auth::check() &&
    isset($sub) &&
    $sub->cancelled() &&
    $sub->onGracePeriod() &&
    $sub->ends_at?->diffInDays(now()) <= 3
)
    <x-alert-flash
        message="Sua assinatura está próxima de acabar. Reative para continuar utilizando nossos serviços."
        type="danger"
        dismissible="false"
        class="m-0"
    />
@endif
