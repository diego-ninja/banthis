<?php

namespace Ninja\BanThis\Contracts;

interface Client
{
    public function check(string $text): Result;
}
