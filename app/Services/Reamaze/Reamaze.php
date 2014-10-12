<?php
namespace Dayssince\Services\Reamaze;

use GuzzleHttp\Client;
use Illuminate\Foundation\Application;

class Reamaze
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * Public constructor.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->client = $app->make('reamaze.client');
    }

    /**
     * Get conversations.
     *
     * @param string $filter
     * @param string $sort
     * @param string|array $for
     * @param int $limit
     * @return array
     */
    public function getConversations($filter = null, $sort = null, $for = null, $limit = null)
    {
        if (is_array($for)) {
            $conversations = [];

            foreach ($for as $contact) {
                // Get list of conversations per contact.
                $additional = $this->getConversationsForContact($filter, $sort, $contact, $limit);

                // Add them to the list.
                $conversations = array_merge($conversations, $additional);
            }

            // Do merge sort.
            $conversations = $this->sortConversations($conversations, $sort);

            // Limit total result. And return it.
            $conversations = array_splice($conversations, 0, $limit);
        } else {
            $conversations = $this->getConversationsForContact($filter, $sort, $for, $limit);
        }

        // Return result.
        return $conversations;
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
     * @param string $filter
     * @param string $sort
     * @param string $for
     * @param int $limit
     *
     * @return array
     */
    protected function getConversationsForContact($filter = null, $sort = null, $for = null, $limit = null)
    {
        // Defaults.
        $filter = $filter ?: 'all';
        $sort = $sort ?: 'created';

        // Create url query for guzzle.
        $query = compact('filter', 'sort');

        if ($for) {
            $query['for'] = $for;
        }

        return $this->paginated('conversations', compact('query'), $limit);
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

                // Stop loading more pages.
                if ($limit && count($result) > $limit) {
                    break;
                }
            }
        }

        // Limit result set.
        return array_splice($result, 0, $limit);
    }

    /**
     * Sort a list of conversations on a type.
     *
     * @param array $conversations
     * @param string $order Default is 'created', otherwise use 'updated'. Direction is _always_ descending.
     * @return array
     */
    protected function sortConversations(array $conversations, $order)
    {
        switch ($order) {
            case 'updated':
                $sortKey = 'last_customer_message.created_at';
                break;
            default:
                $sortKey = 'created_at';
        }

        // Sort on defined key.
        usort($conversations, function ($a, $b) use ($sortKey) {
            return array_get($a, $sortKey) < array_get($b, $sortKey);
        });

        return $conversations;
    }
} 
