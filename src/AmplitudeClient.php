<?php
namespace Amplitude;

use GuzzleHttp\Client;

/**
 * Default Amplitude client implementation
 */
class AmplitudeClient implements AmplitudeClientInterface
{

    /** @var string */
    const AMPLITUDE_URL = 'https://api.amplitude.com/httpapi';

    /**
     * @var string
     */
    protected $apiKey = '';

    /**
     * @var Client|null
     */
    protected $client = null;

    /**
     * AmplitudeClient constructor.
     * @param null|string $apiKey
     */
    public function __construct($apiKey = null)
    {
        if (null !== $apiKey) {
            $this->setApiKey($apiKey);
        }
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @param Message\Event $event
     * @return Message\Response
     */
    public function track(Message\Event $event)
    {
        $client = $this->getClient();
        $body = $this->getPostBody($event);
        $response = $client->post(self::AMPLITUDE_URL, $body);

        return $response->getStatusCode();
    }

    /**
     * Get post body
     * @param Message\Event $event
     * @return array
     */
    protected function getPostBody(Message\Event $event)
    {
        return array( 'form_params' => array(
            'api_key' => $this->apiKey,
            'event' => $event->format(),
        ));
    }

    /**
     * Get client
     * @return Client
     */
    protected function getClient()
    {
        if (null === $this->client) {
            $this->client = new Client( ['base_uri' =>self::AMPLITUDE_URL] );
        }
        return $this->client;
    }
}
