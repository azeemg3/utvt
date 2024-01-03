<?php

namespace App\Providers;

use App\Models\BusinessSetting;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\View as FacadesView;
use Illuminate\Support\Pluralizer;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    protected $site_setting;
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->site_setting = BusinessSetting::first();
        FacadesView::share("site_setting", $this->site_setting);
    }
}
