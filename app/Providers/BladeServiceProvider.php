<?php
namespace Dayssince\Providers;

use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any necessary services.
     */
    public function boot()
    {
        // Add new method to HTML builder.
        $this->app['Illuminate\Html\HtmlBuilder']->macro('temperature', function ($days) {
            // Define css class for how hot support line is.
            switch (true) {
                case ($days < 1):
                    $type = 'bad';
                    break;
                case ($days < 4):
                    $type = 'better';
                    break;
                case ($days < 8):
                    $type = 'ok';
                    break;
                default:
                    $type = 'good';
            }

            return $type;
        });
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        //
    }
}
