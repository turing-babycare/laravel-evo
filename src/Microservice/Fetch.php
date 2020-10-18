<?php


namespace Turing\LaravelEvo\Microservice;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Turing\LaravelEvo\Microservice\Exceptions\FetchException;
use \Exception;
use \Throwable;

class Fetch
{
    public Client $client;
    private array $microserviceHosts;
    private string $traceId;

    public function __construct($microserviceHosts, $traceId)
    {
        $this->client = new Client(['timeout' => 1]);
        $this->microserviceHosts = $microserviceHosts;
        $this->traceId = $traceId;
    }

    /**
     * @param $hostName
     * @return mixed
     * @throws FetchException
     * @throws Exception
     */
    public function getHostUrl($hostName)
    {
        try {
            $url = $this->microserviceHosts[$hostName];
            if (!$url) {
                throw new Exception();
            }
            return $url;
        } catch (Throwable $exception) {
            throw new FetchException('未找到微服务"' . $hostName . '"的主机地址');
        }

    }

    /**
     * @param $hostName
     * @param $method
     * @param $url
     * @param $payload
     * @return mixed
     * @throws FetchException
     */
    public function request($hostName, $method, $url, $payload)
    {
        $host = $this->getHostUrl($hostName);
        $promise = $this->client->requestAsync($method, $host . $url, [
            'json' => $payload
        ]);
        try {
            $resp = $promise->wait();
            return $this->parseResponse($resp);
        } catch (RequestException $e) {
            return $this->parseResponse($e->getResponse());
        } catch (ConnectException $e) {
            throw new FetchException('[MS]服务通讯出现异常: ' . $e->getMessage());
        } catch (FetchException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new FetchException('[MS]服务通讯出现未知错误: ' . $e->getMessage());
        }
    }

    /**
     * @param ResponseInterface $response
     * @return mixed
     * @throws FetchException
     */
    protected function parseResponse(ResponseInterface $response)
    {
        $body = json_decode($response->getBody()->getContents(), true);
        if (empty($body['code']) || $body['code'] !== 1000) {
            throw new FetchException(empty($body['message']) ? '后端服务异常' : $body['message']);
        }
        return $body['data'];
    }
}