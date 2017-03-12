<?php

namespace ApiPlugins;

use GuzzleHttp\Client;

class ChuckNorrisFactGuzzler {

    /** @var  Client */
    protected $client;

    /** @var  string */
    protected $baseURL;

    /** @var  string */
    protected $name;

    /**
     * @return string
     */
    protected function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ChuckNorrisFactGuzzler
     */
    protected function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Client
     */
    protected function getClient()
    {
        return $this->client;
    }

    /**
     * @param Client $client
     * @return ChuckNorrisFactGuzzler
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
     * @return ChuckNorrisFactGuzzler
     */
    protected function setBaseURL($baseURL)
    {
        $this->baseURL = $baseURL;
        return $this;
    }

    public function __construct($baseUrl, $name)
    {
        $this->baseURL = $baseUrl;
        $this->name = $name;
        $this->client = new Client(['base_uri' => $baseUrl]);
    }

    public function getRandomFact()
    {
        $client = $this->client;
        $response = $client->request('GET', 'random');

        $responseBody = json_decode($response->getBody());
        if (!isset($responseBody->value)) {
            return 'Error getting fact';
        }

        return $this->updateNameInFact($responseBody->value);
    }

    protected function updateNameInFact($fact)
    {
        $pattern = '/Chuck Norris/i';

        return preg_replace($pattern, $this->getName(),  $fact);
    }
}