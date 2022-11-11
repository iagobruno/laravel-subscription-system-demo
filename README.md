# laravel-subscription-system-demo

![video.gif](./public/video.gif)

## Built with

- Laravel
- [Laravel Cashier](https://laravel.com/docs/8.x/billing)
- [Stripe](https://stripe.com/br/billing)

## Getting started

```
git clone https://github.com/iagobruno/laravel-subscription-system-demo.git
cd laravel-subscription-system-demo
composer install
copy .env.example .env # And edit the values
php artisan key:generate
php artisan migrate
php artisan db:seed # (Optional)
php artisan serve
```

You can now access the server at http://localhost:8000.

## Notas pro meu eu do futuro

- Usar o [embeddable pricing table](https://stripe.com/docs/payments/checkout/pricing-table?locale=pt-BR) do Stripe pra mostrar os planos.
- Usar o [costumer portal](https://stripe.com/docs/customer-management) do Stripe para permitir q os clientes gerenciem suas assinaturas.
- Usar o [webhooks](https://stripe.com/docs/webhooks) para inciar, atualizar e cancelar assinaturas.
