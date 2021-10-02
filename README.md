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

- Salvar os dados do cartão de crédito do cliente no db para não solicitar novamente.
- Permitir que o cliente mude de plano.
