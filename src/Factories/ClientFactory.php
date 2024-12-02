<?php

namespace Ninja\BanThis\Factories;

use Ninja\BanThis\Clients\AzureContentModeratorClient;
use Ninja\BanThis\Clients\PerspectiveClient;
use Ninja\BanThis\Clients\PurgoMalumClient;
use Ninja\BanThis\Clients\TisaneClient;
use Ninja\BanThis\Contracts\Client;
use Ninja\BanThis\Enums\Service;

class ClientFactory
{
    public static function create(Service $client, array $config = []): Client
    {
        $class = match ($client) {
            Service::Perspective => PerspectiveClient::class,
            Service::PurgoMalum => PurgoMalumClient::class,
            Service::Tisane => TisaneClient::class,
            Service::Azure => AzureContentModeratorClient::class,
        };

        return new $class(...$config);
    }
}
