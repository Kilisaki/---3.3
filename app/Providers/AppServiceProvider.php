<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Product;
use App\Policies\ProductPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Регистрируем политику для Product
        Gate::policy(Product::class, ProductPolicy::class);

        // Gate для администратора (если вдруг понадобится)
        Gate::define('isAdmin', fn ($user) => (bool) ($user->is_admin ?? false));
    }
}
