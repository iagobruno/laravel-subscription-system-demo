## Getting started

```
git clone https://github.com/iagobruno/laravel-subscription-paywall.git
cd laravel-subscription-paywall
composer install
yarn install
copy .env.example .env # And edit the values
php artisan key:generate
php artisan migrate
php artisan db:seed # (Optional)
php artisan serve
```

You can now access the server at http://localhost:8000.
