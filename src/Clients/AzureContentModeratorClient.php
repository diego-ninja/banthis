<?php

namespace Ninja\BanThis\Clients;

use Ninja\BanThis\Contracts\Result;
use Ninja\BanThis\Exceptions\ClientException;
use Ninja\BanThis\Result\AzureResult;
use Psr\Http\Client\ClientInterface;

final class AzureContentModeratorClient extends AbstractClient
{
    public function __construct(
        private readonly string $subscriptionKey,
        private readonly string $region = 'westeurope',
        protected ?ClientInterface $httpClient = null
    ) {
        parent::__construct($httpClient);
    }
    protected function baseUri(): string
    {
        return sprintf('https://%s.api.cognitive.microsoft.com/contentmoderator/moderate/v1.0/', $this->region);
    }

    /**
     * @throws ClientException
     */
    public function check(string $text): Result
    {
        $response = $this->post('ProcessText/Screen', [
            'text' => $text
        ], [
            'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
            'Content-Type' => 'text/plain'
        ]);

        return AzureResult::fromResponse($text, $response);
    }
}
