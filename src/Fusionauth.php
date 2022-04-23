<?php

namespace Mknooihuisen\LaravelFusionauth;

use FusionAuth\FusionAuthClient;

class Fusionauth
{
    private string|null $app_id;
    private FusionAuthClient $client;


    public function __construct()
    {
        $key = config('fusionauth.api_key', null);
        $url = config('fusionauth.base_url', null);
        $this->app_id = config('fusionauth.app_id', null);

        //Do we have legit keys?
        if ($key === null || $url === null || $this->app_id === null) {
            throw new \Exception("One or more required FusionAuth Config variable is null");
        }

        $this->client = new FusionAuthClient($key, $url);
    }

    public function client() : FusionAuthClient
    {
        return $this->client;
    }

    public function requiresPasswordConfirm ()
    {

        return $this->client->retrieveApplication($this->app_id);
    }

}
