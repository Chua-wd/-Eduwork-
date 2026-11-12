<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

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
        // Tampilan link pagination memakai markup sederhana (<ul class="pagination">),
        // bukan versi Tailwind bawaan, karena proyek ini tidak memakai Tailwind.
        Paginator::useBootstrapFour();
    }
}
