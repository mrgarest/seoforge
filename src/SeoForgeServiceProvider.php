<?php

namespace MrGarest\SeoForge;

use Illuminate\Support\ServiceProvider;

class SeoForgeServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->configurePublishing();
    }

    /**
     * Configure publishing for the package.
     *
     * @return void
     */
    private function configurePublishing()
    {
        if (!$this->app->runningInConsole()) {
            return;
        }
    }
}
