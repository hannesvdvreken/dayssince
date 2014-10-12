<?php
namespace Dayssince\Http\Controllers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Response;
use Illuminate\Cache\Repository as Cache;
use Illuminate\Config\Repository as Config;
use Carbon\Carbon;
use Dayssince\Services\Reamaze\Reamaze;

class DayssinceController
{
    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @type Config
     */
    protected $config;

    /**
     * Public constructor
     *
     * @param Cache  $cache
     * @param Config $config
     */
    public function __construct(Cache $cache, Config $config)
    {
        $this->cache = $cache;
        $this->config = $config;
    }

    /*
    |
    | Route::get('/{project?}', 'HomeController@index');
    |
    */
    public function index(Reamaze $reamaze, $projectName = null)
    {
        // Get default project name
        if (!$projectName) {
            $projectName = $this->config->get('dayssince.default');
        }

        // Get the project configuration.
        $project = $this->config->get("dayssince.projects.$projectName");

        if (!$project) {
            return Response::view('404', ['project_name' => $projectName], 404);
        }

        // Cache timeout in minutes. Default: 5.
        $cacheTime = $this->config->get('dayssince.cache', 5);

        // Get latest conversations per contact.
        $latest = $this->cache->remember('reamaze.'. $projectName, $cacheTime, function () use ($reamaze, $project) {
            // Get latest.
            $conversations = $reamaze->getConversations(null, null, $project['contacts'], 1);

            // It returned array with one item. We need that one.
            return reset($conversations);
        });

        // Count the number of days it was ago.
        $days = Carbon::now()->startOfDay()->diffInDays(new Carbon($latest['created_at']));

        return View::make('since', compact('latest', 'days', 'project'));
    }
}
