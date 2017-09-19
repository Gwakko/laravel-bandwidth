<?php

namespace Bandwidth;

use GuzzleHttp\Client;
use Carbon\Carbon;

class BandwidthCore {
        
    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $api_url;

    /**
     * @var string
     */
    protected $account_id;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $timezone;

    /**
     * @var int
     */
    protected $timeout;

    /**
     * Instantiate a new instance
     */
    public function __construct()
    {
        $this->api_url          = config('bandwidth.url');
        $this->account_id       = config('bandwidth.account_id');
        $this->username         = config('bandwidth.username');
        $this->password         = config('bandwidth.password');
        $this->timezone         = config('bandwidth.timezone');

        $this->client = new Client([
            //'base_uri'  => sprintf('%s/%s', rtrim($this->api_url, '/'), ltrim($this->processor, '/')),
            'timeout'   => config('bandwidth.timeout')
        ]);
    }

    /**
     * @return string|integer
     */
    public function getAccountId()
    {
        return $this->account_id;
    }

    /**
     * @param string $method
     * @param array $data
     * @return object
     */
    public function submitGETRequest($method, $data)
    {
        $response = $this->client->get(sprintf('%s/%s', rtrim($this->api_url, '/'), ltrim($method, '/')), [
            'query' => $data,
            'http_errors' => true,
            'verify' => false,
            'auth' => [
                $this->username,
                $this->password
            ]
        ]);

        return $this->parseXML((string)$response->getBody());
    }

    /**
     * @param type
     * @return object
     */
    public function submitPOSTRequest($data)
    {
        $response = $this->client->post(sprintf('%s/%s', rtrim($this->api_url, '/'), ltrim($method, '/')), [
            'form_params' => $data,
            'http_errors' => true,
            'verify' => false
        ]);

        return $this->parseXML((string)$response->getBody());
    }

    public function getDate($format = 'YM')
    {
        return (string)Carbon::now($this->timezone)->format($format);
    }

    /**
     * @param string $xml
     * @return object
     */
    public function parseXML($xml)
    {
        return json_decode(json_encode(simplexml_load_string($xml)));
    }

}