<?php

declare(strict_types=1);

namespace Lishack\Combinatorics\Internal;

/**
 * Normalizes iterables into indexed lists.
 */
final class IterableNormalizer
{
    private function __construct()
    {
        // Prevent instantiation of this class.
    }

    /**
     * Converts an iterable to a zero-based indexed list.
     *
     * @template TValue
     *
     * @param iterable<TValue> $values
     *
     * @return list<TValue>
     */
    public static function toList(iterable $values): array
    {
        return is_array($values)
            ? array_values($values)
            : iterator_to_array($values, false);
    }
}
