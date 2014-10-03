<?php
namespace Dayssince\Services\Reamaze;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\App;

class Reamaze
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * Public constructor.
     */
    public function __construct()
    {
        $this->client = App::make('reamaze.client');
    }

    /**
     * Get conversations.
     *
     * @param string $filter
     * @param string $sort
     * @param string $for
     * @param int $limit
     * @return array
     */
    public function getConversations($filter = 'all', $sort = 'created', $for = null, $limit = null)
    {
        $query = compact('filter', 'sort');

        if ($for) {
            $query['for'] = $for;
        }

        return $this->paginated('conversations', compact('query'), $limit);
    }

    /**
     * Get contacts.
     *
     * @param string $query Search string.
     * @param int $limit
     * @return array
     */
    public function getContacts($query = null, $limit = null)
    {
        if ($query) {
            $result = $this->paginated('contacts', ['query' => ['q' => $query]], $limit);
        } else {
            $result = $this->paginated('contacts', null, $limit);
        }

        return $result;
    }

    /**
     * Make a paginated request and return all results.
     *
     * @param string $resource
     * @param array $options
     * @param int $limit
     * @return array
     */
    protected function paginated($resource, $options = [], $limit = null)
    {
        // Do one request.
        $response = $this->client->get($resource, $options)->json();
        $result = $response[$resource];

        // Loop to get the rest.
        if ($response['page_count'] > 1) {
            foreach (range(2, $response['page_count']) as $page) {
                array_set($options, 'query.page', $page);
                $response = $this->client->get($resource, $options)->json();
                $result = array_merge($result, $response[$resource]);

                if ($limit && count($result) > $limit) {
                    $result = array_splice($result, 0, $limit);
                    break;
                }
            }
        }

        return $result;
    }
} 
