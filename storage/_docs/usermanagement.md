Separate Admin access:

php artisan make:migration add_is_admin_to_users_table --table=users

php artisan migrate

php artisan make:middleware EnsureUserIsAdmin

register the middleware: bootstrap/app.php
->withMiddleware(function (Middleware $middleware) {
    $middleware->redirectGuestsTo('/auth/login');

    // Register your custom admin middleware alias
    $middleware->alias([
        'admin' => \App\Http\Middleware/EnsureUserIsAdmin::class,
    ]);
})

php artisan make:controller Admin/UserManagement/UserController --resource --model=User
