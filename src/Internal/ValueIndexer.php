<?php

declare(strict_types=1);

namespace Lishack\Combinatorics\Internal;

use BackedEnum;
use Lishack\Combinatorics\Assert\Assert;
use Lishack\Combinatorics\Exception\UnsupportedValueTypeException;
use UnitEnum;

final class ValueIndexer
{
    private function __construct()
    {
        // Prevent instantiation of this class.
    }

    /**
     * Creates a lookup that maps values to their zero-based indices.
     *
     * @template TValue
     *
     * @param iterable<TValue> $values
     * @param (callable(TValue): (int|string))|null $keySelector
     *
     * @return array<int|string, int>
     */
    public static function createLookup(iterable $values, ?callable $keySelector = null): array
    {
        $lookup = [];
        $position = 0;

        foreach (self::resolveKeys($values, $keySelector) as $key) {
            $lookup[$key] = $position++;
        }

        return $lookup;
    }

    /**
     * Resolves values to unique keys.
     *
     * @template TValue
     *
     * @param iterable<TValue> $values
     * @param (callable(TValue): (int|string))|null $keySelector
     *
     * @return list<int|string>
     */
    public static function resolveKeys(iterable $values, ?callable $keySelector = null): array
    {
        $keys = [];
        $used = [];

        foreach ($values as $value) {
            $key = self::getKey(
                value: $value,
                keySelector: $keySelector,
            );

            Assert::uniqueValue(
                values: $used,
                key: $key,
            );

            $used[$key] = true;
            $keys[] = $key;
        }

        return $keys;
    }

    /**
     * Resolves values to their zero-based indices using the lookup.
     *
     * @template TValue
     *
     * @param iterable<TValue> $values
     * @param array<int|string, int> $lookup
     * @param (callable(TValue): (int|string))|null $keySelector
     *
     * @return list<int>
     */
    public static function resolveIndices(iterable $values, array $lookup, ?callable $keySelector = null): array
    {
        $indices = [];

        foreach (self::resolveKeys($values, $keySelector) as $key) {
            Assert::valueExists(
                values: $lookup,
                key: $key,
            );

            $indices[] = $lookup[$key];
        }

        return $indices;
    }

    /**
     * Returns a unique key representing the given value.
     *
     * @template TValue
     *
     * @param TValue $value
     * @param (callable(TValue): (int|string))|null $keySelector
     */
    private static function getKey(mixed $value, ?callable $keySelector): int|string
    {
        if ($keySelector !== null) {
            return $keySelector($value);
        }

        return match (true) {
            is_int($value),
            is_string($value) => $value,
            $value instanceof BackedEnum => $value->value,
            $value instanceof UnitEnum => $value->name,
            default => throw new UnsupportedValueTypeException(
                sprintf(
                    'Unsupported value type "%s". Provide a key selector for custom objects.',
                    get_debug_type($value),
                ),
            ),
        };
    }
}
