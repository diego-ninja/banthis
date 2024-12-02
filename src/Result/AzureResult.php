<?php

namespace Ninja\BanThis\Result;

final readonly class AzureResult extends AbstractResult
{
    public static function fromResponse(string $text, array $response): AbstractResult
    {
        $words = array_map(
            fn($term) => $term['Term'],
            $response['Terms'] ?? []
        );

        $categories = [];
        foreach ($response['Classification'] ?? [] as $category => $score) {
            if ($score > 0.5) {
                $categories[] = strtolower($category);
            }
        }

        $score = null;
        if (!empty($response['Terms'])) {
            $scores = array_column($response['Terms'], 'Score');
            $score = array_sum($scores) / count($scores);
        }

        return new self(
            offensive: !empty($words),
            words: $words,
            replaced: $text,
            original: $text,
            score: $score,
            confidence: $response['Classification']['ReviewRecommended'] ?? null,
            categories: $categories
        );
    }
}
