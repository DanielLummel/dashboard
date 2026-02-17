<?php

namespace App\Support;

class TagFormatter
{
    /**
     * @return array<int, string>
     */
    public static function parse(?string $raw): array
    {
        if (! $raw) {
            return [];
        }

        $segments = preg_split('/[,\n]+/', $raw) ?: [];

        $tags = array_values(array_unique(array_filter(array_map(
            static fn (string $segment): string => mb_strtolower(trim($segment)),
            $segments
        ))));

        return $tags;
    }

    /**
     * @param  array<int, string>|null  $tags
     */
    public static function join(?array $tags): string
    {
        if (! $tags) {
            return '';
        }

        return implode(', ', $tags);
    }
}
