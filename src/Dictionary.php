<?php

namespace Ninja\BanThis;

use Ninja\BanThis\Exceptions\DictionaryFileNotFound;

final class Dictionary
{
    public function __construct(private array $words = [])
    {
    }

    public function words(): array
    {
        return $this->words;
    }

    public static function withWords(array $words): self
    {
        return new self($words);
    }

    public static function fromFile(string|array $file): self
    {
        return new self(self::read($file));
    }

    public static function withLanguage(string $language): self
    {
        return new self(self::read(Censor::DICTIONARY_PATH . $language . '.php'));
    }

    private static function read(array|string $source): array
    {
        $words = [];

        if (is_array($source)) {
            foreach ($source as $dictionary_file) {
                $words = array_merge($words, self::read($dictionary_file));
            }
        }

        if (is_string($source)) {
            if (file_exists($source)) {
                $words = include $source;
            } else {
                throw DictionaryFileNotFound::withFile($source);
            }
        }

        return array_keys(array_count_values($words));
    }

    private function mutate(): void
    {
    }
}
