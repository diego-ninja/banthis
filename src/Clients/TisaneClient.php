<?php

namespace Ninja\BanThis\Clients;

use Ninja\BanThis\Contracts\Result;
use Ninja\BanThis\Exceptions\ClientException;
use Ninja\BanThis\Result\TisaneResult;
use Psr\Http\Client\ClientInterface;

final class TisaneClient extends AbstractClient
{
    public function __construct(private readonly string $apiKey, protected ?ClientInterface $httpClient = null)
    {
        parent::__construct($httpClient);
    }

    protected function baseUri(): string
    {
        return 'https://api.tisane.ai/';
    }

    /**
     * @throws ClientException
     */
    public function check(string $text): Result
    {
        $response = $this->post(
            'parse',
            [
                'content' => $text,
                'language' => 'en',
                'settings' => [
                    'snippets' => true,
                    'abuse' => true,
                    'sentiment' => true,
                    'profanity' => true
                ]
            ],
            [
                'Content-Type' => 'application/json',
                'Ocp-Apim-Subscription-Key' => $this->apiKey
            ]
        );

        return TisaneResult::fromResponse($text, $response);
    }
}
