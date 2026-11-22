<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {

    }

    public function boot(): void
    {

        Model::preventLazyLoading(! app()->isProduction());

        Paginator::useBootstrapFive();

        View::composer(['components.layout', 'layouts.*'], function ($view) {
            $user = Auth::user();
            $view->with('jumlahKeranjang', $user ? $user->cartItems()->count() : 0);
            $view->with(
                'kategoriNavbar',
                Schema::hasTable('categories') ? Category::orderBy('id')->take(6)->get() : collect()
            );
        });
    }
}
