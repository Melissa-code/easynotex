<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Repositories\NoteRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(NoteRepository::class, function ($app) {
            return new NoteRepository();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load routes API
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/api.php'));

        // Load routes Web
        Route::middleware('web')
            ->group(base_path('routes/web.php'));
    }
}
