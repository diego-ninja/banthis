<?php

namespace Ninja\BanThis\Result;

final readonly class TisaneResult extends AbstractResult
{
    public static function fromResponse(string $text, array $response): AbstractResult
    {
        $words = [];
        $categories = [];

        foreach ($response['abuse'] ?? [] as $abuse) {
            $words[] = $abuse['text'];
            $categories[] = $abuse['type'];
        }

        foreach ($response['profanity'] ?? [] as $profanity) {
            $words[] = $profanity['text'];
            $categories[] = 'profanity';
        }


        $words = array_unique($words);
        $categories = array_unique($categories);

        return new self(
            offensive: !empty($words),
            words: $words,
            replaced: $text,
            original: $text,
            score: self::calculateScore($response['abuse'] ?? []),
            confidence: null,
            categories: $categories
        );
    }

    private static function calculateScore(array $abuse): float
    {
        $scores = array_map(function ($severity) {
            return match ($severity) {
                'low' => 0.10,
                'medium' => 0.50,
                'high' => 0.75,
                'extreme' => 0.99,
                default => 0.0
            };
        }, array_column($abuse, 'severity'));

        return array_sum($scores) / count($scores);
    }
}
