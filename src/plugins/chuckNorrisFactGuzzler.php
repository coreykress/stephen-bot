<?php

namespace ApiPlugins;

class ChuckNorrisFactGuzzler extends \FactGuzzler {

    /** @var  string */
    protected $name;

    private static $actions = [
        'random',
    ];

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

    public function __construct($baseUrl, $name)
    {
        parent::__construct($baseUrl);
        $this->name;
    }

    public function getFact($action)
    {
        if (!in_array($action, self::$actions)) {
            return null;
        }
        $client = $this->client;
        $response = $client->request('GET', $action);

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