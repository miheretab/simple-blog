Edit your .env with correct database credentials and other settings.

```bash
php artisan breeze:install
php artisan migrate
php artisan db:seed UserSeeder
php artisan storage:link
php artisan vendor:publish --tag=laravel-pagination
```

You can use admin@gmail.com 123456 and user@gmail.com 123456 as admin and user respectively.
