<?php

namespace Ninja\BanThis\Clients;

use Ninja\BanThis\Contracts\Result;
use Ninja\BanThis\Result\PurgoMalumResult;

final class PurgoMalumClient extends AbstractClient
{
    protected function baseUri(): string
    {
        return 'https://www.purgomalum.com/service/';
    }

    public function check(string $text): Result
    {
        $response = $this->get('json', [
            'text' => $text
        ]);

        return PurgoMalumResult::fromResponse($text, $response);
    }
}
