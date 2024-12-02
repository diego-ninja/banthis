<?php

namespace Ninja\BanThis\Result;

final readonly class PerspectiveResult extends AbstractResult
{
    public static function fromResponse(string $text, array $response): AbstractResult
    {
        $score = $response['attributeScores']['TOXICITY']['summaryScore']['value'] ?? null;
        $confidence = $response['attributeScores']['TOXICITY']['summaryScore']['confidence'] ?? null;

        $categories = [];
        foreach ($response['attributeScores'] ?? [] as $category => $data) {
            if (($data['summaryScore']['value'] ?? 0) > 0.5) {
                $categories[] = strtolower($category);
            }
        }

        return new self(
            offensive: ($score ?? 0) > 0.7,
            words: [],
            replaced: $text,
            original: $text,
            score: $score,
            confidence: $confidence,
            categories: $categories
        );
    }
}
