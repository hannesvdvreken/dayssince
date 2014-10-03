<?php
namespace Dayssince\Providers;

use Illuminate\Foundation\Support\Providers\FilterServiceProvider as ServiceProvider;

class FilterServiceProvider extends ServiceProvider
{
    /**
     * The filters that should run before all requests.
     *
     * @var array
     */
    protected $before = [
        'Dayssince\Http\Filters\MaintenanceFilter',
    ];

    /**
     * The filters that should run after all requests.
     *
     * @var array
     */
    protected $after = [
        //
    ];

    /**
     * All available route filters.
     *
     * @var array
     */
    protected $filters = [
        'auth' => 'Dayssince\Http\Filters\AuthFilter',
        'auth.basic' => 'Dayssince\Http\Filters\BasicAuthFilter',
        'csrf' => 'Dayssince\Http\Filters\CsrfFilter',
        'guest' => 'Dayssince\Http\Filters\GuestFilter',
    ];
}
