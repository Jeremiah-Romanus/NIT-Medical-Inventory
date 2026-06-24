<?php

namespace App\Providers;

use App\Models\Medicine;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
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
        Schema::defaultStringLength(191);

        View::composer('layouts.layout', function ($view) {
            if (! auth()->check()) {
                $view->with('sharedAlerts', collect());
                return;
            }

            $today = now()->toDateString();
            $sixMonthsLater = now()->addMonths(6)->toDateString();

            $alerts = collect();

            $expiredCount = Medicine::whereDate('expiry_date', '<', $today)->count();
            if ($expiredCount > 0) {
                $alerts->push([
                    'type' => 'danger',
                    'icon' => 'fa-solid fa-circle-xmark',
                    'title' => 'Expired medicines detected',
                    'message' => $expiredCount . ' medicine records have already expired and need immediate review.',
                ]);
            }

            $expiringSoonCount = Medicine::whereDate('expiry_date', '>=', $today)
                ->whereDate('expiry_date', '<=', $sixMonthsLater)
                ->count();
            if ($expiringSoonCount > 0) {
                $alerts->push([
                    'type' => 'warning',
                    'icon' => 'fa-solid fa-hourglass-half',
                    'title' => 'Near-expiry medicines',
                    'message' => $expiringSoonCount . ' medicine records are expiring within the next 6 months.',
                ]);
            }

            $view->with('sharedAlerts', $alerts);
        });
    }
}
