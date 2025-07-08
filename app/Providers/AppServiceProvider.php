<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
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
        Filament::serving(function () {
            $user = Auth::user();

            // Chỉ chặn nếu đang vào panel admin
            if (
                request()->is('admin*') &&
                !request()->is('admin/login') &&
                !request()->is('admin/logout') &&
                Auth::check() &&
                !Auth::user()->hasAnyRole(['Admin', 'HR'])
            ) {
                abort(403, 'Bạn không có quyền vào trang này.');
            }

        });
    }
}
