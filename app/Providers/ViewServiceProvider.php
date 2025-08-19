<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
// Import your models for a cleaner approach
use App\Models\Menu;
use App\Models\SubMenu;

class ViewServiceProvider extends ServiceProvider
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
        View::share('menus', Menu::where('status', 1)->orderBy('position')->get());
        View::share('submenus', SubMenu::where('status', 1)->get());
    }
}
