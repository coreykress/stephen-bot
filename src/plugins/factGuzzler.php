<?php

use GuzzleHttp\Client;

class FactGuzzler
{
    /** @var  Client */
    protected $client;

    /** @var  string */
    protected $baseURL;

    /**
     * @return Client
     */
    protected function getClient()
    {
        return $this->client;
    }

    /**
     * @param Client $client
     * @return FactGuzzler
     */
    protected function setClient($client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @return string
     */
    protected function getBaseURL()
    {
        return $this->baseURL;
    }

    /**
     * @param string $baseURL
     * @return FactGuzzler
     */
    protected function setBaseURL($baseURL)
    {
        $this->baseURL = $baseURL;
        return $this;
    }

    public function __construct($baseUrl)
    {
        $this->baseURL = $baseUrl;
        $this->client = new Client(['base_uri' => $baseUrl]);
    }

    public function getFact($action)
    {
        $client = $this->client;
        $response = $client->request('GET', $action);

        $responseBody = json_decode($response->getBody());
        if (!isset($responseBody->value)) {
            return 'Error getting fact';
        }

        return $responseBody->value;
    }
}