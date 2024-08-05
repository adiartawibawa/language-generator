<?php

namespace Adiartawibawa\LanguageGenerator;

use Adiartawibawa\LanguageGenerator\Console\Commands\LanguageGenerator;
use Illuminate\Support\ServiceProvider;

class LanguageGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/language-generator.php' => config_path('language-generator.php'),
        ], 'config');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/language-generator.php', 'language-generator');

        if ($this->app->runningInConsole()) {
            $this->commands([
                LanguageGenerator::class,
            ]);
        }
    }
}
