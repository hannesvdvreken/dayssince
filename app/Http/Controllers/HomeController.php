<?php
namespace Dayssince\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use Dayssince\Services\Reamaze\Reamaze;

class HomeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Default Home Controller
    |--------------------------------------------------------------------------
    |
    | You may wish to use controllers instead of, or in addition to, Closure
    | based routes. That's great! Here is an example controller method to
    | get you started. To route to this controller, just add the route:
    |
    | Route::get('/', 'HomeController@index');
    |
    */

    public function index(Reamaze $reamaze)
    {
        // Initialize.
        $latest = null;

        foreach (Config::get('dayssince.contacts') as $contact) {
            // Get lastest conversation for contact.
            $conversations = $reamaze->getConversations(null, null, $contact, 1);

            if (empty($conversations)) {
                // No conversations for this contact.
                continue;
            }
            $latestByContact = $conversations[0];

            // Create a datetime object.
            $latestByContact['created_at'] = new Carbon($latestByContact['created_at']);

            // Replace if no timestamp set or if it was later than latest.
            if (!$latest || $latestByContact['created_at'] > $latest['created_at']) {
                $latest = $latestByContact;
            }
        }

        // Count the number of days it was ago.
        $days = Carbon::now()->startOfDay()->diffInDays($latest['created_at']);

        // Warning type.
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

        return View::make('since', compact('latest', 'days', 'type'));
    }
}
