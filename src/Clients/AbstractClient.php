<?php

namespace Ninja\BanThis\Clients;

use GuzzleHttp\Exception\GuzzleException;
use Ninja\BanThis\Contracts\Client;
use Ninja\BanThis\Exceptions\ClientException;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractClient implements Client
{
    public function __construct(protected ?ClientInterface $httpClient = null)
    {
        $this->httpClient = $httpClient ?? new \GuzzleHttp\Client([
            'timeout' => 5,
            'connect_timeout' => 5,
            'http_errors' => false,
        ]);
    }

    /**
     * @throws ClientException
     */
    protected function get(string $endpoint, array $query = []): array
    {
        try {
            $response = $this->httpClient->request('GET', $this->baseUri() . $endpoint, [
                'query' => $query
            ]);

            return $this->response($response);
        } catch (GuzzleException $e) {
            throw new ClientException(
                "HTTP request failed: " . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * @throws ClientException
     */
    protected function post(string $endpoint, array $data = [], array $headers = []): array
    {
        try {
            $response = $this->httpClient->request('POST', $this->baseUri() . $endpoint, [
                'headers' => array_merge(['Content-Type' => 'application/json'], $headers),
                'json' => $data
            ]);

            return $this->response($response);
        } catch (GuzzleException $e) {
            throw new ClientException(
                "HTTP request failed: " . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * @throws ClientException
     */
    protected function response(ResponseInterface $response): array
    {
        $statusCode = $response->getStatusCode();
        $content = $response->getBody()->getContents();

        if ($statusCode >= 400) {
            throw new ClientException(
                "API request failed with status $statusCode: $content"
            );
        }

        $data = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ClientException(
                "Failed to parse JSON response: " . json_last_error_msg()
            );
        }

        return $data;
    }
    abstract protected function baseUri(): string;
}
