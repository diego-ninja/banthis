<?php

namespace Ninja\BanThis\Clients;

use Ninja\BanThis\Contracts\Result;
use Ninja\BanThis\Exceptions\ClientException;
use Ninja\BanThis\Result\PerspectiveResult;
use Psr\Http\Client\ClientInterface;

final class PerspectiveClient extends AbstractClient
{
    public function __construct(private readonly string $apiKey, protected ?ClientInterface $httpClient = null)
    {
        parent::__construct($httpClient);
    }

    protected function baseUri(): string
    {
        return 'https://commentanalyzer.googleapis.com/v1alpha1/';
    }

    /**
     * @throws ClientException
     */
    public function check(string $text): Result
    {
        $params = [
            'comment' => ['text' => $text],
            'languages' => ['en','es','fr'],
            'requestedAttributes' => [
                'TOXICITY' => ['scoreType' => 'PROBABILITY', 'scoreThreshold' => 0],
                'SEVERE_TOXICITY' => ['scoreType' => 'PROBABILITY', 'scoreThreshold' => 0],
                'IDENTITY_ATTACK' => ['scoreType' => 'PROBABILITY', 'scoreThreshold' => 0],
                'INSULT' => ['scoreType' => 'PROBABILITY', 'scoreThreshold' => 0],
                'THREAT' => ['scoreType' => 'PROBABILITY', 'scoreThreshold' => 0],
                'PROFANITY' => ['scoreType' => 'PROBABILITY', 'scoreThreshold' => 0],
            ],
        ];

        $response = $this->post(sprintf('comments:analyze?key=%s', $this->apiKey), $params);

        return PerspectiveResult::fromResponse($text, $response);
    }
}
