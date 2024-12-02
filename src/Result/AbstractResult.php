<?php

namespace Ninja\BanThis\Result;

use Ninja\BanThis\Contracts\Result;

abstract readonly class AbstractResult implements Result
{
    protected function __construct(
        public bool $offensive,
        public array $words,
        public string $replaced,
        public string $original,
        public ?float $score = null,
        public ?float $confidence = null,
        public ?array $categories = null
    ) {
    }

    abstract public static function fromResponse(string $text, array $response): self;

    public function offensive(): bool
    {
        return $this->offensive;
    }

    public function words(): array
    {
        return $this->words;
    }

    public function replaced(): string
    {
        return $this->replaced;
    }

    public function original(): string
    {
        return $this->original;
    }

    public function score(): ?float
    {
        return $this->score;
    }

    public function confidence(): ?float
    {
        return $this->confidence;
    }

    public function categories(): ?array
    {
        return $this->categories;
    }
}
