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
            $threeMonthsLater = now()->addMonths(3)->toDateString();

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
                ->whereDate('expiry_date', '<=', $threeMonthsLater)
                ->count();
            if ($expiringSoonCount > 0) {
                $alerts->push([
                    'type' => 'warning',
                    'icon' => 'fa-solid fa-hourglass-half',
                    'title' => 'Near-expiry medicines',
                    'message' => $expiringSoonCount . ' medicine records are expiring within the next 3 months.',
                ]);
            }

            $lowStockCount = Medicine::where('quantity', '<', 50)->count();
            if ($lowStockCount > 0) {
                $alerts->push([
                    'type' => 'info',
                    'icon' => 'fa-solid fa-box-open',
                    'title' => 'Low stock attention',
                    'message' => $lowStockCount . ' medicine records are below the stock threshold.',
                ]);
            }

            $view->with('sharedAlerts', $alerts);
        });
    }
}
