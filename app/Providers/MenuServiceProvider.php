<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
      // $menuArray = include resource_path('menu/verticalMenu.blade.php');
      // view()->share('menuData', $menuArray);
  }
}
