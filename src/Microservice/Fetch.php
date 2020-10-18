<?php


namespace Turing\LaravelEvo\Microservice;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Turing\LaravelEvo\Microservice\Exceptions\FetchException;
use Psr\Http\Message\ResponseInterface;

class Fetch
{
    public $client;
    private $microserviceHosts;
    private $traceId;

    public function __construct($microserviceHosts, $traceId)
    {
        $this->client = new Client();
        $this->microserviceHosts = $microserviceHosts;
        $this->traceId = $traceId;
    }

    public function getHostUrl($hostName)
    {
        return $this->microserviceHosts[$hostName];
    }

    /**
     * @param $hostName
     * @param $method
     * @param $url
     * @param $payload
     * @return ResponseInterface
     * @throws FetchException
     * @throws GuzzleException
     */
    public function request($hostName, $method, $url, $payload)
    {
        $host = $this->getHostUrl($hostName);
        if (!$host) {
            throw new FetchException('未找到微服务"' . $hostName . '"的主机地址');
        }
        return $this->client->request($method, $host . $hostName, [
            'json' => $payload
        ]);
    }
}