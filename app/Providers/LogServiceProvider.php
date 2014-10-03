<?php
namespace Dayssince\Providers;

use Illuminate\Contracts\Logging\Log;
use Illuminate\Support\ServiceProvider;

class LogServiceProvider extends ServiceProvider
{
    /**
     * Configure the application's logging facilities.
     *
     * @param Log $log
     */
    public function boot(Log $log)
    {
        $log->useFiles(storage_path() . '/laravel.log');
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        //
    }
}
