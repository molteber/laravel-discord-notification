<?php

namespace Puz\DiscordNotification;

use GuzzleHttp\Client;
use Illuminate\Support\Collection;

class Discord
{

    protected $baseUrl = 'https://discordapp.com/api/webhooks/';

    protected $httpClient;

    /** @var \Illuminate\Support\Collection */
    protected $webhooks;

    public function __construct(Client $http, $webhooks)
    {
        if (is_array($webhooks)) {
            $webhooks = new Collection($webhooks);
        } elseif (!($webhooks instanceof Collection)) {
            throw new \RuntimeException('Discord Service with webhooks is wrong configured.');
        }

        $this->httpClient = $http;
        $this->webhooks = $webhooks;
    }

    public function send($message, array $options = [])
    {
        $data = array_merge($options, ['content' => $message]);

        $this->webhooks->each(function ($webhook) use ($data) {
            $this->request('POST', $webhook['id'] . '/' . $webhook['token'], $data);
        });
    }

    protected function request($method, $endpoint, array $data)
    {
        $url = rtrim($this->baseUrl, '/') . '/' . ltrim($endpoint, '/');

        $response = $this->httpClient->request($method, $url, [
            'json' => $data
        ]);

        $body = json_decode($response->getBody(), true);

        return $body;
    }
}
