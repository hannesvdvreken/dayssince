<?php
namespace Dayssince\Services\Reamaze;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->bind('reamaze.client', function ($app) {
            // Get the config repository.
            $config = $app['config'];

            // Get the reamaze brand name.
            $brand = $config->get('services.reamaze.brand');

            $base = "https://$brand.reamaze.com/api/v1/";
            $auth = [$config->get('services.reamaze.email'), $config->get('services.reamaze.token')];
            $headers = ['Accept' => 'application/json'];

            // Configure the client.
            $client = $app->make('GuzzleHttp\Client', [$options = [
                'base_url' => $base,
                'defaults' => compact('auth', 'headers'),
            ]]);

            // Return the configured client.
            return $client;
        });
    }
}
