<?php

namespace Ninja\BanThis\Contracts;

interface Result
{
    public function offensive(): bool;
    public function words(): array;
    public function replaced(): string;
    public function original(): string;

    public function score(): ?float;
    public function confidence(): ?float;
    public function categories(): ?array;
}
