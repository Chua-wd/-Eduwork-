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

        // Locally the project sits in a folder called "[Eduwork]". PHP's glob() reads "[...]" as a
        // pattern, so Laravel finds no migration files (migrate and the tests see an empty database).
        // Register each file by name instead. On the hosting server the path has no brackets: no-op.
        $migrations = database_path('migrations');
        if (str_contains($migrations, '[')) {
            $this->loadMigrationsFrom(array_map(
                fn ($file) => $migrations . DIRECTORY_SEPARATOR . $file,
                array_values(array_filter(scandir($migrations), fn ($file) => str_ends_with($file, '.php')))
            ));
        }
    }
}
